<?php

declare(strict_types=1);

namespace Modules\Crm\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDealRequest extends FormRequest
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
            'deal_code' => ['nullable', 'string', 'max:40'],
            'title' => ['required', 'string', 'max:180'],
            'customer_id' => ['nullable', 'string'],
            'lead_id' => ['nullable', 'string'],
            'owner_id' => ['nullable', 'string'],
            'stage' => ['required', 'in:qualified,proposal,negotiation,won,lost'],
            'value_amount' => ['required', 'integer', 'min:0'],
            'probability' => ['nullable', 'integer', 'min:0', 'max:100'],
            'expected_close_date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
