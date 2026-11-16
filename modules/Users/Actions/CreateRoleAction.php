<?php

declare(strict_types=1);

namespace Modules\Users\Actions;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\Core\Services\TenantContext;
use Modules\Users\Models\Permission;
use Modules\Users\Models\Role;

final class CreateRoleAction
{
    public function __construct(
        private readonly TenantContext $tenantContext
    ) {}

    /**
     * @param  array{
     *     name: string,
     *     slug?: string|null,
     *     description?: string|null,
     *     permissions?: array<int, string>
     * }  $data
     */
    public function execute(array $data): Role
    {
        // this will get the current tenant id from the tenant context
        $tenantId = $this->tenantContext->require()->id; 
        $slug = ! empty($data['slug']) ? Str::slug($data['slug']) : Str::slug($data['name']);

        return DB::transaction(function () use ($tenantId, $data, $slug): Role {
            $role = Role::create([
                'tenant_id' => $tenantId,
                'name' => trim($data['name']),
                'slug' => $slug,
                'description' => $data['description'] ?? null,
                'is_system' => false,
            ]);

            if (! empty($data['permissions'])) {
                $permissionIds = Permission::whereIn('slug', $data['permissions'])->pluck('id');
                $role->permissions()->sync($permissionIds);
            }

            return $role->load('permissions');
        });
    }
}
