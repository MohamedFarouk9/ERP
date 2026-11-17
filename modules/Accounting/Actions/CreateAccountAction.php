<?php

declare(strict_types=1);

namespace Modules\Accounting\Actions;

use InvalidArgumentException;
use Modules\Accounting\Models\Account;
use Modules\Core\Services\TenantContext;

class CreateAccountAction
{
    public function __construct(
        protected TenantContext $tenantContext
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data): Account
    {
        $tenantId = $this->tenantContext->requireId();

        $existing = Account::withoutGlobalScopes()
            ->where('tenant_id', $tenantId)
            ->where('code', $data['code'])
            ->first();

        if ($existing) {
            throw new InvalidArgumentException("Account with code [{$data['code']}] already exists for this tenant.");
        }

        if (! empty($data['parent_id'])) {
            $parent = Account::where('id', $data['parent_id'])->first();
            if (! $parent) {
                throw new InvalidArgumentException('Parent account not found in current tenant scope.');
            }
        }

        return Account::create([
            'tenant_id' => $tenantId,
            'parent_id' => $data['parent_id'] ?? null,
            'code' => $data['code'],
            'name' => $data['name'],
            'type' => $data['type'],
            'normal_balance' => $data['normal_balance'] ?? ($this->defaultNormalBalance($data['type'])),
            'currency_code' => $data['currency_code'] ?? 'USD',
            'description' => $data['description'] ?? null,
            'is_active' => $data['is_active'] ?? true,
        ]);
    }

    protected function defaultNormalBalance(string $type): string
    {
        return match ($type) {
            Account::TYPE_ASSET, Account::TYPE_EXPENSE => Account::BALANCE_DEBIT,
            Account::TYPE_LIABILITY, Account::TYPE_EQUITY, Account::TYPE_REVENUE => Account::BALANCE_CREDIT,
            default => Account::BALANCE_DEBIT,
        };
    }
}

