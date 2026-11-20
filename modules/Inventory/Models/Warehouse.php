<?php

declare(strict_types=1);

namespace Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Core\Traits\BelongsToTenant;
use Modules\Users\Traits\Auditable;

class Warehouse extends Model
{
    use Auditable;
    use BelongsToTenant;
    use HasUlids;
    use SoftDeletes;

    protected $table = 'warehouses';

    protected $fillable = [
        'tenant_id',
        'code',
        'name',
        'address',
        'is_default',
        'is_active',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'is_default' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * @return HasMany<StockLevel, $this>
     */
    public function stockLevels(): HasMany
    {
        return $this->hasMany(StockLevel::class, 'warehouse_id');
    }
}
