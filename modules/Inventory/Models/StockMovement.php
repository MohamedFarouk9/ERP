<?php

declare(strict_types=1);

namespace Modules\Inventory\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Core\Traits\BelongsToTenant;
use RuntimeException;

class StockMovement extends Model
{
    use BelongsToTenant;
    use HasUlids;

    public const TYPE_IN = 'in';

    public const TYPE_OUT = 'out';

    public const TYPE_ADJUSTMENT = 'adjustment';

    protected $table = 'stock_movements';

    protected $fillable = [
        'tenant_id',
        'warehouse_id',
        'product_id',
        'type',
        'quantity',
        'unit_cost',
        'reference_type',
        'reference_id',
        'notes',
        'created_by',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'quantity' => 'integer',
        'unit_cost' => 'integer',
    ];

    protected static function booted(): void
    {
        static::updating(function (): void {
            throw new RuntimeException('Stock movements are immutable and cannot be modified.');
        });

        static::deleting(function (): void {
            throw new RuntimeException('Stock movements are immutable and cannot be deleted.');
        });
    }

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

    /**
     * @return BelongsTo<User, $this>
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
