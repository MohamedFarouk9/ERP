<?php

declare(strict_types=1);

namespace Modules\Accounting\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Accounting\Actions\CreateAccountingTransactionAction;
use Modules\Accounting\Actions\PostAccountingTransactionAction;
use Modules\Accounting\Http\Requests\StoreAccountingTransactionRequest;
use Modules\Accounting\Models\AccountingTransaction;

class AccountingTransactionController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = AccountingTransaction::query()->with(['lines.account', 'poster']);

        if ($request->has('status')) {
            $query->where('status', $request->query('status'));
        }

        $transactions = $query->orderByDesc('transaction_date')->paginate(25);

        return response()->json($transactions);
    }

    public function store(StoreAccountingTransactionRequest $request, CreateAccountingTransactionAction $action): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $transaction = $action->execute($request->validated(), $user);

        return response()->json([
            'message' => 'Draft accounting transaction created successfully.',
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

        $postedTransaction = $action->execute($transaction, $user);

        return response()->json([
            'message' => 'Accounting transaction posted successfully.',
            'data' => $postedTransaction,
        ]);
    }
}
