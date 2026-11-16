<?php

declare(strict_types=1);

namespace Modules\Users\Actions;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Modules\Users\Models\UserActiveSession;

final class RevokeSessionAction
{
    public function revoke(User $user, string $sessionId): bool
    {
        $deleted = UserActiveSession::where('user_id', $user->id)
            ->where('session_id', $sessionId)
            ->delete();

        // Also purge from framework sessions table if using database driver
        if (config('session.driver') === 'database') {
            DB::table('sessions')->where('id', $sessionId)->delete();
        }

        return $deleted > 0;
    }

    public function revokeAllExcept(User $user, ?string $currentSessionId = null): int
    {
        $query = UserActiveSession::where('user_id', $user->id);

        if ($currentSessionId !== null) {
            $query->where('session_id', '!=', $currentSessionId);
        }

        $sessionIds = $query->pluck('session_id')->toArray();
        $deleted = $query->delete();

        if (config('session.driver') === 'database' && ! empty($sessionIds)) {
            DB::table('sessions')->whereIn('id', $sessionIds)->delete();
        }

        return $deleted;
    }
}
