<?php

declare(strict_types=1);

namespace Modules\Users\Actions;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Modules\Users\Models\LoginHistory;
use Modules\Users\Models\UserActiveSession;
use Modules\Users\Services\TotpService;

final class VerifyMfaTotpAction
{
    public function __construct(
        private readonly TotpService $totpService
    ) {}

    /**
     * @param  array{
     *     user_id: string,
     *     code: string,
     *     ip_address: string,
     *     user_agent?: string|null
     * }  $data
     * @return array{
     *     success: bool,
     *     user: User,
     *     session_id: string
     * }
     *
     * @throws ValidationException
     */
    public function execute(array $data): array
    {
        /** @var User|null $user */
        $user = User::withoutGlobalScope('tenant')->find($data['user_id']);

        if ($user === null || ! $user->hasMfaEnabled()) {
            throw ValidationException::withMessages([
                'code' => ['Invalid authentication request.'],
            ]);
        }

        $mfaSecret = $user->mfaSecret;
        if ($mfaSecret === null) {
            throw ValidationException::withMessages([
                'code' => ['Invalid authentication request.'],
            ]);
        }

        $code = trim($data['code']);
        $verified = false;

        // 1. Try TOTP code verification
        if (strlen($code) === 6 && ctype_digit($code)) {
            $verified = $this->totpService->verify($mfaSecret->secret, $code);
        }

        // 2. Fallback to Recovery Code verification
        if (! $verified && is_array($mfaSecret->recovery_codes)) {
            $recoveryCodes = $mfaSecret->recovery_codes;
            $codeUpper = strtoupper($code);

            $index = array_search($codeUpper, $recoveryCodes, true);
            if ($index !== false) {
                unset($recoveryCodes[$index]);
                $mfaSecret->recovery_codes = array_values($recoveryCodes);
                $mfaSecret->save();
                $verified = true;
            }
        }

        if (! $verified) {
            LoginHistory::create([
                'user_id' => $user->id,
                'ip_address' => $data['ip_address'],
                'user_agent' => $data['user_agent'] ?? null,
                'status' => LoginHistory::STATUS_FAILED,
                'failure_reason' => 'Invalid MFA code or recovery code.',
                'tenant_id' => $user->tenant_id,
                'created_at' => now(),
            ]);

            throw ValidationException::withMessages([
                'code' => ['The provided verification code is incorrect.'],
            ]);
        }

        // Successful MFA authentication
        LoginHistory::create([
            'user_id' => $user->id,
            'ip_address' => $data['ip_address'],
            'user_agent' => $data['user_agent'] ?? null,
            'status' => LoginHistory::STATUS_SUCCESS,
            'tenant_id' => $user->tenant_id,
            'created_at' => now(),
        ]);

        $sessionId = (string) Str::uuid();

        UserActiveSession::create([
            'user_id' => $user->id,
            'session_id' => $sessionId,
            'ip_address' => $data['ip_address'],
            'user_agent' => $data['user_agent'] ?? null,
            'device_name' => str_contains($data['user_agent'] ?? '', 'Mobile') ? 'Mobile Browser' : 'Desktop Browser',
            'last_active_at' => now(),
            'logged_in_at' => now(),
        ]);

        return [
            'success' => true,
            'user' => $user,
            'session_id' => $sessionId,
        ];
    }
}
