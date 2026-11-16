<?php

declare(strict_types=1);

namespace Modules\Users\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modules\Users\Models\Permission;
use Modules\Users\Models\Role;
use Modules\Users\Models\Team;

trait HasPermissions
{
    /**
     * @var array<string, bool>|null
     */
    protected ?array $permissionCache = null;

    /**
     * @return BelongsToMany<Role, $this>
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    /**
     * @return BelongsToMany<Permission, $this>
     */
    public function customPermissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'user_permissions')
            ->withPivot('is_granted');
    }

    /**
     * @return BelongsToMany<Team, $this>
     */
    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class, 'team_members')
            ->withPivot('is_leader')
            ->withTimestamps();
    }

    public function hasRole(string|array $roles): bool
    {
        $roleList = is_array($roles) ? $roles : [$roles];

        return $this->roles->contains(fn (Role $role): bool => in_array($role->slug, $roleList, true));
    }

    public function assignRole(Role|string ...$roles): void
    {
        foreach ($roles as $role) {
            $roleModel = is_string($role)
                ? Role::where('slug', $role)->firstOrFail()
                : $role;

            $this->roles()->syncWithoutDetaching([$roleModel->id]);
        }

        $this->flushPermissionCache();
    }

    public function removeRole(Role|string ...$roles): void
    {
        foreach ($roles as $role) {
            $roleModel = is_string($role)
                ? Role::where('slug', $role)->firstOrFail()
                : $role;

            $this->roles()->detach($roleModel->id);
        }

        $this->flushPermissionCache();
    }

    public function givePermission(Permission|string $permission, bool $isGranted = true): void
    {
        $perm = is_string($permission)
            ? Permission::where('slug', $permission)->firstOrFail()
            : $permission;

        $this->customPermissions()->syncWithoutDetaching([
            $perm->id => ['is_granted' => $isGranted],
        ]);

        $this->flushPermissionCache();
    }

    public function revokePermission(Permission|string $permission): void
    {
        $perm = is_string($permission)
            ? Permission::where('slug', $permission)->firstOrFail()
            : $permission;

        $this->customPermissions()->detach($perm->id);
        $this->flushPermissionCache();
    }

    /**
     * Determine if user has a permission.
     * Order of evaluation:
     * 1. Direct custom permission overrides role (explicit deny = false, explicit grant = true).
     * 2. Role-inherited permissions.
     */
    public function hasPermissionTo(string $permissionSlug): bool
    {
        if ($this->permissionCache !== null && array_key_exists($permissionSlug, $this->permissionCache)) {
            return $this->permissionCache[$permissionSlug];
        }

        // 1. Direct user custom permission check
        $custom = $this->customPermissions->firstWhere('slug', $permissionSlug);
        if ($custom !== null) {
            /** @var object{is_granted: bool|int} $pivot */
            $pivot = $custom->pivot;
            $granted = (bool) $pivot->is_granted;
            $this->permissionCache[$permissionSlug] = $granted;

            return $granted;
        }

        // 2. Check through roles
        foreach ($this->roles as $role) {
            if ($role->permissions->contains('slug', $permissionSlug)) {
                $this->permissionCache[$permissionSlug] = true;

                return true;
            }
        }

        $this->permissionCache[$permissionSlug] = false;

        return false;
    }

    public function flushPermissionCache(): void
    {
        $this->permissionCache = null;
        $this->unsetRelation('roles');
        $this->unsetRelation('customPermissions');
    }
}
