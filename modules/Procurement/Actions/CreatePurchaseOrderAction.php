<?php

declare(strict_types=1);

namespace Modules\Procurement\Actions;

use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use Modules\Core\Services\TenantContext;
use Modules\Procurement\Models\PurchaseOrder;
use Modules\Procurement\Models\PurchaseOrderItem;
use Modules\Procurement\Models\Supplier;

class CreatePurchaseOrderAction
{
    public function __construct(
        protected TenantContext $tenantContext
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data): PurchaseOrder
    {
        $tenantId = $this->tenantContext->requireId();
        $supplier = Supplier::findOrFail($data['supplier_id']);

        $existing = PurchaseOrder::withoutGlobalScopes()
            ->where('tenant_id', $tenantId)
            ->where('po_number', $data['po_number'])
            ->first();

        if ($existing) {
            throw new InvalidArgumentException("Purchase order [{$data['po_number']}] already exists for this tenant.");
        }

        return DB::transaction(function () use ($data, $tenantId, $supplier) {
            $subtotal = 0;
            $taxTotal = 0;

            $po = PurchaseOrder::create([
                'tenant_id' => $tenantId,
                'supplier_id' => $supplier->id,
                'warehouse_id' => $data['warehouse_id'] ?? null,
                'po_number' => $data['po_number'],
                'order_date' => $data['order_date'],
                'expected_date' => $data['expected_date'] ?? null,
                'status' => PurchaseOrder::STATUS_ORDERED,
                'currency_code' => $data['currency_code'] ?? 'USD',
                'notes' => $data['notes'] ?? null,
            ]);

            foreach ($data['items'] as $item) {
                $qty = (int) $item['quantity'];
                $unitCost = (int) $item['unit_cost'];
                $taxRate = (int) ($item['tax_rate'] ?? 0);

                $lineSub = $qty * $unitCost;
                $lineTax = (int) round($lineSub * ($taxRate / 10000));
                $lineTotal = $lineSub + $lineTax;

                $subtotal += $lineSub;
                $taxTotal += $lineTax;

                PurchaseOrderItem::create([
                    'purchase_order_id' => $po->id,
                    'product_id' => $item['product_id'],
                    'description' => $item['description'] ?? null,
                    'quantity' => $qty,
                    'unit_cost' => $unitCost,
                    'tax_rate' => $taxRate,
                    'tax_amount' => $lineTax,
                    'line_total' => $lineTotal,
                ]);
            }

            $po->update([
                'subtotal' => $subtotal,
                'tax_amount' => $taxTotal,
                'total_amount' => $subtotal + $taxTotal,
            ]);

            return $po->load(['supplier', 'items']);
        });
    }
}
