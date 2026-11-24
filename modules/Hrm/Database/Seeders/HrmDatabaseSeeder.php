<?php

declare(strict_types=1);

namespace Modules\Hrm\Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Modules\Core\Services\TenantContext;
use Modules\Hrm\Models\Attendance;
use Modules\Hrm\Models\Employee;
use Modules\Hrm\Models\LeaveRequest;
use Modules\Hrm\Models\PayrollItem;
use Modules\Hrm\Models\PayrollRun;
use Modules\Users\Models\Department;

class HrmDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $tenantId = app(TenantContext::class)->requireId();
        $admin = User::withoutGlobalScopes()->where('tenant_id', $tenantId)->first();
        $deptIds = Department::withoutGlobalScopes()->where('tenant_id', $tenantId)->pluck('id')->all();

        $sampleStaff = [
            ['EMP-001', 'Sarah', 'Jenkins', 'Senior Accountant', 650000],
            ['EMP-002', 'David', 'Miller', 'Sales Director', 850000],
            ['EMP-003', 'Nadia', 'Hassan', 'Warehouse Supervisor', 480000],
            ['EMP-004', 'Omar', 'Farouk', 'Procurement Specialist', 520000],
            ['EMP-005', 'Elena', 'Rostova', 'HR Generalist', 490000],
            ['EMP-006', 'Marcus', 'Vance', 'Full Stack Engineer', 780000],
            ['EMP-007', 'Lina', 'Khoury', 'Financial Analyst', 610000],
            ['EMP-008', 'Tariq', 'Mansour', 'Account Executive', 540000],
        ];

        $employees = [];
        foreach ($sampleStaff as $idx => [$code, $first, $last, $title, $salary]) {
            $employees[] = Employee::firstOrCreate(
                ['tenant_id' => $tenantId, 'employee_code' => $code],
                [
                    'department_id' => ! empty($deptIds) ? $deptIds[$idx % count($deptIds)] : null,
                    'first_name' => $first,
                    'last_name' => $last,
                    'email' => strtolower("{$first}.{$last}@acme.test"),
                    'phone' => '+1-555-090'.($idx + 1),
                    'job_title' => $title,
                    'employment_type' => 'full_time',
                    'hire_date' => now()->subMonths(random_int(3, 36))->toDateString(),
                    'base_salary' => $salary,
                    'currency_code' => 'USD',
                    'status' => Employee::STATUS_ACTIVE,
                ]
            );
        }

        // Today's attendance
        $today = now()->toDateString();
        foreach ($employees as $idx => $emp) {
            Attendance::firstOrCreate(
                ['tenant_id' => $tenantId, 'employee_id' => $emp->id, 'work_date' => $today],
                [
                    'check_in' => $idx % 4 === 0 ? '09:20:00' : '08:55:00',
                    'check_out' => '17:05:00',
                    'status' => $idx % 4 === 0 ? Attendance::STATUS_LATE : Attendance::STATUS_PRESENT,
                ]
            );
        }

        // Sample Leave Requests
        if (LeaveRequest::withoutGlobalScopes()->where('tenant_id', $tenantId)->count() === 0 && ! empty($employees)) {
            LeaveRequest::create([
                'tenant_id' => $tenantId,
                'employee_id' => $employees[1]->id,
                'leave_type' => 'annual',
                'start_date' => now()->addDays(5)->toDateString(),
                'end_date' => now()->addDays(8)->toDateString(),
                'days_count' => 4,
                'reason' => 'Family vacation',
                'status' => LeaveRequest::STATUS_PENDING,
            ]);

            LeaveRequest::create([
                'tenant_id' => $tenantId,
                'employee_id' => $employees[2]->id,
                'leave_type' => 'sick',
                'start_date' => now()->subDays(4)->toDateString(),
                'end_date' => now()->subDays(3)->toDateString(),
                'days_count' => 2,
                'reason' => 'Medical recovery',
                'status' => LeaveRequest::STATUS_APPROVED,
                'reviewed_by' => $admin?->id,
                'reviewed_at' => now()->subDays(3),
            ]);
        }

        // Sample Payroll Runs for last 2 months
        foreach ([2, 1] as $mAgo) {
            $month = now()->subMonths($mAgo)->format('Y-m');
            $num = 'PAYROLL-'.now()->subMonths($mAgo)->format('Ym');
            $run = PayrollRun::firstOrCreate(
                ['tenant_id' => $tenantId, 'payroll_number' => $num],
                [
                    'period_month' => $month,
                    'pay_date' => now()->subMonths($mAgo)->endOfMonth()->toDateString(),
                    'status' => PayrollRun::STATUS_PAID,
                    'processed_by' => $admin?->id,
                    'paid_at' => now()->subMonths($mAgo)->endOfMonth(),
                ]
            );

            if ($run->items()->count() === 0) {
                $gross = 0;
                $ded = 0;
                $net = 0;
                foreach ($employees as $emp) {
                    $allow = (int) round($emp->base_salary * 0.10);
                    $g = $emp->base_salary + $allow;
                    $d = (int) round($g * 0.12);
                    $n = $g - $d;
                    $gross += $g;
                    $ded += $d;
                    $net += $n;

                    PayrollItem::create([
                        'payroll_run_id' => $run->id,
                        'employee_id' => $emp->id,
                        'base_salary' => $emp->base_salary,
                        'allowances' => $allow,
                        'deductions' => $d,
                        'net_pay' => $n,
                    ]);
                }
                $run->update(['total_gross' => $gross, 'total_deductions' => $ded, 'total_net' => $net]);
            }
        }
    }
}
