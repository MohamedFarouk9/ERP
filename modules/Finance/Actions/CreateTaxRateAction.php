<?php

declare(strict_types=1);

namespace Modules\Finance\Actions;

use InvalidArgumentException;
use Modules\Core\Services\TenantContext;
use Modules\Finance\Models\TaxRate;

class CreateTaxRateAction
{
    public function __construct(
        protected TenantContext $tenantContext
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data): TaxRate
    {
        $tenantId = $this->tenantContext->requireId();
        $code = strtoupper(trim((string) $data['code']));

        if (TaxRate::withoutGlobalScopes()->where('tenant_id', $tenantId)->where('code', $code)->exists()) {
            throw new InvalidArgumentException("Tax rate [{$code}] already exists.");
        }

        return TaxRate::create([
            'tenant_id' => $tenantId,
            'code' => $code,
            'name' => $data['name'],
            'rate_bps' => (int) ($data['rate_bps'] ?? 0),
            'type' => $data['type'] ?? 'both',
            'is_default' => (bool) ($data['is_default'] ?? false),
            'is_active' => (bool) ($data['is_active'] ?? true),
        ]);
    }
}
