<?php

declare(strict_types=1);

namespace Modules\Users\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Users\Actions\ChangePasswordAction;
use Modules\Users\Http\Requests\ChangePasswordRequest;
use Modules\Users\Http\Requests\UpdateProfileRequest;
use Modules\Users\Http\Resources\UserResource;

final class ProfileController extends Controller
{
    public function show(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        return response()->json([
            'user' => new UserResource($user),
        ], 200);
    }

    public function update(UpdateProfileRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $validated = $request->validated();
        $user->fill($validated);
        $user->save();

        return response()->json([
            'message' => 'Profile updated successfully.',
            'user' => new UserResource($user),
        ], 200);
    }

    public function changePassword(ChangePasswordRequest $request, ChangePasswordAction $action): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        /** @var array{current_password: string, password: string} $validated */
        $validated = $request->validated();

        $action->execute(
            user: $user,
            currentPassword: $validated['current_password'],
            newPassword: $validated['password'],
        );

        return response()->json([
            'message' => 'Password changed successfully.',
        ], 200);
    }
}

