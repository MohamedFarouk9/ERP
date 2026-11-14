<?php

declare(strict_types=1);

namespace Modules\Users\Actions;

use App\Models\User;
use Illuminate\Validation\ValidationException;
use Modules\Users\Services\TotpService;

final class ConfirmMfaSetupAction
{
    public function __construct(
        private readonly TotpService $totpService
    ) {}

    /**
     * @throws ValidationException
     */
    public function execute(User $user, string $code): bool
    {
        $mfaSecret = $user->mfaSecret;

        if ($mfaSecret === null) {
            throw ValidationException::withMessages([
                'code' => ['MFA setup has not been initiated for this account.'],
            ]);
        }

        if (! $this->totpService->verify($mfaSecret->secret, $code)) {
            throw ValidationException::withMessages([
                'code' => ['The provided verification code is invalid.'],
            ]);
        }

        $mfaSecret->enabled_at = now();
        $mfaSecret->save();

        return true;
    }
}
