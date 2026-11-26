<?php

declare(strict_types=1);

namespace Modules\Core\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Core\Services\FeatureManager;

class FeatureController extends Controller
{
    public function __construct(
        protected FeatureManager $featureManager
    ) {}

    public function index(): View
    {
        $availableModules = [
            'accounting' => 'Accounting & General Ledger',
            'inventory' => 'Inventory & Warehouses',
            'sales' => 'Sales Orders & Invoices',
            'payments' => 'Payments & Refunds',
            'procurement' => 'Procurement & Suppliers',
            'hrm' => 'Human Resources & Payroll',
            'pos' => 'Point of Sale (POS)',
            'crm' => 'CRM (Leads & Deals)',
        ];

        $statuses = $this->featureManager->all();

        return view('admin.features.index', compact('availableModules', 'statuses'));
    }

    public function toggle(Request $request, string $feature): JsonResponse|RedirectResponse
    {
        $enable = $request->boolean('is_enabled');

        if ($enable) {
            $this->featureManager->enable($feature);
        } else {
            $this->featureManager->disable($feature);
        }

        $message = "Feature [{$feature}] has been ".($enable ? 'enabled' : 'disabled').'.';

        if ($request->expectsJson()) {
            return response()->json(['message' => $message, 'is_enabled' => $enable]);
        }

        return back()->with('success', $message);
    }
}
