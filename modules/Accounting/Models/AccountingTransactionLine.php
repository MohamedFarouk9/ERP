<?php

declare(strict_types=1);

namespace Modules\Accounting\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccountingTransactionLine extends Model
{
    use HasUlids;

    protected $table = 'accounting_transaction_lines';

    protected $fillable = [
        'transaction_id',
        'account_id',
        'debit',
        'credit',
        'description',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'debit' => 'integer',
        'credit' => 'integer',
    ];

    /**
     * @return BelongsTo<AccountingTransaction, $this>
     */
    public function transaction(): BelongsTo
    {
        return $this->belongsTo(AccountingTransaction::class, 'transaction_id');
    }

    /**
     * @return BelongsTo<Account, $this>
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'account_id');
    }
}
