<?php

declare(strict_types=1);

namespace Modules\Users\Actions;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Modules\Users\Models\PasswordHistory;

final class ChangePasswordAction
{
    public const HISTORY_LIMIT = 5;

    /**
     * @throws ValidationException
     */
    public function execute(User $user, string $currentPassword, string $newPassword): bool
    {
        if (! Hash::check($currentPassword, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['The current password is incorrect.'],
            ]);
        }

        $this->validatePasswordStrength($newPassword);

        // Check password history (cannot reuse any of the last 5 passwords)
        $recentPasswords = PasswordHistory::where('user_id', $user->id)
            ->latest('created_at')
            ->take(self::HISTORY_LIMIT)
            ->pluck('password_hash');

        foreach ($recentPasswords as $oldHash) {
            if (Hash::check($newPassword, $oldHash)) {
                throw ValidationException::withMessages([
                    'password' => ['You cannot reuse any of your last '.self::HISTORY_LIMIT.' passwords.'],
                ]);
            }
        }

        return DB::transaction(function () use ($user, $newPassword): bool {
            $user->password = $newPassword;
            $user->save();

            PasswordHistory::create([
                'user_id' => $user->id,
                'password_hash' => $user->password,
                'created_at' => now(),
            ]);

            return true;
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

