<?php

declare(strict_types=1);

namespace Modules\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Inventory\Actions\CreateProductAction;
use Modules\Inventory\Actions\UpdateProductAction;
use Modules\Inventory\Http\Requests\StoreProductRequest;
use Modules\Inventory\Http\Requests\UpdateProductRequest;
use Modules\Inventory\Models\Category;
use Modules\Inventory\Models\Product;
use Modules\Inventory\Models\Warehouse;

class ProductController extends Controller
{
    public function index(): View
    {
        $categories = Category::orderBy('name')->get(['id', 'name']);
        $warehouses = Warehouse::where('is_active', true)->orderBy('name')->get(['id', 'code', 'name']);

        return view('admin.products.index', compact('categories', 'warehouses'));
    }

    public function data(Request $request): JsonResponse
    {
        $draw = (int) $request->input('draw', 1);
        $start = (int) $request->input('start', 0);
        $length = min((int) $request->input('length', 25), 100);
        $search = trim((string) $request->input('search.value', ''));

        $baseQuery = Product::query()->with(['category:id,name', 'stockLevels:id,product_id,quantity_on_hand']);
        $recordsTotal = Product::count();

        if ($search !== '') {
            $baseQuery->where(function ($q) use ($search) {
                $q->where('sku', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%");
            });
        }

        $recordsFiltered = (clone $baseQuery)->count();

        $columns = ['sku', 'name', 'category_id', 'cost_price', 'selling_price', 'id', 'is_active'];
        $orderColIdx = (int) $request->input('order.0.column', 1);
        $orderDir = $request->input('order.0.dir', 'asc') === 'desc' ? 'desc' : 'asc';
        $orderCol = $columns[$orderColIdx] ?? 'name';

        $rows = $baseQuery->orderBy($orderCol, $orderDir)
            ->skip($start)
            ->take($length)
            ->get();

        $data = $rows->map(fn (Product $p) => [
            'id' => $p->id,
            'sku' => $p->sku,
            'name' => $p->name,
            'category_id' => $p->category_id,
            'category_name' => $p->category?->name ?? '-',
            'unit' => $p->unit,
            'cost_price' => $p->cost_price,
            'selling_price' => $p->selling_price,
            'reorder_level' => $p->reorder_level,
            'stock_on_hand' => $p->stockLevels->sum('quantity_on_hand'),
            'is_active' => $p->is_active,
            'description' => $p->description ?? '',
        ]);

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ]);
    }

    public function store(StoreProductRequest $request, CreateProductAction $action): JsonResponse
    {
        $product = $action->execute($request->validated());

        return response()->json([
            'message' => "Product [{$product->sku}] created successfully.",
            'data' => $product,
        ], 201);
    }

    public function update(UpdateProductRequest $request, string $id, UpdateProductAction $action): JsonResponse
    {
        $product = Product::findOrFail($id);
        $updated = $action->execute($product, $request->validated());

        return response()->json([
            'message' => "Product [{$updated->sku}] updated successfully.",
            'data' => $updated,
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $product = Product::findOrFail($id);
        $sku = $product->sku;
        $product->delete();

        return response()->json([
            'message' => "Product [{$sku}] deleted successfully.",
        ]);
    }
}
