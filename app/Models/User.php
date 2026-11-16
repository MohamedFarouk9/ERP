<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Modules\Core\Traits\BelongsToTenant;
use Modules\Users\Models\LoginHistory;
use Modules\Users\Models\PasswordHistory;
use Modules\Users\Models\UserActiveSession;
use Modules\Users\Models\UserMfaSecret;
use Modules\Users\Traits\Auditable;
use Modules\Users\Traits\HasPermissions;

/**
 * @property string $id
 * @property string|null $tenant_id
 * @property string $name
 * @property string $email
 * @property string|null $phone
 * @property string $timezone
 * @property string $locale
 * @property string|null $avatar_path
 * @property bool $is_active
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read UserMfaSecret|null $mfaSecret
 */
class User extends Authenticatable
{
    use Auditable;
    use BelongsToTenant;
    use HasFactory;
    use HasPermissions;
    use HasUlids;
    use Notifiable;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'tenant_id',
        'name',
        'email',
        'password',
        'phone',
        'timezone',
        'locale',
        'avatar_path',
        'is_active',
    ];

    /**
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    /**
     * @return HasOne<UserMfaSecret, $this>
     */
    public function mfaSecret(): HasOne
    {
        return $this->hasOne(UserMfaSecret::class);
    }

    /**
     * @return HasMany<LoginHistory, $this>
     */
    public function loginHistories(): HasMany
    {
        return $this->hasMany(LoginHistory::class);
    }

    /**
     * @return HasMany<PasswordHistory, $this>
     */
    public function passwordHistories(): HasMany
    {
        return $this->hasMany(PasswordHistory::class);
    }

    /**
     * @return HasMany<UserActiveSession, $this>
     */
    public function activeSessions(): HasMany
    {
        return $this->hasMany(UserActiveSession::class);
    }

    public function hasMfaEnabled(): bool
    {
        return $this->mfaSecret !== null && $this->mfaSecret->isEnabled();
    }

    public function isActive(): bool
    {
        return $this->is_active;
    }
}
