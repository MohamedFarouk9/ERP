<?php

declare(strict_types=1);

namespace Modules\Finance\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Core\Traits\BelongsToTenant;
use Modules\Users\Traits\Auditable;

class Expense extends Model
{
    use Auditable, BelongsToTenant, HasUlids;

    public const STATUS_DRAFT = 'draft';

    public const STATUS_APPROVED = 'approved';

    public const STATUS_PAID = 'paid';

    protected $table = 'expenses';

    protected $fillable = [
        'tenant_id',
        'expense_number',
        'expense_category_id',
        'tax_rate_id',
        'payee',
        'expense_date',
        'amount',
        'tax_amount',
        'total_amount',
        'payment_method',
        'status',
        'reference',
        'notes',
        'created_by',
        'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'expense_date' => 'date',
            'amount' => 'integer',
            'tax_amount' => 'integer',
            'total_amount' => 'integer',
            'paid_at' => 'datetime',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ExpenseCategory::class, 'expense_category_id');
    }

    public function taxRate(): BelongsTo
    {
        return $this->belongsTo(TaxRate::class, 'tax_rate_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
