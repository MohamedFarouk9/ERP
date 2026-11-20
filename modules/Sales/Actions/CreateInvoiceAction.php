<?php

declare(strict_types=1);

namespace Modules\Sales\Actions;

use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use Modules\Core\Services\TenantContext;
use Modules\Sales\Models\Customer;
use Modules\Sales\Models\Invoice;
use Modules\Sales\Models\InvoiceItem;
use Modules\Sales\Models\SalesOrder;

class CreateInvoiceAction
{
    public function __construct(
        protected TenantContext $tenantContext
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data): Invoice
    {
        $tenantId = $this->tenantContext->requireId();
        $customer = Customer::findOrFail($data['customer_id']);

        if (! empty($data['sales_order_id'])) {
            SalesOrder::findOrFail($data['sales_order_id']);
        }

        $existing = Invoice::withoutGlobalScopes()
            ->where('tenant_id', $tenantId)
            ->where('invoice_number', $data['invoice_number'])
            ->first();

        if ($existing) {
            throw new InvalidArgumentException("Invoice [{$data['invoice_number']}] already exists for this tenant.");
        }

        return DB::transaction(function () use ($data, $tenantId, $customer) {
            $subtotal = 0;
            $taxTotal = 0;

            $invoice = Invoice::create([
                'tenant_id' => $tenantId,
                'customer_id' => $customer->id,
                'sales_order_id' => $data['sales_order_id'] ?? null,
                'invoice_number' => $data['invoice_number'],
                'invoice_date' => $data['invoice_date'],
                'due_date' => $data['due_date'],
                'status' => Invoice::STATUS_DRAFT,
                'currency_code' => $data['currency_code'] ?? 'USD',
                'notes' => $data['notes'] ?? null,
            ]);

            foreach ($data['items'] as $item) {
                $qty = (int) $item['quantity'];
                $unitPrice = (int) $item['unit_price'];
                $taxAmount = (int) ($item['tax_amount'] ?? 0);
                $lineSubtotal = $qty * $unitPrice;
                $lineTotal = $lineSubtotal + $taxAmount;

                $subtotal += $lineSubtotal;
                $taxTotal += $taxAmount;

                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => $item['product_id'] ?? null,
                    'description' => $item['description'],
                    'quantity' => $qty,
                    'unit_price' => $unitPrice,
                    'tax_amount' => $taxAmount,
                    'line_total' => $lineTotal,
                ]);
            }

            $invoice->update([
                'subtotal' => $subtotal,
                'tax_amount' => $taxTotal,
                'total_amount' => $subtotal + $taxTotal,
            ]);

            return $invoice->load(['customer', 'items']);
        });
    }
}
