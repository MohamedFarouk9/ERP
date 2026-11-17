<?php

declare(strict_types=1);

namespace Modules\Accounting\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Core\Traits\BelongsToTenant;
use Modules\Users\Traits\Auditable;

class AccountingTransaction extends Model
{
    use Auditable;
    use BelongsToTenant;
    use HasUlids;

    public const STATUS_DRAFT = 'draft';
    public const STATUS_POSTED = 'posted';
    public const STATUS_CANCELLED = 'cancelled';

    protected $table = 'accounting_transactions';

    protected $fillable = [
        'tenant_id',
        'financial_period_id',
        'transaction_number',
        'transaction_date',
        'status',
        'reference_type',
        'reference_id',
        'notes',
        'posted_by',
        'posted_at',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'transaction_date' => 'date',
        'posted_at' => 'datetime',
    ];

    /**
     * @return BelongsTo<FinancialPeriod, $this>
     */
    public function financialPeriod(): BelongsTo
    {
        return $this->belongsTo(FinancialPeriod::class, 'financial_period_id');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function poster(): BelongsTo
    {
        return $this->belongsTo(User::class, 'posted_by');
    }

    /**
     * @return HasMany<AccountingTransactionLine, $this>
     */
    public function lines(): HasMany
    {
        return $this->hasMany(AccountingTransactionLine::class, 'transaction_id');
    }

    public function totalDebit(): int
    {
        return (int) $this->lines()->sum('debit');
    }

    public function totalCredit(): int
    {
        return (int) $this->lines()->sum('credit');
    }

    public function isBalanced(): bool
    {
        return $this->totalDebit() === $this->totalCredit() && $this->totalDebit() > 0;
    }
}
