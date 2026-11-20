<?php

declare(strict_types=1);

namespace Modules\Inventory\Actions;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use Modules\Core\Services\TenantContext;
use Modules\Inventory\Models\Product;
use Modules\Inventory\Models\StockLevel;
use Modules\Inventory\Models\StockMovement;
use Modules\Inventory\Models\Warehouse;
use RuntimeException;

class RecordStockMovementAction
{
    public function __construct(
        protected TenantContext $tenantContext
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data, ?User $user = null): StockMovement
    {
        $tenantId = $this->tenantContext->requireId();
        $warehouse = Warehouse::findOrFail($data['warehouse_id']);
        $product = Product::findOrFail($data['product_id']);
        $type = (string) $data['type'];
        $quantity = (int) $data['quantity'];

        if ($type !== StockMovement::TYPE_ADJUSTMENT && $quantity <= 0) {
            throw new InvalidArgumentException('Quantity must be greater than zero for in/out stock movements.');
        }

        if ($type === StockMovement::TYPE_ADJUSTMENT && $quantity === 0) {
            throw new InvalidArgumentException('Adjustment quantity cannot be zero.');
        }

        $delta = match ($type) {
            StockMovement::TYPE_IN => $quantity,
            StockMovement::TYPE_OUT => -$quantity,
            StockMovement::TYPE_ADJUSTMENT => $quantity,
            default => throw new InvalidArgumentException("Invalid movement type [{$type}]."),
        };

        return DB::transaction(function () use ($tenantId, $warehouse, $product, $type, $quantity, $delta, $data, $user) {
            $stockLevel = StockLevel::where('warehouse_id', $warehouse->id)
                ->where('product_id', $product->id)
                ->lockForUpdate()
                ->first();

            if (! $stockLevel) {
                $stockLevel = StockLevel::create([
                    'tenant_id' => $tenantId,
                    'warehouse_id' => $warehouse->id,
                    'product_id' => $product->id,
                    'quantity_on_hand' => 0,
                    'quantity_reserved' => 0,
                ]);
            }

            $newQuantity = $stockLevel->quantity_on_hand + $delta;

            if ($newQuantity < 0) {
                throw new RuntimeException("Insufficient stock for product [{$product->sku}] in warehouse [{$warehouse->code}].");
            }

            $stockLevel->update([
                'quantity_on_hand' => $newQuantity,
            ]);

            return StockMovement::create([
                'tenant_id' => $tenantId,
                'warehouse_id' => $warehouse->id,
                'product_id' => $product->id,
                'type' => $type,
                'quantity' => $quantity,
                'unit_cost' => (int) ($data['unit_cost'] ?? $product->cost_price),
                'reference_type' => $data['reference_type'] ?? null,
                'reference_id' => $data['reference_id'] ?? null,
                'notes' => $data['notes'] ?? null,
                'created_by' => $user?->id,
            ]);
        });
    }
}
