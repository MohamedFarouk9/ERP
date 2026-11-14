<?php

declare(strict_types=1);

use App\Models\User;
use Modules\Core\Models\Tenant;
use Modules\Core\Services\TenantContext;

test('it creates a tenant with ULID and default active status', function () {
    $tenant = Tenant::create([
        'name' => 'Acme Corporation',
        'slug' => 'acme-corp',
    ]);

    expect($tenant->id)->toBeString()
        ->and(strlen($tenant->id))->toBe(26)
        ->and($tenant->isActive())->toBeTrue()
        ->and($tenant->status)->toBe(Tenant::ACTIVE);
});

test('tenant context stores and clears current tenant correctly', function () {
    $context = app(TenantContext::class);
    $tenant = Tenant::create([
        'name' => 'Beta Industries',
        'slug' => 'beta-ind',
    ]);

    $context->set($tenant);
    expect($context->has())->toBeTrue()
        ->and($context->id())->toBe($tenant->id)
        ->and($context->require()->slug)->toBe('beta-ind');

    $context->reset();
    expect($context->has())->toBeFalse()
        ->and($context->id())->toBeNull();
});

test('belongs to tenant trait automatically scopes queries by current tenant', function () {
    $tenantA = Tenant::create(['name' => 'Tenant A', 'slug' => 'tenant-a']);
    $tenantB = Tenant::create(['name' => 'Tenant B', 'slug' => 'tenant-b']);

    $context = app(TenantContext::class);

    // Create user in Tenant A context
    $context->set($tenantA);
    $userA = User::create([
        'name' => 'Alice',
        'email' => 'alice@example.com',
        'password' => 'Password123!',
    ]);

    // Create user in Tenant B context
    $context->set($tenantB);
    $userB = User::create([
        'name' => 'Bob',
        'email' => 'bob@example.com',
        'password' => 'Password123!',
    ]);

    // When scoped to Tenant A, Bob must NOT be visible
    $context->set($tenantA);
    $usersInTenantA = User::all();
    expect($usersInTenantA->pluck('email')->toArray())->toContain('alice@example.com')
        ->and($usersInTenantA->pluck('email')->toArray())->not->toContain('bob@example.com');

    // When scoped to Tenant B, Alice must NOT be visible
    $context->set($tenantB);
    $usersInTenantB = User::all();
    expect($usersInTenantB->pluck('email')->toArray())->toContain('bob@example.com')
        ->and($usersInTenantB->pluck('email')->toArray())->not->toContain('alice@example.com');

    $context->reset();
});

test('resolve tenant middleware blocks suspended tenants', function () {
    $tenant = Tenant::create([
        'name' => 'Suspended Corp',
        'slug' => 'suspended-corp',
        'status' => Tenant::SUSPENDED,
    ]);

    $context = app(TenantContext::class);
    $context->set($tenant);

    $user = User::create([
        'name' => 'Suspended User',
        'email' => 'suspended@example.com',
        'password' => 'Password123!',
        'tenant_id' => $tenant->id,
    ]);

    $this->actingAs($user);

    $response = $this->getJson('/api/v1/users/profile');

    // When tenant is suspended, middleware blocks access
    $response->assertStatus(403);
});

