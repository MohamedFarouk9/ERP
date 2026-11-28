<?php

declare(strict_types=1);

namespace Modules\Finance\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Modules\Finance\Actions\CreateBudgetAction;
use Modules\Finance\Http\Requests\StoreBudgetRequest;
use Modules\Finance\Models\Budget;
use Modules\Finance\Models\Expense;
use Modules\Finance\Models\ExpenseCategory;

class BudgetController extends Controller
{
    public function index(): View
    {
        $categories = ExpenseCategory::where('is_active', true)->orderBy('name')->get(['id', 'code', 'name']);

        $budgets = Budget::with('category:id,code,name')
            ->orderByDesc('period_month')
            ->get()
            ->map(function (Budget $b) {
                $actual = (int) Expense::where('expense_category_id', $b->expense_category_id)
                    ->where('expense_date', 'like', $b->period_month.'%')
                    ->sum('total_amount');

                $b->setAttribute('actual_spent', $actual);
                $b->setAttribute('remaining', $b->budget_amount - $actual);
                $b->setAttribute('utilization_pct', $b->budget_amount > 0 ? (int) round(($actual / $b->budget_amount) * 100) : 0);

                return $b;
            });

        $summary = [
            'total_budget' => (int) $budgets->sum('budget_amount'),
            'total_spent' => (int) $budgets->sum('actual_spent'),
            'over_budget_count' => $budgets->filter(fn ($b) => $b->actual_spent > $b->budget_amount)->count(),
        ];

        return view('admin.finance.budgets', compact('budgets', 'categories', 'summary'));
    }

    public function store(StoreBudgetRequest $request, CreateBudgetAction $action): JsonResponse
    {
        $budget = $action->execute($request->validated());

        return response()->json([
            'message' => "Budget [{$budget->name}] saved successfully.",
            'data' => $budget,
        ], 201);
    }

    public function destroy(string $id): JsonResponse
    {
        $budget = Budget::findOrFail($id);
        $name = $budget->name;
        $budget->delete();

        return response()->json([
            'message' => "Budget [{$name}] deleted successfully.",
        ]);
    }
}
