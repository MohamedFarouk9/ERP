<?php

declare(strict_types=1);

namespace Modules\Users\Actions;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use InvalidArgumentException;
use Modules\Core\Services\TenantContext;
use Modules\Users\Models\Role;

class CreateStaffUserAction
{
    public function __construct(
        protected TenantContext $tenantContext
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data): User
    {
        $tenantId = $this->tenantContext->requireId();

        $existing = User::withoutGlobalScopes()->where('email', $data['email'])->first();
        if ($existing) {
            throw new InvalidArgumentException("A user with email [{$data['email']}] already exists.");
        }

        $user = User::create([
            'tenant_id' => $tenantId,
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make((string) $data['password']),
            'phone' => $data['phone'] ?? null,
            'is_active' => (bool) ($data['is_active'] ?? true),
        ]);

        if (! empty($data['role_id'])) {
            $role = Role::where('id', $data['role_id'])->first();
            if ($role) {
                $user->roles()->sync([$role->id]);
            }
        }

        return $user->load('roles');
    }
}
