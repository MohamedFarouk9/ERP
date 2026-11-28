<?php

declare(strict_types=1);

namespace Modules\Finance\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Finance\Actions\CreateExpenseAction;
use Modules\Finance\Actions\PayExpenseAction;
use Modules\Finance\Http\Requests\StoreExpenseRequest;
use Modules\Finance\Models\Expense;
use Modules\Finance\Models\ExpenseCategory;
use Modules\Finance\Models\TaxRate;

class ExpenseController extends Controller
{
    public function index(): View
    {
        $categories = ExpenseCategory::where('is_active', true)->orderBy('name')->get(['id', 'code', 'name']);
        $taxRates = TaxRate::where('is_active', true)->orderBy('code')->get(['id', 'code', 'name', 'rate_bps']);

        return view('admin.finance.expenses', compact('categories', 'taxRates'));
    }

    public function data(Request $request): JsonResponse
    {
        $draw = (int) $request->input('draw', 1);
        $start = (int) $request->input('start', 0);
        $length = min((int) $request->input('length', 25), 100);
        $search = trim((string) $request->input('search.value', ''));

        $baseQuery = Expense::query()->with(['category:id,name', 'taxRate:id,code,rate_bps']);
        $recordsTotal = Expense::count();

        if ($search !== '') {
            $baseQuery->where(function ($q) use ($search) {
                $q->where('expense_number', 'like', "%{$search}%")
                    ->orWhere('payee', 'like', "%{$search}%")
                    ->orWhere('reference', 'like', "%{$search}%");
            });
        }

        $recordsFiltered = (clone $baseQuery)->count();
        $rows = $baseQuery->latest('expense_date')->skip($start)->take($length)->get();

        $data = $rows->map(fn (Expense $e) => [
            'id' => $e->id,
            'expense_number' => $e->expense_number,
            'expense_date' => $e->expense_date?->format('Y-m-d') ?? '-',
            'category_name' => $e->category?->name ?? '-',
            'payee' => $e->payee,
            'payment_method' => $e->payment_method,
            'amount' => $e->amount,
            'tax_amount' => $e->tax_amount,
            'total_amount' => $e->total_amount,
            'status' => $e->status,
        ]);

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ]);
    }

    public function store(StoreExpenseRequest $request, CreateExpenseAction $action): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();
        $expense = $action->execute($request->validated(), $user);

        return response()->json([
            'message' => "Expense [{$expense->expense_number}] recorded successfully.",
            'data' => $expense,
        ], 201);
    }

    public function pay(Request $request, string $id, PayExpenseAction $action): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();
        $expense = Expense::findOrFail($id);
        $paid = $action->execute($expense, $user);

        return response()->json([
            'message' => "Expense [{$paid->expense_number}] marked as paid.",
            'data' => $paid,
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $expense = Expense::findOrFail($id);
        if ($expense->status === Expense::STATUS_PAID) {
            return response()->json([
                'message' => "Expense [{$expense->expense_number}] cannot be deleted because it is already paid.",
            ], 422);
        }

        $num = $expense->expense_number;
        $expense->delete();

        return response()->json([
            'message' => "Expense [{$num}] deleted successfully.",
        ]);
    }
}
