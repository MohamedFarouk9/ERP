<?php

declare(strict_types=1);

namespace Modules\Crm\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDealStageRequest extends FormRequest
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
            'stage' => ['required', 'in:qualified,proposal,negotiation,won,lost'],
        ];
    }
}
