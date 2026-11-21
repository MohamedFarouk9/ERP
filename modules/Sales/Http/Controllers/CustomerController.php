<?php

declare(strict_types=1);

namespace Modules\Sales\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Sales\Actions\CreateCustomerAction;
use Modules\Sales\Http\Requests\StoreCustomerRequest;
use Modules\Sales\Models\Customer;

class CustomerController extends Controller
{
    public function index(): JsonResponse
    {
        $customers = Customer::query()->orderBy('name')->paginate(50);

        return response()->json($customers);
    }

    public function store(StoreCustomerRequest $request, CreateCustomerAction $action): JsonResponse
    {
        $customer = $action->execute($request->validated());

        return response()->json([
            'message' => 'Customer created successfully.',
            'data' => $customer,
        ], 201);
    }

    public function show(string $id): JsonResponse
    {
        $customer = Customer::with(['salesOrders', 'invoices'])->findOrFail($id);

        return response()->json([
            'data' => $customer,
        ]);
    }
}
