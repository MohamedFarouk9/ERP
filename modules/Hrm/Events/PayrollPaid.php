<?php

declare(strict_types=1);

namespace Modules\Hrm\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Hrm\Models\PayrollRun;

class PayrollPaid
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public readonly PayrollRun $payrollRun
    ) {}
}
