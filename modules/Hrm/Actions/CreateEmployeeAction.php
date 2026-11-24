<?php

declare(strict_types=1);

namespace Modules\Hrm\Actions;

use InvalidArgumentException;
use Modules\Core\Services\TenantContext;
use Modules\Hrm\Models\Employee;

class CreateEmployeeAction
{
    public function __construct(
        protected TenantContext $tenantContext
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data): Employee
    {
        $tenantId = $this->tenantContext->requireId();

        $existing = Employee::withoutGlobalScopes()
            ->where('tenant_id', $tenantId)
            ->where('employee_code', $data['employee_code'])
            ->first();

        if ($existing) {
            throw new InvalidArgumentException("Employee with code [{$data['employee_code']}] already exists.");
        }

        return Employee::create([
            'tenant_id' => $tenantId,
            'department_id' => $data['department_id'] ?: null,
            'user_id' => $data['user_id'] ?: null,
            'employee_code' => $data['employee_code'],
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'job_title' => $data['job_title'],
            'employment_type' => $data['employment_type'] ?? 'full_time',
            'hire_date' => $data['hire_date'],
            'base_salary' => (int) $data['base_salary'],
            'currency_code' => $data['currency_code'] ?? 'USD',
            'status' => $data['status'] ?? Employee::STATUS_ACTIVE,
        ]);
    }
}
