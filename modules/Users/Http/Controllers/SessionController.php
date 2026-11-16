<?php

declare(strict_types=1);

namespace Modules\Users\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Users\Actions\RevokeSessionAction;
use Modules\Users\Http\Resources\ActiveSessionResource;

final class SessionController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $sessions = $user->activeSessions()->latest('last_active_at')->get();

        return response()->json([
            'sessions' => ActiveSessionResource::collection($sessions),
        ], 200);
    }

    public function destroy(Request $request, string $sessionId, RevokeSessionAction $action): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $revoked = $action->revoke($user, $sessionId);

        return response()->json([
            'message' => $revoked ? 'Session revoked successfully.' : 'Session not found.',
        ], 200);
    }

    public function destroyAll(Request $request, RevokeSessionAction $action): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $currentSessionId = $request->session()->getId();
        $count = $action->revokeAllExcept($user, $currentSessionId);

        return response()->json([
            'message' => "Revoked {$count} other active session(s).",
        ], 200);
    }
}
