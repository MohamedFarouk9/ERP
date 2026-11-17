<?php

declare(strict_types=1);

namespace Modules\Core\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Modules\Core\Traits\BelongsToTenant;

class TenantFeature extends Model
{
    use BelongsToTenant;
    use HasUlids;

    protected $table = 'tenant_features';

    protected $fillable = [
        'tenant_id',
        'feature_slug',
        'is_enabled',
        'metadata',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'is_enabled' => 'boolean',
        'metadata' => 'array',
    ];
}
