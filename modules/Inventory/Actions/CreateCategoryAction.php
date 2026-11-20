<?php

declare(strict_types=1);

namespace Modules\Inventory\Actions;

use Illuminate\Support\Str;
use InvalidArgumentException;
use Modules\Core\Services\TenantContext;
use Modules\Inventory\Models\Category;

class CreateCategoryAction
{
    public function __construct(
        protected TenantContext $tenantContext
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data): Category
    {
        $tenantId = $this->tenantContext->requireId();
        $slug = $data['slug'] ?? Str::slug((string) $data['name']);

        $existing = Category::withoutGlobalScopes()
            ->where('tenant_id', $tenantId)
            ->where('slug', $slug)
            ->first();

        if ($existing) {
            throw new InvalidArgumentException("Category with slug [{$slug}] already exists for this tenant.");
        }

        if (! empty($data['parent_id'])) {
            $parent = Category::where('id', $data['parent_id'])->first();
            if (! $parent) {
                throw new InvalidArgumentException('Parent category not found in current tenant scope.');
            }
        }

        return Category::create([
            'tenant_id' => $tenantId,
            'parent_id' => $data['parent_id'] ?? null,
            'name' => $data['name'],
            'slug' => $slug,
            'description' => $data['description'] ?? null,
            'is_active' => $data['is_active'] ?? true,
        ]);
    }
}
