<?php

declare(strict_types=1);

namespace Modules\Procurement\Actions;

use InvalidArgumentException;
use Modules\Core\Services\TenantContext;
use Modules\Procurement\Models\PurchaseOrder;
use Modules\Procurement\Models\Supplier;
use Modules\Procurement\Models\VendorBill;

class CreateVendorBillAction
{
    public function __construct(
        protected TenantContext $tenantContext
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data): VendorBill
    {
        $tenantId = $this->tenantContext->requireId();
        $supplier = Supplier::findOrFail($data['supplier_id']);

        if (! empty($data['purchase_order_id'])) {
            PurchaseOrder::findOrFail($data['purchase_order_id']);
        }

        $existing = VendorBill::withoutGlobalScopes()
            ->where('tenant_id', $tenantId)
            ->where('bill_number', $data['bill_number'])
            ->first();

        if ($existing) {
            throw new InvalidArgumentException("Vendor bill [{$data['bill_number']}] already exists for this tenant.");
        }

        $subtotal = (int) $data['subtotal'];
        $tax = (int) ($data['tax_amount'] ?? 0);

        return VendorBill::create([
            'tenant_id' => $tenantId,
            'supplier_id' => $supplier->id,
            'purchase_order_id' => $data['purchase_order_id'] ?: null,
            'bill_number' => $data['bill_number'],
            'bill_date' => $data['bill_date'],
            'due_date' => $data['due_date'],
            'status' => VendorBill::STATUS_DRAFT,
            'subtotal' => $subtotal,
            'tax_amount' => $tax,
            'total_amount' => $subtotal + $tax,
            'amount_paid' => 0,
            'currency_code' => $data['currency_code'] ?? 'USD',
            'notes' => $data['notes'] ?? null,
        ]);
    }
}
