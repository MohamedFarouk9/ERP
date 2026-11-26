<?php

declare(strict_types=1);

namespace Modules\Crm\Actions;

use Modules\Crm\Models\CrmActivity;

class CompleteActivityAction
{
    public function execute(CrmActivity $activity): CrmActivity
    {
        if ($activity->status !== CrmActivity::STATUS_COMPLETED) {
            $activity->update([
                'status' => CrmActivity::STATUS_COMPLETED,
                'completed_at' => now(),
            ]);
        }

        return $activity->fresh(['lead', 'deal', 'assignee']);
    }
}
