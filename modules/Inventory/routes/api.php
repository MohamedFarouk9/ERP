<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Inventory\Http\Controllers\CategoryController;
use Modules\Inventory\Http\Controllers\ProductController;
use Modules\Inventory\Http\Controllers\StockMovementController;
use Modules\Inventory\Http\Controllers\WarehouseController;

Route::middleware(['auth', 'tenant', 'tenant.active', 'feature:inventory'])->group(function () {
    // Categories
    Route::get('/categories', [CategoryController::class, 'index'])
        ->middleware('can:inventory.categories.view')
        ->name('categories.index');
    Route::post('/categories', [CategoryController::class, 'store'])
        ->middleware('can:inventory.categories.create')
        ->name('categories.store');
    Route::get('/categories/{id}', [CategoryController::class, 'show'])
        ->middleware('can:inventory.categories.view')
        ->name('categories.show');

    // Warehouses
    Route::get('/warehouses', [WarehouseController::class, 'index'])
        ->middleware('can:inventory.warehouses.view')
        ->name('warehouses.index');
    Route::post('/warehouses', [WarehouseController::class, 'store'])
        ->middleware('can:inventory.warehouses.create')
        ->name('warehouses.store');
    Route::get('/warehouses/{id}', [WarehouseController::class, 'show'])
        ->middleware('can:inventory.warehouses.view')
        ->name('warehouses.show');

    // Products
    Route::get('/products', [ProductController::class, 'index'])
        ->middleware('can:inventory.products.view')
        ->name('products.index');
    Route::post('/products', [ProductController::class, 'store'])
        ->middleware('can:inventory.products.create')
        ->name('products.store');
    Route::get('/products/{id}', [ProductController::class, 'show'])
        ->middleware('can:inventory.products.view')
        ->name('products.show');

    // Stock Movements
    Route::get('/stock-movements', [StockMovementController::class, 'index'])
        ->middleware('can:inventory.stock-movements.view')
        ->name('stock-movements.index');
    Route::post('/stock-movements', [StockMovementController::class, 'store'])
        ->middleware('can:inventory.stock-movements.create')
        ->name('stock-movements.store');
});
