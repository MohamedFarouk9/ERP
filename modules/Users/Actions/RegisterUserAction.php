<?php

declare(strict_types=1);

namespace Modules\Users\Actions;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Modules\Core\Models\Tenant;
use Modules\Users\Models\PasswordHistory;

final class RegisterUserAction
{
    /**
     * @param  array{
     *     tenant_id: string,
     *     name: string,
     *     email: string,
     *     password: string,
     *     phone?: string|null,
     *     timezone?: string,
     *     locale?: string
     * }  $data
     *
     * @throws ValidationException
     */
    public function execute(array $data): User
    {
        $tenant = Tenant::find($data['tenant_id']);
        if ($tenant === null || ! $tenant->isActive()) {
            throw ValidationException::withMessages([
                'tenant_id' => ['The specified tenant is invalid or inactive.'],
            ]);
        }

        // Email unique per tenant
        $exists = User::withoutGlobalScope('tenant')
            ->where('tenant_id', $data['tenant_id'])
            ->where('email', strtolower(trim($data['email'])))
            ->exists();

        if ($exists) {
            throw ValidationException::withMessages([
                'email' => ['The email address has already been taken for this organization.'],
            ]);
        }

        $this->validatePasswordStrength($data['password']);

        return DB::transaction(function () use ($data): User {
            $user = new User([
                'tenant_id' => $data['tenant_id'],
                'name' => trim($data['name']),
                'email' => strtolower(trim($data['email'])),
                'password' => $data['password'],
                'phone' => $data['phone'] ?? null,
                'timezone' => $data['timezone'] ?? 'UTC',
                'locale' => $data['locale'] ?? 'en',
                'is_active' => true,
            ]);

            $user->save();

            PasswordHistory::create([
                'user_id' => $user->id,
                'password_hash' => $user->password,
                'created_at' => now(),
            ]);

            return $user;
        });
    }

    /**
     * @throws ValidationException
     */
    private function validatePasswordStrength(string $password): void
    {
        if (strlen($password) < 8) {
            throw ValidationException::withMessages([
                'password' => ['The password must be at least 8 characters in length.'],
            ]);
        }

        if (! preg_match('/[A-Z]/', $password) || ! preg_match('/[a-z]/', $password)) {
            throw ValidationException::withMessages([
                'password' => ['The password must contain both uppercase and lowercase letters.'],
            ]);
        }

        if (! preg_match('/[0-9]/', $password)) {
            throw ValidationException::withMessages([
                'password' => ['The password must contain at least one number.'],
            ]);
        }

        if (! preg_match('/[!@#$%^&*()_+\-=\[\]{};\':"\\|,.<>\/?]/', $password)) {
            throw ValidationException::withMessages([
                'password' => ['The password must contain at least one special character.'],
            ]);
        }
    }
}
