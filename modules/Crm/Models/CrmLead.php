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

class CrmLead extends Model
{
    use Auditable, BelongsToTenant, HasUlids;

    public const STATUS_NEW = 'new';

    public const STATUS_CONTACTED = 'contacted';

    public const STATUS_QUALIFIED = 'qualified';

    public const STATUS_CONVERTED = 'converted';

    public const STATUS_LOST = 'lost';

    protected $table = 'crm_leads';

    protected $fillable = [
        'tenant_id',
        'lead_code',
        'name',
        'company',
        'email',
        'phone',
        'source',
        'status',
        'estimated_value',
        'owner_id',
        'converted_customer_id',
        'converted_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'estimated_value' => 'integer',
            'converted_at' => 'datetime',
        ];
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function convertedCustomer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'converted_customer_id');
    }

    public function deals(): HasMany
    {
        return $this->hasMany(CrmDeal::class, 'lead_id');
    }

    public function activities(): HasMany
    {
        return $this->hasMany(CrmActivity::class, 'lead_id');
    }
}
