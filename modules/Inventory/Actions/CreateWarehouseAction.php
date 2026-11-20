<?php

declare(strict_types=1);

namespace Modules\Inventory\Actions;

use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use Modules\Core\Services\TenantContext;
use Modules\Inventory\Models\Warehouse;

class CreateWarehouseAction
{
    public function __construct(
        protected TenantContext $tenantContext
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data): Warehouse
    {
        $tenantId = $this->tenantContext->requireId();

        $existing = Warehouse::withoutGlobalScopes()
            ->where('tenant_id', $tenantId)
            ->where('code', $data['code'])
            ->first();

        if ($existing) {
            throw new InvalidArgumentException("Warehouse with code [{$data['code']}] already exists for this tenant.");
        }

        return DB::transaction(function () use ($data, $tenantId) {
            $isDefault = (bool) ($data['is_default'] ?? false);

            if ($isDefault) {
                Warehouse::where('tenant_id', $tenantId)->update(['is_default' => false]);
            }

            return Warehouse::create([
                'tenant_id' => $tenantId,
                'code' => $data['code'],
                'name' => $data['name'],
                'address' => $data['address'] ?? null,
                'is_default' => $isDefault,
                'is_active' => $data['is_active'] ?? true,
            ]);
        });
    }
}
