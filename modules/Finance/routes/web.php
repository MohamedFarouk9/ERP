<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Finance\Http\Controllers\BudgetController;
use Modules\Finance\Http\Controllers\CashflowController;
use Modules\Finance\Http\Controllers\ExpenseCategoryController;
use Modules\Finance\Http\Controllers\ExpenseController;
use Modules\Finance\Http\Controllers\FinanceReportController;
use Modules\Finance\Http\Controllers\TaxRateController;

Route::prefix('admin/finance')
    ->name('admin.finance.')
    ->middleware(['auth', 'tenant', 'tenant.active', 'feature:finance'])
    ->group(function (): void {
        // Expense Categories
        Route::get('/expense-categories', [ExpenseCategoryController::class, 'index'])
            ->middleware('can:finance.expenses.view')
            ->name('expense-categories.index');
        Route::post('/expense-categories', [ExpenseCategoryController::class, 'store'])
            ->middleware('can:finance.expenses.manage')
            ->name('expense-categories.store');
        Route::delete('/expense-categories/{id}', [ExpenseCategoryController::class, 'destroy'])
            ->middleware('can:finance.expenses.manage')
            ->name('expense-categories.destroy');

        // Expenses
        Route::get('/expenses', [ExpenseController::class, 'index'])
            ->middleware('can:finance.expenses.view')
            ->name('expenses.index');
        Route::get('/expenses/data', [ExpenseController::class, 'data'])
            ->middleware('can:finance.expenses.view')
            ->name('expenses.data');
        Route::post('/expenses', [ExpenseController::class, 'store'])
            ->middleware('can:finance.expenses.manage')
            ->name('expenses.store');
        Route::post('/expenses/{id}/pay', [ExpenseController::class, 'pay'])
            ->middleware('can:finance.expenses.manage')
            ->name('expenses.pay');
        Route::delete('/expenses/{id}', [ExpenseController::class, 'destroy'])
            ->middleware('can:finance.expenses.manage')
            ->name('expenses.destroy');

        // Cashflow
        Route::get('/cashflow', [CashflowController::class, 'index'])
            ->middleware('can:finance.reports.view')
            ->name('cashflow');

        // Budgeting
        Route::get('/budgets', [BudgetController::class, 'index'])
            ->middleware('can:finance.budgets.view')
            ->name('budgets.index');
        Route::post('/budgets', [BudgetController::class, 'store'])
            ->middleware('can:finance.budgets.manage')
            ->name('budgets.store');
        Route::delete('/budgets/{id}', [BudgetController::class, 'destroy'])
            ->middleware('can:finance.budgets.manage')
            ->name('budgets.destroy');

        // Taxes
        Route::get('/taxes', [TaxRateController::class, 'index'])
            ->middleware('can:finance.budgets.view')
            ->name('taxes.index');
        Route::post('/taxes', [TaxRateController::class, 'store'])
            ->middleware('can:finance.budgets.manage')
            ->name('taxes.store');
        Route::delete('/taxes/{id}', [TaxRateController::class, 'destroy'])
            ->middleware('can:finance.budgets.manage')
            ->name('taxes.destroy');

        // Financial Reports
        Route::prefix('reports')->name('reports.')->middleware('can:finance.reports.view')->group(function (): void {
            Route::get('/expense-summary', [FinanceReportController::class, 'expenseSummary'])->name('expense-summary');
            Route::get('/income-summary', [FinanceReportController::class, 'incomeSummary'])->name('income-summary');
            Route::get('/profit-loss', [FinanceReportController::class, 'profitAndLoss'])->name('profit-loss');
            Route::get('/income-vs-expense', [FinanceReportController::class, 'incomeVsExpense'])->name('income-vs-expense');
            Route::get('/tax-summary', [FinanceReportController::class, 'taxSummary'])->name('tax-summary');
        });
    });
