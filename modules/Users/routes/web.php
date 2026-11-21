<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Users\Http\Controllers\AdminAuthController;
use Modules\Users\Http\Controllers\AuditLogController;
use Modules\Users\Http\Controllers\RoleController;

Route::prefix('admin')->name('admin.')->group(function (): void {
    Route::middleware('guest')->group(function (): void {
        Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'login'])->middleware('throttle:10,1')->name('login.submit');
    });

    Route::middleware(['auth', 'tenant', 'tenant.active'])->group(function (): void {
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

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
