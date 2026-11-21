<?php

declare(strict_types=1);

namespace Modules\Accounting\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Accounting\Actions\CreateAccountingTransactionAction;
use Modules\Accounting\Actions\PostAccountingTransactionAction;
use Modules\Accounting\Http\Requests\StoreAccountingTransactionRequest;
use Modules\Accounting\Models\Account;
use Modules\Accounting\Models\AccountingTransaction;
use Modules\Accounting\Models\FinancialPeriod;

class AccountingTransactionController extends Controller
{
    public function index(): View
    {
        $accounts = Account::where('is_active', true)->orderBy('code')->get(['id', 'code', 'name', 'type']);
        $periods = FinancialPeriod::where('is_closed', false)->orderBy('start_date')->get(['id', 'name']);

        return view('admin.accounting.transactions', compact('accounts', 'periods'));
    }

    public function data(Request $request): JsonResponse
    {
        $draw = (int) $request->input('draw', 1);
        $start = (int) $request->input('start', 0);
        $length = min((int) $request->input('length', 25), 100);
        $search = trim((string) $request->input('search.value', ''));

        $baseQuery = AccountingTransaction::query()->with(['lines:id,transaction_id,debit,credit']);
        $recordsTotal = AccountingTransaction::count();

        if ($search !== '') {
            $baseQuery->where(function ($q) use ($search) {
                $q->where('transaction_number', 'like', "%{$search}%")
                    ->orWhere('reference_type', 'like', "%{$search}%")
                    ->orWhere('notes', 'like', "%{$search}%");
            });
        }

        $recordsFiltered = (clone $baseQuery)->count();

        $columns = ['transaction_number', 'transaction_date', 'reference_type', 'notes', 'id', 'status'];
        $orderColIdx = (int) $request->input('order.0.column', 1);
        $orderDir = $request->input('order.0.dir', 'desc') === 'asc' ? 'asc' : 'desc';
        $orderCol = $columns[$orderColIdx] ?? 'transaction_date';

        $rows = $baseQuery->orderBy($orderCol, $orderDir)
            ->skip($start)
            ->take($length)
            ->get();

        $data = $rows->map(fn (AccountingTransaction $txn) => [
            'id' => $txn->id,
            'transaction_number' => $txn->transaction_number,
            'transaction_date' => $txn->transaction_date?->format('Y-m-d') ?? '-',
            'reference_type' => $txn->reference_type ?? '-',
            'notes' => $txn->notes ?? '-',
            'total_debit' => (int) $txn->lines->sum('debit'),
            'lines_count' => $txn->lines->count(),
            'status' => $txn->status,
        ]);

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ]);
    }

    public function store(StoreAccountingTransactionRequest $request, CreateAccountingTransactionAction $action): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $transaction = $action->execute($request->validated(), $user);

        return response()->json([
            'message' => "Draft transaction [{$transaction->transaction_number}] created successfully.",
            'data' => $transaction,
        ], 201);
    }

    public function show(string $id): JsonResponse
    {
        $transaction = AccountingTransaction::with(['lines.account', 'poster', 'financialPeriod'])->findOrFail($id);

        return response()->json([
            'data' => $transaction,
        ]);
    }

    public function post(Request $request, string $id, PostAccountingTransactionAction $action): JsonResponse
    {
        $transaction = AccountingTransaction::with('lines')->findOrFail($id);

        /** @var User $user */
        $user = $request->user();

        $posted = $action->execute($transaction, $user);

        return response()->json([
            'message' => "Transaction [{$posted->transaction_number}] posted to the general ledger.",
            'data' => $posted,
        ]);
    }
}
