<?php

declare(strict_types=1);

namespace Modules\Payments\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Payments\Actions\CreateRefundAction;
use Modules\Payments\Http\Requests\StoreRefundRequest;
use Modules\Payments\Models\Payment;
use Modules\Payments\Models\Refund;

class RefundController extends Controller
{
    public function index(): View
    {
        $completedPayments = Payment::where('status', Payment::STATUS_COMPLETED)
            ->orderByDesc('payment_date')
            ->take(100)
            ->get(['id', 'payment_number', 'amount']);

        return view('admin.payments.refunds', compact('completedPayments'));
    }

    public function data(Request $request): JsonResponse
    {
        $draw = (int) $request->input('draw', 1);
        $start = (int) $request->input('start', 0);
        $length = min((int) $request->input('length', 25), 100);
        $search = trim((string) $request->input('search.value', ''));

        $baseQuery = Refund::query()->with(['payment:id,payment_number']);
        $recordsTotal = Refund::count();

        if ($search !== '') {
            $baseQuery->where(function ($q) use ($search) {
                $q->where('refund_number', 'like', "%{$search}%")
                    ->orWhere('reason', 'like', "%{$search}%")
                    ->orWhereHas('payment', fn ($pq) => $pq->where('payment_number', 'like', "%{$search}%"));
            });
        }

        $recordsFiltered = (clone $baseQuery)->count();

        $columns = ['refund_number', 'payment_id', 'refund_date', 'amount', 'reason', 'status'];
        $orderColIdx = (int) $request->input('order.0.column', 2);
        $orderDir = $request->input('order.0.dir', 'desc') === 'asc' ? 'asc' : 'desc';
        $orderCol = $columns[$orderColIdx] ?? 'refund_date';

        $rows = $baseQuery->orderBy($orderCol, $orderDir)
            ->skip($start)
            ->take($length)
            ->get();

        $data = $rows->map(fn (Refund $r) => [
            'id' => $r->id,
            'refund_number' => $r->refund_number,
            'payment_number' => $r->payment?->payment_number ?? '-',
            'refund_date' => $r->refund_date?->format('Y-m-d') ?? '-',
            'amount' => $r->amount,
            'reason' => $r->reason ?? '-',
            'status' => $r->status,
        ]);

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ]);
    }

    public function store(StoreRefundRequest $request, CreateRefundAction $action): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $refund = $action->execute($request->validated(), $user);

        return response()->json([
            'message' => "Refund [{$refund->refund_number}] processed successfully.",
            'data' => $refund,
        ], 201);
    }
}
