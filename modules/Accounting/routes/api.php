<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Accounting\Http\Controllers\AccountController;
use Modules\Accounting\Http\Controllers\AccountingTransactionController;

Route::middleware(['auth', 'tenant', 'tenant.active', 'feature:accounting'])->group(function () {
    // Accounts
    Route::get('/accounts', [AccountController::class, 'index'])
        ->middleware('can:accounting.accounts.view')
        ->name('accounts.index');
    Route::post('/accounts', [AccountController::class, 'store'])
        ->middleware('can:accounting.accounts.create')
        ->name('accounts.store');
    Route::get('/accounts/{id}', [AccountController::class, 'show'])
        ->middleware('can:accounting.accounts.view')
        ->name('accounts.show');

    // Accounting Transactions
    Route::get('/transactions', [AccountingTransactionController::class, 'index'])
        ->middleware('can:accounting.transactions.view')
        ->name('transactions.index');
    Route::post('/transactions', [AccountingTransactionController::class, 'store'])
        ->middleware('can:accounting.transactions.create')
        ->name('transactions.store');
    Route::get('/transactions/{id}', [AccountingTransactionController::class, 'show'])
        ->middleware('can:accounting.transactions.view')
        ->name('transactions.show');
    Route::post('/transactions/{id}/post', [AccountingTransactionController::class, 'post'])
        ->middleware('can:accounting.transactions.post')
        ->name('transactions.post');
});
