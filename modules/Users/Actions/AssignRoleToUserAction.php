<?php

declare(strict_types=1);

namespace Modules\Users\Actions;

use App\Models\User;
use Modules\Users\Models\Role;

final class AssignRoleToUserAction
{
    /**
     * @param  array<int, string>  $roleSlugs
     */
    public function execute(User $user, array $roleSlugs): void
    {
        $roleIds = Role::whereIn('slug', $roleSlugs)->pluck('id');
        $user->roles()->syncWithoutDetaching($roleIds);
        $user->flushPermissionCache();
    }
}
