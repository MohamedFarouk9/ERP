<?php

declare(strict_types=1);

namespace Modules\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Inventory\Actions\CreateProductAction;
use Modules\Inventory\Http\Requests\StoreProductRequest;
use Modules\Inventory\Models\Product;

class ProductController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Product::query()->with(['category', 'stockLevels.warehouse']);

        if ($request->has('category_id')) {
            $query->where('category_id', $request->query('category_id'));
        }

        if ($request->has('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        $products = $query->orderBy('name')->paginate(50);

        return response()->json($products);
    }

    public function store(StoreProductRequest $request, CreateProductAction $action): JsonResponse
    {
        $product = $action->execute($request->validated());

        return response()->json([
            'message' => 'Product created successfully.',
            'data' => $product,
        ], 201);
    }

    public function show(string $id): JsonResponse
    {
        $product = Product::with(['category', 'stockLevels.warehouse'])->findOrFail($id);

        return response()->json([
            'data' => $product,
        ]);
    }
}
