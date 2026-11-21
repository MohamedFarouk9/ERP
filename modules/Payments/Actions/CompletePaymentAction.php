<?php

declare(strict_types=1);

namespace Modules\Payments\Actions;

use App\Models\User;
use Modules\Payments\Events\PaymentCompleted;
use Modules\Payments\Models\Payment;
use RuntimeException;

class CompletePaymentAction
{
    public function execute(Payment $payment, User $user): Payment
    {
        if ($payment->status !== Payment::STATUS_PENDING) {
            throw new RuntimeException("Only pending payments can be completed. Current status: {$payment->status}.");
        }

        $payment->update([
            'status' => Payment::STATUS_COMPLETED,
            'processed_by' => $user->id,
            'processed_at' => now(),
        ]);

        event(new PaymentCompleted($payment));

        return $payment->fresh(['paymentMethod', 'processor']);
    }
}
