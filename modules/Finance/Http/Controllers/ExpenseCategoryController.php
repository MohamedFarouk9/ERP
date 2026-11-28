<?php

declare(strict_types=1);

namespace Modules\Finance\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Modules\Accounting\Models\Account;
use Modules\Finance\Actions\CreateExpenseCategoryAction;
use Modules\Finance\Http\Requests\StoreExpenseCategoryRequest;
use Modules\Finance\Models\ExpenseCategory;

class ExpenseCategoryController extends Controller
{
    public function index(): View
    {
        $categories = ExpenseCategory::with('account:id,code,name')
            ->withCount('expenses')
            ->orderBy('code')
            ->get();

        $expenseAccounts = Account::where('type', Account::TYPE_EXPENSE)
            ->orderBy('code')
            ->get(['id', 'code', 'name']);

        return view('admin.finance.expense-categories', compact('categories', 'expenseAccounts'));
    }

    public function store(StoreExpenseCategoryRequest $request, CreateExpenseCategoryAction $action): JsonResponse
    {
        $category = $action->execute($request->validated());

        return response()->json([
            'message' => "Category [{$category->name}] created successfully.",
            'data' => $category,
        ], 201);
    }

    public function destroy(string $id): JsonResponse
    {
        $category = ExpenseCategory::withCount('expenses')->findOrFail($id);
        if ($category->expenses_count > 0) {
            return response()->json([
                'message' => "Category [{$category->name}] cannot be deleted because it has recorded expenses.",
            ], 422);
        }

        $name = $category->name;
        $category->delete();

        return response()->json([
            'message' => "Category [{$name}] deleted successfully.",
        ]);
    }
}
