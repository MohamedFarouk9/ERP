<?php

declare(strict_types=1);

namespace Modules\Sales\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Inventory\Models\Product;
use Modules\Inventory\Models\Warehouse;
use Modules\Sales\Actions\ConfirmSalesOrderAction;
use Modules\Sales\Actions\CreateSalesOrderAction;
use Modules\Sales\Http\Requests\StoreSalesOrderRequest;
use Modules\Sales\Models\Customer;
use Modules\Sales\Models\SalesOrder;

class SalesOrderController extends Controller
{
    public function index(): View
    {
        $customers = Customer::where('is_active', true)->orderBy('name')->get(['id', 'code', 'name']);
        $warehouses = Warehouse::where('is_active', true)->orderBy('name')->get(['id', 'code', 'name']);
        $products = Product::where('is_active', true)->orderBy('name')->take(100)->get(['id', 'sku', 'name', 'selling_price']);

        return view('admin.orders.index', compact('customers', 'warehouses', 'products'));
    }

    public function data(Request $request): JsonResponse
    {
        $draw = (int) $request->input('draw', 1);
        $start = (int) $request->input('start', 0);
        $length = min((int) $request->input('length', 25), 100);
        $search = trim((string) $request->input('search.value', ''));

        $baseQuery = SalesOrder::query()->with(['customer:id,name'])->withCount('items');
        $recordsTotal = SalesOrder::count();

        if ($search !== '') {
            $baseQuery->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%")
                    ->orWhereHas('customer', fn ($cq) => $cq->where('name', 'like', "%{$search}%"));
            });
        }

        $recordsFiltered = (clone $baseQuery)->count();

        $columns = ['order_number', 'customer_id', 'order_date', 'items_count', 'total_amount', 'status'];
        $orderColIdx = (int) $request->input('order.0.column', 2);
        $orderDir = $request->input('order.0.dir', 'desc') === 'asc' ? 'asc' : 'desc';
        $orderCol = $columns[$orderColIdx] ?? 'order_date';

        $rows = $baseQuery->orderBy($orderCol, $orderDir)
            ->skip($start)
            ->take($length)
            ->get();

        $data = $rows->map(fn (SalesOrder $o) => [
            'id' => $o->id,
            'order_number' => $o->order_number,
            'customer_name' => $o->customer?->name ?? '-',
            'order_date' => $o->order_date?->format('Y-m-d') ?? '-',
            'items_count' => $o->items_count,
            'total_amount' => $o->total_amount,
            'status' => $o->status,
        ]);

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ]);
    }

    public function store(StoreSalesOrderRequest $request, CreateSalesOrderAction $action): JsonResponse
    {
        $order = $action->execute($request->validated());

        return response()->json([
            'message' => "Draft sales order [{$order->order_number}] created successfully.",
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
            'message' => "Sales order [{$confirmedOrder->order_number}] confirmed successfully.",
            'data' => $confirmedOrder,
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $order = SalesOrder::findOrFail($id);
        if ($order->status !== SalesOrder::STATUS_DRAFT) {
            return response()->json([
                'message' => 'Only draft sales orders can be deleted.',
            ], 422);
        }

        $num = $order->order_number;
        $order->delete();

        return response()->json([
            'message' => "Sales order [{$num}] deleted successfully.",
        ]);
    }
}
