<?php

declare(strict_types=1);

namespace Modules\Hrm\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Hrm\Actions\RecordAttendanceAction;
use Modules\Hrm\Http\Requests\StoreAttendanceRequest;
use Modules\Hrm\Models\Attendance;
use Modules\Hrm\Models\Employee;

class AttendanceController extends Controller
{
    public function index(): View
    {
        $employees = Employee::where('status', Employee::STATUS_ACTIVE)->orderBy('first_name')->get(['id', 'employee_code', 'first_name', 'last_name']);

        return view('admin.hrm.attendance', compact('employees'));
    }

    public function data(Request $request): JsonResponse
    {
        $draw = (int) $request->input('draw', 1);
        $start = (int) $request->input('start', 0);
        $length = min((int) $request->input('length', 25), 100);
        $search = trim((string) $request->input('search.value', ''));

        $baseQuery = Attendance::query()->with('employee:id,employee_code,first_name,last_name');
        $recordsTotal = Attendance::count();

        if ($search !== '') {
            $baseQuery->where(function ($q) use ($search) {
                $q->where('status', 'like', "%{$search}%")
                    ->orWhereHas('employee', fn ($eq) => $eq->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('employee_code', 'like', "%{$search}%"));
            });
        }

        $recordsFiltered = (clone $baseQuery)->count();

        $rows = $baseQuery->orderByDesc('work_date')
            ->skip($start)
            ->take($length)
            ->get();

        $data = $rows->map(fn (Attendance $a) => [
            'id' => $a->id,
            'employee_name' => $a->employee ? "{$a->employee->employee_code} - {$a->employee->fullName()}" : '-',
            'work_date' => $a->work_date?->format('Y-m-d') ?? '-',
            'check_in' => $a->check_in ?? '-',
            'check_out' => $a->check_out ?? '-',
            'status' => $a->status,
            'notes' => $a->notes ?? '-',
        ]);

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ]);
    }

    public function store(StoreAttendanceRequest $request, RecordAttendanceAction $action): JsonResponse
    {
        $attendance = $action->execute($request->validated());

        return response()->json([
            'message' => 'Attendance recorded successfully.',
            'data' => $attendance,
        ], 201);
    }
}
