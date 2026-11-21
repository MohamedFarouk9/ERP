<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Core\Http\Controllers\DashboardController;
use Modules\Core\Http\Controllers\FeatureController;

Route::get('/core', function () {
    return response()->json([
        'module' => 'core',
        'channel' => 'web',
    ]);
});

Route::prefix('admin')->name('admin.')->middleware(['auth', 'tenant', 'tenant.active'])->group(function (): void {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/features', [FeatureController::class, 'index'])
        ->middleware('can:core.features.view')
        ->name('features.index');
    Route::post('/features/{feature}/toggle', [FeatureController::class, 'toggle'])
        ->middleware('can:core.features.manage')
        ->name('features.toggle');
});
