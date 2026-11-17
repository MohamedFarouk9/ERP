<?php

declare(strict_types=1);

namespace Modules\Users\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Users\Actions\AssignUserToTeamAction;
use Modules\Users\Actions\CreateDepartmentAction;
use Modules\Users\Actions\CreateTeamAction;
use Modules\Users\Http\Requests\CreateDepartmentRequest;
use Modules\Users\Http\Requests\CreateTeamRequest;
use Modules\Users\Models\Department;
use Modules\Users\Models\Team;

final class OrganizationController extends Controller
{
    public function departments(): JsonResponse
    {
        $departments = Department::with('teams')->get();

        return response()->json([
            'departments' => $departments,
        ], 200);
    }

    public function storeDepartment(CreateDepartmentRequest $request, CreateDepartmentAction $action): JsonResponse
    {
        /** @var array{name: string, code: string, description?: string|null} $data */
        $data = $request->validated();
        $department = $action->execute($data);

        return response()->json([
            'message' => 'Department created successfully.',
            'department' => $department,
        ], 201);
    }

    public function teams(): JsonResponse
    {
        $teams = Team::with(['department', 'children', 'members'])->get();

        return response()->json([
            'teams' => $teams,
        ], 200);
    }

    public function storeTeam(CreateTeamRequest $request, CreateTeamAction $action): JsonResponse
    {
        /** @var array{name: string, slug?: string|null, department_id?: string|null, parent_id?: string|null, description?: string|null} $data */
        $data = $request->validated();
        $team = $action->execute($data);

        return response()->json([
            'message' => 'Team created successfully.',
            'team' => $team,
        ], 201);
    }

    public function assignMember(Request $request, AssignUserToTeamAction $action): JsonResponse
    {
        $request->validate([
            'team_id' => ['required', 'string'],
            'user_id' => ['required', 'string'],
            'is_leader' => ['boolean'],
        ]);

        /** @var Team $team */
        $team = Team::findOrFail($request->input('team_id'));
        /** @var User $user */
        $user = User::findOrFail($request->input('user_id'));
        $isLeader = (bool) $request->input('is_leader', false);

        $action->execute($team, $user, $isLeader);

        return response()->json([
            'message' => 'Team member assigned successfully.',
        ], 200);
    }
}
