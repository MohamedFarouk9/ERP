<?php

declare(strict_types=1);

namespace Modules\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Inventory\Actions\CreateWarehouseAction;
use Modules\Inventory\Http\Requests\StoreWarehouseRequest;
use Modules\Inventory\Models\Warehouse;

class WarehouseController extends Controller
{
    public function index(): JsonResponse
    {
        $warehouses = Warehouse::query()->orderBy('code')->paginate(50);

        return response()->json($warehouses);
    }

    public function store(StoreWarehouseRequest $request, CreateWarehouseAction $action): JsonResponse
    {
        $warehouse = $action->execute($request->validated());

        return response()->json([
            'message' => 'Warehouse created successfully.',
            'data' => $warehouse,
        ], 201);
    }

    public function show(string $id): JsonResponse
    {
        $warehouse = Warehouse::with('stockLevels.product')->findOrFail($id);

        return response()->json([
            'data' => $warehouse,
        ]);
    }
}
