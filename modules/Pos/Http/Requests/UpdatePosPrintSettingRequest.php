<?php

declare(strict_types=1);

namespace Modules\Pos\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePosPrintSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'printer_type' => ['required', 'in:thermal_80mm,thermal_58mm,a4'],
            'barcode_type' => ['required', 'in:CODE128,CODE39,EAN13'],
            'label_paper_size' => ['required', 'in:A4,thermal_80mm,thermal_58mm,roll_50x30'],
            'store_name' => ['nullable', 'string', 'max:150'],
            'store_address' => ['nullable', 'string', 'max:255'],
            'store_phone' => ['nullable', 'string', 'max:50'],
            'tax_number' => ['nullable', 'string', 'max:60'],
            'footer_message' => ['nullable', 'string', 'max:255'],
            'auto_print_receipt' => ['nullable', 'boolean'],
            'show_logo' => ['nullable', 'boolean'],
            'show_barcode_on_receipt' => ['nullable', 'boolean'],
            'show_cashier' => ['nullable', 'boolean'],
            'show_customer' => ['nullable', 'boolean'],
            'show_tax_breakdown' => ['nullable', 'boolean'],
        ];
    }
}
