<?php

declare(strict_types=1);

namespace Modules\Core\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * The anchor model for the entire multi-tenant system.
 *
 * Every tenant-scoped resource ultimately belongs to a Tenant.
 *
 * @property string $id
 * @property string $name
 * @property string $slug
 * @property string|null $domain
 * @property string $status
 * @property array|null $settings
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 */
final class Tenant extends Model
{
    use HasFactory;
    use HasUlids;
    use SoftDeletes;

    public const ACTIVE = 'active';

    public const SUSPENDED = 'suspended';

    public const DEACTIVATED = 'deactivated';

    protected $table = 'tenants';

    protected $attributes = [
        'status' => self::ACTIVE,
    ];

    protected $fillable = [
        'name',
        'slug',
        'domain',
        'status',
        'settings',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'settings' => 'array',
            'status' => 'string',
        ];
    }

    /**
     * Determine whether this tenant is currently active.
     */
    public function isActive(): bool
    {
        return $this->status === self::ACTIVE;
    }

    /**
     * Determine whether this tenant is suspended.
     */
    public function isSuspended(): bool
    {
        return $this->status === self::SUSPENDED;
    }

    /**
     * Determine whether this tenant is deactivated.
     */
    public function isDeactivated(): bool
    {
        return $this->status === self::DEACTIVATED;
    }
}
