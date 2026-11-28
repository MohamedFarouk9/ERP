<?php

declare(strict_types=1);

namespace Modules\Finance\Actions;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Accounting\Actions\CreateAccountingTransactionAction;
use Modules\Accounting\Actions\PostAccountingTransactionAction;
use Modules\Accounting\Models\Account;
use Modules\Finance\Models\Expense;
use RuntimeException;
use Throwable;

class PayExpenseAction
{
    public function __construct(
        protected CreateAccountingTransactionAction $createTransaction,
        protected PostAccountingTransactionAction $postTransaction
    ) {}

    public function execute(Expense $expense, User $user): Expense
    {
        if ($expense->status === Expense::STATUS_PAID) {
            throw new RuntimeException("Expense [{$expense->expense_number}] is already paid.");
        }

        return DB::transaction(function () use ($expense, $user) {
            $expense->update([
                'status' => Expense::STATUS_PAID,
                'paid_at' => now(),
            ]);

            $expense->loadMissing('category');
            $expenseAccount = $expense->category?->account_id
                ? Account::where('id', $expense->category->account_id)->first()
                : Account::where('type', Account::TYPE_EXPENSE)->first();
            $cashAccount = Account::where('code', '1010')->first();

            if ($expenseAccount && $cashAccount && $expense->total_amount > 0) {
                try {
                    $txn = $this->createTransaction->execute([
                        'transaction_number' => 'AUTO-EXP-'.$expense->expense_number,
                        'transaction_date' => $expense->expense_date?->toDateString() ?? now()->toDateString(),
                        'reference_type' => 'Expense',
                        'reference_id' => $expense->id,
                        'notes' => "Paid Expense {$expense->expense_number} ({$expense->payee})",
                        'lines' => [
                            [
                                'account_id' => $expenseAccount->id,
                                'debit' => $expense->total_amount,
                                'credit' => 0,
                                'description' => "Expense - {$expense->payee}",
                            ],
                            [
                                'account_id' => $cashAccount->id,
                                'debit' => 0,
                                'credit' => $expense->total_amount,
                                'description' => "Cash/Bank Paid - {$expense->expense_number}",
                            ],
                        ],
                    ], $user);

                    $this->postTransaction->execute($txn, $user);
                } catch (Throwable $e) {
                    Log::warning("Auto ledger entry skipped for Expense {$expense->expense_number}: {$e->getMessage()}");
                }
            }

            return $expense->fresh(['category', 'taxRate']);
        });
    }
}
