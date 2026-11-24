<?php

declare(strict_types=1);

namespace Modules\Procurement\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Procurement\Actions\CreateSupplierAction;
use Modules\Procurement\Actions\UpdateSupplierAction;
use Modules\Procurement\Http\Requests\StoreSupplierRequest;
use Modules\Procurement\Http\Requests\UpdateSupplierRequest;
use Modules\Procurement\Models\Supplier;

class SupplierController extends Controller
{
    public function index(): View
    {
        return view('admin.procurement.suppliers');
    }

    public function data(Request $request): JsonResponse
    {
        $draw = (int) $request->input('draw', 1);
        $start = (int) $request->input('start', 0);
        $length = min((int) $request->input('length', 25), 100);
        $search = trim((string) $request->input('search.value', ''));

        $baseQuery = Supplier::query()->withCount(['purchaseOrders', 'vendorBills']);
        $recordsTotal = Supplier::count();

        if ($search !== '') {
            $baseQuery->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $recordsFiltered = (clone $baseQuery)->count();

        $columns = ['code', 'name', 'email', 'payment_terms_days', 'purchase_orders_count', 'is_active'];
        $orderColIdx = (int) $request->input('order.0.column', 1);
        $orderDir = $request->input('order.0.dir', 'asc') === 'desc' ? 'desc' : 'asc';
        $orderCol = $columns[$orderColIdx] ?? 'name';

        $rows = $baseQuery->orderBy($orderCol, $orderDir)
            ->skip($start)
            ->take($length)
            ->get();

        $data = $rows->map(fn (Supplier $s) => [
            'id' => $s->id,
            'code' => $s->code,
            'name' => $s->name,
            'email' => $s->email ?? '',
            'phone' => $s->phone ?? '',
            'tax_number' => $s->tax_number ?? '',
            'address' => $s->address ?? '',
            'payment_terms_days' => $s->payment_terms_days,
            'purchase_orders_count' => $s->purchase_orders_count,
            'vendor_bills_count' => $s->vendor_bills_count,
            'is_active' => $s->is_active,
        ]);

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ]);
    }

    public function store(StoreSupplierRequest $request, CreateSupplierAction $action): JsonResponse
    {
        $supplier = $action->execute($request->validated());

        return response()->json([
            'message' => "Supplier [{$supplier->code}] created successfully.",
            'data' => $supplier,
        ], 201);
    }

    public function update(UpdateSupplierRequest $request, string $id, UpdateSupplierAction $action): JsonResponse
    {
        $supplier = Supplier::findOrFail($id);
        $updated = $action->execute($supplier, $request->validated());

        return response()->json([
            'message' => "Supplier [{$updated->code}] updated successfully.",
            'data' => $updated,
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $supplier = Supplier::findOrFail($id);
        $code = $supplier->code;
        $supplier->delete();

        return response()->json([
            'message' => "Supplier [{$code}] deleted successfully.",
        ]);
    }
}
