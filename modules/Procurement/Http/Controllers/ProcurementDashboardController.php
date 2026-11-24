<?php

declare(strict_types=1);

namespace Modules\Procurement\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Modules\Procurement\Models\PurchaseOrder;
use Modules\Procurement\Models\Supplier;
use Modules\Procurement\Models\VendorBill;

class ProcurementDashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'suppliers_count' => Supplier::count(),
            'open_pos_count' => PurchaseOrder::whereIn('status', [PurchaseOrder::STATUS_DRAFT, PurchaseOrder::STATUS_ORDERED])->count(),
            'received_pos_count' => PurchaseOrder::where('status', PurchaseOrder::STATUS_RECEIVED)->count(),
            'total_spend_cents' => (int) PurchaseOrder::where('status', PurchaseOrder::STATUS_RECEIVED)->sum('total_amount'),
            'unpaid_bills_cents' => (int) VendorBill::whereIn('status', [VendorBill::STATUS_DRAFT, VendorBill::STATUS_APPROVED])
                ->selectRaw('COALESCE(SUM(total_amount - amount_paid), 0) as balance')
                ->value('balance'),
            'bills_count' => VendorBill::count(),
        ];

        $months = [];
        $monthlyPoSpend = [];
        $monthlyBills = [];
        for ($i = 5; $i >= 0; $i--) {
            $start = now()->subMonths($i)->startOfMonth();
            $end = now()->subMonths($i)->endOfMonth();
            $months[] = $start->format('M Y');

            $poAmt = (int) PurchaseOrder::whereBetween('order_date', [$start->toDateString(), $end->toDateString()])
                ->where('status', '!=', PurchaseOrder::STATUS_CANCELLED)
                ->sum('total_amount');

            $billAmt = (int) VendorBill::whereBetween('bill_date', [$start->toDateString(), $end->toDateString()])
                ->where('status', '!=', VendorBill::STATUS_CANCELLED)
                ->sum('total_amount');

            $monthlyPoSpend[] = round($poAmt / 100, 2);
            $monthlyBills[] = round($billAmt / 100, 2);
        }

        $poStatuses = PurchaseOrder::query()
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $chartData = [
            'months' => $months,
            'po_spend' => $monthlyPoSpend,
            'bills_volume' => $monthlyBills,
            'po_statuses' => [
                'received' => (int) ($poStatuses['received'] ?? 0),
                'ordered' => (int) ($poStatuses['ordered'] ?? 0),
                'draft' => (int) ($poStatuses['draft'] ?? 0),
                'cancelled' => (int) ($poStatuses['cancelled'] ?? 0),
            ],
        ];

        $recentOrders = PurchaseOrder::with('supplier:id,name')->orderByDesc('order_date')->take(8)->get();
        $recentBills = VendorBill::with('supplier:id,name')->orderByDesc('bill_date')->take(8)->get();

        return view('admin.procurement.dashboard', compact('stats', 'chartData', 'recentOrders', 'recentBills'));
    }
}
