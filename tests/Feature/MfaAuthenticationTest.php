<?php

declare(strict_types=1);

use App\Models\User;
use Modules\Core\Models\Tenant;
use Modules\Users\Services\TotpService;

test('it enables, confirms, and authenticates with MFA and recovery codes', function () {
    $tenant = Tenant::create(['name' => 'MFA Tenant', 'slug' => 'mfa-tenant']);

    $user = User::create([
        'name' => 'MFA User',
        'email' => 'mfa@example.com',
        'password' => 'SecureP@ssw0rd1!',
        'tenant_id' => $tenant->id,
        'is_active' => true,
    ]);

    $this->actingAs($user);

    // 1. Setup MFA
    $setupResponse = $this->postJson('/api/v1/users/mfa/setup');
    $setupResponse->assertStatus(200)
        ->assertJsonStructure(['secret', 'qr_uri', 'recovery_codes']);

    $secret = (string) $setupResponse->json('secret');
    $recoveryCodes = $setupResponse->json('recovery_codes');
    expect($recoveryCodes)->toHaveCount(8);

    // 2. Confirm with bad code should fail
    $this->postJson('/api/v1/users/mfa/confirm', ['code' => '000000'])
        ->assertStatus(422);

    // 3. Confirm with valid code
    /** @var TotpService $totpService */
    $totpService = app(TotpService::class);

    // Calculate current code
    $timeSlice = (int) floor(time() / 30);
    $binarySecret = '';
    $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
    for ($i = 0; $i < strlen($secret); $i++) {
        $binarySecret .= str_pad(decbin(strpos($alphabet, $secret[$i])), 5, '0', STR_PAD_LEFT);
    }
    $decodedSecret = '';
    foreach (str_split($binarySecret, 8) as $chunk) {
        if (strlen($chunk) === 8) {
            $decodedSecret .= chr(bindec($chunk));
        }
    }
    $counter = pack('N*', 0).pack('N*', $timeSlice);
    $hmac = hash_hmac('sha1', $counter, $decodedSecret, true);
    $offset = ord($hmac[19]) & 0x0F;
    $truncated = (
        ((ord($hmac[$offset + 0]) & 0x7F) << 24) |
        ((ord($hmac[$offset + 1]) & 0xFF) << 16) |
        ((ord($hmac[$offset + 2]) & 0xFF) << 8) |
        (ord($hmac[$offset + 3]) & 0xFF)
    ) % 1000000;
    $validCode = str_pad((string) $truncated, 6, '0', STR_PAD_LEFT);

    $this->postJson('/api/v1/users/mfa/confirm', ['code' => $validCode])
        ->assertStatus(200)
        ->assertJsonPath('mfa_enabled', true);

    // Refresh user model
    $user->refresh();
    expect($user->hasMfaEnabled())->toBeTrue();

    // 4. Test Login flow with MFA challenge
    $loginResponse = $this->postJson('/api/v1/users/login', [
        'email' => 'mfa@example.com',
        'password' => 'SecureP@ssw0rd1!',
    ]);

    $loginResponse->assertStatus(200)
        ->assertJsonPath('requires_mfa', true)
        ->assertJsonPath('user_id', $user->id);

    // 5. Complete login via recovery code
    $recoveryCode = $recoveryCodes[0];
    $verifyResponse = $this->postJson('/api/v1/users/login/mfa', [
        'user_id' => $user->id,
        'code' => $recoveryCode,
    ]);

    $verifyResponse->assertStatus(200)
        ->assertJsonPath('user.email', 'mfa@example.com');

    // That recovery code is now consumed and cannot be reused
    $this->postJson('/api/v1/users/login/mfa', [
        'user_id' => $user->id,
        'code' => $recoveryCode,
    ])->assertStatus(422);
});
