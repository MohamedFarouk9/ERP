<?php

declare(strict_types=1);

namespace Modules\Payments\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentMethodRequest extends FormRequest
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
            'type' => ['required', 'in:cash,bank_transfer,card,cheque,wallet'],
            'account_id' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
            'settings' => ['nullable', 'array'],
        ];
    }
}
