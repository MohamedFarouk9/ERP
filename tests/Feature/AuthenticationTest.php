<?php

declare(strict_types=1);

use App\Models\User;
use Modules\Core\Models\Tenant;
use Modules\Users\Models\LoginHistory;

test('it registers a user successfully with strong password', function () {
    $tenant = Tenant::create(['name' => 'Test Tenant', 'slug' => 'test-tenant']);

    $response = $this->postJson('/api/v1/users/register', [
        'tenant_id' => $tenant->id,
        'name' => 'John Doe',
        'email' => 'john.doe@example.com',
        'password' => 'StrongP@ssw0rd!',
        'password_confirmation' => 'StrongP@ssw0rd!',
        'timezone' => 'America/New_York',
        'locale' => 'en',
    ]);

    $response->assertStatus(201)
        ->assertJsonPath('user.name', 'John Doe')
        ->assertJsonPath('user.email', 'john.doe@example.com');

    $this->assertDatabaseHas('users', [
        'email' => 'john.doe@example.com',
        'tenant_id' => $tenant->id,
    ]);

    $user = User::withoutGlobalScope('tenant')->where('email', 'john.doe@example.com')->first();
    expect($user->passwordHistories()->count())->toBe(1);
});

test('it rejects registration when password is weak', function () {
    $tenant = Tenant::create(['name' => 'Test Tenant 2', 'slug' => 'test-tenant-2']);

    $response = $this->postJson('/api/v1/users/register', [
        'tenant_id' => $tenant->id,
        'name' => 'Weak User',
        'email' => 'weak@example.com',
        'password' => 'weak',
        'password_confirmation' => 'weak',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['password']);
});

test('it authenticates user and logs successful history', function () {
    $tenant = Tenant::create(['name' => 'Login Tenant', 'slug' => 'login-tenant']);

    $this->postJson('/api/v1/users/register', [
        'tenant_id' => $tenant->id,
        'name' => 'Login User',
        'email' => 'login@example.com',
        'password' => 'ValidP@ssw0rd!',
        'password_confirmation' => 'ValidP@ssw0rd!',
    ])->assertStatus(201);

    $response = $this->postJson('/api/v1/users/login', [
        'email' => 'login@example.com',
        'password' => 'ValidP@ssw0rd!',
    ]);

    $response->assertStatus(200)
        ->assertJsonPath('user.email', 'login@example.com')
        ->assertJsonPath('requires_mfa', false);

    $user = User::withoutGlobalScope('tenant')->where('email', 'login@example.com')->first();
    expect($user->loginHistories()->where('status', LoginHistory::STATUS_SUCCESS)->count())->toBe(1);
});

test('it locks out user after consecutive failed login attempts', function () {
    $tenant = Tenant::create(['name' => 'Lockout Tenant', 'slug' => 'lockout-tenant']);

    $this->postJson('/api/v1/users/register', [
        'tenant_id' => $tenant->id,
        'name' => 'Lockout User',
        'email' => 'lockout@example.com',
        'password' => 'ValidP@ssw0rd!',
        'password_confirmation' => 'ValidP@ssw0rd!',
    ])->assertStatus(201);

    // Attempt 5 failed logins
    for ($i = 0; $i < 5; $i++) {
        $this->postJson('/api/v1/users/login', [
            'email' => 'lockout@example.com',
            'password' => 'WrongPassword123!',
        ])->assertStatus(422);
    }

    // 6th attempt should be blocked due to lockout
    $response = $this->postJson('/api/v1/users/login', [
        'email' => 'lockout@example.com',
        'password' => 'ValidP@ssw0rd!',
    ]);

    $response->assertStatus(422)
        ->assertJsonPath('errors.email.0', 'Account temporarily locked due to excessive failed attempts. Please try again later.');

    $user = User::withoutGlobalScope('tenant')->where('email', 'lockout@example.com')->first();
    expect($user->loginHistories()->where('status', LoginHistory::STATUS_BLOCKED)->count())->toBe(1);
});

test('it prevents reusing recent passwords', function () {
    $tenant = Tenant::create(['name' => 'PW Tenant', 'slug' => 'pw-tenant']);

    $this->postJson('/api/v1/users/register', [
        'tenant_id' => $tenant->id,
        'name' => 'PW User',
        'email' => 'pw@example.com',
        'password' => 'InitialP@ss1!',
        'password_confirmation' => 'InitialP@ss1!',
    ])->assertStatus(201);

    $user = User::withoutGlobalScope('tenant')->where('email', 'pw@example.com')->first();
    $this->actingAs($user);

    // Change to password 2
    $this->putJson('/api/v1/users/profile/password', [
        'current_password' => 'InitialP@ss1!',
        'password' => 'SecondP@ss2!',
        'password_confirmation' => 'SecondP@ss2!',
    ])->assertStatus(200);

    // Attempt to reuse InitialP@ss1! - should fail
    $response = $this->putJson('/api/v1/users/profile/password', [
        'current_password' => 'SecondP@ss2!',
        'password' => 'InitialP@ss1!',
        'password_confirmation' => 'InitialP@ss1!',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['password']);
});
