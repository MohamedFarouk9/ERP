<?php

declare(strict_types=1);

namespace Modules\Procurement\Actions;

use Modules\Procurement\Models\VendorBill;
use RuntimeException;

class ApproveVendorBillAction
{
    public function execute(VendorBill $bill): VendorBill
    {
        if ($bill->status !== VendorBill::STATUS_DRAFT) {
            throw new RuntimeException("Only draft vendor bills can be approved. Current status: {$bill->status}.");
        }

        $bill->update(['status' => VendorBill::STATUS_APPROVED]);

        return $bill->fresh(['supplier']);
    }
}
