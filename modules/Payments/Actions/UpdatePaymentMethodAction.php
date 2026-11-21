<?php

declare(strict_types=1);

namespace Modules\Payments\Actions;

use InvalidArgumentException;
use Modules\Core\Services\TenantContext;
use Modules\Payments\Models\PaymentMethod;

class UpdatePaymentMethodAction
{
    public function __construct(
        protected TenantContext $tenantContext
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(PaymentMethod $method, array $data): PaymentMethod
    {
        $tenantId = $this->tenantContext->requireId();

        $duplicate = PaymentMethod::withoutGlobalScopes()
            ->where('tenant_id', $tenantId)
            ->where('code', $data['code'])
            ->where('id', '!=', $method->id)
            ->first();

        if ($duplicate) {
            throw new InvalidArgumentException("Payment method with code [{$data['code']}] already exists for this tenant.");
        }

        $method->update([
            'code' => $data['code'],
            'name' => $data['name'],
            'type' => $data['type'],
            'account_id' => $data['account_id'] ?: null,
            'is_active' => (bool) ($data['is_active'] ?? $method->is_active),
        ]);

        return $method->fresh();
    }
}
