<?php

declare(strict_types=1);

namespace Modules\Accounting\Actions;

use App\Models\User;
use InvalidArgumentException;
use Modules\Accounting\Models\AccountingTransaction;
use RuntimeException;

class PostAccountingTransactionAction
{
    public function execute(AccountingTransaction $transaction, User $user): AccountingTransaction
    {
        if ($transaction->status !== AccountingTransaction::STATUS_DRAFT) {
            throw new RuntimeException("Only draft transactions can be posted. Current status: {$transaction->status}.");
        }

        if (! $transaction->isBalanced()) {
            throw new InvalidArgumentException('Cannot post an unbalanced accounting transaction.');
        }

        if ($transaction->financialPeriod && $transaction->financialPeriod->is_closed) {
            throw new RuntimeException('Cannot post a transaction to a closed financial period.');
        }

        $transaction->update([
            'status' => AccountingTransaction::STATUS_POSTED,
            'posted_by' => $user->id,
            'posted_at' => now(),
        ]);

        return $transaction;
    }
}
