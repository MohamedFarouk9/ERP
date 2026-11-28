<?php

declare(strict_types=1);

namespace Modules\Finance\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Core\Traits\BelongsToTenant;
use Modules\Users\Traits\Auditable;

class Budget extends Model
{
    use Auditable, BelongsToTenant, HasUlids;

    protected $table = 'budgets';

    protected $fillable = [
        'tenant_id',
        'name',
        'expense_category_id',
        'period_month',
        'budget_amount',
        'notes',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'budget_amount' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ExpenseCategory::class, 'expense_category_id');
    }
}
