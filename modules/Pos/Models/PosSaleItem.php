<?php

declare(strict_types=1);

namespace Modules\Pos\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PosSaleItem extends Model
{
    use HasUlids;

    protected $table = 'pos_sale_items';

    protected $fillable = [
        'pos_sale_id',
        'product_id',
        'sku',
        'product_name',
        'quantity',
        'unit_price',
        'tax_amount',
        'line_total',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'integer',
        'tax_amount' => 'integer',
        'line_total' => 'integer',
    ];

    /**
     * @return BelongsTo<PosSale, $this>
     */
    public function sale(): BelongsTo
    {
        return $this->belongsTo(PosSale::class, 'pos_sale_id');
    }
}
