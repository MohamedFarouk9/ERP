<?php

declare(strict_types=1);

namespace Modules\Users\Actions;

use App\Models\User;
use Modules\Core\Models\Tenant;
use Modules\Users\Models\UserMfaSecret;
use Modules\Users\Services\TotpService;

final class EnableMfaAction
{
    public function __construct(
        private readonly TotpService $totpService
    ) {}

    /**
     * @return array{
     *     secret: string,
     *     qr_uri: string,
     *     recovery_codes: array<int, string>
     * }
     */
    public function execute(User $user): array
    {
        $secret = $this->totpService->generateSecret();
        $recoveryCodes = $this->totpService->generateRecoveryCodes();

        UserMfaSecret::updateOrCreate(
            ['user_id' => $user->id],
            [
                'secret' => $secret,
                'recovery_codes' => $recoveryCodes,
                'enabled_at' => null, // Pending confirmation
            ]
        );

        $tenantName = 'ERP System';
        if ($user->tenant_id !== null) {
            $tenant = Tenant::find($user->tenant_id);
            if ($tenant !== null) {
                $tenantName = $tenant->name;
            }
        }

        $qrUri = $this->totpService->getProvisioningUri(
            company: $tenantName,
            accountName: $user->email,
            secret: $secret
        );

        return [
            'secret' => $secret,
            'qr_uri' => $qrUri,
            'recovery_codes' => $recoveryCodes,
        ];
    }
}
