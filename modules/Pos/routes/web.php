<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Pos\Http\Controllers\PosDashboardController;
use Modules\Pos\Http\Controllers\PosPrintController;
use Modules\Pos\Http\Controllers\PosShiftController;
use Modules\Pos\Http\Controllers\PosTerminalController;

Route::prefix('admin/pos')
    ->name('admin.pos.')
    ->middleware(['auth', 'tenant', 'tenant.active', 'feature:pos'])
    ->group(function (): void {
        Route::get('/dashboard', [PosDashboardController::class, 'index'])
            ->middleware('can:pos.terminal.view')
            ->name('dashboard');

        // Cashier Terminal & Receipts
        Route::get('/terminal', [PosTerminalController::class, 'index'])
            ->middleware('can:pos.terminal.view')
            ->name('terminal');
        Route::post('/checkout', [PosTerminalController::class, 'checkout'])
            ->middleware('can:pos.sales.create')
            ->name('checkout');
        Route::get('/receipts/{id}', [PosTerminalController::class, 'receipt'])
            ->middleware('can:pos.terminal.view')
            ->name('receipt');

        // POS Orders, Barcode Print, QR Print & Print Settings
        Route::get('/orders', [PosPrintController::class, 'orders'])
            ->middleware('can:pos.terminal.view')
            ->name('orders.index');
        Route::get('/barcodes', [PosPrintController::class, 'barcodes'])
            ->middleware('can:pos.terminal.view')
            ->name('barcodes');
        Route::get('/qrcodes', [PosPrintController::class, 'qrcodes'])
            ->middleware('can:pos.terminal.view')
            ->name('qrcodes');
        Route::get('/print-settings', [PosPrintController::class, 'settings'])
            ->middleware('can:pos.shifts.manage')
            ->name('print-settings');
        Route::post('/print-settings', [PosPrintController::class, 'updateSettings'])
            ->middleware('can:pos.shifts.manage')
            ->name('print-settings.update');

        // Register Shifts & Sales History
        Route::get('/shifts', [PosShiftController::class, 'index'])
            ->middleware('can:pos.shifts.view')
            ->name('shifts.index');
        Route::get('/shifts/data', [PosShiftController::class, 'data'])
            ->middleware('can:pos.shifts.view')
            ->name('shifts.data');
        Route::get('/sales/data', [PosShiftController::class, 'salesData'])
            ->middleware('can:pos.shifts.view')
            ->name('sales.data');
        Route::post('/shifts/open', [PosShiftController::class, 'open'])
            ->middleware('can:pos.shifts.manage')
            ->name('shifts.open');
        Route::post('/shifts/{id}/close', [PosShiftController::class, 'close'])
            ->middleware('can:pos.shifts.manage')
            ->name('shifts.close');
    });
