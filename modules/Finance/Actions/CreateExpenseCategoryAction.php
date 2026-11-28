<?php

declare(strict_types=1);

namespace Modules\Finance\Actions;

use InvalidArgumentException;
use Modules\Core\Services\TenantContext;
use Modules\Finance\Models\ExpenseCategory;

class CreateExpenseCategoryAction
{
    public function __construct(
        protected TenantContext $tenantContext
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data): ExpenseCategory
    {
        $tenantId = $this->tenantContext->requireId();
        $code = strtoupper(trim((string) $data['code']));

        if (ExpenseCategory::withoutGlobalScopes()->where('tenant_id', $tenantId)->where('code', $code)->exists()) {
            throw new InvalidArgumentException("Expense category [{$code}] already exists.");
        }

        return ExpenseCategory::create([
            'tenant_id' => $tenantId,
            'code' => $code,
            'name' => $data['name'],
            'account_id' => $data['account_id'] ?: null,
            'description' => $data['description'] ?? null,
            'is_active' => (bool) ($data['is_active'] ?? true),
        ]);
    }
}
