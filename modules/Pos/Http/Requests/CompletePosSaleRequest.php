<?php

declare(strict_types=1);

namespace Modules\Pos\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompletePosSaleRequest extends FormRequest
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
            'pos_shift_id' => ['required', 'string'],
            'customer_id' => ['nullable', 'string'],
            'payment_type' => ['required', 'in:cash,card,split'],
            'amount_tendered' => ['required', 'integer', 'min:0'],
            'discount_amount' => ['nullable', 'integer', 'min:0'],
            'tax_rate_bps' => ['nullable', 'integer', 'min:0', 'max:5000'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'string'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
        ];
    }
}
