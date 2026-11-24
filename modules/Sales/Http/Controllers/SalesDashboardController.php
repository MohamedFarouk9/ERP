<?php

declare(strict_types=1);

namespace Modules\Sales\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Modules\Sales\Models\Customer;
use Modules\Sales\Models\Invoice;
use Modules\Sales\Models\SalesOrder;

class SalesDashboardController extends Controller
{
    public function index(): View
    {
        $invoicedTotal = (int) Invoice::whereIn('status', [Invoice::STATUS_ISSUED, Invoice::STATUS_PAID, Invoice::STATUS_OVERDUE])->sum('total_amount');
        $paidTotal = (int) Invoice::whereIn('status', [Invoice::STATUS_ISSUED, Invoice::STATUS_PAID, Invoice::STATUS_OVERDUE])->sum('amount_paid');

        $stats = [
            'customers_count' => Customer::count(),
            'orders_count' => SalesOrder::count(),
            'confirmed_orders' => SalesOrder::where('status', SalesOrder::STATUS_CONFIRMED)->count(),
            'invoiced_revenue' => $invoicedTotal,
            'outstanding_receivables' => max(0, $invoicedTotal - $paidTotal),
            'overdue_invoices' => Invoice::where('status', Invoice::STATUS_OVERDUE)->count(),
        ];

        $months = [];
        $monthlyOrders = [];
        $monthlyInvoiced = [];
        for ($i = 5; $i >= 0; $i--) {
            $start = now()->subMonths($i)->startOfMonth();
            $end = now()->subMonths($i)->endOfMonth();
            $months[] = $start->format('M Y');

            $ordAmt = (int) SalesOrder::whereBetween('order_date', [$start->toDateString(), $end->toDateString()])
                ->where('status', '!=', SalesOrder::STATUS_CANCELLED)
                ->sum('total_amount');

            $invAmt = (int) Invoice::whereBetween('invoice_date', [$start->toDateString(), $end->toDateString()])
                ->where('status', '!=', Invoice::STATUS_CANCELLED)
                ->sum('total_amount');

            $monthlyOrders[] = round($ordAmt / 100, 2);
            $monthlyInvoiced[] = round($invAmt / 100, 2);
        }

        $invByStatus = Invoice::query()
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $chartData = [
            'months' => $months,
            'orders_volume' => $monthlyOrders,
            'invoiced_volume' => $monthlyInvoiced,
            'invoice_statuses' => [
                'paid' => (int) ($invByStatus['paid'] ?? 0),
                'issued' => (int) ($invByStatus['issued'] ?? 0),
                'overdue' => (int) ($invByStatus['overdue'] ?? 0),
                'draft' => (int) ($invByStatus['draft'] ?? 0),
            ],
        ];

        $topCustomers = Invoice::query()
            ->select('customer_id', DB::raw('SUM(total_amount) as total_billed'), DB::raw('COUNT(*) as invoices_count'))
            ->with('customer:id,code,name')
            ->groupBy('customer_id')
            ->orderByDesc('total_billed')
            ->take(6)
            ->get();

        $recentOrders = SalesOrder::with('customer:id,name')
            ->orderByDesc('order_date')
            ->take(8)
            ->get();

        return view('admin.sales.dashboard', compact('stats', 'chartData', 'topCustomers', 'recentOrders'));
    }
}
