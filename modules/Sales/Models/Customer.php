<?php

declare(strict_types=1);

namespace Modules\Sales\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Core\Traits\BelongsToTenant;
use Modules\Users\Traits\Auditable;

class Customer extends Model
{
    use Auditable;
    use BelongsToTenant;
    use HasUlids;
    use SoftDeletes;

    protected $table = 'customers';

    protected $fillable = [
        'tenant_id',
        'code',
        'name',
        'email',
        'phone',
        'tax_number',
        'billing_address',
        'shipping_address',
        'credit_limit',
        'is_active',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'credit_limit' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * @return HasMany<SalesOrder, $this>
     */
    public function salesOrders(): HasMany
    {
        return $this->hasMany(SalesOrder::class, 'customer_id');
    }

    /**
     * @return HasMany<Invoice, $this>
     */
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class, 'customer_id');
    }
}
