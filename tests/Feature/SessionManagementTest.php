<?php

declare(strict_types=1);

use App\Models\User;
use Modules\Core\Models\Tenant;
use Modules\Users\Models\UserActiveSession;

test('it lists active sessions and allows revoking them', function () {
    $tenant = Tenant::create(['name' => 'Session Tenant', 'slug' => 'session-tenant']);

    $user = User::create([
        'name' => 'Session User',
        'email' => 'session@example.com',
        'password' => 'Password123!',
        'tenant_id' => $tenant->id,
    ]);

    UserActiveSession::create([
        'user_id' => $user->id,
        'session_id' => 'session-device-1',
        'ip_address' => '127.0.0.1',
        'device_name' => 'Desktop Chrome',
        'last_active_at' => now(),
        'logged_in_at' => now(),
    ]);

    UserActiveSession::create([
        'user_id' => $user->id,
        'session_id' => 'session-device-2',
        'ip_address' => '192.168.1.1',
        'device_name' => 'Mobile Safari',
        'last_active_at' => now()->subHour(),
        'logged_in_at' => now()->subHour(),
    ]);

    $this->actingAs($user);

    // List sessions
    $listResponse = $this->getJson('/api/v1/users/sessions');
    $listResponse->assertStatus(200)
        ->assertJsonCount(2, 'sessions');

    // Revoke specific session
    $this->deleteJson('/api/v1/users/sessions/session-device-2')
        ->assertStatus(200)
        ->assertJsonPath('message', 'Session revoked successfully.');

    expect($user->activeSessions()->count())->toBe(1);

    // Logout
    $this->postJson('/api/v1/users/logout')
        ->assertStatus(200)
        ->assertJsonPath('message', 'Logged out successfully.');
});

