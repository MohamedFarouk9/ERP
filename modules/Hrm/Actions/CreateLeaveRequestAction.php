<?php

declare(strict_types=1);

namespace Modules\Hrm\Actions;

use Illuminate\Support\Carbon;
use Modules\Core\Services\TenantContext;
use Modules\Hrm\Models\Employee;
use Modules\Hrm\Models\LeaveRequest;

class CreateLeaveRequestAction
{
    public function __construct(
        protected TenantContext $tenantContext
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data): LeaveRequest
    {
        $tenantId = $this->tenantContext->requireId();
        $employee = Employee::findOrFail($data['employee_id']);

        $start = Carbon::parse((string) $data['start_date']);
        $end = Carbon::parse((string) $data['end_date']);
        $days = max(1, (int) $start->diffInDays($end) + 1);

        return LeaveRequest::create([
            'tenant_id' => $tenantId,
            'employee_id' => $employee->id,
            'leave_type' => $data['leave_type'],
            'start_date' => $start->toDateString(),
            'end_date' => $end->toDateString(),
            'days_count' => $days,
            'reason' => $data['reason'] ?? null,
            'status' => LeaveRequest::STATUS_PENDING,
        ]);
    }
}
