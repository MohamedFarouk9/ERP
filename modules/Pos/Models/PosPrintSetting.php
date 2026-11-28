<?php

declare(strict_types=1);

namespace Modules\Pos\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Modules\Core\Traits\BelongsToTenant;
use Modules\Users\Traits\Auditable;

class PosPrintSetting extends Model
{
    use Auditable, BelongsToTenant, HasUlids;

    protected $table = 'pos_print_settings';

    protected $fillable = [
        'tenant_id',
        'printer_type',
        'barcode_type',
        'label_paper_size',
        'store_name',
        'store_address',
        'store_phone',
        'tax_number',
        'footer_message',
        'auto_print_receipt',
        'show_logo',
        'show_barcode_on_receipt',
        'show_cashier',
        'show_customer',
        'show_tax_breakdown',
    ];

    protected function casts(): array
    {
        return [
            'auto_print_receipt' => 'boolean',
            'show_logo' => 'boolean',
            'show_barcode_on_receipt' => 'boolean',
            'show_cashier' => 'boolean',
            'show_customer' => 'boolean',
            'show_tax_breakdown' => 'boolean',
        ];
    }
}
