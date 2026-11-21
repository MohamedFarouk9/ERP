<?php

declare(strict_types=1);

namespace Modules\Sales\Actions;

use InvalidArgumentException;
use Modules\Core\Services\TenantContext;
use Modules\Sales\Models\Customer;

class UpdateCustomerAction
{
    public function __construct(
        protected TenantContext $tenantContext
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(Customer $customer, array $data): Customer
    {
        $tenantId = $this->tenantContext->requireId();

        $duplicate = Customer::withoutGlobalScopes()
            ->where('tenant_id', $tenantId)
            ->where('code', $data['code'])
            ->where('id', '!=', $customer->id)
            ->first();

        if ($duplicate) {
            throw new InvalidArgumentException("Customer with code [{$data['code']}] already exists for this tenant.");
        }

        $customer->update([
            'code' => $data['code'],
            'name' => $data['name'],
            'email' => $data['email'] ?? null,
            'phone' => $data['phone'] ?? null,
            'tax_number' => $data['tax_number'] ?? null,
            'billing_address' => $data['billing_address'] ?? null,
            'shipping_address' => $data['shipping_address'] ?? null,
            'credit_limit' => (int) ($data['credit_limit'] ?? $customer->credit_limit),
            'is_active' => (bool) ($data['is_active'] ?? $customer->is_active),
        ]);

        return $customer->fresh();
    }
}
