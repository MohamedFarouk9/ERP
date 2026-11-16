<?php

declare(strict_types=1);

namespace Modules\Users\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Modules\Core\Traits\BelongsToTenant;

/**
 * @property string $id
 * @property string $tenant_id
 * @property string $name
 * @property string $code
 * @property string|null $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
final class Department extends Model
{
    use BelongsToTenant;
    use HasUlids;

    protected $table = 'departments';

    protected $fillable = [
        'tenant_id',
        'name',
        'code',
        'description',
    ];

    /**
     * @return HasMany<Team, $this>
     */
    public function teams(): HasMany
    {
        return $this->hasMany(Team::class);
    }
}
