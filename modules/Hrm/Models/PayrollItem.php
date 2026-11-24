<?php

declare(strict_types=1);

namespace Modules\Hrm\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PayrollItem extends Model
{
    use HasUlids;

    protected $table = 'payroll_items';

    protected $fillable = [
        'payroll_run_id',
        'employee_id',
        'base_salary',
        'allowances',
        'deductions',
        'net_pay',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'base_salary' => 'integer',
        'allowances' => 'integer',
        'deductions' => 'integer',
        'net_pay' => 'integer',
    ];

    /**
     * @return BelongsTo<PayrollRun, $this>
     */
    public function payrollRun(): BelongsTo
    {
        return $this->belongsTo(PayrollRun::class, 'payroll_run_id');
    }

    /**
     * @return BelongsTo<Employee, $this>
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
