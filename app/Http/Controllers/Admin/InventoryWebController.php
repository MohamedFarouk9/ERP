<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Modules\Inventory\Models\Category;
use Modules\Inventory\Models\Product;
use Modules\Inventory\Models\Warehouse;

class InventoryWebController extends Controller
{
    public function products(): View
    {
        $products = Product::with(['category', 'stockLevels.warehouse'])->orderBy('name')->paginate(25);

        return view('admin.products.index', compact('products'));
    }

    public function categories(): View
    {
        $categories = Category::with('parent')->withCount('products')->orderBy('name')->paginate(25);

        return view('admin.categories.index', compact('categories'));
    }

    public function warehouses(): View
    {
        $warehouses = Warehouse::withCount('stockLevels')->orderBy('code')->paginate(25);

        return view('admin.inventory.warehouses', compact('warehouses'));
    }
}
