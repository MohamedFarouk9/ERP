<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Sales\Http\Controllers\CustomerController;
use Modules\Sales\Http\Controllers\InvoiceController;
use Modules\Sales\Http\Controllers\SalesOrderController;

Route::middleware(['auth', 'tenant', 'tenant.active', 'feature:sales'])->group(function () {
    // Customers
    Route::get('/customers', [CustomerController::class, 'index'])
        ->middleware('can:sales.customers.view')
        ->name('customers.index');
    Route::post('/customers', [CustomerController::class, 'store'])
        ->middleware('can:sales.customers.create')
        ->name('customers.store');
    Route::get('/customers/{id}', [CustomerController::class, 'show'])
        ->middleware('can:sales.customers.view')
        ->name('customers.show');

    // Sales Orders
    Route::get('/orders', [SalesOrderController::class, 'index'])
        ->middleware('can:sales.orders.view')
        ->name('orders.index');
    Route::post('/orders', [SalesOrderController::class, 'store'])
        ->middleware('can:sales.orders.create')
        ->name('orders.store');
    Route::get('/orders/{id}', [SalesOrderController::class, 'show'])
        ->middleware('can:sales.orders.view')
        ->name('orders.show');
    Route::post('/orders/{id}/confirm', [SalesOrderController::class, 'confirm'])
        ->middleware('can:sales.orders.confirm')
        ->name('orders.confirm');

    // Invoices
    Route::get('/invoices', [InvoiceController::class, 'index'])
        ->middleware('can:sales.invoices.view')
        ->name('invoices.index');
    Route::post('/invoices', [InvoiceController::class, 'store'])
        ->middleware('can:sales.invoices.create')
        ->name('invoices.store');
    Route::get('/invoices/{id}', [InvoiceController::class, 'show'])
        ->middleware('can:sales.invoices.view')
        ->name('invoices.show');
    Route::post('/invoices/{id}/issue', [InvoiceController::class, 'issue'])
        ->middleware('can:sales.invoices.issue')
        ->name('invoices.issue');
});
