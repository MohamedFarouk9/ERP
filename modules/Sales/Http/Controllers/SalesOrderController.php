<?php

declare(strict_types=1);

namespace Modules\Sales\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Sales\Actions\ConfirmSalesOrderAction;
use Modules\Sales\Actions\CreateSalesOrderAction;
use Modules\Sales\Http\Requests\StoreSalesOrderRequest;
use Modules\Sales\Models\SalesOrder;

class SalesOrderController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = SalesOrder::query()->with(['customer', 'items']);

        if ($request->has('status')) {
            $query->where('status', $request->query('status'));
        }

        $orders = $query->orderByDesc('order_date')->paginate(25);

        return response()->json($orders);
    }

    public function store(StoreSalesOrderRequest $request, CreateSalesOrderAction $action): JsonResponse
    {
        $order = $action->execute($request->validated());

        return response()->json([
            'message' => 'Draft sales order created successfully.',
            'data' => $order,
        ], 201);
    }

    public function show(string $id): JsonResponse
    {
        $order = SalesOrder::with(['customer', 'items', 'confirmer'])->findOrFail($id);

        return response()->json([
            'data' => $order,
        ]);
    }

    public function confirm(Request $request, string $id, ConfirmSalesOrderAction $action): JsonResponse
    {
        $order = SalesOrder::with('items')->findOrFail($id);

        /** @var User $user */
        $user = $request->user();

        $confirmedOrder = $action->execute($order, $user);

        return response()->json([
            'message' => 'Sales order confirmed successfully.',
            'data' => $confirmedOrder,
        ]);
    }
}
