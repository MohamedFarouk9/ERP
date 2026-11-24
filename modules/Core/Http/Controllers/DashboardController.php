<?php

declare(strict_types=1);

namespace Modules\Core\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Modules\Accounting\Models\Account;
use Modules\Accounting\Models\AccountingTransaction;
use Modules\Core\Services\FeatureManager;
use Modules\Core\Services\TenantContext;
use Modules\Inventory\Models\Product;
use Modules\Inventory\Models\StockLevel;
use Modules\Payments\Models\Payment;
use Modules\Procurement\Models\PurchaseOrder;
use Modules\Sales\Models\Customer;
use Modules\Sales\Models\Invoice;
use Modules\Sales\Models\SalesOrder;
use Modules\Sales\Models\SalesOrderItem;

class DashboardController extends Controller
{
    public function index(TenantContext $tenantContext, FeatureManager $featureManager): View
    {
        $tenant = $tenantContext->require();
        $features = $featureManager->all($tenant->id);

        $revenueCents = (int) Invoice::whereIn('status', [Invoice::STATUS_ISSUED, Invoice::STATUS_PAID, Invoice::STATUS_OVERDUE])->sum('total_amount');
        $paymentsCents = (int) Payment::where('status', Payment::STATUS_COMPLETED)->sum('amount');
        $productsCount = Product::count();
        $lowStockCount = StockLevel::query()
            ->whereHas('product', fn ($q) => $q->whereColumn('stock_levels.quantity_on_hand', '<=', 'products.reorder_level'))
            ->count();

        $ordersCount = SalesOrder::count();
        $confirmedOrFulfilled = SalesOrder::whereIn('status', [SalesOrder::STATUS_CONFIRMED, SalesOrder::STATUS_FULFILLED])->count();

        $stats = [
            'orders_count' => $ordersCount,
            'revenue_cents' => $revenueCents,
            'payments_cents' => $paymentsCents,
            'customers_count' => Customer::count(),
            'products_count' => $productsCount,
            'staff_count' => User::count(),
            'accounts_count' => Account::count(),
            'transactions_count' => AccountingTransaction::count(),
            'low_stock_count' => $lowStockCount,
            'collection_rate' => $revenueCents > 0 ? min(100, (int) round(($paymentsCents / $revenueCents) * 100)) : 0,
            'fulfillment_rate' => $ordersCount > 0 ? min(100, (int) round(($confirmedOrFulfilled / $ordersCount) * 100)) : 0,
            'healthy_stock_rate' => $productsCount > 0 ? max(0, min(100, (int) round((($productsCount - min($productsCount, $lowStockCount)) / $productsCount) * 100))) : 100,
        ];

        // 6-Month Time Series (Revenue vs Cash Collected vs Procurement Spend)
        $months = [];
        $monthlyRevenue = [];
        $monthlyCollected = [];
        $monthlySpend = [];

        for ($i = 5; $i >= 0; $i--) {
            $start = now()->subMonths($i)->startOfMonth();
            $end = now()->subMonths($i)->endOfMonth();
            $months[] = $start->format('M Y');

            $rev = (int) Invoice::whereBetween('invoice_date', [$start->toDateString(), $end->toDateString()])
                ->where('status', '!=', Invoice::STATUS_CANCELLED)
                ->sum('total_amount');

            $col = (int) Payment::whereBetween('payment_date', [$start->toDateString(), $end->toDateString()])
                ->where('status', Payment::STATUS_COMPLETED)
                ->sum('amount');

            $spd = (int) PurchaseOrder::whereBetween('order_date', [$start->toDateString(), $end->toDateString()])
                ->where('status', '!=', PurchaseOrder::STATUS_CANCELLED)
                ->sum('total_amount');

            $monthlyRevenue[] = round($rev / 100, 2);
            $monthlyCollected[] = round($col / 100, 2);
            $monthlySpend[] = round($spd / 100, 2);
        }

        $orderStatusBreakdown = SalesOrder::query()
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->all();

        $orderIds = SalesOrder::pluck('id');
        $topProducts = SalesOrderItem::query()
            ->whereIn('sales_order_id', $orderIds)
            ->select('product_id', DB::raw('SUM(quantity) as units_sold'), DB::raw('SUM(line_total) as total_sales'))
            ->groupBy('product_id')
            ->orderByDesc('total_sales')
            ->take(5)
            ->get();

        $productMap = Product::whereIn('id', $topProducts->pluck('product_id'))->get()->keyBy('id');

        $chartData = [
            'months' => $months,
            'revenue' => $monthlyRevenue,
            'collected' => $monthlyCollected,
            'spend' => $monthlySpend,
            'order_statuses' => [
                'confirmed' => (int) ($orderStatusBreakdown['confirmed'] ?? 0),
                'fulfilled' => (int) ($orderStatusBreakdown['fulfilled'] ?? 0),
                'draft' => (int) ($orderStatusBreakdown['draft'] ?? 0),
                'cancelled' => (int) ($orderStatusBreakdown['cancelled'] ?? 0),
            ],
        ];

        $recentOrders = SalesOrder::with('customer')->latest()->take(7)->get();
        $recentPayments = Payment::with('paymentMethod')->latest()->take(6)->get();

        return view('admin.dashboard', compact(
            'tenant',
            'features',
            'stats',
            'chartData',
            'topProducts',
            'productMap',
            'recentOrders',
            'recentPayments'
        ));
    }
}
