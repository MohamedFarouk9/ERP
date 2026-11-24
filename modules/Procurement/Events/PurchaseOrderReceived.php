<?php

declare(strict_types=1);

namespace Modules\Procurement\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Procurement\Models\PurchaseOrder;

class PurchaseOrderReceived
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public readonly PurchaseOrder $purchaseOrder
    ) {}
}
