<?php

declare(strict_types=1);

namespace Modules\Crm\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreActivityRequest extends FormRequest
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
            'type' => ['required', 'in:call,email,meeting,task'],
            'subject' => ['required', 'string', 'max:180'],
            'lead_id' => ['nullable', 'string'],
            'deal_id' => ['nullable', 'string'],
            'assigned_to' => ['nullable', 'string'],
            'due_at' => ['nullable', 'date'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
