<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Core\Services\FeatureManager;

class FeatureFlagController extends Controller
{
    public function index(FeatureManager $featureManager): View
    {
        $availableModules = [
            'accounting' => 'Accounting & General Ledger',
            'inventory' => 'Inventory & Warehouses',
            'sales' => 'Sales Orders & Invoices',
            'payments' => 'Payments & Refunds',
        ];

        $statuses = $featureManager->all();

        return view('admin.features.index', compact('availableModules', 'statuses'));
    }

    public function toggle(Request $request, string $feature, FeatureManager $featureManager): RedirectResponse
    {
        $enable = $request->boolean('is_enabled');

        if ($enable) {
            $featureManager->enable($feature);
        } else {
            $featureManager->disable($feature);
        }

        return back()->with('success', "Feature [{$feature}] has been ".($enable ? 'enabled' : 'disabled').'.');
    }
}
