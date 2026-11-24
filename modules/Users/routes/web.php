<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Users\Http\Controllers\AdminAuthController;
use Modules\Users\Http\Controllers\AuditLogController;
use Modules\Users\Http\Controllers\OrganizationController;
use Modules\Users\Http\Controllers\RoleController;

Route::prefix('admin')->name('admin.')->group(function (): void {
    Route::middleware('guest')->group(function (): void {
        Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'login'])->middleware('throttle:10,1')->name('login.submit');
    });

    Route::middleware(['auth', 'tenant', 'tenant.active'])->group(function (): void {
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

        // Organization & HR (Staff, Departments, Teams)
        Route::prefix('organization')->name('organization.')->group(function (): void {
            Route::get('/staff', [OrganizationController::class, 'staff'])
                ->middleware('can:users.departments.view')
                ->name('staff');
            Route::get('/staff/data', [OrganizationController::class, 'staffData'])
                ->middleware('can:users.departments.view')
                ->name('staff.data');
            Route::post('/staff', [OrganizationController::class, 'storeStaff'])
                ->middleware('can:users.roles.assign')
                ->name('staff.store');
            Route::put('/staff/{id}', [OrganizationController::class, 'updateStaff'])
                ->middleware('can:users.roles.assign')
                ->name('staff.update');

            Route::get('/departments', [OrganizationController::class, 'departments'])
                ->middleware('can:users.departments.view')
                ->name('departments');
            Route::post('/departments', [OrganizationController::class, 'storeDepartment'])
                ->middleware('can:users.departments.create')
                ->name('departments.store');
            Route::put('/departments/{id}', [OrganizationController::class, 'updateDepartment'])
                ->middleware('can:users.departments.create')
                ->name('departments.update');
            Route::delete('/departments/{id}', [OrganizationController::class, 'destroyDepartment'])
                ->middleware('can:users.departments.create')
                ->name('departments.destroy');

            Route::get('/teams', [OrganizationController::class, 'teams'])
                ->middleware('can:users.teams.view')
                ->name('teams');
            Route::post('/teams', [OrganizationController::class, 'storeTeam'])
                ->middleware('can:users.teams.create')
                ->name('teams.store');
            Route::put('/teams/{id}', [OrganizationController::class, 'updateTeam'])
                ->middleware('can:users.teams.create')
                ->name('teams.update');
            Route::delete('/teams/{id}', [OrganizationController::class, 'destroyTeam'])
                ->middleware('can:users.teams.create')
                ->name('teams.destroy');
            Route::post('/teams/members', [OrganizationController::class, 'assignMember'])
                ->middleware('can:users.teams.assign')
                ->name('teams.members');
        });

        // Roles CRUD
        Route::get('/roles', [RoleController::class, 'index'])
            ->middleware('can:users.roles.view')
            ->name('roles.index');
        Route::post('/roles', [RoleController::class, 'store'])
            ->middleware('can:users.roles.create')
            ->name('roles.store');
        Route::put('/roles/{id}', [RoleController::class, 'update'])
            ->middleware('can:users.roles.create')
            ->name('roles.update');
        Route::delete('/roles/{id}', [RoleController::class, 'destroy'])
            ->middleware('can:users.roles.create')
            ->name('roles.destroy');

        // Permissions
        Route::get('/permissions', [RoleController::class, 'permissions'])
            ->middleware('can:users.permissions.view')
            ->name('permissions.index');

        // Audit Logs
        Route::get('/audit-logs', [AuditLogController::class, 'index'])
            ->middleware('can:users.audit-logs.view')
            ->name('audit-logs.index');
        Route::get('/audit-logs/data', [AuditLogController::class, 'data'])
            ->middleware('can:users.audit-logs.view')
            ->name('audit-logs.data');
    });
});
