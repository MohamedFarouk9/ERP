<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Accounting\Http\Controllers\AccountController;
use Modules\Accounting\Http\Controllers\AccountingTransactionController;

Route::prefix('admin/accounting')
    ->name('admin.accounting.')
    ->middleware(['auth', 'tenant', 'tenant.active', 'feature:accounting'])
    ->group(function (): void {
        // Accounts CRUD
        Route::get('/accounts', [AccountController::class, 'index'])
            ->middleware('can:accounting.accounts.view')
            ->name('accounts');
        Route::post('/accounts', [AccountController::class, 'store'])
            ->middleware('can:accounting.accounts.create')
            ->name('accounts.store');
        Route::put('/accounts/{id}', [AccountController::class, 'update'])
            ->middleware('can:accounting.accounts.create')
            ->name('accounts.update');
        Route::delete('/accounts/{id}', [AccountController::class, 'destroy'])
            ->middleware('can:accounting.accounts.create')
            ->name('accounts.destroy');

        // Accounting Transactions CRUD + Post
        Route::get('/transactions', [AccountingTransactionController::class, 'index'])
            ->middleware('can:accounting.transactions.view')
            ->name('transactions');
        Route::get('/transactions/data', [AccountingTransactionController::class, 'data'])
            ->middleware('can:accounting.transactions.view')
            ->name('transactions.data');
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
