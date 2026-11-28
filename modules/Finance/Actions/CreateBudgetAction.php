<?php

declare(strict_types=1);

namespace Modules\Finance\Actions;

use Modules\Core\Services\TenantContext;
use Modules\Finance\Models\Budget;
use Modules\Finance\Models\ExpenseCategory;

class CreateBudgetAction
{
    public function __construct(
        protected TenantContext $tenantContext
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data): Budget
    {
        $tenantId = $this->tenantContext->requireId();
        $category = ExpenseCategory::findOrFail($data['expense_category_id']);

        return Budget::updateOrCreate(
            [
                'tenant_id' => $tenantId,
                'expense_category_id' => $category->id,
                'period_month' => $data['period_month'],
            ],
            [
                'name' => $data['name'],
                'budget_amount' => (int) $data['budget_amount'],
                'notes' => $data['notes'] ?? null,
                'is_active' => true,
            ]
        );
    }
}
