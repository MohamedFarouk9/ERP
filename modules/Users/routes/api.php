<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Users\Http\Controllers\AuditLogController;
use Modules\Users\Http\Controllers\AuthController;
use Modules\Users\Http\Controllers\MfaController;
use Modules\Users\Http\Controllers\OrganizationController;
use Modules\Users\Http\Controllers\ProfileController;
use Modules\Users\Http\Controllers\RoleController;
use Modules\Users\Http\Controllers\SessionController;

// Public Authentication
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login/mfa', [AuthController::class, 'verifyMfa'])->name('login.mfa');

// Authenticated User Endpoints
Route::middleware(['auth', 'tenant', 'tenant.active'])->group(function (): void {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Profile Management
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'changePassword'])->name('profile.password');

    // Multi-Factor Authentication (MFA)
    Route::post('/mfa/setup', [MfaController::class, 'setup'])->name('mfa.setup');
    Route::post('/mfa/confirm', [MfaController::class, 'confirm'])->name('mfa.confirm');
    Route::delete('/mfa', [MfaController::class, 'disable'])->name('mfa.disable');
    Route::post('/mfa/recovery-codes', [MfaController::class, 'recoveryCodes'])->name('mfa.recovery-codes');

    // Active Sessions Management
    Route::get('/sessions', [SessionController::class, 'index'])->name('sessions.index');
    Route::delete('/sessions/{sessionId}', [SessionController::class, 'destroy'])->name('sessions.destroy');
    Route::delete('/sessions', [SessionController::class, 'destroyAll'])->name('sessions.destroy-all');

    // Role-Based Access Control (RBAC) & Custom Permissions
    Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
    Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
    Route::get('/permissions', [RoleController::class, 'permissions'])->name('permissions.index');
    Route::post('/roles/assign', [RoleController::class, 'assign'])->name('roles.assign');
    Route::post('/permissions/custom', [RoleController::class, 'assignCustomPermission'])->name('permissions.custom');

    // Organizational Hierarchy: Departments & Teams
    Route::get('/departments', [OrganizationController::class, 'departments'])->name('departments.index');
    Route::post('/departments', [OrganizationController::class, 'storeDepartment'])->name('departments.store');
    Route::get('/teams', [OrganizationController::class, 'teams'])->name('teams.index');
    Route::post('/teams', [OrganizationController::class, 'storeTeam'])->name('teams.store');
    Route::post('/teams/members', [OrganizationController::class, 'assignMember'])->name('teams.members.assign');

    // Immutable Compliance Audit Trail
    Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');
});
