<?php

declare(strict_types=1);

namespace Modules\Payments\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Payments\Actions\CompletePaymentAction;
use Modules\Payments\Actions\RecordPaymentAction;
use Modules\Payments\Http\Requests\StorePaymentRequest;
use Modules\Payments\Models\Payment;

class PaymentController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Payment::query()->with(['paymentMethod', 'processor']);

        if ($request->has('status')) {
            $query->where('status', $request->query('status'));
        }

        $payments = $query->orderByDesc('payment_date')->paginate(25);

        return response()->json($payments);
    }

    public function store(StorePaymentRequest $request, RecordPaymentAction $action): JsonResponse
    {
        $payment = $action->execute($request->validated());

        return response()->json([
            'message' => 'Payment recorded successfully.',
            'data' => $payment->load('paymentMethod'),
        ], 201);
    }

    public function show(string $id): JsonResponse
    {
        $payment = Payment::with(['paymentMethod', 'processor', 'refunds'])->findOrFail($id);

        return response()->json([
            'data' => $payment,
        ]);
    }

    public function complete(Request $request, string $id, CompletePaymentAction $action): JsonResponse
    {
        $payment = Payment::findOrFail($id);

        /** @var User $user */
        $user = $request->user();

        $completedPayment = $action->execute($payment, $user);

        return response()->json([
            'message' => 'Payment completed successfully.',
            'data' => $completedPayment,
        ]);
    }
}
