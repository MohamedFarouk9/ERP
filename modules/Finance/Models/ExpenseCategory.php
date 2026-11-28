<?php

declare(strict_types=1);

namespace Modules\Finance\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Accounting\Models\Account;
use Modules\Core\Traits\BelongsToTenant;
use Modules\Users\Traits\Auditable;

class ExpenseCategory extends Model
{
    use Auditable, BelongsToTenant, HasUlids;

    protected $table = 'expense_categories';

    protected $fillable = [
        'tenant_id',
        'code',
        'name',
        'account_id',
        'description',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class, 'expense_category_id');
    }

    public function budgets(): HasMany
    {
        return $this->hasMany(Budget::class, 'expense_category_id');
    }
}
