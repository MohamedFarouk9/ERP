<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Payments\Http\Controllers\PaymentController;
use Modules\Payments\Http\Controllers\PaymentMethodController;
use Modules\Payments\Http\Controllers\RefundController;

Route::prefix('admin/payments')
    ->name('admin.payments.')
    ->middleware(['auth', 'tenant', 'tenant.active', 'feature:payments'])
    ->group(function (): void {
        // Payment Methods
        Route::post('/methods', [PaymentMethodController::class, 'store'])
            ->middleware('can:payments.methods.create')
            ->name('methods.store');
        Route::put('/methods/{id}', [PaymentMethodController::class, 'update'])
            ->middleware('can:payments.methods.create')
            ->name('methods.update');
        Route::delete('/methods/{id}', [PaymentMethodController::class, 'destroy'])
            ->middleware('can:payments.methods.create')
            ->name('methods.destroy');

        // Refunds
        Route::get('/refunds', [RefundController::class, 'index'])
            ->middleware('can:payments.refunds.view')
            ->name('refunds');
        Route::get('/refunds/data', [RefundController::class, 'data'])
            ->middleware('can:payments.refunds.view')
            ->name('refunds.data');
        Route::post('/refunds', [RefundController::class, 'store'])
            ->middleware('can:payments.refunds.create')
            ->name('refunds.store');

        // Payments
        Route::get('/', [PaymentController::class, 'index'])
            ->middleware('can:payments.transactions.view')
            ->name('index');
        Route::get('/data', [PaymentController::class, 'data'])
            ->middleware('can:payments.transactions.view')
            ->name('data');
        Route::post('/', [PaymentController::class, 'store'])
            ->middleware('can:payments.transactions.create')
            ->name('store');
        Route::post('/{id}/complete', [PaymentController::class, 'complete'])
            ->middleware('can:payments.transactions.complete')
            ->name('complete');
        Route::delete('/{id}', [PaymentController::class, 'destroy'])
            ->middleware('can:payments.transactions.create')
            ->name('destroy');
    });
