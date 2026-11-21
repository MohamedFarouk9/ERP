<?php

declare(strict_types=1);

namespace Modules\Payments\Actions;

use InvalidArgumentException;
use Modules\Core\Services\TenantContext;
use Modules\Payments\Models\PaymentMethod;

class CreatePaymentMethodAction
{
    public function __construct(
        protected TenantContext $tenantContext
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data): PaymentMethod
    {
        $tenantId = $this->tenantContext->requireId();

        $existing = PaymentMethod::withoutGlobalScopes()
            ->where('tenant_id', $tenantId)
            ->where('code', $data['code'])
            ->first();

        if ($existing) {
            throw new InvalidArgumentException("Payment method with code [{$data['code']}] already exists for this tenant.");
        }

        return PaymentMethod::create([
            'tenant_id' => $tenantId,
            'code' => $data['code'],
            'name' => $data['name'],
            'type' => $data['type'],
            'account_id' => $data['account_id'] ?? null,
            'is_active' => $data['is_active'] ?? true,
            'settings' => $data['settings'] ?? null,
        ]);
    }
}
