<?php

declare(strict_types=1);

namespace Modules\Pos\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Modules\Core\Services\TenantContext;
use Modules\Inventory\Models\Product;
use Modules\Pos\Actions\UpdatePosPrintSettingAction;
use Modules\Pos\Http\Requests\UpdatePosPrintSettingRequest;
use Modules\Pos\Models\PosPrintSetting;
use Modules\Pos\Models\PosSale;

class PosPrintController extends Controller
{
    public function orders(): View
    {
        $stats = [
            'total_orders' => PosSale::count(),
            'today_orders' => PosSale::whereDate('sale_date', today())->count(),
            'total_revenue' => (int) PosSale::where('status', PosSale::STATUS_COMPLETED)->sum('total_amount'),
            'cash_revenue' => (int) PosSale::where('payment_type', 'cash')->sum('total_amount'),
        ];

        return view('admin.pos.orders', compact('stats'));
    }

    public function barcodes(TenantContext $tenantContext): View
    {
        $products = Product::where('is_active', true)->orderBy('name')->get(['id', 'sku', 'name', 'selling_price']);
        $settings = PosPrintSetting::first() ?? new PosPrintSetting([
            'tenant_id' => $tenantContext->requireId(),
            'barcode_type' => 'CODE128',
            'label_paper_size' => 'A4',
        ]);

        return view('admin.pos.barcodes', compact('products', 'settings'));
    }

    public function qrcodes(TenantContext $tenantContext): View
    {
        $products = Product::where('is_active', true)->orderBy('name')->get(['id', 'sku', 'name', 'selling_price']);
        $settings = PosPrintSetting::first() ?? new PosPrintSetting([
            'tenant_id' => $tenantContext->requireId(),
            'label_paper_size' => 'A4',
        ]);

        return view('admin.pos.qrcodes', compact('products', 'settings'));
    }

    public function settings(TenantContext $tenantContext): View
    {
        $tenant = $tenantContext->require();
        $settings = PosPrintSetting::firstOrCreate(
            ['tenant_id' => $tenant->id],
            [
                'printer_type' => 'thermal_80mm',
                'barcode_type' => 'CODE128',
                'label_paper_size' => 'A4',
                'store_name' => $tenant->name,
                'footer_message' => 'Thank you for your business!',
            ]
        );

        return view('admin.pos.print-settings', compact('settings', 'tenant'));
    }

    public function updateSettings(UpdatePosPrintSettingRequest $request, UpdatePosPrintSettingAction $action): JsonResponse
    {
        $settings = $action->execute($request->validated());

        return response()->json([
            'message' => 'POS print settings updated successfully.',
            'data' => $settings,
        ]);
    }
}
