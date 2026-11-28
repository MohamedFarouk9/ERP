<?php

declare(strict_types=1);

namespace Modules\Finance\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Modules\Core\Traits\BelongsToTenant;
use Modules\Users\Traits\Auditable;

class TaxRate extends Model
{
    use Auditable, BelongsToTenant, HasUlids;

    protected $table = 'tax_rates';

    protected $fillable = [
        'tenant_id',
        'code',
        'name',
        'rate_bps',
        'type',
        'is_default',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'rate_bps' => 'integer',
            'is_default' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function ratePercent(): float
    {
        return round($this->rate_bps / 100, 2);
    }
}
