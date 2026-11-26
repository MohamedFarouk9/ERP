<?php

declare(strict_types=1);

namespace Modules\Crm\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Crm\Models\CrmLead;
use Modules\Sales\Models\Customer;

class LeadConverted
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly CrmLead $lead,
        public readonly Customer $customer
    ) {}
}
