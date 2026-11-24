<?php

declare(strict_types=1);

namespace Modules\Procurement\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Core\Traits\BelongsToTenant;
use Modules\Users\Traits\Auditable;

class VendorBill extends Model
{
    use Auditable;
    use BelongsToTenant;
    use HasUlids;
    use SoftDeletes;

    public const STATUS_DRAFT = 'draft';

    public const STATUS_APPROVED = 'approved';

    public const STATUS_PAID = 'paid';

    public const STATUS_CANCELLED = 'cancelled';

    protected $table = 'vendor_bills';

    protected $fillable = [
        'tenant_id',
        'supplier_id',
        'purchase_order_id',
        'bill_number',
        'bill_date',
        'due_date',
        'status',
        'subtotal',
        'tax_amount',
        'total_amount',
        'amount_paid',
        'currency_code',
        'notes',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'bill_date' => 'date',
        'due_date' => 'date',
        'subtotal' => 'integer',
        'tax_amount' => 'integer',
        'total_amount' => 'integer',
        'amount_paid' => 'integer',
    ];

    /**
     * @return BelongsTo<Supplier, $this>
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    /**
     * @return BelongsTo<PurchaseOrder, $this>
     */
    public function purchaseOrder(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class, 'purchase_order_id');
    }
}
