<?php

declare(strict_types=1);

namespace Modules\Payments\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRefundRequest extends FormRequest
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
            'payment_id' => ['required', 'string'],
            'refund_number' => ['required', 'string', 'max:50'],
            'refund_date' => ['required', 'date'],
            'amount' => ['required', 'integer', 'min:1'],
            'reason' => ['nullable', 'string'],
        ];
    }
}
