<?php

declare(strict_types=1);

namespace Modules\Payments\Actions;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use Modules\Core\Services\TenantContext;
use Modules\Payments\Events\RefundCompleted;
use Modules\Payments\Models\Payment;
use Modules\Payments\Models\Refund;
use RuntimeException;

class CreateRefundAction
{
    public function __construct(
        protected TenantContext $tenantContext
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data, User $user): Refund
    {
        $tenantId = $this->tenantContext->requireId();

        $existing = Refund::withoutGlobalScopes()
            ->where('tenant_id', $tenantId)
            ->where('refund_number', $data['refund_number'])
            ->first();

        if ($existing) {
            throw new InvalidArgumentException("Refund [{$data['refund_number']}] already exists for this tenant.");
        }

        return DB::transaction(function () use ($tenantId, $data, $user) {
            $payment = Payment::where('id', $data['payment_id'])->lockForUpdate()->firstOrFail();

            if (! in_array($payment->status, [Payment::STATUS_COMPLETED, Payment::STATUS_REFUNDED], true)) {
                throw new RuntimeException("Only completed payments can be refunded. Current status: {$payment->status}.");
            }

            $refundAmount = (int) $data['amount'];
            $alreadyRefunded = (int) $payment->refunds()
                ->where('status', Refund::STATUS_COMPLETED)
                ->sum('amount');

            if ($alreadyRefunded + $refundAmount > $payment->amount) {
                throw new InvalidArgumentException('Refund amount exceeds remaining refundable payment balance.');
            }

            $refund = Refund::create([
                'tenant_id' => $tenantId,
                'payment_id' => $payment->id,
                'refund_number' => $data['refund_number'],
                'refund_date' => $data['refund_date'],
                'amount' => $refundAmount,
                'reason' => $data['reason'] ?? null,
                'status' => Refund::STATUS_COMPLETED,
                'processed_by' => $user->id,
                'processed_at' => now(),
            ]);

            if ($alreadyRefunded + $refundAmount === $payment->amount) {
                $payment->update(['status' => Payment::STATUS_REFUNDED]);
            }

            event(new RefundCompleted($refund));

            return $refund->load(['payment', 'processor']);
        });
    }
}
