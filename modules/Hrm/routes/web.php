<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Hrm\Http\Controllers\AttendanceController;
use Modules\Hrm\Http\Controllers\EmployeeController;
use Modules\Hrm\Http\Controllers\HrmDashboardController;
use Modules\Hrm\Http\Controllers\LeaveRequestController;
use Modules\Hrm\Http\Controllers\PayrollController;

Route::prefix('admin/hrm')
    ->name('admin.hrm.')
    ->middleware(['auth', 'tenant', 'tenant.active', 'feature:hrm'])
    ->group(function (): void {
        Route::get('/dashboard', [HrmDashboardController::class, 'index'])
            ->middleware('can:hrm.employees.view')
            ->name('dashboard');

        // Employees
        Route::get('/employees', [EmployeeController::class, 'index'])
            ->middleware('can:hrm.employees.view')
            ->name('employees.index');
        Route::get('/employees/data', [EmployeeController::class, 'data'])
            ->middleware('can:hrm.employees.view')
            ->name('employees.data');
        Route::post('/employees', [EmployeeController::class, 'store'])
            ->middleware('can:hrm.employees.create')
            ->name('employees.store');
        Route::put('/employees/{id}', [EmployeeController::class, 'update'])
            ->middleware('can:hrm.employees.create')
            ->name('employees.update');
        Route::delete('/employees/{id}', [EmployeeController::class, 'destroy'])
            ->middleware('can:hrm.employees.create')
            ->name('employees.destroy');

        // Attendance
        Route::get('/attendance', [AttendanceController::class, 'index'])
            ->middleware('can:hrm.attendance.view')
            ->name('attendance.index');
        Route::get('/attendance/data', [AttendanceController::class, 'data'])
            ->middleware('can:hrm.attendance.view')
            ->name('attendance.data');
        Route::post('/attendance', [AttendanceController::class, 'store'])
            ->middleware('can:hrm.attendance.create')
            ->name('attendance.store');

        // Leaves
        Route::get('/leaves', [LeaveRequestController::class, 'index'])
            ->middleware('can:hrm.leaves.view')
            ->name('leaves.index');
        Route::get('/leaves/data', [LeaveRequestController::class, 'data'])
            ->middleware('can:hrm.leaves.view')
            ->name('leaves.data');
        Route::post('/leaves', [LeaveRequestController::class, 'store'])
            ->middleware('can:hrm.leaves.create')
            ->name('leaves.store');
        Route::post('/leaves/{id}/approve', [LeaveRequestController::class, 'approve'])
            ->middleware('can:hrm.leaves.approve')
            ->name('leaves.approve');
        Route::post('/leaves/{id}/reject', [LeaveRequestController::class, 'reject'])
            ->middleware('can:hrm.leaves.approve')
            ->name('leaves.reject');

        // Payroll
        Route::get('/payroll', [PayrollController::class, 'index'])
            ->middleware('can:hrm.payroll.view')
            ->name('payroll.index');
        Route::post('/payroll', [PayrollController::class, 'store'])
            ->middleware('can:hrm.payroll.create')
            ->name('payroll.store');
        Route::get('/payroll/{id}', [PayrollController::class, 'show'])
            ->middleware('can:hrm.payroll.view')
            ->name('payroll.show');
        Route::post('/payroll/{id}/pay', [PayrollController::class, 'pay'])
            ->middleware('can:hrm.payroll.pay')
            ->name('payroll.pay');
    });
