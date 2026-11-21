<?php

declare(strict_types=1);

namespace Modules\Payments\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Core\Traits\BelongsToTenant;
use Modules\Users\Traits\Auditable;

class PaymentMethod extends Model
{
    use Auditable;
    use BelongsToTenant;
    use HasUlids;
    use SoftDeletes;

    public const TYPE_CASH = 'cash';

    public const TYPE_BANK_TRANSFER = 'bank_transfer';

    public const TYPE_CARD = 'card';

    public const TYPE_CHEQUE = 'cheque';

    public const TYPE_WALLET = 'wallet';

    protected $table = 'payment_methods';

    protected $fillable = [
        'tenant_id',
        'code',
        'name',
        'type',
        'account_id',
        'is_active',
        'settings',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'settings' => 'array',
    ];

    /**
     * @return HasMany<Payment, $this>
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'payment_method_id');
    }
}
