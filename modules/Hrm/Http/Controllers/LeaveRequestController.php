<?php

declare(strict_types=1);

namespace Modules\Hrm\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Hrm\Actions\CreateLeaveRequestAction;
use Modules\Hrm\Actions\ReviewLeaveRequestAction;
use Modules\Hrm\Http\Requests\StoreLeaveRequest;
use Modules\Hrm\Models\Employee;
use Modules\Hrm\Models\LeaveRequest;

class LeaveRequestController extends Controller
{
    public function index(): View
    {
        $employees = Employee::where('status', Employee::STATUS_ACTIVE)->orderBy('first_name')->get(['id', 'employee_code', 'first_name', 'last_name']);

        return view('admin.hrm.leaves', compact('employees'));
    }

    public function data(Request $request): JsonResponse
    {
        $draw = (int) $request->input('draw', 1);
        $start = (int) $request->input('start', 0);
        $length = min((int) $request->input('length', 25), 100);

        $baseQuery = LeaveRequest::query()->with('employee:id,employee_code,first_name,last_name');
        $recordsTotal = LeaveRequest::count();
        $recordsFiltered = $recordsTotal;

        $rows = $baseQuery->latest()
            ->skip($start)
            ->take($length)
            ->get();

        $data = $rows->map(fn (LeaveRequest $l) => [
            'id' => $l->id,
            'employee_name' => $l->employee ? "{$l->employee->employee_code} - {$l->employee->fullName()}" : '-',
            'leave_type' => $l->leave_type,
            'start_date' => $l->start_date?->format('Y-m-d') ?? '-',
            'end_date' => $l->end_date?->format('Y-m-d') ?? '-',
            'days_count' => $l->days_count,
            'reason' => $l->reason ?? '-',
            'status' => $l->status,
        ]);

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ]);
    }

    public function store(StoreLeaveRequest $request, CreateLeaveRequestAction $action): JsonResponse
    {
        $leave = $action->execute($request->validated());

        return response()->json([
            'message' => 'Leave request submitted successfully.',
            'data' => $leave,
        ], 201);
    }

    public function approve(Request $request, string $id, ReviewLeaveRequestAction $action): JsonResponse
    {
        $leave = LeaveRequest::findOrFail($id);

        /** @var User $user */
        $user = $request->user();

        $action->execute($leave, LeaveRequest::STATUS_APPROVED, $user);

        return response()->json(['message' => 'Leave request approved.']);
    }

    public function reject(Request $request, string $id, ReviewLeaveRequestAction $action): JsonResponse
    {
        $leave = LeaveRequest::findOrFail($id);

        /** @var User $user */
        $user = $request->user();

        $action->execute($leave, LeaveRequest::STATUS_REJECTED, $user);

        return response()->json(['message' => 'Leave request rejected.']);
    }
}
