<?php

declare(strict_types=1);

namespace Modules\Procurement\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Core\Traits\BelongsToTenant;
use Modules\Users\Traits\Auditable;

class Supplier extends Model
{
    use Auditable;
    use BelongsToTenant;
    use HasUlids;
    use SoftDeletes;

    protected $table = 'suppliers';

    protected $fillable = [
        'tenant_id',
        'code',
        'name',
        'email',
        'phone',
        'tax_number',
        'address',
        'payment_terms_days',
        'is_active',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'payment_terms_days' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * @return HasMany<PurchaseOrder, $this>
     */
    public function purchaseOrders(): HasMany
    {
        return $this->hasMany(PurchaseOrder::class, 'supplier_id');
    }

    /**
     * @return HasMany<VendorBill, $this>
     */
    public function vendorBills(): HasMany
    {
        return $this->hasMany(VendorBill::class, 'supplier_id');
    }
}
