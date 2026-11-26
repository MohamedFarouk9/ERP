<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Crm\Http\Controllers\ActivityController;
use Modules\Crm\Http\Controllers\CrmDashboardController;
use Modules\Crm\Http\Controllers\DealController;
use Modules\Crm\Http\Controllers\LeadController;

Route::prefix('admin/crm')
    ->name('admin.crm.')
    ->middleware(['auth', 'tenant', 'tenant.active', 'feature:crm'])
    ->group(function (): void {
        Route::get('/dashboard', [CrmDashboardController::class, 'index'])
            ->middleware('can:crm.leads.view')
            ->name('dashboard');

        // Leads
        Route::get('/leads', [LeadController::class, 'index'])
            ->middleware('can:crm.leads.view')
            ->name('leads.index');
        Route::get('/leads/data', [LeadController::class, 'data'])
            ->middleware('can:crm.leads.view')
            ->name('leads.data');
        Route::post('/leads', [LeadController::class, 'store'])
            ->middleware('can:crm.leads.manage')
            ->name('leads.store');
        Route::put('/leads/{id}', [LeadController::class, 'update'])
            ->middleware('can:crm.leads.manage')
            ->name('leads.update');
        Route::post('/leads/{id}/convert', [LeadController::class, 'convert'])
            ->middleware('can:crm.leads.manage')
            ->name('leads.convert');

        // Deals Pipeline
        Route::get('/deals', [DealController::class, 'index'])
            ->middleware('can:crm.deals.view')
            ->name('deals.index');
        Route::get('/deals/data', [DealController::class, 'data'])
            ->middleware('can:crm.deals.view')
            ->name('deals.data');
        Route::post('/deals', [DealController::class, 'store'])
            ->middleware('can:crm.deals.manage')
            ->name('deals.store');
        Route::post('/deals/{id}/stage', [DealController::class, 'updateStage'])
            ->middleware('can:crm.deals.manage')
            ->name('deals.stage');

        // Activities
        Route::get('/activities', [ActivityController::class, 'index'])
            ->middleware('can:crm.activities.view')
            ->name('activities.index');
        Route::get('/activities/data', [ActivityController::class, 'data'])
            ->middleware('can:crm.activities.view')
            ->name('activities.data');
        Route::post('/activities', [ActivityController::class, 'store'])
            ->middleware('can:crm.activities.manage')
            ->name('activities.store');
        Route::post('/activities/{id}/complete', [ActivityController::class, 'complete'])
            ->middleware('can:crm.activities.manage')
            ->name('activities.complete');
    });
