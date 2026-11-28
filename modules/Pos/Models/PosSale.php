<?php

declare(strict_types=1);

namespace Modules\Pos\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Core\Traits\BelongsToTenant;
use Modules\Sales\Models\Customer;
use Modules\Users\Traits\Auditable;

class PosSale extends Model
{
    use Auditable;
    use BelongsToTenant;
    use HasUlids;

    public const STATUS_COMPLETED = 'completed';

    public const STATUS_VOIDED = 'voided';

    protected $table = 'pos_sales';

    protected $fillable = [
        'tenant_id',
        'pos_shift_id',
        'customer_id',
        'user_id',
        'receipt_number',
        'sale_date',
        'subtotal',
        'tax_amount',
        'discount_amount',
        'total_amount',
        'amount_tendered',
        'change_due',
        'payment_type',
        'status',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'sale_date' => 'date',
        'subtotal' => 'integer',
        'tax_amount' => 'integer',
        'discount_amount' => 'integer',
        'total_amount' => 'integer',
        'amount_tendered' => 'integer',
        'change_due' => 'integer',
    ];

    /**
     * @return BelongsTo<PosShift, $this>
     */
    public function shift(): BelongsTo
    {
        return $this->belongsTo(PosShift::class, 'pos_shift_id');
    }

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
    public function cashier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @return HasMany<PosSaleItem, $this>
     */
    public function items(): HasMany
    {
        return $this->hasMany(PosSaleItem::class, 'pos_sale_id');
    }
}
