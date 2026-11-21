<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Inventory\Http\Controllers\CategoryController;
use Modules\Inventory\Http\Controllers\ProductController;
use Modules\Inventory\Http\Controllers\StockMovementController;
use Modules\Inventory\Http\Controllers\WarehouseController;

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'tenant', 'tenant.active', 'feature:inventory'])
    ->group(function (): void {
        // Products
        Route::get('/products', [ProductController::class, 'index'])
            ->middleware('can:inventory.products.view')
            ->name('products.index');
        Route::get('/products/data', [ProductController::class, 'data'])
            ->middleware('can:inventory.products.view')
            ->name('products.data');
        Route::post('/products', [ProductController::class, 'store'])
            ->middleware('can:inventory.products.create')
            ->name('products.store');
        Route::put('/products/{id}', [ProductController::class, 'update'])
            ->middleware('can:inventory.products.create')
            ->name('products.update');
        Route::delete('/products/{id}', [ProductController::class, 'destroy'])
            ->middleware('can:inventory.products.create')
            ->name('products.destroy');

        // Stock Movements
        Route::post('/stock-movements', [StockMovementController::class, 'store'])
            ->middleware('can:inventory.stock-movements.create')
            ->name('stock-movements.store');

        // Categories
        Route::get('/categories', [CategoryController::class, 'index'])
            ->middleware('can:inventory.categories.view')
            ->name('categories.index');
        Route::post('/categories', [CategoryController::class, 'store'])
            ->middleware('can:inventory.categories.create')
            ->name('categories.store');
        Route::put('/categories/{id}', [CategoryController::class, 'update'])
            ->middleware('can:inventory.categories.create')
            ->name('categories.update');
        Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])
            ->middleware('can:inventory.categories.create')
            ->name('categories.destroy');

        // Warehouses
        Route::get('/warehouses', [WarehouseController::class, 'index'])
            ->middleware('can:inventory.warehouses.view')
            ->name('warehouses.index');
        Route::post('/warehouses', [WarehouseController::class, 'store'])
            ->middleware('can:inventory.warehouses.create')
            ->name('warehouses.store');
        Route::put('/warehouses/{id}', [WarehouseController::class, 'update'])
            ->middleware('can:inventory.warehouses.create')
            ->name('warehouses.update');
        Route::delete('/warehouses/{id}', [WarehouseController::class, 'destroy'])
            ->middleware('can:inventory.warehouses.create')
            ->name('warehouses.destroy');
    });
