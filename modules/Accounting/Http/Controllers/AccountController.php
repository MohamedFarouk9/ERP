<?php

declare(strict_types=1);

namespace Modules\Accounting\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Accounting\Actions\CreateAccountAction;
use Modules\Accounting\Http\Requests\StoreAccountRequest;
use Modules\Accounting\Models\Account;

class AccountController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Account::query()->with('children');

        if ($request->has('type')) {
            $query->where('type', $request->query('type'));
        }

        if ($request->boolean('root_only')) {
            $query->whereNull('parent_id');
        }

        $accounts = $query->orderBy('code')->paginate(50);

        return response()->json($accounts);
    }

    public function store(StoreAccountRequest $request, CreateAccountAction $action): JsonResponse
    {
        $account = $action->execute($request->validated());

        return response()->json([
            'message' => 'Account created successfully.',
            'data' => $account,
        ], 201);
    }

    public function show(string $id): JsonResponse
    {
        $account = Account::with(['children', 'parent'])->findOrFail($id);

        return response()->json([
            'data' => $account,
        ]);
    }
}
