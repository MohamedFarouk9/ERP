<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Sales\Http\Controllers\CustomerController;
use Modules\Sales\Http\Controllers\InvoiceController;
use Modules\Sales\Http\Controllers\SalesDashboardController;
use Modules\Sales\Http\Controllers\SalesOrderController;

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'tenant', 'tenant.active', 'feature:sales'])
    ->group(function (): void {
        Route::get('/sales/dashboard', [SalesDashboardController::class, 'index'])
            ->middleware('can:sales.orders.view')
            ->name('sales.dashboard');
        // Customers
        Route::get('/customers', [CustomerController::class, 'index'])
            ->middleware('can:sales.customers.view')
            ->name('customers.index');
        Route::get('/customers/data', [CustomerController::class, 'data'])
            ->middleware('can:sales.customers.view')
            ->name('customers.data');
        Route::post('/customers', [CustomerController::class, 'store'])
            ->middleware('can:sales.customers.create')
            ->name('customers.store');
        Route::put('/customers/{id}', [CustomerController::class, 'update'])
            ->middleware('can:sales.customers.create')
            ->name('customers.update');
        Route::delete('/customers/{id}', [CustomerController::class, 'destroy'])
            ->middleware('can:sales.customers.create')
            ->name('customers.destroy');

        // Sales Orders
        Route::get('/orders', [SalesOrderController::class, 'index'])
            ->middleware('can:sales.orders.view')
            ->name('orders.index');
        Route::get('/orders/data', [SalesOrderController::class, 'data'])
            ->middleware('can:sales.orders.view')
            ->name('orders.data');
        Route::post('/orders', [SalesOrderController::class, 'store'])
            ->middleware('can:sales.orders.create')
            ->name('orders.store');
        Route::get('/orders/{id}', [SalesOrderController::class, 'show'])
            ->middleware('can:sales.orders.view')
            ->name('orders.show');
        Route::post('/orders/{id}/confirm', [SalesOrderController::class, 'confirm'])
            ->middleware('can:sales.orders.confirm')
            ->name('orders.confirm');
        Route::delete('/orders/{id}', [SalesOrderController::class, 'destroy'])
            ->middleware('can:sales.orders.create')
            ->name('orders.destroy');

        // Invoices
        Route::get('/invoices', [InvoiceController::class, 'index'])
            ->middleware('can:sales.invoices.view')
            ->name('invoices.index');
        Route::get('/invoices/data', [InvoiceController::class, 'data'])
            ->middleware('can:sales.invoices.view')
            ->name('invoices.data');
        Route::post('/invoices', [InvoiceController::class, 'store'])
            ->middleware('can:sales.invoices.create')
            ->name('invoices.store');
        Route::get('/invoices/{id}', [InvoiceController::class, 'show'])
            ->middleware('can:sales.invoices.view')
            ->name('invoices.show');
        Route::post('/invoices/{id}/issue', [InvoiceController::class, 'issue'])
            ->middleware('can:sales.invoices.issue')
            ->name('invoices.issue');
        Route::delete('/invoices/{id}', [InvoiceController::class, 'destroy'])
            ->middleware('can:sales.invoices.create')
            ->name('invoices.destroy');
    });
