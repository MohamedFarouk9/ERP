<?php

declare(strict_types=1);

namespace Modules\Inventory\Listeners;

use Illuminate\Support\Facades\Log;
use Modules\Inventory\Actions\RecordStockMovementAction;
use Modules\Inventory\Models\Product;
use Modules\Inventory\Models\StockMovement;
use Modules\Procurement\Events\PurchaseOrderReceived;
use Throwable;

class AddStockOnPurchaseOrderReceived
{
    public function __construct(
        protected RecordStockMovementAction $recordStockMovement
    ) {}

    public function handle(PurchaseOrderReceived $event): void
    {
        $po = $event->purchaseOrder->loadMissing(['items', 'receiver']);

        if (empty($po->warehouse_id)) {
            return;
        }

        foreach ($po->items as $item) {
            $product = Product::where('id', $item->product_id)->first();
            if (! $product || ! $product->track_inventory) {
                continue;
            }

            try {
                $this->recordStockMovement->execute([
                    'warehouse_id' => $po->warehouse_id,
                    'product_id' => $product->id,
                    'type' => StockMovement::TYPE_IN,
                    'quantity' => $item->quantity,
                    'unit_cost' => $item->unit_cost,
                    'reference_type' => 'PurchaseOrder',
                    'reference_id' => $po->id,
                    'notes' => "Auto-received from PO {$po->po_number}",
                ], $po->receiver);
            } catch (Throwable $e) {
                Log::warning("Auto stock receipt skipped for PO {$po->po_number}, SKU {$product->sku}: {$e->getMessage()}");
            }
        }
    }
}
