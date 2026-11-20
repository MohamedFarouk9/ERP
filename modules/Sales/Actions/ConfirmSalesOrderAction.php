<?php

declare(strict_types=1);

namespace Modules\Sales\Actions;

use App\Models\User;
use Modules\Sales\Events\SalesOrderConfirmed;
use Modules\Sales\Models\SalesOrder;
use RuntimeException;

class ConfirmSalesOrderAction
{
    public function execute(SalesOrder $order, User $user): SalesOrder
    {
        if ($order->status !== SalesOrder::STATUS_DRAFT) {
            throw new RuntimeException("Only draft sales orders can be confirmed. Current status: {$order->status}.");
        }

        if ($order->items()->count() === 0) {
            throw new RuntimeException('Cannot confirm an empty sales order.');
        }

        $order->update([
            'status' => SalesOrder::STATUS_CONFIRMED,
            'confirmed_by' => $user->id,
            'confirmed_at' => now(),
        ]);

        event(new SalesOrderConfirmed($order));

        return $order->fresh(['customer', 'items', 'confirmer']);
    }
}
