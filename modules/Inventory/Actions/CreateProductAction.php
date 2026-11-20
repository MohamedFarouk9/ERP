<?php

declare(strict_types=1);

namespace Modules\Inventory\Actions;

use InvalidArgumentException;
use Modules\Core\Services\TenantContext;
use Modules\Inventory\Models\Category;
use Modules\Inventory\Models\Product;

class CreateProductAction
{
    public function __construct(
        protected TenantContext $tenantContext
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data): Product
    {
        $tenantId = $this->tenantContext->requireId();

        $existing = Product::withoutGlobalScopes()
            ->where('tenant_id', $tenantId)
            ->where('sku', $data['sku'])
            ->first();

        if ($existing) {
            throw new InvalidArgumentException("Product with SKU [{$data['sku']}] already exists for this tenant.");
        }

        if (! empty($data['category_id'])) {
            $category = Category::where('id', $data['category_id'])->first();
            if (! $category) {
                throw new InvalidArgumentException('Category not found in current tenant scope.');
            }
        }

        return Product::create([
            'tenant_id' => $tenantId,
            'category_id' => $data['category_id'] ?? null,
            'sku' => $data['sku'],
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'unit' => $data['unit'] ?? 'pcs',
            'cost_price' => (int) ($data['cost_price'] ?? 0),
            'selling_price' => (int) ($data['selling_price'] ?? 0),
            'reorder_level' => (int) ($data['reorder_level'] ?? 0),
            'track_inventory' => $data['track_inventory'] ?? true,
            'is_active' => $data['is_active'] ?? true,
        ]);
    }
}
