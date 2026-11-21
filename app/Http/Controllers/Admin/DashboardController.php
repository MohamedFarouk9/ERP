<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Modules\Accounting\Models\Account;
use Modules\Accounting\Models\AccountingTransaction;
use Modules\Core\Services\FeatureManager;
use Modules\Core\Services\TenantContext;
use Modules\Inventory\Models\Product;
use Modules\Inventory\Models\StockLevel;
use Modules\Payments\Models\Payment;
use Modules\Sales\Models\Customer;
use Modules\Sales\Models\Invoice;
use Modules\Sales\Models\SalesOrder;

class DashboardController extends Controller
{
    public function index(TenantContext $tenantContext, FeatureManager $featureManager): View
    {
        $tenant = $tenantContext->require();
        $features = $featureManager->all($tenant->id);

        $stats = [
            'orders_count' => SalesOrder::count(),
            'revenue_cents' => (int) Invoice::whereIn('status', [Invoice::STATUS_ISSUED, Invoice::STATUS_PAID])->sum('total_amount'),
            'payments_cents' => (int) Payment::where('status', Payment::STATUS_COMPLETED)->sum('amount'),
            'customers_count' => Customer::count(),
            'products_count' => Product::count(),
            'accounts_count' => Account::count(),
            'transactions_count' => AccountingTransaction::count(),
            'low_stock_count' => StockLevel::query()
                ->whereHas('product', function ($query) {
                    $query->whereColumn('stock_levels.quantity_on_hand', '<=', 'products.reorder_level');
                })
                ->count(),
        ];

        $recentOrders = SalesOrder::with('customer')->latest()->take(6)->get();
        $recentPayments = Payment::with('paymentMethod')->latest()->take(6)->get();

        return view('admin.dashboard', compact('tenant', 'features', 'stats', 'recentOrders', 'recentPayments'));
    }
}
