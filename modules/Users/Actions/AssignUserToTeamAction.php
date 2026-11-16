<?php

declare(strict_types=1);

namespace Modules\Users\Actions;

use App\Models\User;
use Modules\Users\Models\Team;

final class AssignUserToTeamAction
{
    public function execute(Team $team, User $user, bool $isLeader = false): void
    {
        $team->members()->syncWithoutDetaching([
            $user->id => [
                'is_leader' => $isLeader,
                'created_at' => now(),
            ],
        ]);
    }

    public function remove(Team $team, User $user): void
    {
        $team->members()->detach($user->id);
    }
}

