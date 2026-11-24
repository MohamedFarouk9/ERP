<?php

declare(strict_types=1);

namespace Modules\Users\Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Modules\Core\Services\TenantContext;
use Modules\Users\Models\Department;
use Modules\Users\Models\Permission;
use Modules\Users\Models\Role;
use Modules\Users\Models\Team;

class UsersDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $tenantId = app(TenantContext::class)->requireId();

        $permissionDefinitions = [
            // Core
            ['core', 'features', 'view', 'View Feature Flags'],
            ['core', 'features', 'manage', 'Manage Feature Flags'],
            // Users
            ['users', 'roles', 'view', 'View Roles'],
            ['users', 'roles', 'create', 'Create Roles'],
            ['users', 'roles', 'assign', 'Assign Roles'],
            ['users', 'permissions', 'view', 'View Permissions'],
            ['users', 'permissions', 'assign', 'Assign Custom Permissions'],
            ['users', 'departments', 'view', 'View Departments'],
            ['users', 'departments', 'create', 'Create Departments'],
            ['users', 'teams', 'view', 'View Teams'],
            ['users', 'teams', 'create', 'Create Teams'],
            ['users', 'teams', 'assign', 'Assign Team Members'],
            ['users', 'audit-logs', 'view', 'View Audit Logs'],
            // Accounting
            ['accounting', 'accounts', 'view', 'View Accounts'],
            ['accounting', 'accounts', 'create', 'Create Accounts'],
            ['accounting', 'transactions', 'view', 'View Accounting Transactions'],
            ['accounting', 'transactions', 'create', 'Create Accounting Transactions'],
            ['accounting', 'transactions', 'post', 'Post Accounting Transactions'],
            // Inventory
            ['inventory', 'categories', 'view', 'View Categories'],
            ['inventory', 'categories', 'create', 'Create Categories'],
            ['inventory', 'warehouses', 'view', 'View Warehouses'],
            ['inventory', 'warehouses', 'create', 'Create Warehouses'],
            ['inventory', 'products', 'view', 'View Products'],
            ['inventory', 'products', 'create', 'Create Products'],
            ['inventory', 'stock-movements', 'view', 'View Stock Movements'],
            ['inventory', 'stock-movements', 'create', 'Record Stock Movements'],
            // Sales
            ['sales', 'customers', 'view', 'View Customers'],
            ['sales', 'customers', 'create', 'Create Customers'],
            ['sales', 'orders', 'view', 'View Sales Orders'],
            ['sales', 'orders', 'create', 'Create Sales Orders'],
            ['sales', 'orders', 'confirm', 'Confirm Sales Orders'],
            ['sales', 'invoices', 'view', 'View Invoices'],
            ['sales', 'invoices', 'create', 'Create Invoices'],
            ['sales', 'invoices', 'issue', 'Issue Invoices'],
            // Payments
            ['payments', 'methods', 'view', 'View Payment Methods'],
            ['payments', 'methods', 'create', 'Create Payment Methods'],
            ['payments', 'transactions', 'view', 'View Payments'],
            ['payments', 'transactions', 'create', 'Record Payments'],
            ['payments', 'transactions', 'complete', 'Complete Payments'],
            ['payments', 'refunds', 'view', 'View Refunds'],
            ['payments', 'refunds', 'create', 'Process Refunds'],
            // Procurement
            ['procurement', 'suppliers', 'view', 'View Suppliers'],
            ['procurement', 'suppliers', 'create', 'Manage Suppliers'],
            ['procurement', 'orders', 'view', 'View Purchase Orders'],
            ['procurement', 'orders', 'create', 'Create Purchase Orders'],
            ['procurement', 'orders', 'receive', 'Receive Purchase Orders'],
            ['procurement', 'bills', 'view', 'View Vendor Bills'],
            ['procurement', 'bills', 'create', 'Create Vendor Bills'],
            ['procurement', 'bills', 'approve', 'Approve Vendor Bills'],
            // HRM
            ['hrm', 'employees', 'view', 'View Employees'],
            ['hrm', 'employees', 'create', 'Manage Employees'],
            ['hrm', 'attendance', 'view', 'View Attendance'],
            ['hrm', 'attendance', 'create', 'Record Attendance'],
            ['hrm', 'leaves', 'view', 'View Leave Requests'],
            ['hrm', 'leaves', 'create', 'Submit Leave Requests'],
            ['hrm', 'leaves', 'approve', 'Approve/Reject Leaves'],
            ['hrm', 'payroll', 'view', 'View Payroll'],
            ['hrm', 'payroll', 'create', 'Generate Payroll'],
            ['hrm', 'payroll', 'pay', 'Pay Payroll'],
            // POS
            ['pos', 'terminal', 'view', 'Access POS Terminal'],
            ['pos', 'sales', 'create', 'Complete POS Sales'],
            ['pos', 'shifts', 'view', 'View POS Shifts'],
            ['pos', 'shifts', 'manage', 'Open/Close POS Shifts'],
        ];

        $allPermissions = [];
        foreach ($permissionDefinitions as [$module, $resource, $action, $name]) {
            $slug = "{$module}.{$resource}.{$action}";
            $allPermissions[$slug] = Permission::firstOrCreate(
                ['slug' => $slug],
                [
                    'module' => $module,
                    'resource' => $resource,
                    'action' => $action,
                    'name' => $name,
                ]
            );
        }

        $superAdminRole = Role::firstOrCreate(
            ['tenant_id' => $tenantId, 'slug' => 'super-admin'],
            ['name' => 'Super Admin', 'description' => 'Full tenant administrator', 'is_system' => true]
        );
        $superAdminRole->permissions()->sync(collect($allPermissions)->pluck('id')->all());

        $accountantRole = Role::firstOrCreate(
            ['tenant_id' => $tenantId, 'slug' => 'accountant'],
            ['name' => 'Accountant', 'description' => 'Accounting module access', 'is_system' => false]
        );
        $accountantRole->permissions()->sync(
            collect($allPermissions)
                ->filter(fn ($p, $slug) => str_starts_with($slug, 'accounting.'))
                ->pluck('id')
                ->all()
        );

        $salesRole = Role::firstOrCreate(
            ['tenant_id' => $tenantId, 'slug' => 'sales-manager'],
            ['name' => 'Sales Manager', 'description' => 'Sales & Inventory view access', 'is_system' => false]
        );
        $salesRole->permissions()->sync(
            collect($allPermissions)
                ->filter(fn ($p, $slug) => str_starts_with($slug, 'sales.') || $slug === 'inventory.products.view')
                ->pluck('id')
                ->all()
        );

        $adminUser = User::withoutGlobalScopes()->firstOrCreate(
            ['email' => 'admin@acme.test'],
            [
                'tenant_id' => $tenantId,
                'name' => 'Acme Admin',
                'password' => Hash::make('Password123!'),
                'is_active' => true,
            ]
        );
        $adminUser->roles()->syncWithoutDetaching([$superAdminRole->id]);

        $accountantUser = User::withoutGlobalScopes()->firstOrCreate(
            ['email' => 'accountant@acme.test'],
            [
                'tenant_id' => $tenantId,
                'name' => 'Acme Accountant',
                'password' => Hash::make('Password123!'),
                'is_active' => true,
            ]
        );
        $accountantUser->roles()->syncWithoutDetaching([$accountantRole->id]);

        $salesUser = User::withoutGlobalScopes()->firstOrCreate(
            ['email' => 'sales@acme.test'],
            [
                'tenant_id' => $tenantId,
                'name' => 'Acme Sales Manager',
                'password' => Hash::make('Password123!'),
                'is_active' => true,
            ]
        );
        $salesUser->roles()->syncWithoutDetaching([$salesRole->id]);

        $financeDept = Department::firstOrCreate(
            ['tenant_id' => $tenantId, 'code' => 'FIN'],
            ['name' => 'Finance', 'description' => 'Finance & Accounting Department']
        );

        $salesDept = Department::firstOrCreate(
            ['tenant_id' => $tenantId, 'code' => 'SLS'],
            ['name' => 'Sales', 'description' => 'Commercial & Sales Department']
        );

        $glTeam = Team::firstOrCreate(
            ['tenant_id' => $tenantId, 'slug' => 'general-ledger-team'],
            ['name' => 'General Ledger Team', 'department_id' => $financeDept->id, 'description' => 'Core accounting team']
        );
        $glTeam->members()->syncWithoutDetaching([$accountantUser->id => ['is_leader' => true]]);

        $salesTeam = Team::firstOrCreate(
            ['tenant_id' => $tenantId, 'slug' => 'enterprise-sales-team'],
            ['name' => 'Enterprise Sales Team', 'department_id' => $salesDept->id, 'description' => 'B2B Sales team']
        );
        $salesTeam->members()->syncWithoutDetaching([$salesUser->id => ['is_leader' => true]]);
    }
}
