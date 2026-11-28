<?php

declare(strict_types=1);

namespace Modules\Finance\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaxRateRequest extends FormRequest
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
            'code' => ['required', 'string', 'max:30'],
            'name' => ['required', 'string', 'max:120'],
            'rate_bps' => ['required', 'integer', 'min:0', 'max:10000'],
            'type' => ['required', 'in:sales,purchase,both'],
            'is_default' => ['nullable', 'boolean'],
        ];
    }
}
