<?php

declare(strict_types=1);

namespace Modules\Hrm\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePayrollRunRequest extends FormRequest
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
            'payroll_number' => ['required', 'string', 'max:50'],
            'period_month' => ['required', 'string', 'size:7'],
            'pay_date' => ['required', 'date'],
            'default_allowance_bps' => ['nullable', 'integer', 'min:0', 'max:5000'],
            'default_deduction_bps' => ['nullable', 'integer', 'min:0', 'max:5000'],
        ];
    }
}
