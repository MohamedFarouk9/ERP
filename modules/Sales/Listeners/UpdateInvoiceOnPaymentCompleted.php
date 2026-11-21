<?php

declare(strict_types=1);

namespace Modules\Sales\Listeners;

use Illuminate\Support\Facades\DB;
use Modules\Payments\Events\PaymentCompleted;
use Modules\Sales\Models\Invoice;

class UpdateInvoiceOnPaymentCompleted
{
    public function handle(PaymentCompleted $event): void
    {
        $payment = $event->payment;

        if (empty($payment->invoice_id)) {
            return;
        }

        DB::transaction(function () use ($payment) {
            $invoice = Invoice::where('id', $payment->invoice_id)->lockForUpdate()->first();
            if (! $invoice) {
                return;
            }

            $newAmountPaid = $invoice->amount_paid + $payment->amount;
            $newStatus = $newAmountPaid >= $invoice->total_amount
                ? Invoice::STATUS_PAID
                : $invoice->status;

            $invoice->update([
                'amount_paid' => $newAmountPaid,
                'status' => $newStatus,
            ]);
        });
    }
}
