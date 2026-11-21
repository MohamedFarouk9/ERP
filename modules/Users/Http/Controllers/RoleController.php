<?php

declare(strict_types=1);

namespace Modules\Users\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Modules\Users\Actions\CreateRoleAction;
use Modules\Users\Actions\UpdateRoleAction;
use Modules\Users\Http\Requests\CreateRoleRequest;
use Modules\Users\Http\Requests\UpdateRoleRequest;
use Modules\Users\Models\Permission;
use Modules\Users\Models\Role;

class RoleController extends Controller
{
    public function index(): View
    {
        $roles = Role::with('permissions:id')->withCount(['permissions', 'users'])->orderBy('name')->get();
        $groupedPermissions = Permission::orderBy('module')->orderBy('resource')->get()->groupBy('module');

        return view('admin.roles.index', compact('roles', 'groupedPermissions'));
    }

    public function store(CreateRoleRequest $request, CreateRoleAction $action): JsonResponse
    {
        $validated = $request->validated();
        $role = $action->execute($validated);

        if (! empty($validated['permissions']) && is_array($validated['permissions'])) {
            $permIds = Permission::whereIn('id', $validated['permissions'])->pluck('id')->all();
            $role->permissions()->sync($permIds);
        }

        return response()->json([
            'message' => "Role [{$role->name}] created successfully.",
            'data' => $role,
        ], 201);
    }

    public function update(UpdateRoleRequest $request, string $id, UpdateRoleAction $action): JsonResponse
    {
        $role = Role::findOrFail($id);
        $updated = $action->execute($role, $request->validated());

        return response()->json([
            'message' => "Role [{$updated->name}] updated successfully.",
            'data' => $updated,
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $role = Role::findOrFail($id);
        if ($role->is_system) {
            return response()->json([
                'message' => "System role [{$role->name}] cannot be deleted.",
            ], 422);
        }

        $name = $role->name;
        $role->delete();

        return response()->json([
            'message' => "Role [{$name}] deleted successfully.",
        ]);
    }

    public function permissions(): View
    {
        $permissions = Permission::orderBy('module')->orderBy('resource')->get()->groupBy('module');

        return view('admin.permissions.index', compact('permissions'));
    }
}
