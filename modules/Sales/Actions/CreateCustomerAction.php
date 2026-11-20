<?php

declare(strict_types=1);

namespace Modules\Sales\Actions;

use InvalidArgumentException;
use Modules\Core\Services\TenantContext;
use Modules\Sales\Models\Customer;

class CreateCustomerAction
{
    public function __construct(
        protected TenantContext $tenantContext
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data): Customer
    {
        $tenantId = $this->tenantContext->requireId();

        $existing = Customer::withoutGlobalScopes()
            ->where('tenant_id', $tenantId)
            ->where('code', $data['code'])
            ->first();

        if ($existing) {
            throw new InvalidArgumentException("Customer with code [{$data['code']}] already exists for this tenant.");
        }

        return Customer::create([
            'tenant_id' => $tenantId,
            'code' => $data['code'],
            'name' => $data['name'],
            'email' => $data['email'] ?? null,
            'phone' => $data['phone'] ?? null,
            'tax_number' => $data['tax_number'] ?? null,
            'billing_address' => $data['billing_address'] ?? null,
            'shipping_address' => $data['shipping_address'] ?? null,
            'credit_limit' => (int) ($data['credit_limit'] ?? 0),
            'is_active' => $data['is_active'] ?? true,
        ]);
    }
}
