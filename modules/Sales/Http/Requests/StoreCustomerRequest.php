<?php

declare(strict_types=1);

namespace Modules\Sales\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerRequest extends FormRequest
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
            'code' => ['required', 'string', 'max:50'],
            'name' => ['required', 'string', 'max:150'],
            'email' => ['nullable', 'email', 'max:150'],
            'phone' => ['nullable', 'string', 'max:50'],
            'tax_number' => ['nullable', 'string', 'max:50'],
            'billing_address' => ['nullable', 'string', 'max:255'],
            'shipping_address' => ['nullable', 'string', 'max:255'],
            'credit_limit' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
