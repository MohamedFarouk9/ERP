<?php

declare(strict_types=1);

namespace Modules\Procurement\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Inventory\Models\Product;
use Modules\Inventory\Models\Warehouse;
use Modules\Procurement\Actions\CreatePurchaseOrderAction;
use Modules\Procurement\Actions\ReceivePurchaseOrderAction;
use Modules\Procurement\Http\Requests\StorePurchaseOrderRequest;
use Modules\Procurement\Models\PurchaseOrder;
use Modules\Procurement\Models\Supplier;

class PurchaseOrderController extends Controller
{
    public function index(): View
    {
        $suppliers = Supplier::where('is_active', true)->orderBy('name')->get(['id', 'code', 'name']);
        $warehouses = Warehouse::where('is_active', true)->orderBy('name')->get(['id', 'code', 'name']);
        $products = Product::where('is_active', true)->orderBy('name')->take(100)->get(['id', 'sku', 'name', 'cost_price']);

        return view('admin.procurement.purchase-orders', compact('suppliers', 'warehouses', 'products'));
    }

    public function data(Request $request): JsonResponse
    {
        $draw = (int) $request->input('draw', 1);
        $start = (int) $request->input('start', 0);
        $length = min((int) $request->input('length', 25), 100);
        $search = trim((string) $request->input('search.value', ''));

        $baseQuery = PurchaseOrder::query()->with(['supplier:id,name'])->withCount('items');
        $recordsTotal = PurchaseOrder::count();

        if ($search !== '') {
            $baseQuery->where(function ($q) use ($search) {
                $q->where('po_number', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%")
                    ->orWhereHas('supplier', fn ($sq) => $sq->where('name', 'like', "%{$search}%"));
            });
        }

        $recordsFiltered = (clone $baseQuery)->count();

        $columns = ['po_number', 'supplier_id', 'order_date', 'items_count', 'total_amount', 'status'];
        $orderColIdx = (int) $request->input('order.0.column', 2);
        $orderDir = $request->input('order.0.dir', 'desc') === 'asc' ? 'asc' : 'desc';
        $orderCol = $columns[$orderColIdx] ?? 'order_date';

        $rows = $baseQuery->orderBy($orderCol, $orderDir)
            ->skip($start)
            ->take($length)
            ->get();

        $data = $rows->map(fn (PurchaseOrder $po) => [
            'id' => $po->id,
            'po_number' => $po->po_number,
            'supplier_name' => $po->supplier?->name ?? '-',
            'order_date' => $po->order_date?->format('Y-m-d') ?? '-',
            'items_count' => $po->items_count,
            'total_amount' => $po->total_amount,
            'status' => $po->status,
        ]);

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ]);
    }

    public function store(StorePurchaseOrderRequest $request, CreatePurchaseOrderAction $action): JsonResponse
    {
        $po = $action->execute($request->validated());

        return response()->json([
            'message' => "Purchase Order [{$po->po_number}] created successfully.",
            'data' => $po,
        ], 201);
    }

    public function show(string $id): JsonResponse
    {
        $po = PurchaseOrder::with(['supplier', 'items', 'receiver'])->findOrFail($id);

        return response()->json([
            'data' => $po,
        ]);
    }

    public function receive(Request $request, string $id, ReceivePurchaseOrderAction $action): JsonResponse
    {
        $po = PurchaseOrder::with('items')->findOrFail($id);

        /** @var User $user */
        $user = $request->user();

        $received = $action->execute($po, $user);

        return response()->json([
            'message' => "Purchase Order [{$received->po_number}] received & inventory stock updated.",
            'data' => $received,
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $po = PurchaseOrder::findOrFail($id);
        if ($po->status === PurchaseOrder::STATUS_RECEIVED) {
            return response()->json([
                'message' => 'Received purchase orders cannot be deleted.',
            ], 422);
        }

        $num = $po->po_number;
        $po->delete();

        return response()->json([
            'message' => "Purchase Order [{$num}] deleted successfully.",
        ]);
    }
}
