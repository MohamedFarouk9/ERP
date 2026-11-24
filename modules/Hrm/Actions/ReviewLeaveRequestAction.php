<?php

declare(strict_types=1);

namespace Modules\Hrm\Actions;

use App\Models\User;
use Modules\Hrm\Models\LeaveRequest;
use RuntimeException;

class ReviewLeaveRequestAction
{
    public function execute(LeaveRequest $leave, string $decision, User $reviewer): LeaveRequest
    {
        if ($leave->status !== LeaveRequest::STATUS_PENDING) {
            throw new RuntimeException("Leave request has already been {$leave->status}.");
        }

        if (! in_array($decision, [LeaveRequest::STATUS_APPROVED, LeaveRequest::STATUS_REJECTED], true)) {
            throw new RuntimeException("Invalid leave decision [{$decision}].");
        }

        $leave->update([
            'status' => $decision,
            'reviewed_by' => $reviewer->id,
            'reviewed_at' => now(),
        ]);

        return $leave->fresh(['employee', 'reviewer']);
    }
}
