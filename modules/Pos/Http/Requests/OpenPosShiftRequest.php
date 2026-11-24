<?php

declare(strict_types=1);

namespace Modules\Pos\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OpenPosShiftRequest extends FormRequest
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
            'shift_number' => ['required', 'string', 'max:50'],
            'warehouse_id' => ['nullable', 'string'],
            'opening_cash' => ['required', 'integer', 'min:0'],
            'notes' => ['nullable', 'string', 'max:255'],
        ];
    }
}
