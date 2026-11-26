<?php

declare(strict_types=1);

namespace Modules\Crm\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLeadRequest extends FormRequest
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
            'lead_code' => ['nullable', 'string', 'max:40'],
            'name' => ['required', 'string', 'max:150'],
            'company' => ['nullable', 'string', 'max:150'],
            'email' => ['nullable', 'email', 'max:150'],
            'phone' => ['nullable', 'string', 'max:50'],
            'source' => ['required', 'in:website,referral,cold_call,social,event'],
            'status' => ['nullable', 'in:new,contacted,qualified,lost'],
            'estimated_value' => ['nullable', 'integer', 'min:0'],
            'owner_id' => ['nullable', 'string'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
