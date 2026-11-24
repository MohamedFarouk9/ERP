<?php

declare(strict_types=1);

namespace Modules\Hrm\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAttendanceRequest extends FormRequest
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
            'employee_id' => ['required', 'string'],
            'work_date' => ['required', 'date'],
            'check_in' => ['nullable', 'string', 'max:10'],
            'check_out' => ['nullable', 'string', 'max:10'],
            'status' => ['required', 'in:present,absent,late,half_day,remote'],
            'notes' => ['nullable', 'string', 'max:255'],
        ];
    }
}
