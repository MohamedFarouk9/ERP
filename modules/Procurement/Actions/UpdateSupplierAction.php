<?php

declare(strict_types=1);

namespace Modules\Procurement\Actions;

use InvalidArgumentException;
use Modules\Core\Services\TenantContext;
use Modules\Procurement\Models\Supplier;

class UpdateSupplierAction
{
    public function __construct(
        protected TenantContext $tenantContext
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(Supplier $supplier, array $data): Supplier
    {
        $tenantId = $this->tenantContext->requireId();

        $duplicate = Supplier::withoutGlobalScopes()
            ->where('tenant_id', $tenantId)
            ->where('code', $data['code'])
            ->where('id', '!=', $supplier->id)
            ->first();

        if ($duplicate) {
            throw new InvalidArgumentException("Supplier with code [{$data['code']}] already exists for this tenant.");
        }

        $supplier->update([
            'code' => $data['code'],
            'name' => $data['name'],
            'email' => $data['email'] ?? null,
            'phone' => $data['phone'] ?? null,
            'tax_number' => $data['tax_number'] ?? null,
            'address' => $data['address'] ?? null,
            'payment_terms_days' => (int) ($data['payment_terms_days'] ?? $supplier->payment_terms_days),
            'is_active' => (bool) ($data['is_active'] ?? $supplier->is_active),
        ]);

        return $supplier->fresh();
    }
}
