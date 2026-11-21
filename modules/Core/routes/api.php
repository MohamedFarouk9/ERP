<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Core\Http\Controllers\FeatureController;

Route::get('/ping', function () {
    return response()->json([
        'module' => 'core',
        'status' => 'active',
        'timestamp' => now()->toIso8601String(),
    ]);
});

Route::middleware(['auth', 'tenant', 'tenant.active'])->prefix('features')->group(function () {
    Route::get('/', [FeatureController::class, 'index'])
        ->middleware('can:core.features.view')
        ->name('features.index');
    Route::get('/{feature}', [FeatureController::class, 'show'])
        ->middleware('can:core.features.view')
        ->name('features.show');
    Route::put('/{feature}', [FeatureController::class, 'update'])
        ->middleware('can:core.features.manage')
        ->name('features.update');
});
