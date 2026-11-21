<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Users\Http\Controllers\Web\AccessControlWebController;
use Modules\Users\Http\Controllers\Web\AuthController;

Route::prefix('admin')->name('admin.')->group(function (): void {
    Route::middleware('guest')->group(function (): void {
        Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1')->name('login.submit');
    });

    Route::middleware(['auth', 'tenant', 'tenant.active'])->group(function (): void {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

        Route::get('/roles', [AccessControlWebController::class, 'roles'])
            ->middleware('can:users.roles.view')
            ->name('roles.index');
        Route::get('/permissions', [AccessControlWebController::class, 'permissions'])
            ->middleware('can:users.permissions.view')
            ->name('permissions.index');
        Route::get('/audit-logs', [AccessControlWebController::class, 'auditLogs'])
            ->middleware('can:users.audit-logs.view')
            ->name('audit-logs.index');
    });
});
