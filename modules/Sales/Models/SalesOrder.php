<?php

declare(strict_types=1);

namespace Modules\Sales\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Core\Traits\BelongsToTenant;
use Modules\Users\Traits\Auditable;

class SalesOrder extends Model
{
    use Auditable;
    use BelongsToTenant;
    use HasUlids;
    use SoftDeletes;

    public const STATUS_DRAFT = 'draft';

    public const STATUS_CONFIRMED = 'confirmed';

    public const STATUS_FULFILLED = 'fulfilled';

    public const STATUS_CANCELLED = 'cancelled';

    protected $table = 'sales_orders';

    protected $fillable = [
        'tenant_id',
        'customer_id',
        'warehouse_id',
        'order_number',
        'order_date',
        'status',
        'subtotal',
        'tax_amount',
        'discount_amount',
        'total_amount',
        'currency_code',
        'notes',
        'confirmed_by',
        'confirmed_at',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'order_date' => 'date',
        'subtotal' => 'integer',
        'tax_amount' => 'integer',
        'discount_amount' => 'integer',
        'total_amount' => 'integer',
        'confirmed_at' => 'datetime',
    ];

    /**
     * @return BelongsTo<Customer, $this>
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function confirmer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'confirmed_by');
    }

    /**
     * @return HasMany<SalesOrderItem, $this>
     */
    public function items(): HasMany
    {
        return $this->hasMany(SalesOrderItem::class, 'sales_order_id');
    }
}
