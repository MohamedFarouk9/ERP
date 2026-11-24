<?php

declare(strict_types=1);

namespace Modules\Inventory\Listeners;

use Illuminate\Support\Facades\Log;
use Modules\Inventory\Actions\RecordStockMovementAction;
use Modules\Inventory\Models\Product;
use Modules\Inventory\Models\StockMovement;
use Modules\Pos\Events\PosSaleCompleted;
use Throwable;

class DeductStockOnPosSaleCompleted
{
    public function __construct(
        protected RecordStockMovementAction $recordStockMovement
    ) {}

    public function handle(PosSaleCompleted $event): void
    {
        $sale = $event->posSale->loadMissing(['shift', 'items', 'cashier']);
        $warehouseId = $sale->shift?->warehouse_id;

        if (empty($warehouseId)) {
            return;
        }

        foreach ($sale->items as $item) {
            $product = Product::where('id', $item->product_id)->first();
            if (! $product || ! $product->track_inventory) {
                continue;
            }

            try {
                $this->recordStockMovement->execute([
                    'warehouse_id' => $warehouseId,
                    'product_id' => $product->id,
                    'type' => StockMovement::TYPE_OUT,
                    'quantity' => $item->quantity,
                    'unit_cost' => $product->cost_price,
                    'reference_type' => 'PosSale',
                    'reference_id' => $sale->id,
                    'notes' => "POS Receipt {$sale->receipt_number}",
                ], $sale->cashier);
            } catch (Throwable $e) {
                Log::warning("POS stock deduction skipped for {$sale->receipt_number}, SKU {$product->sku}: {$e->getMessage()}");
            }
        }
    }
}
