<?php

declare(strict_types=1);

namespace Modules\Payments\Actions;

use InvalidArgumentException;
use Modules\Core\Services\TenantContext;
use Modules\Payments\Models\Payment;
use Modules\Payments\Models\PaymentMethod;

class RecordPaymentAction
{
    public function __construct(
        protected TenantContext $tenantContext
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data): Payment
    {
        $tenantId = $this->tenantContext->requireId();
        $method = PaymentMethod::findOrFail($data['payment_method_id']);

        if (! $method->is_active) {
            throw new InvalidArgumentException("Payment method [{$method->code}] is inactive.");
        }

        $existing = Payment::withoutGlobalScopes()
            ->where('tenant_id', $tenantId)
            ->where('payment_number', $data['payment_number'])
            ->first();

        if ($existing) {
            throw new InvalidArgumentException("Payment [{$data['payment_number']}] already exists for this tenant.");
        }

        return Payment::create([
            'tenant_id' => $tenantId,
            'payment_method_id' => $method->id,
            'customer_id' => $data['customer_id'] ?? null,
            'invoice_id' => $data['invoice_id'] ?? null,
            'payment_number' => $data['payment_number'],
            'payment_date' => $data['payment_date'],
            'amount' => (int) $data['amount'],
            'currency_code' => $data['currency_code'] ?? 'USD',
            'status' => Payment::STATUS_PENDING,
            'reference' => $data['reference'] ?? null,
            'notes' => $data['notes'] ?? null,
        ]);
    }
}
