<?php

declare(strict_types=1);

namespace Modules\Hrm\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmployeeRequest extends FormRequest
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
            'employee_code' => ['required', 'string', 'max:50'],
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150'],
            'phone' => ['nullable', 'string', 'max:50'],
            'department_id' => ['nullable', 'string'],
            'user_id' => ['nullable', 'string'],
            'job_title' => ['required', 'string', 'max:150'],
            'employment_type' => ['required', 'in:full_time,part_time,contract,intern'],
            'hire_date' => ['required', 'date'],
            'base_salary' => ['required', 'integer', 'min:0'],
            'currency_code' => ['nullable', 'string', 'size:3'],
            'status' => ['nullable', 'in:active,on_leave,terminated'],
        ];
    }
}
