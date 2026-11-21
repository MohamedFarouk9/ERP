<?php

declare(strict_types=1);

namespace Modules\Users\Actions;

use Illuminate\Support\Str;
use InvalidArgumentException;
use Modules\Core\Services\TenantContext;
use Modules\Users\Models\Permission;
use Modules\Users\Models\Role;

class UpdateRoleAction
{
    public function __construct(
        protected TenantContext $tenantContext
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(Role $role, array $data): Role
    {
        $tenantId = $this->tenantContext->requireId();
        $slug = ! empty($data['slug']) ? (string) $data['slug'] : Str::slug((string) $data['name']);

        $duplicate = Role::withoutGlobalScopes()
            ->where('tenant_id', $tenantId)
            ->where('slug', $slug)
            ->where('id', '!=', $role->id)
            ->first();

        if ($duplicate) {
            throw new InvalidArgumentException("Role with slug [{$slug}] already exists for this tenant.");
        }

        $role->update([
            'name' => $data['name'],
            'slug' => $slug,
            'description' => $data['description'] ?? null,
        ]);

        if (isset($data['permissions']) && is_array($data['permissions'])) {
            $permIds = Permission::whereIn('id', $data['permissions'])->pluck('id')->all();
            $role->permissions()->sync($permIds);
        } else {
            $role->permissions()->sync([]);
        }

        return $role->fresh(['permissions']);
    }
}
