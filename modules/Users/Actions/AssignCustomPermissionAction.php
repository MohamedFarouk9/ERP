<?php

declare(strict_types=1);

namespace Modules\Users\Actions;

use App\Models\User;
use Modules\Users\Models\Permission;

final class AssignCustomPermissionAction
{
    /**
     * Assign a direct custom permission to a user.
     *
     * @param  bool  $isGranted  true = explicit grant, false = explicit deny (overrides roles)
     */
    public function execute(User $user, string $permissionSlug, bool $isGranted = true): void
    {
        $permission = Permission::where('slug', $permissionSlug)->firstOrFail();

        $user->customPermissions()->syncWithoutDetaching([
            $permission->id => ['is_granted' => $isGranted],
        ]);

        $user->flushPermissionCache();
    }
}

