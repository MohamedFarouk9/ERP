<?php

declare(strict_types=1);

namespace Modules\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Inventory\Actions\RecordStockMovementAction;
use Modules\Inventory\Http\Requests\RecordStockMovementRequest;
use Modules\Inventory\Models\StockMovement;

class StockMovementController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = StockMovement::query()->with(['warehouse', 'product', 'creator']);

        if ($request->has('warehouse_id')) {
            $query->where('warehouse_id', $request->query('warehouse_id'));
        }

        if ($request->has('product_id')) {
            $query->where('product_id', $request->query('product_id'));
        }

        if ($request->has('type')) {
            $query->where('type', $request->query('type'));
        }

        $movements = $query->orderByDesc('created_at')->paginate(50);

        return response()->json($movements);
    }

    public function store(RecordStockMovementRequest $request, RecordStockMovementAction $action): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $movement = $action->execute($request->validated(), $user);

        return response()->json([
            'message' => 'Stock movement recorded successfully.',
            'data' => $movement->load(['warehouse', 'product']),
        ], 201);
    }
}
