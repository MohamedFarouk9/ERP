<?php

declare(strict_types=1);

namespace Modules\Users\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;

/**
 * @property string $id
 * @property string $module
 * @property string $resource
 * @property string $action
 * @property string $slug
 * @property string $name
 * @property string|null $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
final class Permission extends Model
{
    use HasUlids;

    protected $table = 'permissions';

    protected $fillable = [
        'module',
        'resource',
        'action',
        'slug',
        'name',
        'description',
    ];

    /**
     * @return BelongsToMany<Role, $this>
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_permissions');
    }

    /**
     * @return BelongsToMany<User, $this>
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_permissions')
            ->withPivot('is_granted');
    }
}
