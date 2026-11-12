<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::get('/core', function () {
    return response()->json([
        'module' => 'core',
        'channel' => 'web',
    ]);
});
