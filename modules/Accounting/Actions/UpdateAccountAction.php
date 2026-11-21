<?php

declare(strict_types=1);

namespace Modules\Accounting\Actions;

use InvalidArgumentException;
use Modules\Accounting\Models\Account;
use Modules\Core\Services\TenantContext;

class UpdateAccountAction
{
    public function __construct(
        protected TenantContext $tenantContext
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(Account $account, array $data): Account
    {
        $tenantId = $this->tenantContext->requireId();

        $duplicate = Account::withoutGlobalScopes()
            ->where('tenant_id', $tenantId)
            ->where('code', $data['code'])
            ->where('id', '!=', $account->id)
            ->first();

        if ($duplicate) {
            throw new InvalidArgumentException("Account with code [{$data['code']}] already exists for this tenant.");
        }

        if (! empty($data['parent_id'])) {
            if ($data['parent_id'] === $account->id) {
                throw new InvalidArgumentException('Account cannot be its own parent.');
            }
            $parent = Account::where('id', $data['parent_id'])->first();
            if (! $parent) {
                throw new InvalidArgumentException('Parent account not found in current tenant scope.');
            }
        }

        $account->update([
            'code' => $data['code'],
            'name' => $data['name'],
            'type' => $data['type'],
            'normal_balance' => $data['normal_balance'],
            'parent_id' => $data['parent_id'] ?: null,
            'currency_code' => $data['currency_code'] ?? $account->currency_code,
            'description' => $data['description'] ?? null,
            'is_active' => (bool) ($data['is_active'] ?? $account->is_active),
        ]);

        return $account->fresh(['parent']);
    }
}
