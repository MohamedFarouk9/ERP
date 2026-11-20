<?php

declare(strict_types=1);

namespace Modules\Sales\Actions;

use App\Models\User;
use Modules\Sales\Events\InvoiceIssued;
use Modules\Sales\Models\Invoice;
use RuntimeException;

class IssueInvoiceAction
{
    public function execute(Invoice $invoice, User $user): Invoice
    {
        if ($invoice->status !== Invoice::STATUS_DRAFT) {
            throw new RuntimeException("Only draft invoices can be issued. Current status: {$invoice->status}.");
        }

        if ($invoice->items()->count() === 0) {
            throw new RuntimeException('Cannot issue an empty invoice.');
        }

        $invoice->update([
            'status' => Invoice::STATUS_ISSUED,
            'issued_by' => $user->id,
            'issued_at' => now(),
        ]);

        event(new InvoiceIssued($invoice));

        return $invoice->fresh(['customer', 'items', 'issuer']);
    }
}
