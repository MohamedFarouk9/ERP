<?php

declare(strict_types=1);

namespace Modules\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Modules\Inventory\Models\Category;
use Modules\Inventory\Models\Product;
use Modules\Inventory\Models\StockLevel;
use Modules\Inventory\Models\StockMovement;
use Modules\Inventory\Models\Warehouse;
use Modules\Procurement\Models\Supplier;

class InventoryDashboardController extends Controller
{
    public function index(Request $request): View
    {
        $year = (int) $request->integer('year', (int) now()->year);
        if ($year < 2020 || $year > 2100) {
            $year = (int) now()->year;
        }

        $valuation = (int) StockLevel::query()
            ->join('products', 'stock_levels.product_id', '=', 'products.id')
            ->sum(DB::raw('stock_levels.quantity_on_hand * products.cost_price'));

        $totalUnits = (int) StockLevel::sum('quantity_on_hand');

        $lowStockCount = StockLevel::query()
            ->whereHas('product', fn ($q) => $q->whereColumn('stock_levels.quantity_on_hand', '<=', 'products.reorder_level'))
            ->count();

        $outOfStockCount = Product::query()
            ->where('track_inventory', true)
            ->withSum('stockLevels as total_qty', 'quantity_on_hand')
            ->get()
            ->filter(fn ($p) => (int) ($p->total_qty ?? 0) <= 0)
            ->count();

        // Category Distribution (top 6 categories by total units, fallback to product count)
        $categories = Category::query()
            ->with(['products' => fn ($q) => $q->withSum('stockLevels as total_qty', 'quantity_on_hand')])
            ->get()
            ->map(function (Category $cat): array {
                $units = (int) $cat->products->sum(fn ($p) => (int) ($p->total_qty ?? 0));

                return [
                    'name' => $cat->name,
                    'units' => $units > 0 ? $units : $cat->products->count(),
                    'products_count' => $cat->products->count(),
                ];
            })
            ->sortByDesc('units')
            ->take(6)
            ->values();

        // Monthly Stock Movements & Inventory Value Trend (Jan - Dec for selected year)
        $movementsYear = StockMovement::query()
            ->with('product:id,cost_price')
            ->whereYear('created_at', $year)
            ->get();

        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $monthlyIn = array_fill(0, 12, 0);
        $monthlyOut = array_fill(0, 12, 0);
        $monthlyNetDelta = array_fill(0, 12, 0);
        $monthlyValueDelta = array_fill(0, 12, 0);

        foreach ($movementsYear as $mv) {
            $mIdx = (int) Carbon::parse($mv->created_at)->month - 1;
            $qty = abs((int) $mv->quantity);
            $cost = (int) ($mv->product?->cost_price ?? 0);

            if ($mv->type === StockMovement::TYPE_IN) {
                $monthlyIn[$mIdx] += $qty;
                $monthlyNetDelta[$mIdx] += $qty;
                $monthlyValueDelta[$mIdx] += ($qty * $cost);
            } elseif ($mv->type === StockMovement::TYPE_OUT) {
                $monthlyOut[$mIdx] += $qty;
                $monthlyNetDelta[$mIdx] -= $qty;
                $monthlyValueDelta[$mIdx] -= ($qty * $cost);
            } else {
                $monthlyNetDelta[$mIdx] += (int) $mv->quantity;
                $monthlyValueDelta[$mIdx] += ((int) $mv->quantity * $cost);
            }
        }

        // Build cumulative monthly stock & value trend ending at current totals
        $currentMonthIdx = $year === (int) now()->year ? ((int) now()->month - 1) : 11;
        $monthlyStockSeries = array_fill(0, 12, 0);
        $monthlyValueSeries = array_fill(0, 12, 0.0);

        $runningUnits = $totalUnits;
        $runningVal = $valuation;
        for ($i = $currentMonthIdx; $i >= 0; $i--) {
            $monthlyStockSeries[$i] = max(0, $runningUnits);
            $monthlyValueSeries[$i] = round(max(0, $runningVal) / 100, 2);
            $runningUnits -= $monthlyNetDelta[$i];
            $runningVal -= $monthlyValueDelta[$i];
        }
        // Fill future months gently with current snapshot if viewing current year
        for ($i = $currentMonthIdx + 1; $i < 12; $i++) {
            $monthlyStockSeries[$i] = $totalUnits;
            $monthlyValueSeries[$i] = round($valuation / 100, 2);
        }

        // Suppliers summary (from Procurement module if available)
        $suppliers = Supplier::query()
            ->withSum('purchaseOrders as supplied_total', 'total_amount')
            ->orderByDesc('supplied_total')
            ->take(5)
            ->get();

        // Warehouses with utilization share
        $warehouses = Warehouse::query()
            ->withSum('stockLevels as total_qty', 'quantity_on_hand')
            ->orderByDesc('total_qty')
            ->take(5)
            ->get()
            ->map(function (Warehouse $wh) use ($totalUnits): array {
                $qty = (int) ($wh->total_qty ?? 0);
                $pct = $totalUnits > 0 ? (int) min(100, round(($qty / $totalUnits) * 100)) : 0;

                return [
                    'code' => $wh->code,
                    'name' => $wh->name,
                    'address' => $wh->address ?: __('Main Storage'),
                    'qty' => $qty,
                    'percent' => $pct,
                    'is_active' => (bool) $wh->is_active,
                ];
            });

        // Products table (latest 10 products with live stock)
        $inventoryProducts = Product::query()
            ->with('category:id,name')
            ->withSum('stockLevels as total_qty', 'quantity_on_hand')
            ->latest()
            ->take(10)
            ->get();

        $inTotal = array_sum($monthlyIn);
        $outTotal = array_sum($monthlyOut);
        $stockGrowthPct = $totalUnits > 0 ? round((($inTotal - $outTotal) / max(1, $totalUnits)) * 100, 2) : 0.0;

        $stats = [
            'products_count' => Product::count(),
            'categories_count' => Category::count(),
            'warehouses_count' => Warehouse::count(),
            'total_units' => $totalUnits,
            'inventory_valuation' => $valuation,
            'low_stock_count' => $lowStockCount,
            'out_of_stock_count' => $outOfStockCount,
            'stock_growth_pct' => $stockGrowthPct,
        ];

        $chartData = [
            'months' => $months,
            'category_labels' => $categories->pluck('name')->all(),
            'category_values' => $categories->pluck('units')->all(),
            'monthly_in' => $monthlyIn,
            'monthly_out' => $monthlyOut,
            'monthly_stock_series' => $monthlyStockSeries,
            'monthly_value_series' => $monthlyValueSeries,
        ];

        return view('admin.inventory.dashboard', compact(
            'year',
            'stats',
            'chartData',
            'suppliers',
            'warehouses',
            'inventoryProducts'
        ));
    }
}
