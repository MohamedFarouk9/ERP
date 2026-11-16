<?php

declare(strict_types=1);

use App\Models\User;
use Modules\Core\Models\Tenant;
use Modules\Core\Services\TenantContext;
use Modules\Users\Models\AuditLog;

test('models using auditable trait automatically create immutable audit log entries', function () {
    $tenant = Tenant::create(['name' => 'Audit Tenant', 'slug' => 'audit-tenant']);
    $context = app(TenantContext::class);
    $context->set($tenant);

    // 1. Creation event
    $user = User::create([
        'name' => 'Audited User',
        'email' => 'audited@example.com',
        'password' => 'SecurePass123!',
        'tenant_id' => $tenant->id,
        'phone' => '+1234567890',
    ]);

    $creationLog = AuditLog::where('auditable_type', User::class)
        ->where('auditable_id', $user->id)
        ->where('event', 'created')
        ->first();

    expect($creationLog)->not->toBeNull()
        ->and($creationLog->tenant_id)->toBe($tenant->id)
        ->and($creationLog->new_values['name'])->toBe('Audited User');

    // 2. Update event
    $user->name = 'Audited User Renamed';
    $user->phone = '+9999999999';
    $user->save();

    $updateLog = AuditLog::where('auditable_type', User::class)
        ->where('auditable_id', $user->id)
        ->where('event', 'updated')
        ->first();

    expect($updateLog)->not->toBeNull()
        ->and($updateLog->old_values['name'])->toBe('Audited User')
        ->and($updateLog->new_values['name'])->toBe('Audited User Renamed');

    // 3. Deletion event
    $userId = $user->id;
    $user->delete();

    $deleteLog = AuditLog::where('auditable_type', User::class)
        ->where('auditable_id', $userId)
        ->where('event', 'deleted')
        ->first();

    expect($deleteLog)->not->toBeNull();

    $context->reset();
});

test('audit logs are strictly immutable and cannot be updated or deleted', function () {
    $tenant = Tenant::create(['name' => 'Immutability Tenant', 'slug' => 'immutability-tenant']);

    $log = AuditLog::create([
        'tenant_id' => $tenant->id,
        'event' => 'custom_action',
        'auditable_type' => 'App\Models\Invoice',
        'auditable_id' => '01m77x17h772r6g3g8bdb7sess',
        'new_values' => ['amount' => 1000],
        'created_at' => now(),
    ]);

    // Attempting to update must throw RuntimeException
    expect(function () use ($log) {
        $log->update(['event' => 'tampered_event']);
    })->toThrow(RuntimeException::class, 'Audit logs are strictly immutable and cannot be updated.');

    // Attempting to delete must throw RuntimeException
    expect(function () use ($log) {
        $log->delete();
    })->toThrow(RuntimeException::class, 'Audit logs are strictly immutable and cannot be deleted.');
});

test('audit log api endpoint lists logs scoped to tenant', function () {
    $tenant = Tenant::create(['name' => 'API Tenant', 'slug' => 'api-tenant']);

    $user = User::create([
        'name' => 'Admin User',
        'email' => 'admin@example.com',
        'password' => 'Password123!',
        'tenant_id' => $tenant->id,
    ]);

    $this->actingAs($user);

    $response = $this->getJson('/api/v1/users/audit-logs');

    $response->assertStatus(200)
        ->assertJsonStructure(['data', 'current_page', 'total']);
});

