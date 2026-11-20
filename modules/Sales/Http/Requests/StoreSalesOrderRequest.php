<?php

declare(strict_types=1);

namespace Modules\Sales\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSalesOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'customer_id' => ['required', 'string'],
            'warehouse_id' => ['nullable', 'string'],
            'order_number' => ['required', 'string', 'max:50'],
            'order_date' => ['required', 'date'],
            'discount_amount' => ['nullable', 'integer', 'min:0'],
            'currency_code' => ['nullable', 'string', 'size:3'],
            'notes' => ['nullable', 'string'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'string'],
            'items.*.description' => ['nullable', 'string', 'max:255'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.unit_price' => ['required', 'integer', 'min:0'],
            'items.*.tax_rate' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
