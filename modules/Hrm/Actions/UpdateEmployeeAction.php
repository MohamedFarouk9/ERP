<?php

declare(strict_types=1);

namespace Modules\Hrm\Actions;

use InvalidArgumentException;
use Modules\Core\Services\TenantContext;
use Modules\Hrm\Models\Employee;

class UpdateEmployeeAction
{
    public function __construct(
        protected TenantContext $tenantContext
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(Employee $employee, array $data): Employee
    {
        $tenantId = $this->tenantContext->requireId();

        $duplicate = Employee::withoutGlobalScopes()
            ->where('tenant_id', $tenantId)
            ->where('employee_code', $data['employee_code'])
            ->where('id', '!=', $employee->id)
            ->first();

        if ($duplicate) {
            throw new InvalidArgumentException("Employee with code [{$data['employee_code']}] already exists.");
        }

        $employee->update([
            'department_id' => $data['department_id'] ?: null,
            'employee_code' => $data['employee_code'],
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'job_title' => $data['job_title'],
            'employment_type' => $data['employment_type'] ?? $employee->employment_type,
            'hire_date' => $data['hire_date'],
            'base_salary' => (int) $data['base_salary'],
            'status' => $data['status'] ?? $employee->status,
        ]);

        return $employee->fresh(['department']);
    }
}
