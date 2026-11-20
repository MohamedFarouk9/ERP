<?php

declare(strict_types=1);

namespace Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Core\Traits\BelongsToTenant;

class StockLevel extends Model
{
    use BelongsToTenant;
    use HasUlids;

    protected $table = 'stock_levels';

    protected $fillable = [
        'tenant_id',
        'warehouse_id',
        'product_id',
        'quantity_on_hand',
        'quantity_reserved',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'quantity_on_hand' => 'integer',
        'quantity_reserved' => 'integer',
    ];

    /**
     * @return BelongsTo<Warehouse, $this>
     */
    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    /**
     * @return BelongsTo<Product, $this>
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function availableQuantity(): int
    {
        return $this->quantity_on_hand - $this->quantity_reserved;
    }
}
