<?php

declare(strict_types=1);

namespace Modules\Accounting\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Modules\Accounting\Actions\CreateAccountAction;
use Modules\Accounting\Actions\UpdateAccountAction;
use Modules\Accounting\Http\Requests\StoreAccountRequest;
use Modules\Accounting\Http\Requests\UpdateAccountRequest;
use Modules\Accounting\Models\Account;
use Modules\Accounting\Models\Currency;

class AccountController extends Controller
{
    public function index(): View
    {
        $accounts = Account::with('parent')->orderBy('code')->get();
        $currencies = Currency::where('is_active', true)->orderBy('code')->get();

        return view('admin.accounting.accounts', compact('accounts', 'currencies'));
    }

    public function store(StoreAccountRequest $request, CreateAccountAction $action): JsonResponse
    {
        $account = $action->execute($request->validated());

        return response()->json([
            'message' => "Account [{$account->code} - {$account->name}] created successfully.",
            'data' => $account,
        ], 201);
    }

    public function update(UpdateAccountRequest $request, string $id, UpdateAccountAction $action): JsonResponse
    {
        $account = Account::findOrFail($id);
        $updated = $action->execute($account, $request->validated());

        return response()->json([
            'message' => "Account [{$updated->code}] updated successfully.",
            'data' => $updated,
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $account = Account::findOrFail($id);
        if ($account->transactionLines()->exists()) {
            return response()->json([
                'message' => "Account [{$account->code}] cannot be deleted because it has ledger transaction lines.",
            ], 422);
        }

        $code = $account->code;
        $account->delete();

        return response()->json([
            'message' => "Account [{$code}] deleted successfully.",
        ]);
    }
}
