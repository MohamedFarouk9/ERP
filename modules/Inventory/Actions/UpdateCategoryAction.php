<?php

declare(strict_types=1);

namespace Modules\Inventory\Actions;

use Illuminate\Support\Str;
use InvalidArgumentException;
use Modules\Core\Services\TenantContext;
use Modules\Inventory\Models\Category;

class UpdateCategoryAction
{
    public function __construct(
        protected TenantContext $tenantContext
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(Category $category, array $data): Category
    {
        $tenantId = $this->tenantContext->requireId();
        $slug = ! empty($data['slug']) ? (string) $data['slug'] : Str::slug((string) $data['name']);

        $duplicate = Category::withoutGlobalScopes()
            ->where('tenant_id', $tenantId)
            ->where('slug', $slug)
            ->where('id', '!=', $category->id)
            ->first();

        if ($duplicate) {
            throw new InvalidArgumentException("Category with slug [{$slug}] already exists for this tenant.");
        }

        if (! empty($data['parent_id'])) {
            if ($data['parent_id'] === $category->id) {
                throw new InvalidArgumentException('Category cannot be its own parent.');
            }
            $parent = Category::where('id', $data['parent_id'])->first();
            if (! $parent) {
                throw new InvalidArgumentException('Parent category not found in current tenant scope.');
            }
        }

        $category->update([
            'name' => $data['name'],
            'slug' => $slug,
            'parent_id' => $data['parent_id'] ?: null,
            'description' => $data['description'] ?? null,
            'is_active' => (bool) ($data['is_active'] ?? $category->is_active),
        ]);

        return $category->fresh(['parent']);
    }
}
