<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\AccessControlWebController;
use App\Http\Controllers\Admin\AccountingWebController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FeatureFlagController;
use App\Http\Controllers\Admin\InventoryWebController;
use App\Http\Controllers\Admin\PaymentWebController;
use App\Http\Controllers\Admin\SalesWebController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('admin.dashboard'));

Route::prefix('admin')->name('admin.')->group(function (): void {
    // Guest Auth Routes
    Route::middleware('guest')->group(function (): void {
        Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1')->name('login.submit');
    });

    // Authenticated Tenant Admin Routes
    Route::middleware(['auth', 'tenant', 'tenant.active'])->group(function (): void {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Feature Flags (Module Toggling)
        Route::get('/features', [FeatureFlagController::class, 'index'])
            ->middleware('can:core.features.view')
            ->name('features.index');
        Route::post('/features/{feature}/toggle', [FeatureFlagController::class, 'toggle'])
            ->middleware('can:core.features.manage')
            ->name('features.toggle');

        // Accounting Module
        Route::middleware('feature:accounting')->prefix('accounting')->name('accounting.')->group(function (): void {
            Route::get('/accounts', [AccountingWebController::class, 'accounts'])
                ->middleware('can:accounting.accounts.view')
                ->name('accounts');
            Route::get('/transactions', [AccountingWebController::class, 'transactions'])
                ->middleware('can:accounting.transactions.view')
                ->name('transactions');
        });

        // Inventory Module
        Route::middleware('feature:inventory')->group(function (): void {
            Route::get('/products', [InventoryWebController::class, 'products'])
                ->middleware('can:inventory.products.view')
                ->name('products.index');
            Route::get('/categories', [InventoryWebController::class, 'categories'])
                ->middleware('can:inventory.categories.view')
                ->name('categories.index');
            Route::get('/warehouses', [InventoryWebController::class, 'warehouses'])
                ->middleware('can:inventory.warehouses.view')
                ->name('warehouses.index');
        });

        // Sales Module
        Route::middleware('feature:sales')->group(function (): void {
            Route::get('/customers', [SalesWebController::class, 'customers'])
                ->middleware('can:sales.customers.view')
                ->name('customers.index');
            Route::get('/orders', [SalesWebController::class, 'orders'])
                ->middleware('can:sales.orders.view')
                ->name('orders.index');
            Route::get('/invoices', [SalesWebController::class, 'invoices'])
                ->middleware('can:sales.invoices.view')
                ->name('invoices.index');
        });

        // Payments Module
        Route::middleware('feature:payments')->prefix('payments')->name('payments.')->group(function (): void {
            Route::get('/', [PaymentWebController::class, 'index'])
                ->middleware('can:payments.transactions.view')
                ->name('index');
            Route::get('/refunds', [PaymentWebController::class, 'refunds'])
                ->middleware('can:payments.refunds.view')
                ->name('refunds');
        });

        // Access Control & Audit Trail
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
