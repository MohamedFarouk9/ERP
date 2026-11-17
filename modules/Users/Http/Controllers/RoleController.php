<?php

declare(strict_types=1);

namespace Modules\Users\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Users\Actions\AssignCustomPermissionAction;
use Modules\Users\Actions\AssignRoleToUserAction;
use Modules\Users\Actions\CreateRoleAction;
use Modules\Users\Http\Requests\CreateRoleRequest;
use Modules\Users\Models\Permission;
use Modules\Users\Models\Role;

final class RoleController extends Controller
{
    public function index(): JsonResponse
    {
        $roles = Role::with('permissions')->get();

        return response()->json([
            'roles' => $roles,
        ], 200);
    }

    public function permissions(): JsonResponse
    {
        $permissions = Permission::all();

        return response()->json([
            'permissions' => $permissions,
        ], 200);
    }

    public function store(CreateRoleRequest $request, CreateRoleAction $action): JsonResponse
    {
        /** @var array{name: string, slug?: string|null, description?: string|null, permissions?: array<int, string>} $data */
        $data = $request->validated();
        $role = $action->execute($data);

        return response()->json([
            'message' => 'Role created successfully.',
            'role' => $role,
        ], 201);
    }

    public function assign(Request $request, AssignRoleToUserAction $action): JsonResponse
    {
        $request->validate([
            'user_id' => ['required', 'string'],
            'roles' => ['required', 'array'],
            'roles.*' => ['string'],
        ]);

        /** @var User $user */
        $user = User::findOrFail($request->input('user_id'));
        /** @var array<int, string> $roles */
        $roles = $request->input('roles');

        $action->execute($user, $roles);

        return response()->json([
            'message' => 'Roles assigned successfully.',
        ], 200);
    }

    public function assignCustomPermission(Request $request, AssignCustomPermissionAction $action): JsonResponse
    {
        $request->validate([
            'user_id' => ['required', 'string'],
            'permission' => ['required', 'string'],
            'is_granted' => ['boolean'],
        ]);

        /** @var User $user */
        $user = User::findOrFail($request->input('user_id'));
        $permission = (string) $request->input('permission');
        $isGranted = (bool) $request->input('is_granted', true);

        $action->execute($user, $permission, $isGranted);

        return response()->json([
            'message' => 'Custom permission applied successfully.',
        ], 200);
    }
}
