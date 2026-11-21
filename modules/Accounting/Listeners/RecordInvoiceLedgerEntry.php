<?php

declare(strict_types=1);

namespace Modules\Accounting\Listeners;

use Illuminate\Support\Facades\Log;
use Modules\Accounting\Actions\CreateAccountingTransactionAction;
use Modules\Accounting\Actions\PostAccountingTransactionAction;
use Modules\Accounting\Models\Account;
use Modules\Sales\Events\InvoiceIssued;
use Throwable;

class RecordInvoiceLedgerEntry
{
    public function __construct(
        protected CreateAccountingTransactionAction $createTransaction,
        protected PostAccountingTransactionAction $postTransaction
    ) {}

    public function handle(InvoiceIssued $event): void
    {
        $invoice = $event->invoice;

        if ($invoice->total_amount <= 0) {
            return;
        }

        $arAccount = Account::where('code', '1200')->first();
        $revenueAccount = Account::where('code', '4000')->first();

        if (! $arAccount || ! $revenueAccount) {
            return;
        }

        try {
            $txn = $this->createTransaction->execute([
                'transaction_number' => 'AUTO-INV-'.$invoice->invoice_number,
                'transaction_date' => $invoice->invoice_date?->toDateString() ?? now()->toDateString(),
                'reference_type' => 'Invoice',
                'reference_id' => $invoice->id,
                'notes' => "Auto-posted for Invoice {$invoice->invoice_number}",
                'lines' => [
                    [
                        'account_id' => $arAccount->id,
                        'debit' => $invoice->total_amount,
                        'credit' => 0,
                        'description' => "Accounts Receivable - {$invoice->invoice_number}",
                    ],
                    [
                        'account_id' => $revenueAccount->id,
                        'debit' => 0,
                        'credit' => $invoice->total_amount,
                        'description' => "Sales Revenue - {$invoice->invoice_number}",
                    ],
                ],
            ], $invoice->issuer);

            if ($invoice->issuer) {
                $this->postTransaction->execute($txn, $invoice->issuer);
            }
        } catch (Throwable $e) {
            Log::warning("Auto ledger entry skipped for Invoice {$invoice->invoice_number}: {$e->getMessage()}");
        }
    }
}
