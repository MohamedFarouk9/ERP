<?php

declare(strict_types=1);

namespace Modules\Pos\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Inventory\Models\Warehouse;
use Modules\Pos\Actions\ClosePosShiftAction;
use Modules\Pos\Actions\OpenPosShiftAction;
use Modules\Pos\Http\Requests\ClosePosShiftRequest;
use Modules\Pos\Http\Requests\OpenPosShiftRequest;
use Modules\Pos\Models\PosSale;
use Modules\Pos\Models\PosShift;

class PosShiftController extends Controller
{
    public function index(): View
    {
        $warehouses = Warehouse::where('is_active', true)->orderBy('code')->get(['id', 'code', 'name']);

        return view('admin.pos.shifts', compact('warehouses'));
    }

    public function data(Request $request): JsonResponse
    {
        $draw = (int) $request->input('draw', 1);
        $start = (int) $request->input('start', 0);
        $length = min((int) $request->input('length', 25), 100);

        $baseQuery = PosShift::query()->with('cashier:id,name')->withCount('sales');
        $recordsTotal = PosShift::count();

        $rows = $baseQuery->latest('opened_at')
            ->skip($start)
            ->take($length)
            ->get();

        $data = $rows->map(fn (PosShift $s) => [
            'id' => $s->id,
            'shift_number' => $s->shift_number,
            'cashier_name' => $s->cashier?->name ?? '-',
            'opened_at' => $s->opened_at?->format('Y-m-d H:i') ?? '-',
            'closed_at' => $s->closed_at?->format('Y-m-d H:i') ?? '-',
            'opening_cash' => $s->opening_cash,
            'expected_cash' => $s->expected_cash,
            'counted_cash' => $s->counted_cash,
            'sales_count' => $s->sales_count,
            'status' => $s->status,
        ]);

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsTotal,
            'data' => $data,
        ]);
    }

    public function salesData(Request $request): JsonResponse
    {
        $draw = (int) $request->input('draw', 1);
        $start = (int) $request->input('start', 0);
        $length = min((int) $request->input('length', 25), 100);

        $baseQuery = PosSale::query()->with('cashier:id,name');
        $recordsTotal = PosSale::count();

        $rows = $baseQuery->latest()
            ->skip($start)
            ->take($length)
            ->get();

        $data = $rows->map(fn (PosSale $s) => [
            'id' => $s->id,
            'receipt_number' => $s->receipt_number,
            'sale_date' => $s->sale_date?->format('Y-m-d') ?? '-',
            'cashier_name' => $s->cashier?->name ?? '-',
            'payment_type' => $s->payment_type,
            'total_amount' => $s->total_amount,
            'status' => $s->status,
        ]);

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsTotal,
            'data' => $data,
        ]);
    }

    public function open(OpenPosShiftRequest $request, OpenPosShiftAction $action): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $shift = $action->execute($request->validated(), $user);

        return response()->json([
            'message' => "Register shift [{$shift->shift_number}] opened!",
            'data' => $shift,
        ], 201);
    }

    public function close(ClosePosShiftRequest $request, string $id, ClosePosShiftAction $action): JsonResponse
    {
        $shift = PosShift::findOrFail($id);
        $closed = $action->execute($shift, $request->validated());

        return response()->json([
            'message' => "Register shift [{$closed->shift_number}] closed & reconciled.",
            'data' => $closed,
        ]);
    }
}
