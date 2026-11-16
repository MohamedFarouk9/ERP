<?php

declare(strict_types=1);

namespace Modules\Users\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class ConfirmMfaRequest extends FormRequest
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
            'code' => ['required', 'string', 'size:6'],
        ];
    }
}
