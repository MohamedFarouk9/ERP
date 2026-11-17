<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Modules\Core\Models\Tenant;
use Modules\Core\Services\TenantContext;
use Modules\Users\Models\Permission;
use Modules\Users\Models\Role;

test('it creates system permissions and assigns them to per-tenant roles', function () {
    $tenant = Tenant::create(['name' => 'RBAC Tenant', 'slug' => 'rbac-tenant']);
    $context = app(TenantContext::class);
    $context->set($tenant);

    $viewInvoices = Permission::create([
        'module' => 'sales',
        'resource' => 'invoices',
        'action' => 'view',
        'slug' => 'sales.invoices.view',
        'name' => 'View Invoices',
    ]);

    $createInvoices = Permission::create([
        'module' => 'sales',
        'resource' => 'invoices',
        'action' => 'create',
        'slug' => 'sales.invoices.create',
        'name' => 'Create Invoices',
    ]);

    $role = Role::create([
        'tenant_id' => $tenant->id,
        'name' => 'Sales Executive',
        'slug' => 'sales-executive',
    ]);

    $role->givePermissionTo($viewInvoices);
    $role->givePermissionTo($createInvoices);

    $user = User::create([
        'name' => 'Sales Rep',
        'email' => 'sales@example.com',
        'password' => 'SecureP@ss1!',
        'tenant_id' => $tenant->id,
    ]);

    $user->assignRole($role);

    expect($user->hasRole('sales-executive'))->toBeTrue()
        ->and($user->hasPermissionTo('sales.invoices.view'))->toBeTrue()
        ->and($user->hasPermissionTo('sales.invoices.create'))->toBeTrue()
        ->and($user->hasPermissionTo('sales.invoices.delete'))->toBeFalse();

    // Verify Gate integration
    $this->actingAs($user);
    expect(Gate::allows('sales.invoices.create'))->toBeTrue()
        ->and(Gate::allows('sales.invoices.delete'))->toBeFalse();

    $context->reset();
});

test('direct custom permissions can grant or explicitly deny abilities overriding roles', function () {
    $tenant = Tenant::create(['name' => 'Override Tenant', 'slug' => 'override-tenant']);
    $context = app(TenantContext::class);
    $context->set($tenant);

    $deleteInvoice = Permission::create([
        'module' => 'sales',
        'resource' => 'invoices',
        'action' => 'delete',
        'slug' => 'sales.invoices.delete',
        'name' => 'Delete Invoices',
    ]);

    $role = Role::create([
        'tenant_id' => $tenant->id,
        'name' => 'Manager',
        'slug' => 'manager',
    ]);
    $role->givePermissionTo($deleteInvoice);

    $user = User::create([
        'name' => 'Restricted Manager',
        'email' => 'restricted@example.com',
        'password' => 'SecureP@ss1!',
        'tenant_id' => $tenant->id,
    ]);

    $user->assignRole($role);
    expect($user->hasPermissionTo('sales.invoices.delete'))->toBeTrue();

    // Now apply explicit direct DENY on user
    $user->givePermission($deleteInvoice, isGranted: false);

    expect($user->hasPermissionTo('sales.invoices.delete'))->toBeFalse();

    $this->actingAs($user);
    expect(Gate::allows('sales.invoices.delete'))->toBeFalse();

    $context->reset();
});

test('roles are strictly scoped to their tenant', function () {
    $tenantA = Tenant::create(['name' => 'Company A', 'slug' => 'company-a']);
    $tenantB = Tenant::create(['name' => 'Company B', 'slug' => 'company-b']);

    $roleA = Role::create([
        'tenant_id' => $tenantA->id,
        'name' => 'Accountant',
        'slug' => 'accountant',
    ]);

    $roleB = Role::create([
        'tenant_id' => $tenantB->id,
        'name' => 'Accountant',
        'slug' => 'accountant',
    ]);

    $context = app(TenantContext::class);

    $context->set($tenantA);
    $rolesA = Role::all();
    expect($rolesA->pluck('id')->toArray())->toContain($roleA->id)
        ->and($rolesA->pluck('id')->toArray())->not->toContain($roleB->id);

    $context->set($tenantB);
    $rolesB = Role::all();
    expect($rolesB->pluck('id')->toArray())->toContain($roleB->id)
        ->and($rolesB->pluck('id')->toArray())->not->toContain($roleA->id);

    $context->reset();
});
