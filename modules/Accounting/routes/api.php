<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Accounting\Http\Controllers\AccountController;
use Modules\Accounting\Http\Controllers\AccountingTransactionController;

Route::middleware(['auth', 'tenant', 'tenant.active', 'feature:accounting'])->group(function () {
    // Accounts
    Route::get('/accounts', [AccountController::class, 'index'])->name('accounts.index');
    Route::post('/accounts', [AccountController::class, 'store'])->name('accounts.store');
    Route::get('/accounts/{id}', [AccountController::class, 'show'])->name('accounts.show');

    // Accounting Transactions
    Route::get('/transactions', [AccountingTransactionController::class, 'index'])->name('transactions.index');
    Route::post('/transactions', [AccountingTransactionController::class, 'store'])->name('transactions.store');
    Route::get('/transactions/{id}', [AccountingTransactionController::class, 'show'])->name('transactions.show');
    Route::post('/transactions/{id}/post', [AccountingTransactionController::class, 'post'])->name('transactions.post');
});
