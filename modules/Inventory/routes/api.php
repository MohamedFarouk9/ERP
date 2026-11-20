<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Inventory\Http\Controllers\CategoryController;
use Modules\Inventory\Http\Controllers\ProductController;
use Modules\Inventory\Http\Controllers\StockMovementController;
use Modules\Inventory\Http\Controllers\WarehouseController;

Route::middleware(['auth', 'tenant', 'tenant.active', 'feature:inventory'])->group(function () {
    // Categories
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{id}', [CategoryController::class, 'show'])->name('categories.show');

    // Warehouses
    Route::get('/warehouses', [WarehouseController::class, 'index'])->name('warehouses.index');
    Route::post('/warehouses', [WarehouseController::class, 'store'])->name('warehouses.store');
    Route::get('/warehouses/{id}', [WarehouseController::class, 'show'])->name('warehouses.show');

    // Products
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');

    // Stock Movements
    Route::get('/stock-movements', [StockMovementController::class, 'index'])->name('stock-movements.index');
    Route::post('/stock-movements', [StockMovementController::class, 'store'])->name('stock-movements.store');
});
