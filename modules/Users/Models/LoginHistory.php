<?php

declare(strict_types=1);

namespace Modules\Users\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property string $id
 * @property string $user_id
 * @property string $ip_address
 * @property string|null $user_agent
 * @property string $status
 * @property string|null $failure_reason
 * @property string|null $tenant_id
 * @property Carbon|null $created_at
 * @property-read User $user
 */
final class LoginHistory extends Model
{
    use HasUlids;

    public const STATUS_SUCCESS = 'success';

    public const STATUS_FAILED = 'failed';

    public const STATUS_BLOCKED = 'blocked';

    public $timestamps = false;

    protected $table = 'login_histories';

    protected $fillable = [
        'user_id',
        'ip_address',
        'user_agent',
        'status',
        'failure_reason',
        'tenant_id',
        'created_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
