<?php

declare(strict_types=1);

namespace Modules\Users\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Users\Actions\AuthenticateUserAction;
use Modules\Users\Actions\RegisterUserAction;
use Modules\Users\Actions\RevokeSessionAction;
use Modules\Users\Actions\VerifyMfaTotpAction;
use Modules\Users\Http\Requests\LoginRequest;
use Modules\Users\Http\Requests\RegisterRequest;
use Modules\Users\Http\Requests\VerifyMfaRequest;
use Modules\Users\Http\Resources\UserResource;

final class AuthController extends Controller
{
    public function register(RegisterRequest $request, RegisterUserAction $action): JsonResponse
    {
        /** @var array{tenant_id: string, name: string, email: string, password: string, phone?: string|null, timezone?: string, locale?: string} $data */
        $data = $request->validated();
        $user = $action->execute($data);

        return response()->json([
            'message' => 'User registered successfully.',
            'user' => new UserResource($user),
        ], 201);
    }

    public function login(LoginRequest $request, AuthenticateUserAction $action): JsonResponse
    {
        /** @var array{email: string, password: string, tenant_slug?: string|null} $validated */
        $validated = $request->validated();

        $result = $action->execute([
            'email' => $validated['email'],
            'password' => $validated['password'],
            'ip_address' => (string) $request->ip(),
            'user_agent' => $request->userAgent(),
            'tenant_slug' => $validated['tenant_slug'] ?? null,
        ]);

        if ($result['requires_mfa']) {
            return response()->json([
                'requires_mfa' => true,
                'user_id' => $result['user']->id,
                'message' => 'Two-factor authentication code required.',
            ], 200);
        }

        Auth::login($result['user']);

        return response()->json([
            'message' => 'Login successful.',
            'requires_mfa' => false,
            'user' => new UserResource($result['user']),
            'session_id' => $result['session_id'] ?? null,
        ], 200);
    }

    public function verifyMfa(VerifyMfaRequest $request, VerifyMfaTotpAction $action): JsonResponse
    {
        /** @var array{user_id: string, code: string} $validated */
        $validated = $request->validated();

        $result = $action->execute([
            'user_id' => $validated['user_id'],
            'code' => $validated['code'],
            'ip_address' => (string) $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        Auth::login($result['user']);

        return response()->json([
            'message' => 'Two-factor authentication verified successfully.',
            'user' => new UserResource($result['user']),
            'session_id' => $result['session_id'],
        ], 200);
    }

    public function logout(Request $request, RevokeSessionAction $action): JsonResponse
    {
        /** @var User|null $user */
        $user = $request->user();

        if ($user !== null) {
            $sessionId = $request->session()->getId();
            $action->revoke($user, $sessionId);
        }

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'message' => 'Logged out successfully.',
        ], 200);
    }
}
