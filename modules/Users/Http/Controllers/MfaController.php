<?php

declare(strict_types=1);

namespace Modules\Users\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Modules\Users\Actions\ConfirmMfaSetupAction;
use Modules\Users\Actions\EnableMfaAction;
use Modules\Users\Http\Requests\ConfirmMfaRequest;
use Modules\Users\Services\TotpService;

final class MfaController extends Controller
{
    public function setup(Request $request, EnableMfaAction $action): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $setupData = $action->execute($user);

        return response()->json([
            'message' => 'Scan the QR code or enter the secret manually, then submit a verification code to confirm.',
            'secret' => $setupData['secret'],
            'qr_uri' => $setupData['qr_uri'],
            'recovery_codes' => $setupData['recovery_codes'],
        ], 200);
    }

    public function confirm(ConfirmMfaRequest $request, ConfirmMfaSetupAction $action): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        /** @var array{code: string} $validated */
        $validated = $request->validated();

        $action->execute($user, $validated['code']);

        return response()->json([
            'message' => 'Two-factor authentication enabled successfully.',
            'mfa_enabled' => true,
        ], 200);
    }

    public function disable(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $request->validate([
            'password' => ['required', 'string'],
        ]);

        if (! Hash::check((string) $request->input('password'), $user->password)) {
            throw ValidationException::withMessages([
                'password' => ['The provided password does not match our records.'],
            ]);
        }

        if ($user->mfaSecret !== null) {
            $user->mfaSecret->delete();
        }

        return response()->json([
            'message' => 'Two-factor authentication disabled successfully.',
            'mfa_enabled' => false,
        ], 200);
    }

    public function recoveryCodes(Request $request, TotpService $totpService): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $request->validate([
            'password' => ['required', 'string'],
        ]);

        if (! Hash::check((string) $request->input('password'), $user->password)) {
            throw ValidationException::withMessages([
                'password' => ['The provided password does not match our records.'],
            ]);
        }

        $mfaSecret = $user->mfaSecret;
        if ($mfaSecret === null || ! $mfaSecret->isEnabled()) {
            throw ValidationException::withMessages([
                'mfa' => ['Two-factor authentication is not active on this account.'],
            ]);
        }

        $newCodes = $totpService->generateRecoveryCodes();
        $mfaSecret->recovery_codes = $newCodes;
        $mfaSecret->save();

        return response()->json([
            'message' => 'New recovery codes generated successfully.',
            'recovery_codes' => $newCodes,
        ], 200);
    }
}

