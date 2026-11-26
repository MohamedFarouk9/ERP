<?php

declare(strict_types=1);

namespace Modules\Crm\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Crm\Models\CrmDeal;

class DealWon
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly CrmDeal $deal
    ) {}
}
