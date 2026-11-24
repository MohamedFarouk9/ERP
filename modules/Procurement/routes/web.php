<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Procurement\Http\Controllers\ProcurementDashboardController;
use Modules\Procurement\Http\Controllers\PurchaseOrderController;
use Modules\Procurement\Http\Controllers\SupplierController;
use Modules\Procurement\Http\Controllers\VendorBillController;

Route::prefix('admin/procurement')
    ->name('admin.procurement.')
    ->middleware(['auth', 'tenant', 'tenant.active', 'feature:procurement'])
    ->group(function (): void {
        Route::get('/dashboard', [ProcurementDashboardController::class, 'index'])
            ->middleware('can:procurement.orders.view')
            ->name('dashboard');

        // Suppliers
        Route::get('/suppliers', [SupplierController::class, 'index'])
            ->middleware('can:procurement.suppliers.view')
            ->name('suppliers.index');
        Route::get('/suppliers/data', [SupplierController::class, 'data'])
            ->middleware('can:procurement.suppliers.view')
            ->name('suppliers.data');
        Route::post('/suppliers', [SupplierController::class, 'store'])
            ->middleware('can:procurement.suppliers.create')
            ->name('suppliers.store');
        Route::put('/suppliers/{id}', [SupplierController::class, 'update'])
            ->middleware('can:procurement.suppliers.create')
            ->name('suppliers.update');
        Route::delete('/suppliers/{id}', [SupplierController::class, 'destroy'])
            ->middleware('can:procurement.suppliers.create')
            ->name('suppliers.destroy');

        // Purchase Orders
        Route::get('/purchase-orders', [PurchaseOrderController::class, 'index'])
            ->middleware('can:procurement.orders.view')
            ->name('orders.index');
        Route::get('/purchase-orders/data', [PurchaseOrderController::class, 'data'])
            ->middleware('can:procurement.orders.view')
            ->name('orders.data');
        Route::post('/purchase-orders', [PurchaseOrderController::class, 'store'])
            ->middleware('can:procurement.orders.create')
            ->name('orders.store');
        Route::get('/purchase-orders/{id}', [PurchaseOrderController::class, 'show'])
            ->middleware('can:procurement.orders.view')
            ->name('orders.show');
        Route::post('/purchase-orders/{id}/receive', [PurchaseOrderController::class, 'receive'])
            ->middleware('can:procurement.orders.receive')
            ->name('orders.receive');
        Route::delete('/purchase-orders/{id}', [PurchaseOrderController::class, 'destroy'])
            ->middleware('can:procurement.orders.create')
            ->name('orders.destroy');

        // Vendor Bills
        Route::get('/vendor-bills', [VendorBillController::class, 'index'])
            ->middleware('can:procurement.bills.view')
            ->name('bills.index');
        Route::get('/vendor-bills/data', [VendorBillController::class, 'data'])
            ->middleware('can:procurement.bills.view')
            ->name('bills.data');
        Route::post('/vendor-bills', [VendorBillController::class, 'store'])
            ->middleware('can:procurement.bills.create')
            ->name('bills.store');
        Route::post('/vendor-bills/{id}/approve', [VendorBillController::class, 'approve'])
            ->middleware('can:procurement.bills.approve')
            ->name('bills.approve');
        Route::post('/vendor-bills/{id}/pay', [VendorBillController::class, 'markPaid'])
            ->middleware('can:procurement.bills.approve')
            ->name('bills.pay');
        Route::delete('/vendor-bills/{id}', [VendorBillController::class, 'destroy'])
            ->middleware('can:procurement.bills.create')
            ->name('bills.destroy');
    });
