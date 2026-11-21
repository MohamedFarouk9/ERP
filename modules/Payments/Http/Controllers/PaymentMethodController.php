<?php

declare(strict_types=1);

namespace Modules\Payments\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Payments\Actions\CreatePaymentMethodAction;
use Modules\Payments\Actions\UpdatePaymentMethodAction;
use Modules\Payments\Http\Requests\StorePaymentMethodRequest;
use Modules\Payments\Http\Requests\UpdatePaymentMethodRequest;
use Modules\Payments\Models\PaymentMethod;

class PaymentMethodController extends Controller
{
    public function store(StorePaymentMethodRequest $request, CreatePaymentMethodAction $action): JsonResponse
    {
        $method = $action->execute($request->validated());

        return response()->json([
            'message' => "Payment method [{$method->code}] created successfully.",
            'data' => $method,
        ], 201);
    }

    public function update(UpdatePaymentMethodRequest $request, string $id, UpdatePaymentMethodAction $action): JsonResponse
    {
        $method = PaymentMethod::findOrFail($id);
        $updated = $action->execute($method, $request->validated());

        return response()->json([
            'message' => "Payment method [{$updated->code}] updated successfully.",
            'data' => $updated,
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $method = PaymentMethod::findOrFail($id);
        if ($method->payments()->exists()) {
            return response()->json([
                'message' => "Payment method [{$method->code}] cannot be deleted because it has recorded payments.",
            ], 422);
        }

        $code = $method->code;
        $method->delete();

        return response()->json([
            'message' => "Payment method [{$code}] deleted successfully.",
        ]);
    }
}
