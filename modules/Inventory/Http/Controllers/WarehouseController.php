<?php

declare(strict_types=1);

namespace Modules\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Modules\Inventory\Actions\CreateWarehouseAction;
use Modules\Inventory\Actions\UpdateWarehouseAction;
use Modules\Inventory\Http\Requests\StoreWarehouseRequest;
use Modules\Inventory\Http\Requests\UpdateWarehouseRequest;
use Modules\Inventory\Models\Warehouse;

class WarehouseController extends Controller
{
    public function index(): View
    {
        $warehouses = Warehouse::withCount('stockLevels')->orderBy('code')->get();

        return view('admin.inventory.warehouses', compact('warehouses'));
    }

    public function store(StoreWarehouseRequest $request, CreateWarehouseAction $action): JsonResponse
    {
        $warehouse = $action->execute($request->validated());

        return response()->json([
            'message' => "Warehouse [{$warehouse->code}] created successfully.",
            'data' => $warehouse,
        ], 201);
    }

    public function update(UpdateWarehouseRequest $request, string $id, UpdateWarehouseAction $action): JsonResponse
    {
        $warehouse = Warehouse::findOrFail($id);
        $updated = $action->execute($warehouse, $request->validated());

        return response()->json([
            'message' => "Warehouse [{$updated->code}] updated successfully.",
            'data' => $updated,
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $warehouse = Warehouse::findOrFail($id);
        $code = $warehouse->code;
        $warehouse->delete();

        return response()->json([
            'message' => "Warehouse [{$code}] deleted successfully.",
        ]);
    }
}
