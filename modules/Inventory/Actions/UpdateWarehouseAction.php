<?php

declare(strict_types=1);

namespace Modules\Inventory\Actions;

use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use Modules\Core\Services\TenantContext;
use Modules\Inventory\Models\Warehouse;

class UpdateWarehouseAction
{
    public function __construct(
        protected TenantContext $tenantContext
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(Warehouse $warehouse, array $data): Warehouse
    {
        $tenantId = $this->tenantContext->requireId();

        $duplicate = Warehouse::withoutGlobalScopes()
            ->where('tenant_id', $tenantId)
            ->where('code', $data['code'])
            ->where('id', '!=', $warehouse->id)
            ->first();

        if ($duplicate) {
            throw new InvalidArgumentException("Warehouse with code [{$data['code']}] already exists for this tenant.");
        }

        return DB::transaction(function () use ($warehouse, $data) {
            $isDefault = (bool) ($data['is_default'] ?? false);

            if ($isDefault) {
                Warehouse::where('id', '!=', $warehouse->id)->update(['is_default' => false]);
            }

            $warehouse->update([
                'code' => $data['code'],
                'name' => $data['name'],
                'address' => $data['address'] ?? null,
                'is_default' => $isDefault,
                'is_active' => (bool) ($data['is_active'] ?? $warehouse->is_active),
            ]);

            return $warehouse->fresh();
        });
    }
}
