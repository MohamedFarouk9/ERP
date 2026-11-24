<?php

declare(strict_types=1);

namespace Modules\Procurement\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Procurement\Actions\ApproveVendorBillAction;
use Modules\Procurement\Actions\CreateVendorBillAction;
use Modules\Procurement\Http\Requests\StoreVendorBillRequest;
use Modules\Procurement\Models\PurchaseOrder;
use Modules\Procurement\Models\Supplier;
use Modules\Procurement\Models\VendorBill;

class VendorBillController extends Controller
{
    public function index(): View
    {
        $suppliers = Supplier::where('is_active', true)->orderBy('name')->get(['id', 'code', 'name']);
        $purchaseOrders = PurchaseOrder::orderByDesc('order_date')->take(50)->get(['id', 'po_number', 'total_amount']);

        return view('admin.procurement.vendor-bills', compact('suppliers', 'purchaseOrders'));
    }

    public function data(Request $request): JsonResponse
    {
        $draw = (int) $request->input('draw', 1);
        $start = (int) $request->input('start', 0);
        $length = min((int) $request->input('length', 25), 100);
        $search = trim((string) $request->input('search.value', ''));

        $baseQuery = VendorBill::query()->with(['supplier:id,name']);
        $recordsTotal = VendorBill::count();

        if ($search !== '') {
            $baseQuery->where(function ($q) use ($search) {
                $q->where('bill_number', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%")
                    ->orWhereHas('supplier', fn ($sq) => $sq->where('name', 'like', "%{$search}%"));
            });
        }

        $recordsFiltered = (clone $baseQuery)->count();

        $columns = ['bill_number', 'supplier_id', 'bill_date', 'due_date', 'total_amount', 'status'];
        $orderColIdx = (int) $request->input('order.0.column', 2);
        $orderDir = $request->input('order.0.dir', 'desc') === 'asc' ? 'asc' : 'desc';
        $orderCol = $columns[$orderColIdx] ?? 'bill_date';

        $rows = $baseQuery->orderBy($orderCol, $orderDir)
            ->skip($start)
            ->take($length)
            ->get();

        $data = $rows->map(fn (VendorBill $b) => [
            'id' => $b->id,
            'bill_number' => $b->bill_number,
            'supplier_name' => $b->supplier?->name ?? '-',
            'bill_date' => $b->bill_date?->format('Y-m-d') ?? '-',
            'due_date' => $b->due_date?->format('Y-m-d') ?? '-',
            'total_amount' => $b->total_amount,
            'status' => $b->status,
        ]);

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ]);
    }

    public function store(StoreVendorBillRequest $request, CreateVendorBillAction $action): JsonResponse
    {
        $bill = $action->execute($request->validated());

        return response()->json([
            'message' => "Vendor Bill [{$bill->bill_number}] created successfully.",
            'data' => $bill,
        ], 201);
    }

    public function approve(string $id, ApproveVendorBillAction $action): JsonResponse
    {
        $bill = VendorBill::findOrFail($id);
        $approved = $action->execute($bill);

        return response()->json([
            'message' => "Vendor Bill [{$approved->bill_number}] approved.",
            'data' => $approved,
        ]);
    }

    public function markPaid(string $id): JsonResponse
    {
        $bill = VendorBill::findOrFail($id);
        $bill->update([
            'status' => VendorBill::STATUS_PAID,
            'amount_paid' => $bill->total_amount,
        ]);

        return response()->json([
            'message' => "Vendor Bill [{$bill->bill_number}] marked as paid.",
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $bill = VendorBill::findOrFail($id);
        if ($bill->status !== VendorBill::STATUS_DRAFT) {
            return response()->json([
                'message' => 'Only draft vendor bills can be deleted.',
            ], 422);
        }

        $num = $bill->bill_number;
        $bill->delete();

        return response()->json([
            'message' => "Vendor Bill [{$num}] deleted successfully.",
        ]);
    }
}
