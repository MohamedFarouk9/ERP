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
            ->take(10)
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

        $recentMovements = StockMovement::with(['product:id,sku,name', 'warehouse:id,code,name'])
            ->latest()
            ->take(10)
            ->get();

        return view('admin.inventory.dashboard', compact('stats', 'lowStockItems', 'recentMovements'));
    }
}
