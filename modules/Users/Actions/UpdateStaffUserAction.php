<?php

declare(strict_types=1);

namespace Modules\Users\Actions;

use App\Models\User;
use InvalidArgumentException;
use Modules\Users\Models\Role;

class UpdateStaffUserAction
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(User $user, array $data): User
    {
        $duplicate = User::withoutGlobalScopes()
            ->where('email', $data['email'])
            ->where('id', '!=', $user->id)
            ->first();

        if ($duplicate) {
            throw new InvalidArgumentException("A user with email [{$data['email']}] already exists.");
        }

        $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'is_active' => (bool) ($data['is_active'] ?? $user->is_active),
        ]);

        if (! empty($data['role_id'])) {
            $role = Role::where('id', $data['role_id'])->first();
            if ($role) {
                $user->roles()->sync([$role->id]);
            }
        }

        return $user->fresh(['roles']);
    }
}
