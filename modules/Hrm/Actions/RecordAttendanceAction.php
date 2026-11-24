<?php

declare(strict_types=1);

namespace Modules\Hrm\Actions;

use Modules\Core\Services\TenantContext;
use Modules\Hrm\Models\Attendance;
use Modules\Hrm\Models\Employee;

class RecordAttendanceAction
{
    public function __construct(
        protected TenantContext $tenantContext
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data): Attendance
    {
        $tenantId = $this->tenantContext->requireId();
        $employee = Employee::findOrFail($data['employee_id']);

        return Attendance::updateOrCreate(
            [
                'tenant_id' => $tenantId,
                'employee_id' => $employee->id,
                'work_date' => $data['work_date'],
            ],
            [
                'check_in' => $data['check_in'] ?? null,
                'check_out' => $data['check_out'] ?? null,
                'status' => $data['status'],
                'notes' => $data['notes'] ?? null,
            ]
        );
    }
}
