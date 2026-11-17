<?php

declare(strict_types=1);

namespace Modules\Accounting\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Core\Traits\BelongsToTenant;
use Modules\Users\Traits\Auditable;

class FinancialYear extends Model
{
    use Auditable;
    use BelongsToTenant;
    use HasUlids;

    protected $table = 'financial_years';

    protected $fillable = [
        'tenant_id',
        'name',
        'start_date',
        'end_date',
        'is_closed',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_closed' => 'boolean',
    ];

    /**
     * @return HasMany<FinancialPeriod, $this>
     */
    public function periods(): HasMany
    {
        return $this->hasMany(FinancialPeriod::class, 'financial_year_id');
    }
}
