<?php

declare(strict_types=1);

namespace Modules\Finance\Actions;

use App\Models\User;
use InvalidArgumentException;
use Modules\Core\Services\TenantContext;
use Modules\Finance\Models\Expense;
use Modules\Finance\Models\ExpenseCategory;
use Modules\Finance\Models\TaxRate;

class CreateExpenseAction
{
    public function __construct(
        protected TenantContext $tenantContext
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data, User $user): Expense
    {
        $tenantId = $this->tenantContext->requireId();
        $category = ExpenseCategory::findOrFail($data['expense_category_id']);
        $number = (string) ($data['expense_number'] ?? ('EXP-'.now()->format('YmdHis').'-'.random_int(10, 99)));

        if (Expense::withoutGlobalScopes()->where('tenant_id', $tenantId)->where('expense_number', $number)->exists()) {
            throw new InvalidArgumentException("Expense [{$number}] already exists.");
        }

        $amount = (int) $data['amount'];
        $taxAmount = 0;
        $taxRateId = $data['tax_rate_id'] ?: null;

        if ($taxRateId) {
            $taxRate = TaxRate::findOrFail($taxRateId);
            $taxAmount = (int) round($amount * ($taxRate->rate_bps / 10000));
        }

        return Expense::create([
            'tenant_id' => $tenantId,
            'expense_number' => $number,
            'expense_category_id' => $category->id,
            'tax_rate_id' => $taxRateId,
            'payee' => $data['payee'],
            'expense_date' => $data['expense_date'],
            'amount' => $amount,
            'tax_amount' => $taxAmount,
            'total_amount' => $amount + $taxAmount,
            'payment_method' => $data['payment_method'] ?? 'bank_transfer',
            'status' => Expense::STATUS_DRAFT,
            'reference' => $data['reference'] ?? null,
            'notes' => $data['notes'] ?? null,
            'created_by' => $user->id,
        ]);
    }
}
