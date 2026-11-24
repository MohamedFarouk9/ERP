<?php

declare(strict_types=1);

namespace Modules\Hrm\Actions;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use Modules\Core\Services\TenantContext;
use Modules\Hrm\Models\Employee;
use Modules\Hrm\Models\PayrollItem;
use Modules\Hrm\Models\PayrollRun;
use RuntimeException;

class GeneratePayrollRunAction
{
    public function __construct(
        protected TenantContext $tenantContext
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data, User $user): PayrollRun
    {
        $tenantId = $this->tenantContext->requireId();

        $existing = PayrollRun::withoutGlobalScopes()
            ->where('tenant_id', $tenantId)
            ->where('payroll_number', $data['payroll_number'])
            ->first();

        if ($existing) {
            throw new InvalidArgumentException("Payroll run [{$data['payroll_number']}] already exists.");
        }

        $activeEmployees = Employee::where('status', Employee::STATUS_ACTIVE)->get();
        if ($activeEmployees->isEmpty()) {
            throw new RuntimeException('No active employees found to generate payroll.');
        }

        $allowanceRate = (int) ($data['default_allowance_bps'] ?? 0);
        $taxRate = (int) ($data['default_deduction_bps'] ?? 0);

        return DB::transaction(function () use ($data, $tenantId, $user, $activeEmployees, $allowanceRate, $taxRate) {
            $run = PayrollRun::create([
                'tenant_id' => $tenantId,
                'payroll_number' => $data['payroll_number'],
                'period_month' => $data['period_month'],
                'pay_date' => $data['pay_date'],
                'status' => PayrollRun::STATUS_DRAFT,
                'processed_by' => $user->id,
            ]);

            $totalGross = 0;
            $totalDeductions = 0;
            $totalNet = 0;

            foreach ($activeEmployees as $emp) {
                $base = $emp->base_salary;
                $allowances = (int) round($base * ($allowanceRate / 10000));
                $gross = $base + $allowances;
                $deductions = (int) round($gross * ($taxRate / 10000));
                $net = max(0, $gross - $deductions);

                $totalGross += $gross;
                $totalDeductions += $deductions;
                $totalNet += $net;

                PayrollItem::create([
                    'payroll_run_id' => $run->id,
                    'employee_id' => $emp->id,
                    'base_salary' => $base,
                    'allowances' => $allowances,
                    'deductions' => $deductions,
                    'net_pay' => $net,
                ]);
            }

            $run->update([
                'total_gross' => $totalGross,
                'total_deductions' => $totalDeductions,
                'total_net' => $totalNet,
            ]);

            return $run->load('items.employee');
        });
    }
}
