<?php

declare(strict_types=1);

namespace Modules\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Inventory\Actions\CreateCategoryAction;
use Modules\Inventory\Http\Requests\StoreCategoryRequest;
use Modules\Inventory\Models\Category;

class CategoryController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Category::query()->with('children');

        if ($request->boolean('root_only')) {
            $query->whereNull('parent_id');
        }

        $categories = $query->orderBy('name')->paginate(50);

        return response()->json($categories);
    }

    public function store(StoreCategoryRequest $request, CreateCategoryAction $action): JsonResponse
    {
        $category = $action->execute($request->validated());

        return response()->json([
            'message' => 'Category created successfully.',
            'data' => $category,
        ], 201);
    }

    public function show(string $id): JsonResponse
    {
        $category = Category::with(['parent', 'children', 'products'])->findOrFail($id);

        return response()->json([
            'data' => $category,
        ]);
    }
}
