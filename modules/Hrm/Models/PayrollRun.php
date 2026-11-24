<?php

declare(strict_types=1);

namespace Modules\Hrm\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Core\Traits\BelongsToTenant;
use Modules\Users\Traits\Auditable;

class PayrollRun extends Model
{
    use Auditable;
    use BelongsToTenant;
    use HasUlids;

    public const STATUS_DRAFT = 'draft';

    public const STATUS_APPROVED = 'approved';

    public const STATUS_PAID = 'paid';

    protected $table = 'payroll_runs';

    protected $fillable = [
        'tenant_id',
        'payroll_number',
        'period_month',
        'pay_date',
        'total_gross',
        'total_deductions',
        'total_net',
        'status',
        'processed_by',
        'paid_at',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'pay_date' => 'date',
        'total_gross' => 'integer',
        'total_deductions' => 'integer',
        'total_net' => 'integer',
        'paid_at' => 'datetime',
    ];

    /**
     * @return BelongsTo<User, $this>
     */
    public function processor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    /**
     * @return HasMany<PayrollItem, $this>
     */
    public function items(): HasMany
    {
        return $this->hasMany(PayrollItem::class, 'payroll_run_id');
    }
}
