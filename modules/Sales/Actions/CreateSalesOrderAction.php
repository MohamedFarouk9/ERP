<?php

declare(strict_types=1);

namespace Modules\Sales\Actions;

use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use Modules\Core\Services\TenantContext;
use Modules\Sales\Models\Customer;
use Modules\Sales\Models\SalesOrder;
use Modules\Sales\Models\SalesOrderItem;

class CreateSalesOrderAction
{
    public function __construct(
        protected TenantContext $tenantContext
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data): SalesOrder
    {
        $tenantId = $this->tenantContext->requireId();

        $customer = Customer::findOrFail($data['customer_id']);

        $existing = SalesOrder::withoutGlobalScopes()
            ->where('tenant_id', $tenantId)
            ->where('order_number', $data['order_number'])
            ->first();

        if ($existing) {
            throw new InvalidArgumentException("Sales order [{$data['order_number']}] already exists for this tenant.");
        }

        return DB::transaction(function () use ($data, $tenantId, $customer) {
            $subtotal = 0;
            $taxTotal = 0;
            $discount = (int) ($data['discount_amount'] ?? 0);

            $order = SalesOrder::create([
                'tenant_id' => $tenantId,
                'customer_id' => $customer->id,
                'warehouse_id' => $data['warehouse_id'] ?? null,
                'order_number' => $data['order_number'],
                'order_date' => $data['order_date'],
                'status' => SalesOrder::STATUS_DRAFT,
                'discount_amount' => $discount,
                'currency_code' => $data['currency_code'] ?? 'USD',
                'notes' => $data['notes'] ?? null,
            ]);

            foreach ($data['items'] as $item) {
                $qty = (int) $item['quantity'];
                $unitPrice = (int) $item['unit_price'];
                $taxRate = (int) ($item['tax_rate'] ?? 0);

                $lineSubtotal = $qty * $unitPrice;
                $lineTax = (int) round($lineSubtotal * ($taxRate / 10000));
                $lineTotal = $lineSubtotal + $lineTax;

                $subtotal += $lineSubtotal;
                $taxTotal += $lineTax;

                SalesOrderItem::create([
                    'sales_order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'description' => $item['description'] ?? null,
                    'quantity' => $qty,
                    'unit_price' => $unitPrice,
                    'tax_rate' => $taxRate,
                    'tax_amount' => $lineTax,
                    'line_total' => $lineTotal,
                ]);
            }

            $total = max(0, $subtotal + $taxTotal - $discount);

            $order->update([
                'subtotal' => $subtotal,
                'tax_amount' => $taxTotal,
                'total_amount' => $total,
            ]);

            return $order->load(['customer', 'items']);
        });
    }
}
