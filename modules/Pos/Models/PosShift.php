<?php

declare(strict_types=1);

namespace Modules\Pos\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Core\Traits\BelongsToTenant;
use Modules\Users\Traits\Auditable;

class PosShift extends Model
{
    use Auditable;
    use BelongsToTenant;
    use HasUlids;

    public const STATUS_OPEN = 'open';

    public const STATUS_CLOSED = 'closed';

    protected $table = 'pos_shifts';

    protected $fillable = [
        'tenant_id',
        'user_id',
        'warehouse_id',
        'shift_number',
        'opened_at',
        'closed_at',
        'opening_cash',
        'expected_cash',
        'counted_cash',
        'status',
        'notes',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'opened_at' => 'datetime',
        'closed_at' => 'datetime',
        'opening_cash' => 'integer',
        'expected_cash' => 'integer',
        'counted_cash' => 'integer',
    ];

    /**
     * @return BelongsTo<User, $this>
     */
    public function cashier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @return HasMany<PosSale, $this>
     */
    public function sales(): HasMany
    {
        return $this->hasMany(PosSale::class, 'pos_shift_id');
    }
}
