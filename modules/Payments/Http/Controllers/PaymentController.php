<?php

declare(strict_types=1);

namespace Modules\Payments\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Accounting\Models\Account;
use Modules\Payments\Actions\CompletePaymentAction;
use Modules\Payments\Actions\RecordPaymentAction;
use Modules\Payments\Http\Requests\StorePaymentRequest;
use Modules\Payments\Models\Payment;
use Modules\Payments\Models\PaymentMethod;
use Modules\Sales\Models\Customer;
use Modules\Sales\Models\Invoice;

class PaymentController extends Controller
{
    public function index(): View
    {
        $methods = PaymentMethod::orderBy('name')->get();
        $accounts = Account::where('is_active', true)->orderBy('code')->get(['id', 'code', 'name']);
        $customers = Customer::where('is_active', true)->orderBy('name')->take(100)->get(['id', 'code', 'name']);
        $invoices = Invoice::orderByDesc('invoice_date')->take(100)->get(['id', 'invoice_number', 'total_amount']);

        return view('admin.payments.index', compact('methods', 'accounts', 'customers', 'invoices'));
    }

    public function data(Request $request): JsonResponse
    {
        $draw = (int) $request->input('draw', 1);
        $start = (int) $request->input('start', 0);
        $length = min((int) $request->input('length', 25), 100);
        $search = trim((string) $request->input('search.value', ''));

        $baseQuery = Payment::query()->with(['paymentMethod:id,name']);
        $recordsTotal = Payment::count();

        if ($search !== '') {
            $baseQuery->where(function ($q) use ($search) {
                $q->where('payment_number', 'like', "%{$search}%")
                    ->orWhere('reference', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%");
            });
        }

        $recordsFiltered = (clone $baseQuery)->count();

        $columns = ['payment_number', 'payment_method_id', 'payment_date', 'amount', 'reference', 'status'];
        $orderColIdx = (int) $request->input('order.0.column', 2);
        $orderDir = $request->input('order.0.dir', 'desc') === 'asc' ? 'asc' : 'desc';
        $orderCol = $columns[$orderColIdx] ?? 'payment_date';

        $rows = $baseQuery->orderBy($orderCol, $orderDir)
            ->skip($start)
            ->take($length)
            ->get();

        $data = $rows->map(fn (Payment $p) => [
            'id' => $p->id,
            'payment_number' => $p->payment_number,
            'method_name' => $p->paymentMethod?->name ?? '-',
            'payment_date' => $p->payment_date?->format('Y-m-d') ?? '-',
            'amount' => $p->amount,
            'reference' => $p->reference ?? '-',
            'status' => $p->status,
        ]);

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ]);
    }

    public function store(StorePaymentRequest $request, RecordPaymentAction $action): JsonResponse
    {
        $payment = $action->execute($request->validated());

        return response()->json([
            'message' => "Payment [{$payment->payment_number}] recorded successfully.",
            'data' => $payment->load('paymentMethod'),
        ], 201);
    }

    public function complete(Request $request, string $id, CompletePaymentAction $action): JsonResponse
    {
        $payment = Payment::findOrFail($id);

        /** @var User $user */
        $user = $request->user();

        $completed = $action->execute($payment, $user);

        return response()->json([
            'message' => "Payment [{$completed->payment_number}] marked as completed.",
            'data' => $completed,
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $payment = Payment::findOrFail($id);
        if ($payment->status !== Payment::STATUS_PENDING) {
            return response()->json([
                'message' => 'Only pending payments can be deleted.',
            ], 422);
        }

        $num = $payment->payment_number;
        $payment->delete();

        return response()->json([
            'message' => "Payment [{$num}] deleted successfully.",
        ]);
    }
}
