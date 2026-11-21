<?php

declare(strict_types=1);

namespace Modules\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Modules\Inventory\Actions\CreateCategoryAction;
use Modules\Inventory\Actions\UpdateCategoryAction;
use Modules\Inventory\Http\Requests\StoreCategoryRequest;
use Modules\Inventory\Http\Requests\UpdateCategoryRequest;
use Modules\Inventory\Models\Category;

class CategoryController extends Controller
{
    public function index(): View
    {
        $categories = Category::with('parent')->withCount('products')->orderBy('name')->get();

        return view('admin.categories.index', compact('categories'));
    }

    public function store(StoreCategoryRequest $request, CreateCategoryAction $action): JsonResponse
    {
        $category = $action->execute($request->validated());

        return response()->json([
            'message' => "Category [{$category->name}] created successfully.",
            'data' => $category,
        ], 201);
    }

    public function update(UpdateCategoryRequest $request, string $id, UpdateCategoryAction $action): JsonResponse
    {
        $category = Category::findOrFail($id);
        $updated = $action->execute($category, $request->validated());

        return response()->json([
            'message' => "Category [{$updated->name}] updated successfully.",
            'data' => $updated,
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $category = Category::findOrFail($id);
        $name = $category->name;
        $category->delete();

        return response()->json([
            'message' => "Category [{$name}] deleted successfully.",
        ]);
    }
}
