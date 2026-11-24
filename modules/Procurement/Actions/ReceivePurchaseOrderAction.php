<?php

declare(strict_types=1);

namespace Modules\Procurement\Actions;

use App\Models\User;
use Modules\Procurement\Events\PurchaseOrderReceived;
use Modules\Procurement\Models\PurchaseOrder;
use RuntimeException;

class ReceivePurchaseOrderAction
{
    public function execute(PurchaseOrder $po, User $user): PurchaseOrder
    {
        if (! in_array($po->status, [PurchaseOrder::STATUS_DRAFT, PurchaseOrder::STATUS_ORDERED], true)) {
            throw new RuntimeException("Purchase order [{$po->po_number}] cannot be received in status [{$po->status}].");
        }

        $po->update([
            'status' => PurchaseOrder::STATUS_RECEIVED,
            'received_by' => $user->id,
            'received_at' => now(),
        ]);

        event(new PurchaseOrderReceived($po));

        return $po->fresh(['supplier', 'items', 'receiver']);
    }
}
