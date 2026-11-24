<?php

declare(strict_types=1);

namespace Modules\Hrm\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Hrm\Actions\CreateEmployeeAction;
use Modules\Hrm\Actions\UpdateEmployeeAction;
use Modules\Hrm\Http\Requests\StoreEmployeeRequest;
use Modules\Hrm\Http\Requests\UpdateEmployeeRequest;
use Modules\Hrm\Models\Employee;
use Modules\Users\Models\Department;

class EmployeeController extends Controller
{
    public function index(): View
    {
        $departments = Department::orderBy('name')->get(['id', 'code', 'name']);

        return view('admin.hrm.employees', compact('departments'));
    }

    public function data(Request $request): JsonResponse
    {
        $draw = (int) $request->input('draw', 1);
        $start = (int) $request->input('start', 0);
        $length = min((int) $request->input('length', 25), 100);
        $search = trim((string) $request->input('search.value', ''));

        $baseQuery = Employee::query()->with('department:id,name');
        $recordsTotal = Employee::count();

        if ($search !== '') {
            $baseQuery->where(function ($q) use ($search) {
                $q->where('employee_code', 'like', "%{$search}%")
                    ->orWhere('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('job_title', 'like', "%{$search}%");
            });
        }

        $recordsFiltered = (clone $baseQuery)->count();

        $columns = ['employee_code', 'first_name', 'department_id', 'job_title', 'base_salary', 'hire_date', 'status'];
        $orderColIdx = (int) $request->input('order.0.column', 0);
        $orderDir = $request->input('order.0.dir', 'asc') === 'desc' ? 'desc' : 'asc';
        $orderCol = $columns[$orderColIdx] ?? 'employee_code';

        $rows = $baseQuery->orderBy($orderCol, $orderDir)
            ->skip($start)
            ->take($length)
            ->get();

        $data = $rows->map(fn (Employee $e) => [
            'id' => $e->id,
            'employee_code' => $e->employee_code,
            'first_name' => $e->first_name,
            'last_name' => $e->last_name,
            'full_name' => $e->fullName(),
            'email' => $e->email,
            'phone' => $e->phone ?? '',
            'department_id' => $e->department_id ?? '',
            'department_name' => $e->department?->name ?? '-',
            'job_title' => $e->job_title,
            'employment_type' => $e->employment_type,
            'hire_date' => $e->hire_date?->format('Y-m-d') ?? '-',
            'base_salary' => $e->base_salary,
            'status' => $e->status,
        ]);

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ]);
    }

    public function store(StoreEmployeeRequest $request, CreateEmployeeAction $action): JsonResponse
    {
        $employee = $action->execute($request->validated());

        return response()->json([
            'message' => "Employee [{$employee->employee_code}] created successfully.",
            'data' => $employee,
        ], 201);
    }

    public function update(UpdateEmployeeRequest $request, string $id, UpdateEmployeeAction $action): JsonResponse
    {
        $employee = Employee::findOrFail($id);
        $updated = $action->execute($employee, $request->validated());

        return response()->json([
            'message' => "Employee [{$updated->employee_code}] updated successfully.",
            'data' => $updated,
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $employee = Employee::findOrFail($id);
        $code = $employee->employee_code;
        $employee->delete();

        return response()->json([
            'message' => "Employee [{$code}] deleted successfully.",
        ]);
    }
}
