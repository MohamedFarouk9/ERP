<?php

declare(strict_types=1);

namespace Modules\Accounting\Listeners;

use Illuminate\Support\Facades\Log;
use Modules\Accounting\Actions\CreateAccountingTransactionAction;
use Modules\Accounting\Actions\PostAccountingTransactionAction;
use Modules\Accounting\Models\Account;
use Modules\Payments\Events\PaymentCompleted;
use Throwable;

class RecordPaymentLedgerEntry
{
    public function __construct(
        protected CreateAccountingTransactionAction $createTransaction,
        protected PostAccountingTransactionAction $postTransaction
    ) {}

    public function handle(PaymentCompleted $event): void
    {
        $payment = $event->payment->loadMissing(['paymentMethod', 'processor']);

        if ($payment->amount <= 0) {
            return;
        }

        $cashAccount = $payment->paymentMethod?->account_id
            ? Account::where('id', $payment->paymentMethod->account_id)->first()
            : Account::where('code', '1010')->first();

        $arAccount = Account::where('code', '1200')->first();

        if (! $cashAccount || ! $arAccount) {
            return;
        }

        try {
            $txn = $this->createTransaction->execute([
                'transaction_number' => 'AUTO-PAY-'.$payment->payment_number,
                'transaction_date' => $payment->payment_date?->toDateString() ?? now()->toDateString(),
                'reference_type' => 'Payment',
                'reference_id' => $payment->id,
                'notes' => "Auto-posted for Payment {$payment->payment_number}",
                'lines' => [
                    [
                        'account_id' => $cashAccount->id,
                        'debit' => $payment->amount,
                        'credit' => 0,
                        'description' => "Cash/Bank received - {$payment->payment_number}",
                    ],
                    [
                        'account_id' => $arAccount->id,
                        'debit' => 0,
                        'credit' => $payment->amount,
                        'description' => "Accounts Receivable settled - {$payment->payment_number}",
                    ],
                ],
            ], $payment->processor);

            if ($payment->processor) {
                $this->postTransaction->execute($txn, $payment->processor);
            }
        } catch (Throwable $e) {
            Log::warning("Auto ledger entry skipped for Payment {$payment->payment_number}: {$e->getMessage()}");
        }
    }
}
