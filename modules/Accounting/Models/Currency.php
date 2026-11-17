<?php

declare(strict_types=1);

namespace Modules\Accounting\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasUlids;

    protected $table = 'currencies';

    protected $fillable = [
        'code',
        'name',
        'symbol',
        'decimal_places',
        'is_active',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'decimal_places' => 'integer',
        'is_active' => 'boolean',
    ];
}

