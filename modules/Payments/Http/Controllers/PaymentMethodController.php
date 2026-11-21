<?php

declare(strict_types=1);

namespace Modules\Payments\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Payments\Actions\CreatePaymentMethodAction;
use Modules\Payments\Http\Requests\StorePaymentMethodRequest;
use Modules\Payments\Models\PaymentMethod;

class PaymentMethodController extends Controller
{
    public function index(): JsonResponse
    {
        $methods = PaymentMethod::query()->orderBy('name')->paginate(50);

        return response()->json($methods);
    }

    public function store(StorePaymentMethodRequest $request, CreatePaymentMethodAction $action): JsonResponse
    {
        $method = $action->execute($request->validated());

        return response()->json([
            'message' => 'Payment method created successfully.',
            'data' => $method,
        ], 201);
    }

    public function show(string $id): JsonResponse
    {
        $method = PaymentMethod::findOrFail($id);

        return response()->json([
            'data' => $method,
        ]);
    }
}
