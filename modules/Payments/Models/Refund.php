<?php

declare(strict_types=1);

namespace Modules\Payments\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Core\Traits\BelongsToTenant;
use Modules\Users\Traits\Auditable;

class Refund extends Model
{
    use Auditable;
    use BelongsToTenant;
    use HasUlids;

    public const STATUS_PENDING = 'pending';

    public const STATUS_COMPLETED = 'completed';

    public const STATUS_REJECTED = 'rejected';

    protected $table = 'refunds';

    protected $fillable = [
        'tenant_id',
        'payment_id',
        'refund_number',
        'refund_date',
        'amount',
        'reason',
        'status',
        'processed_by',
        'processed_at',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'refund_date' => 'date',
        'amount' => 'integer',
        'processed_at' => 'datetime',
    ];

    /**
     * @return BelongsTo<Payment, $this>
     */
    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class, 'payment_id');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function processor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }
}
