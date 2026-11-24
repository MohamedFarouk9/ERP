<?php

declare(strict_types=1);

namespace Modules\Hrm\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Modules\Hrm\Models\Attendance;
use Modules\Hrm\Models\Employee;
use Modules\Hrm\Models\LeaveRequest;
use Modules\Hrm\Models\PayrollRun;

class HrmDashboardController extends Controller
{
    public function index(): View
    {
        $activeEmployees = Employee::where('status', Employee::STATUS_ACTIVE)->count();
        $today = now()->toDateString();
        $presentToday = Attendance::where('work_date', $today)
            ->whereIn('status', [Attendance::STATUS_PRESENT, Attendance::STATUS_LATE, Attendance::STATUS_REMOTE])
            ->count();

        $stats = [
            'total_employees' => Employee::count(),
            'active_employees' => $activeEmployees,
            'monthly_payroll_liability' => (int) Employee::where('status', Employee::STATUS_ACTIVE)->sum('base_salary'),
            'present_today' => $presentToday,
            'attendance_rate' => $activeEmployees > 0 ? min(100, (int) round(($presentToday / $activeEmployees) * 100)) : 0,
            'pending_leaves' => LeaveRequest::where('status', LeaveRequest::STATUS_PENDING)->count(),
            'paid_payroll_total' => (int) PayrollRun::where('status', PayrollRun::STATUS_PAID)->sum('total_net'),
        ];

        $payrollHistory = PayrollRun::orderBy('period_month')->take(6)->get(['period_month', 'total_gross', 'total_net']);
        $deptBreakdown = Employee::query()
            ->leftJoin('departments', 'employees.department_id', '=', 'departments.id')
            ->select(DB::raw("COALESCE(departments.name, 'Unassigned') as dept_name"), DB::raw('COUNT(employees.id) as total'))
            ->groupBy('dept_name')
            ->get();

        $chartData = [
            'payroll_months' => $payrollHistory->pluck('period_month')->all(),
            'payroll_gross' => $payrollHistory->map(fn ($r) => round($r->total_gross / 100, 2))->all(),
            'payroll_net' => $payrollHistory->map(fn ($r) => round($r->total_net / 100, 2))->all(),
            'dept_labels' => $deptBreakdown->pluck('dept_name')->all(),
            'dept_counts' => $deptBreakdown->pluck('total')->map(fn ($v) => (int) $v)->all(),
        ];

        $recentLeaves = LeaveRequest::with('employee:id,first_name,last_name,employee_code')
            ->latest()
            ->take(6)
            ->get();

        $recentPayrolls = PayrollRun::orderByDesc('pay_date')->take(6)->get();

        return view('admin.hrm.dashboard', compact('stats', 'chartData', 'recentLeaves', 'recentPayrolls'));
    }
}
