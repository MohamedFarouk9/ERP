<?php

declare(strict_types=1);

namespace Modules\Accounting\Actions;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use Modules\Accounting\Models\AccountingPeriod;
use Modules\Accounting\Models\AccountingTransaction;
use Modules\Accounting\Models\AccountingTransactionLine;
use Modules\Accounting\Models\FinancialPeriod;
use Modules\Core\Services\TenantContext;

class CreateAccountingTransactionAction
{
    public function __construct(
        protected TenantContext $tenantContext
    ) {}

    /**
     * @param array{
     *     transaction_number: string,
     *     transaction_date: string,
     *     financial_period_id?: string|null,
     *     notes?: string|null,
     *     reference_type?: string|null,
     *     reference_id?: string|null,
     *     lines: array<int, array{account_id: string, debit: int, credit: int, description?: string|null}>
     * } $data
     */
    public function execute(array $data, ?User $user = null): AccountingTransaction
    {
        $tenantId = $this->tenantContext->requireId();

        if (empty($data['lines']) || count($data['lines']) < 2) {
            throw new InvalidArgumentException('An accounting transaction must contain at least two lines.');
        }

        $totalDebit = 0;
        $totalCredit = 0;

        foreach ($data['lines'] as $line) {
            if ($line['debit'] < 0 || $line['credit'] < 0) {
                throw new InvalidArgumentException('Debit and credit values must be non-negative.');
            }
            if ($line['debit'] > 0 && $line['credit'] > 0) {
                throw new InvalidArgumentException('A single line cannot have both debit and credit amounts.');
            }
            $totalDebit += $line['debit'];
            $totalCredit += $line['credit'];
        }

        if ($totalDebit !== $totalCredit) {
            throw new InvalidArgumentException("Accounting transaction is not balanced. Debits: {$totalDebit}, Credits: {$totalCredit}.");
        }

        if (! empty($data['financial_period_id'])) {
            $period = FinancialPeriod::where('id', $data['financial_period_id'])->first();
            if (! $period || $period->is_closed) {
                throw new InvalidArgumentException('Financial period is either invalid or closed.');
            }
        }

        return DB::transaction(function () use ($data, $tenantId) {
            $transaction = AccountingTransaction::create([
                'tenant_id' => $tenantId,
                'transaction_number' => $data['transaction_number'],
                'transaction_date' => $data['transaction_date'],
                'financial_period_id' => $data['financial_period_id'] ?? null,
                'notes' => $data['notes'] ?? null,
                'reference_type' => $data['reference_type'] ?? null,
                'reference_id' => $data['reference_id'] ?? null,
                'status' => AccountingTransaction::STATUS_DRAFT,
            ]);

            foreach ($data['lines'] as $line) {
                AccountingTransactionLine::create([
                    'transaction_id' => $transaction->id,
                    'account_id' => $line['account_id'],
                    'debit' => $line['debit'],
                    'credit' => $line['credit'],
                    'description' => $line['description'] ?? null,
                ]);
            }

            return $transaction->load('lines.account');
        });
    }
}
