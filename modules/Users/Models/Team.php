<?php

declare(strict_types=1);

namespace Modules\Users\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Modules\Core\Traits\BelongsToTenant;

/**
 * @property string $id
 * @property string $tenant_id
 * @property string|null $department_id
 * @property string|null $parent_id
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
final class Team extends Model
{
    use BelongsToTenant;
    use HasUlids;

    protected $table = 'teams';

    protected $fillable = [
        'tenant_id',
        'department_id',
        'parent_id',
        'name',
        'slug',
        'description',
    ];

    /**
     * @return BelongsTo<Department, $this>
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * @return BelongsTo<Team, $this>
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'parent_id');
    }

    /**
     * @return HasMany<Team, $this>
     */
    public function children(): HasMany
    {
        return $this->hasMany(Team::class, 'parent_id');
    }

    /**
     * @return BelongsToMany<User, $this>
     */
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'team_members')
            ->withPivot('is_leader')
            ->withTimestamps();
    }
}
