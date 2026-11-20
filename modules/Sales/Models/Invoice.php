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

class Invoice extends Model
{
    use Auditable;
    use BelongsToTenant;
    use HasUlids;
    use SoftDeletes;

    public const STATUS_DRAFT = 'draft';

    public const STATUS_ISSUED = 'issued';

    public const STATUS_PAID = 'paid';

    public const STATUS_OVERDUE = 'overdue';

    public const STATUS_CANCELLED = 'cancelled';

    protected $table = 'invoices';

    protected $fillable = [
        'tenant_id',
        'customer_id',
        'sales_order_id',
        'invoice_number',
        'invoice_date',
        'due_date',
        'status',
        'subtotal',
        'tax_amount',
        'total_amount',
        'amount_paid',
        'currency_code',
        'notes',
        'issued_by',
        'issued_at',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'invoice_date' => 'date',
        'due_date' => 'date',
        'subtotal' => 'integer',
        'tax_amount' => 'integer',
        'total_amount' => 'integer',
        'amount_paid' => 'integer',
        'issued_at' => 'datetime',
    ];

    /**
     * @return BelongsTo<Customer, $this>
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    /**
     * @return BelongsTo<SalesOrder, $this>
     */
    public function salesOrder(): BelongsTo
    {
        return $this->belongsTo(SalesOrder::class, 'sales_order_id');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function issuer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    /**
     * @return HasMany<InvoiceItem, $this>
     */
    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class, 'invoice_id');
    }
}
