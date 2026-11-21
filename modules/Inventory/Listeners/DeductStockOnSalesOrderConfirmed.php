<?php

declare(strict_types=1);

namespace Modules\Inventory\Listeners;

use Illuminate\Support\Facades\Log;
use Modules\Inventory\Actions\RecordStockMovementAction;
use Modules\Inventory\Models\Product;
use Modules\Inventory\Models\StockMovement;
use Modules\Sales\Events\SalesOrderConfirmed;
use Throwable;

class DeductStockOnSalesOrderConfirmed
{
    public function __construct(
        protected RecordStockMovementAction $recordStockMovement
    ) {}

    public function handle(SalesOrderConfirmed $event): void
    {
        $order = $event->salesOrder->loadMissing('items');

        if (empty($order->warehouse_id)) {
            return;
        }

        foreach ($order->items as $item) {
            $product = Product::where('id', $item->product_id)->first();
            if (! $product || ! $product->track_inventory) {
                continue;
            }

            try {
                $this->recordStockMovement->execute([
                    'warehouse_id' => $order->warehouse_id,
                    'product_id' => $product->id,
                    'type' => StockMovement::TYPE_OUT,
                    'quantity' => $item->quantity,
                    'unit_cost' => $product->cost_price,
                    'reference_type' => 'SalesOrder',
                    'reference_id' => $order->id,
                    'notes' => "Auto-deducted for Sales Order {$order->order_number}",
                ], $order->confirmer);
            } catch (Throwable $e) {
                Log::warning("Auto stock deduction skipped for Order {$order->order_number}, SKU {$product->sku}: {$e->getMessage()}");
            }
        }
    }
}
