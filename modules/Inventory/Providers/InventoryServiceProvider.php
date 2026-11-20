<?php

declare(strict_types=1);

namespace Modules\Inventory\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

final class InventoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->registerRoutes();
        $this->registerMigrations();
    }

    protected function registerRoutes(): void
    {
        $apiRoutes = __DIR__.'/../routes/api.php';
        if (file_exists($apiRoutes)) {
            Route::prefix('api/v1/inventory')
                ->middleware('api')
                ->name('api.v1.inventory.')
                ->group($apiRoutes);
        }

        $webRoutes = __DIR__.'/../routes/web.php';
        if (file_exists($webRoutes)) {
            Route::middleware('web')
                ->group($webRoutes);
        }
    }

    protected function registerMigrations(): void
    {
        $migrations = __DIR__.'/../Database/Migrations';
        if (is_dir($migrations)) {
            $this->loadMigrationsFrom($migrations);
        }
    }
}
