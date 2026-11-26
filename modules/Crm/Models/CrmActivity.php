<?php

declare(strict_types=1);

namespace Modules\Crm\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Core\Traits\BelongsToTenant;
use Modules\Users\Traits\Auditable;

class CrmActivity extends Model
{
    use Auditable, BelongsToTenant, HasUlids;

    public const TYPE_CALL = 'call';

    public const TYPE_EMAIL = 'email';

    public const TYPE_MEETING = 'meeting';

    public const TYPE_TASK = 'task';

    public const STATUS_OPEN = 'open';

    public const STATUS_COMPLETED = 'completed';

    protected $table = 'crm_activities';

    protected $fillable = [
        'tenant_id',
        'type',
        'subject',
        'lead_id',
        'deal_id',
        'assigned_to',
        'due_at',
        'status',
        'completed_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'due_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    public function lead(): BelongsTo
    {
        return $this->belongsTo(CrmLead::class, 'lead_id');
    }

    public function deal(): BelongsTo
    {
        return $this->belongsTo(CrmDeal::class, 'deal_id');
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
