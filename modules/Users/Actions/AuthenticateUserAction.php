<?php

declare(strict_types=1);

namespace Modules\Users\Actions;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Modules\Core\Models\Tenant;
use Modules\Users\Models\LoginHistory;
use Modules\Users\Models\UserActiveSession;

final class AuthenticateUserAction
{
    public const MAX_FAILED_ATTEMPTS = 5;

    public const LOCKOUT_MINUTES = 15;

    /**
     * @param  array{
     *     email: string,
     *     password: string,
     *     ip_address: string,
     *     user_agent?: string|null,
     *     tenant_slug?: string|null
     * }  $credentials
     * @return array{
     *     success: bool,
     *     requires_mfa: bool,
     *     user: User,
     *     session_id?: string
     * }
     *
     * @throws ValidationException
     */
    public function execute(array $credentials): array
    {
        $email = strtolower(trim($credentials['email']));

        $userQuery = User::withoutGlobalScope('tenant')->where('email', $email);

        if (! empty($credentials['tenant_slug'])) {
            $tenant = Tenant::where('slug', $credentials['tenant_slug'])->first();
            if ($tenant !== null) {
                $userQuery->where('tenant_id', $tenant->id);
            }
        }

        /** @var User|null $user */
        $user = $userQuery->first();

        if ($user === null) {
            throw ValidationException::withMessages([
                'email' => ['These credentials do not match our records.'],
            ]);
        }

        // Check Lockout
        $recentFailures = LoginHistory::where('user_id', $user->id)
            ->where('status', LoginHistory::STATUS_FAILED)
            ->where('created_at', '>=', now()->subMinutes(self::LOCKOUT_MINUTES))
            ->count();

        if ($recentFailures >= self::MAX_FAILED_ATTEMPTS) {
            LoginHistory::create([
                'user_id' => $user->id,
                'ip_address' => $credentials['ip_address'],
                'user_agent' => $credentials['user_agent'] ?? null,
                'status' => LoginHistory::STATUS_BLOCKED,
                'failure_reason' => 'Account temporarily locked due to consecutive failed attempts.',
                'tenant_id' => $user->tenant_id,
                'created_at' => now(),
            ]);

            throw ValidationException::withMessages([
                'email' => ['Account temporarily locked due to excessive failed attempts. Please try again later.'],
            ]);
        }

        if (! $user->isActive()) {
            throw ValidationException::withMessages([
                'email' => ['Your user account has been deactivated.'],
            ]);
        }

        // Verify password
        if (! Hash::check($credentials['password'], $user->password)) {
            LoginHistory::create([
                'user_id' => $user->id,
                'ip_address' => $credentials['ip_address'],
                'user_agent' => $credentials['user_agent'] ?? null,
                'status' => LoginHistory::STATUS_FAILED,
                'failure_reason' => 'Invalid password.',
                'tenant_id' => $user->tenant_id,
                'created_at' => now(),
            ]);

            throw ValidationException::withMessages([
                'email' => ['These credentials do not match our records.'],
            ]);
        }

        // MFA check
        if ($user->hasMfaEnabled()) {
            return [
                'success' => true,
                'requires_mfa' => true,
                'user' => $user,
            ];
        }

        // Record successful login
        LoginHistory::create([
            'user_id' => $user->id,
            'ip_address' => $credentials['ip_address'],
            'user_agent' => $credentials['user_agent'] ?? null,
            'status' => LoginHistory::STATUS_SUCCESS,
            'tenant_id' => $user->tenant_id,
            'created_at' => now(),
        ]);

        $sessionId = (string) Str::uuid();

        UserActiveSession::create([
            'user_id' => $user->id,
            'session_id' => $sessionId,
            'ip_address' => $credentials['ip_address'],
            'user_agent' => $credentials['user_agent'] ?? null,
            'device_name' => $this->detectDevice($credentials['user_agent'] ?? null),
            'last_active_at' => now(),
            'logged_in_at' => now(),
        ]);

        return [
            'success' => true,
            'requires_mfa' => false,
            'user' => $user,
            'session_id' => $sessionId,
        ];
    }

    private function detectDevice(?string $userAgent): string
    {
        if ($userAgent === null) {
            return 'Unknown Device';
        }

        if (str_contains($userAgent, 'Mobile')) {
            return 'Mobile Browser';
        }

        return 'Desktop Browser';
    }
}
