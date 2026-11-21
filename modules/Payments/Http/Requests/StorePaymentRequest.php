<?php

declare(strict_types=1);

namespace Modules\Payments\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRequest extends FormRequest
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
            'payment_method_id' => ['required', 'string'],
            'customer_id' => ['nullable', 'string'],
            'invoice_id' => ['nullable', 'string'],
            'payment_number' => ['required', 'string', 'max:50'],
            'payment_date' => ['required', 'date'],
            'amount' => ['required', 'integer', 'min:1'],
            'currency_code' => ['nullable', 'string', 'size:3'],
            'reference' => ['nullable', 'string', 'max:150'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
