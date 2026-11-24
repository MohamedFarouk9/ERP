<?php

declare(strict_types=1);

namespace Modules\Users\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Users\Actions\AssignUserToTeamAction;
use Modules\Users\Actions\CreateDepartmentAction;
use Modules\Users\Actions\CreateStaffUserAction;
use Modules\Users\Actions\CreateTeamAction;
use Modules\Users\Actions\UpdateDepartmentAction;
use Modules\Users\Actions\UpdateStaffUserAction;
use Modules\Users\Actions\UpdateTeamAction;
use Modules\Users\Http\Requests\AssignTeamMemberRequest;
use Modules\Users\Http\Requests\CreateDepartmentRequest;
use Modules\Users\Http\Requests\CreateTeamRequest;
use Modules\Users\Http\Requests\StoreStaffRequest;
use Modules\Users\Http\Requests\UpdateDepartmentRequest;
use Modules\Users\Http\Requests\UpdateStaffRequest;
use Modules\Users\Http\Requests\UpdateTeamRequest;
use Modules\Users\Models\Department;
use Modules\Users\Models\Role;
use Modules\Users\Models\Team;

class OrganizationController extends Controller
{
    // ==================== STAFF DIRECTORY ====================

    public function staff(): View
    {
        $roles = Role::orderBy('name')->get(['id', 'name']);

        return view('admin.organization.staff', compact('roles'));
    }

    public function staffData(Request $request): JsonResponse
    {
        $draw = (int) $request->input('draw', 1);
        $start = (int) $request->input('start', 0);
        $length = min((int) $request->input('length', 25), 100);
        $search = trim((string) $request->input('search.value', ''));

        $baseQuery = User::query()->with('roles:id,name');
        $recordsTotal = User::count();

        if ($search !== '') {
            $baseQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $recordsFiltered = (clone $baseQuery)->count();

        $rows = $baseQuery->orderBy('name')
            ->skip($start)
            ->take($length)
            ->get();

        $data = $rows->map(fn (User $u) => [
            'id' => $u->id,
            'name' => $u->name,
            'email' => $u->email,
            'phone' => $u->phone ?? '',
            'role_id' => $u->roles->first()?->id ?? '',
            'role_names' => $u->roles->pluck('name')->join(', ') ?: 'Unassigned',
            'is_active' => $u->is_active,
        ]);

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ]);
    }

    public function storeStaff(StoreStaffRequest $request, CreateStaffUserAction $action): JsonResponse
    {
        $staff = $action->execute($request->validated());

        return response()->json([
            'message' => "Staff member [{$staff->name}] created successfully.",
            'data' => $staff,
        ], 201);
    }

    public function updateStaff(UpdateStaffRequest $request, string $id, UpdateStaffUserAction $action): JsonResponse
    {
        $staff = User::findOrFail($id);
        $updated = $action->execute($staff, $request->validated());

        return response()->json([
            'message' => "Staff member [{$updated->name}] updated successfully.",
            'data' => $updated,
        ]);
    }

    // ==================== DEPARTMENTS ====================

    public function departments(): View
    {
        $departments = Department::withCount('teams')->orderBy('code')->get();

        return view('admin.organization.departments', compact('departments'));
    }

    public function storeDepartment(CreateDepartmentRequest $request, CreateDepartmentAction $action): JsonResponse
    {
        /** @var array{name: string, code: string, description?: string|null} $data */
        $data = $request->validated();
        $department = $action->execute($data);

        return response()->json([
            'message' => "Department [{$department->code}] created successfully.",
            'data' => $department,
        ], 201);
    }

    public function updateDepartment(UpdateDepartmentRequest $request, string $id, UpdateDepartmentAction $action): JsonResponse
    {
        $department = Department::findOrFail($id);
        $updated = $action->execute($department, $request->validated());

        return response()->json([
            'message' => "Department [{$updated->code}] updated successfully.",
            'data' => $updated,
        ]);
    }

    public function destroyDepartment(string $id): JsonResponse
    {
        $department = Department::findOrFail($id);
        $code = $department->code;
        $department->delete();

        return response()->json([
            'message' => "Department [{$code}] deleted successfully.",
        ]);
    }

    // ==================== TEAMS ====================

    public function teams(): View
    {
        $teams = Team::with(['department', 'parent', 'members'])->orderBy('name')->get();
        $departments = Department::orderBy('name')->get(['id', 'code', 'name']);
        $users = User::where('is_active', true)->orderBy('name')->take(100)->get(['id', 'name', 'email']);

        return view('admin.organization.teams', compact('teams', 'departments', 'users'));
    }

    public function storeTeam(CreateTeamRequest $request, CreateTeamAction $action): JsonResponse
    {
        /** @var array{name: string, slug?: string|null, department_id?: string|null, parent_id?: string|null, description?: string|null} $data */
        $data = $request->validated();
        $team = $action->execute($data);

        return response()->json([
            'message' => "Team [{$team->name}] created successfully.",
            'data' => $team,
        ], 201);
    }

    public function updateTeam(UpdateTeamRequest $request, string $id, UpdateTeamAction $action): JsonResponse
    {
        $team = Team::findOrFail($id);
        $updated = $action->execute($team, $request->validated());

        return response()->json([
            'message' => "Team [{$updated->name}] updated successfully.",
            'data' => $updated,
        ]);
    }

    public function destroyTeam(string $id): JsonResponse
    {
        $team = Team::findOrFail($id);
        $name = $team->name;
        $team->delete();

        return response()->json([
            'message' => "Team [{$name}] deleted successfully.",
        ]);
    }

    public function assignMember(AssignTeamMemberRequest $request, AssignUserToTeamAction $action): JsonResponse
    {
        $data = $request->validated();
        $team = Team::findOrFail($data['team_id']);
        $user = User::findOrFail($data['user_id']);

        $action->execute($team, $user, (bool) ($data['is_leader'] ?? false));

        return response()->json([
            'message' => "Assigned [{$user->name}] to [{$team->name}].",
        ]);
    }
}
