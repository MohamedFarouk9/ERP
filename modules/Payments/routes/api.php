<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Payments\Http\Controllers\PaymentController;
use Modules\Payments\Http\Controllers\PaymentMethodController;
use Modules\Payments\Http\Controllers\RefundController;

Route::middleware(['auth', 'tenant', 'tenant.active', 'feature:payments'])->group(function () {
    // Payment Methods
    Route::get('/methods', [PaymentMethodController::class, 'index'])
        ->middleware('can:payments.methods.view')
        ->name('methods.index');
    Route::post('/methods', [PaymentMethodController::class, 'store'])
        ->middleware('can:payments.methods.create')
        ->name('methods.store');
    Route::get('/methods/{id}', [PaymentMethodController::class, 'show'])
        ->middleware('can:payments.methods.view')
        ->name('methods.show');

    // Payments
    Route::get('/', [PaymentController::class, 'index'])
        ->middleware('can:payments.transactions.view')
        ->name('index');
    Route::post('/', [PaymentController::class, 'store'])
        ->middleware('can:payments.transactions.create')
        ->name('store');
    Route::get('/{id}', [PaymentController::class, 'show'])
        ->middleware('can:payments.transactions.view')
        ->name('show');
    Route::post('/{id}/complete', [PaymentController::class, 'complete'])
        ->middleware('can:payments.transactions.complete')
        ->name('complete');

    // Refunds
    Route::get('/refunds/list', [RefundController::class, 'index'])
        ->middleware('can:payments.refunds.view')
        ->name('refunds.index');
    Route::post('/refunds', [RefundController::class, 'store'])
        ->middleware('can:payments.refunds.create')
        ->name('refunds.store');
    Route::get('/refunds/{id}', [RefundController::class, 'show'])
        ->middleware('can:payments.refunds.view')
        ->name('refunds.show');
});
