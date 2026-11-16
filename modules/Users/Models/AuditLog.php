<?php

declare(strict_types=1);

namespace Modules\Users\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Modules\Core\Models\Tenant;
use RuntimeException;

/**
 * Immutable audit log record for regulatory compliance and activity tracking.
 *
 * @property string $id
 * @property string|null $tenant_id
 * @property string|null $user_id
 * @property string $event
 * @property string $auditable_type
 * @property string $auditable_id
 * @property array<string, mixed>|null $old_values
 * @property array<string, mixed>|null $new_values
 * @property string|null $ip_address
 * @property string|null $user_agent
 * @property Carbon|null $created_at
 */
final class AuditLog extends Model
{
    use HasUlids;

    public $timestamps = false;

    protected $table = 'audit_logs';

    protected $fillable = [
        'tenant_id',
        'user_id',
        'event',
        'auditable_type',
        'auditable_id',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
        'created_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'old_values' => 'array',
            'new_values' => 'array',
            'created_at' => 'datetime',
        ];
    }

    /**
     * Prevent updates to ensure immutability.
     */
    public static function boot(): void
    {
        parent::boot();

        self::updating(function (): void {
            throw new RuntimeException('Audit logs are strictly immutable and cannot be updated.');
        });

        self::deleting(function (): void {
            throw new RuntimeException('Audit logs are strictly immutable and cannot be deleted.');
        });
    }

    /**
     * @return BelongsTo<Tenant, $this>
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
