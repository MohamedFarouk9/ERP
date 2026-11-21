<?php

declare(strict_types=1);

namespace Modules\Payments\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Modules\Payments\Actions\CreateRefundAction;
use Modules\Payments\Http\Requests\StoreRefundRequest;
use Modules\Payments\Models\Refund;

class RefundController extends Controller
{
    public function index(): JsonResponse
    {
        $refunds = Refund::query()->with(['payment', 'processor'])->orderByDesc('refund_date')->paginate(25);

        return response()->json($refunds);
    }

    public function store(StoreRefundRequest $request, CreateRefundAction $action): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $refund = $action->execute($request->validated(), $user);

        return response()->json([
            'message' => 'Refund processed successfully.',
            'data' => $refund,
        ], 201);
    }

    public function show(string $id): JsonResponse
    {
        $refund = Refund::with(['payment', 'processor'])->findOrFail($id);

        return response()->json([
            'data' => $refund,
        ]);
    }
}
