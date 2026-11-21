<?php

declare(strict_types=1);

namespace Modules\Accounting\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Modules\Accounting\Listeners\RecordInvoiceLedgerEntry;
use Modules\Accounting\Listeners\RecordPaymentLedgerEntry;
use Modules\Payments\Events\PaymentCompleted;
use Modules\Sales\Events\InvoiceIssued;

final class AccountingServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Bindings for Accounting module
    }

    public function boot(): void
    {
        $this->registerRoutes();
        $this->registerMigrations();

        Event::listen(InvoiceIssued::class, RecordInvoiceLedgerEntry::class);
        Event::listen(PaymentCompleted::class, RecordPaymentLedgerEntry::class);
    }

    protected function registerRoutes(): void
    {
        $apiRoutes = __DIR__.'/../routes/api.php';
        if (file_exists($apiRoutes)) {
            Route::prefix('api/v1/accounting')
                ->middleware('api')
                ->name('api.v1.accounting.')
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
