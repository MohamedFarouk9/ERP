<?php

declare(strict_types=1);

namespace Modules\Procurement\Actions;

use InvalidArgumentException;
use Modules\Core\Services\TenantContext;
use Modules\Procurement\Models\Supplier;

class CreateSupplierAction
{
    public function __construct(
        protected TenantContext $tenantContext
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data): Supplier
    {
        $tenantId = $this->tenantContext->requireId();

        $existing = Supplier::withoutGlobalScopes()
            ->where('tenant_id', $tenantId)
            ->where('code', $data['code'])
            ->first();

        if ($existing) {
            throw new InvalidArgumentException("Supplier with code [{$data['code']}] already exists for this tenant.");
        }

        return Supplier::create([
            'tenant_id' => $tenantId,
            'code' => $data['code'],
            'name' => $data['name'],
            'email' => $data['email'] ?? null,
            'phone' => $data['phone'] ?? null,
            'tax_number' => $data['tax_number'] ?? null,
            'address' => $data['address'] ?? null,
            'payment_terms_days' => (int) ($data['payment_terms_days'] ?? 30),
            'is_active' => $data['is_active'] ?? true,
        ]);
    }
}
