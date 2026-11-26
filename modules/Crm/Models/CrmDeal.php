<?php

declare(strict_types=1);

namespace Modules\Crm\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Core\Traits\BelongsToTenant;
use Modules\Sales\Models\Customer;
use Modules\Users\Traits\Auditable;

class CrmDeal extends Model
{
    use Auditable, BelongsToTenant, HasUlids;

    public const STAGE_QUALIFIED = 'qualified';

    public const STAGE_PROPOSAL = 'proposal';

    public const STAGE_NEGOTIATION = 'negotiation';

    public const STAGE_WON = 'won';

    public const STAGE_LOST = 'lost';

    protected $table = 'crm_deals';

    protected $fillable = [
        'tenant_id',
        'deal_code',
        'title',
        'customer_id',
        'lead_id',
        'owner_id',
        'stage',
        'value_amount',
        'probability',
        'expected_close_date',
        'closed_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'value_amount' => 'integer',
            'probability' => 'integer',
            'expected_close_date' => 'date',
            'closed_at' => 'datetime',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function lead(): BelongsTo
    {
        return $this->belongsTo(CrmLead::class, 'lead_id');
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function activities(): HasMany
    {
        return $this->hasMany(CrmActivity::class, 'deal_id');
    }
}
