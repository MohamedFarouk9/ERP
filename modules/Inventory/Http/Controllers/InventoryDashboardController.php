<?php

declare(strict_types=1);

namespace Modules\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Modules\Inventory\Models\Category;
use Modules\Inventory\Models\Product;
use Modules\Inventory\Models\StockLevel;
use Modules\Inventory\Models\StockMovement;
use Modules\Inventory\Models\Warehouse;

class InventoryDashboardController extends Controller
{
    public function index(): View
    {
        $valuation = (int) StockLevel::query()
            ->join('products', 'stock_levels.product_id', '=', 'products.id')
            ->sum(DB::raw('stock_levels.quantity_on_hand * products.cost_price'));

        $lowStockItems = StockLevel::query()
            ->with(['product:id,sku,name,unit,reorder_level', 'warehouse:id,code,name'])
            ->whereHas('product', fn ($q) => $q->whereColumn('stock_levels.quantity_on_hand', '<=', 'products.reorder_level'))
            ->orderBy('quantity_on_hand')
            ->take(8)
            ->get();

        $stats = [
            'products_count' => Product::count(),
            'categories_count' => Category::count(),
            'warehouses_count' => Warehouse::count(),
            'total_units' => (int) StockLevel::sum('quantity_on_hand'),
            'inventory_valuation' => $valuation,
            'low_stock_count' => StockLevel::query()
                ->whereHas('product', fn ($q) => $q->whereColumn('stock_levels.quantity_on_hand', '<=', 'products.reorder_level'))
                ->count(),
        ];

        $warehouseStats = Warehouse::query()
            ->withSum('stockLevels as total_qty', 'quantity_on_hand')
            ->orderBy('code')
            ->get();

        $movementCounts = StockMovement::query()
            ->select('type', DB::raw('SUM(ABS(quantity)) as total_qty'))
            ->groupBy('type')
            ->pluck('total_qty', 'type');

        $chartData = [
            'warehouse_labels' => $warehouseStats->pluck('code')->all(),
            'warehouse_units' => $warehouseStats->map(fn ($w) => (int) ($w->total_qty ?? 0))->all(),
            'movements' => [
                'in' => (int) ($movementCounts['in'] ?? 0),
                'out' => (int) ($movementCounts['out'] ?? 0),
                'adjustment' => (int) ($movementCounts['adjustment'] ?? 0),
            ],
        ];

        $recentMovements = StockMovement::with(['product:id,sku,name', 'warehouse:id,code,name'])
            ->latest()
            ->take(8)
            ->get();

        return view('admin.inventory.dashboard', compact('stats', 'chartData', 'lowStockItems', 'recentMovements'));
    }
}
