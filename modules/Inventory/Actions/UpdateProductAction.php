<?php

declare(strict_types=1);

namespace Modules\Inventory\Actions;

use InvalidArgumentException;
use Modules\Core\Services\TenantContext;
use Modules\Inventory\Models\Category;
use Modules\Inventory\Models\Product;

class UpdateProductAction
{
    public function __construct(
        protected TenantContext $tenantContext
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(Product $product, array $data): Product
    {
        $tenantId = $this->tenantContext->requireId();

        $duplicate = Product::withoutGlobalScopes()
            ->where('tenant_id', $tenantId)
            ->where('sku', $data['sku'])
            ->where('id', '!=', $product->id)
            ->first();

        if ($duplicate) {
            throw new InvalidArgumentException("Product with SKU [{$data['sku']}] already exists for this tenant.");
        }

        if (! empty($data['category_id'])) {
            $category = Category::where('id', $data['category_id'])->first();
            if (! $category) {
                throw new InvalidArgumentException('Category not found in current tenant scope.');
            }
        }

        $product->update([
            'sku' => $data['sku'],
            'name' => $data['name'],
            'category_id' => $data['category_id'] ?: null,
            'description' => $data['description'] ?? null,
            'unit' => $data['unit'] ?? $product->unit,
            'cost_price' => (int) $data['cost_price'],
            'selling_price' => (int) $data['selling_price'],
            'reorder_level' => (int) ($data['reorder_level'] ?? $product->reorder_level),
            'is_active' => (bool) ($data['is_active'] ?? $product->is_active),
        ]);

        return $product->fresh(['category']);
    }
}
