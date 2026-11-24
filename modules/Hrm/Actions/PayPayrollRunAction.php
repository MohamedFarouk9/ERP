<?php

declare(strict_types=1);

namespace Modules\Hrm\Actions;

use App\Models\User;
use Modules\Hrm\Events\PayrollPaid;
use Modules\Hrm\Models\PayrollRun;
use RuntimeException;

class PayPayrollRunAction
{
    public function execute(PayrollRun $run, User $user): PayrollRun
    {
        if ($run->status === PayrollRun::STATUS_PAID) {
            throw new RuntimeException("Payroll run [{$run->payroll_number}] is already paid.");
        }

        $run->update([
            'status' => PayrollRun::STATUS_PAID,
            'processed_by' => $user->id,
            'paid_at' => now(),
        ]);

        event(new PayrollPaid($run));

        return $run->fresh(['items.employee', 'processor']);
    }
}
