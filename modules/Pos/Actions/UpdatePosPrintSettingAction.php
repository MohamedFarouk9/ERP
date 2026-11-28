<?php

declare(strict_types=1);

namespace Modules\Pos\Actions;

use Modules\Core\Services\TenantContext;
use Modules\Pos\Models\PosPrintSetting;

class UpdatePosPrintSettingAction
{
    public function __construct(
        protected TenantContext $tenantContext
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data): PosPrintSetting
    {
        $tenantId = $this->tenantContext->requireId();

        return PosPrintSetting::updateOrCreate(
            ['tenant_id' => $tenantId],
            [
                'printer_type' => $data['printer_type'] ?? 'thermal_80mm',
                'barcode_type' => $data['barcode_type'] ?? 'CODE128',
                'label_paper_size' => $data['label_paper_size'] ?? 'A4',
                'store_name' => $data['store_name'] ?? null,
                'store_address' => $data['store_address'] ?? null,
                'store_phone' => $data['store_phone'] ?? null,
                'tax_number' => $data['tax_number'] ?? null,
                'footer_message' => $data['footer_message'] ?? 'Thank you for your business!',
                'auto_print_receipt' => (bool) ($data['auto_print_receipt'] ?? false),
                'show_logo' => (bool) ($data['show_logo'] ?? false),
                'show_barcode_on_receipt' => (bool) ($data['show_barcode_on_receipt'] ?? false),
                'show_cashier' => (bool) ($data['show_cashier'] ?? false),
                'show_customer' => (bool) ($data['show_customer'] ?? false),
                'show_tax_breakdown' => (bool) ($data['show_tax_breakdown'] ?? false),
            ]
        );
    }
}
