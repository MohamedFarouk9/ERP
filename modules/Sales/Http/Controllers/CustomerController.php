<?php

declare(strict_types=1);

namespace Modules\Sales\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Sales\Actions\CreateCustomerAction;
use Modules\Sales\Actions\UpdateCustomerAction;
use Modules\Sales\Http\Requests\StoreCustomerRequest;
use Modules\Sales\Http\Requests\UpdateCustomerRequest;
use Modules\Sales\Models\Customer;

class CustomerController extends Controller
{
    public function index(): View
    {
        return view('admin.sales.customers');
    }

    public function data(Request $request): JsonResponse
    {
        $draw = (int) $request->input('draw', 1);
        $start = (int) $request->input('start', 0);
        $length = min((int) $request->input('length', 25), 100);
        $search = trim((string) $request->input('search.value', ''));

        $baseQuery = Customer::query()->withCount(['salesOrders', 'invoices']);
        $recordsTotal = Customer::count();

        if ($search !== '') {
            $baseQuery->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $recordsFiltered = (clone $baseQuery)->count();

        $columns = ['code', 'name', 'email', 'sales_orders_count', 'invoices_count', 'credit_limit', 'is_active'];
        $orderColIdx = (int) $request->input('order.0.column', 1);
        $orderDir = $request->input('order.0.dir', 'asc') === 'desc' ? 'desc' : 'asc';
        $orderCol = $columns[$orderColIdx] ?? 'name';

        $rows = $baseQuery->orderBy($orderCol, $orderDir)
            ->skip($start)
            ->take($length)
            ->get();

        $data = $rows->map(fn (Customer $c) => [
            'id' => $c->id,
            'code' => $c->code,
            'name' => $c->name,
            'email' => $c->email ?? '',
            'phone' => $c->phone ?? '',
            'tax_number' => $c->tax_number ?? '',
            'billing_address' => $c->billing_address ?? '',
            'shipping_address' => $c->shipping_address ?? '',
            'sales_orders_count' => $c->sales_orders_count,
            'invoices_count' => $c->invoices_count,
            'credit_limit' => $c->credit_limit,
            'is_active' => $c->is_active,
        ]);

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ]);
    }

    public function store(StoreCustomerRequest $request, CreateCustomerAction $action): JsonResponse
    {
        $customer = $action->execute($request->validated());

        return response()->json([
            'message' => "Customer [{$customer->code}] created successfully.",
            'data' => $customer,
        ], 201);
    }

    public function update(UpdateCustomerRequest $request, string $id, UpdateCustomerAction $action): JsonResponse
    {
        $customer = Customer::findOrFail($id);
        $updated = $action->execute($customer, $request->validated());

        return response()->json([
            'message' => "Customer [{$updated->code}] updated successfully.",
            'data' => $updated,
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $customer = Customer::findOrFail($id);
        $code = $customer->code;
        $customer->delete();

        return response()->json([
            'message' => "Customer [{$code}] deleted successfully.",
        ]);
    }
}
