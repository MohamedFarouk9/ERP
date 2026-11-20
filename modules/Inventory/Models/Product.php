<?php

declare(strict_types=1);

namespace Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Core\Traits\BelongsToTenant;
use Modules\Users\Traits\Auditable;

class Product extends Model
{
    use Auditable;
    use BelongsToTenant;
    use HasUlids;
    use SoftDeletes;

    protected $table = 'products';

    protected $fillable = [
        'tenant_id',
        'category_id',
        'sku',
        'name',
        'description',
        'unit',
        'cost_price',
        'selling_price',
        'reorder_level',
        'track_inventory',
        'is_active',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'cost_price' => 'integer',
        'selling_price' => 'integer',
        'reorder_level' => 'integer',
        'track_inventory' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * @return BelongsTo<Category, $this>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * @return HasMany<StockLevel, $this>
     */
    public function stockLevels(): HasMany
    {
        return $this->hasMany(StockLevel::class, 'product_id');
    }

    /**
     * @return HasMany<StockMovement, $this>
     */
    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class, 'product_id');
    }
}
