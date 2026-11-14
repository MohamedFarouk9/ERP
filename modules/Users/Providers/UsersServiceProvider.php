<?php

declare(strict_types=1);

namespace Modules\Users\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Modules\Users\Services\TotpService;

final class UsersServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(TotpService::class);
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
            Route::prefix('api/v1/users')
                ->middleware('api')
                ->name('api.v1.users.')
                ->group($apiRoutes);
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

