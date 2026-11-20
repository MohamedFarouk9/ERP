<?php

declare(strict_types=1);

namespace Modules\Sales\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalesOrderItem extends Model
{
    use HasUlids;

    protected $table = 'sales_order_items';

    protected $fillable = [
        'sales_order_id',
        'product_id',
        'description',
        'quantity',
        'unit_price',
        'tax_rate',
        'tax_amount',
        'line_total',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'integer',
        'tax_rate' => 'integer',
        'tax_amount' => 'integer',
        'line_total' => 'integer',
    ];

    /**
     * @return BelongsTo<SalesOrder, $this>
     */
    public function salesOrder(): BelongsTo
    {
        return $this->belongsTo(SalesOrder::class, 'sales_order_id');
    }
}
