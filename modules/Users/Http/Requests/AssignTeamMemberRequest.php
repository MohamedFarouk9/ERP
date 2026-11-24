<?php

declare(strict_types=1);

namespace Modules\Users\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssignTeamMemberRequest extends FormRequest
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
            'team_id' => ['required', 'string'],
            'user_id' => ['required', 'string'],
            'is_leader' => ['nullable', 'boolean'],
        ];
    }
}
