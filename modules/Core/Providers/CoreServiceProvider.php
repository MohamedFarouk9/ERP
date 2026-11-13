<?php

declare(strict_types=1);

namespace Modules\Core\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Modules\Core\Services\TenantContext;

final class CoreServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(TenantContext::class);
    }

    public function boot(): void
    {
        $this->registerRoutes();
        $this->registerMigrations();
        $this->registerViews();
        $this->registerTranslations();
    }

    protected function registerRoutes(): void
    {
        $apiRoutes = __DIR__.'/../routes/api.php';
        if (file_exists($apiRoutes)) {
            Route::prefix('api/v1/core')
                ->middleware('api')
                ->name('api.v1.core.')
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

    protected function registerViews(): void
    {
        $views = __DIR__.'/../Resources/views';
        if (is_dir($views)) {
            $this->loadViewsFrom($views, 'core');
        }
    }

    protected function registerTranslations(): void
    {
        $lang = __DIR__.'/../Resources/lang';
        if (is_dir($lang)) {
            $this->loadTranslationsFrom($lang, 'core');
        }
    }
}
