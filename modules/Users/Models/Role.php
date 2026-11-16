<?php

declare(strict_types=1);

namespace Modules\Users\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;
use Modules\Core\Traits\BelongsToTenant;

/**
 * @property string $id
 * @property string $tenant_id
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property bool $is_system
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
final class Role extends Model
{
    use BelongsToTenant;
    use HasUlids;

    protected $table = 'roles';

    protected $fillable = [
        'tenant_id',
        'name',
        'slug',
        'description',
        'is_system',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_system' => 'boolean',
        ];
    }

    /**
     * @return BelongsToMany<Permission, $this>
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'role_permissions');
    }

    /**
     * @return BelongsToMany<User, $this>
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_roles');
    }

    public function givePermissionTo(Permission|string $permission): void
    {
        $perm = is_string($permission)
            ? Permission::where('slug', $permission)->firstOrFail()
            : $permission;

        $this->permissions()->syncWithoutDetaching([$perm->id]);
    }

    public function revokePermissionTo(Permission|string $permission): void
    {
        $perm = is_string($permission)
            ? Permission::where('slug', $permission)->firstOrFail()
            : $permission;

        $this->permissions()->detach($perm->id);
    }

    public function hasPermissionTo(string $permissionSlug): bool
    {
        return $this->permissions->contains('slug', $permissionSlug);
    }
}
