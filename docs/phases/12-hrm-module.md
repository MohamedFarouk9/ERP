# Phase 12: Wave 2 — HRM & Payroll Module

## Module
- `modules/Hrm/` (`HrmServiceProvider` registered in `bootstrap/providers.php`)

## Database Tables (1 migration = 1 table)
- `employees`
- `attendances`
- `leave_requests`
- `payroll_runs`
- `payroll_items`

## Features & Security
- **HRM Analytics Dashboard** (`/admin/hrm/dashboard`): Headcount, Attendance %, Monthly Base Payroll, Pending Leaves, 6-Month Payroll Cost & Department Headcount ApexCharts.
- **Employees CRUD** (`CreateEmployeeAction`, `UpdateEmployeeAction`, Server-Side DataTables).
- **Attendance** (`RecordAttendanceAction`, daily check-in/out).
- **Leave Requests** (`CreateLeaveRequestAction`, `ReviewLeaveRequestAction` with Approve/Reject).
- **Payroll Runs** (`GeneratePayrollRunAction` calculates gross, allowances, deductions, and net pay for all active employees in `DB::transaction`; `PayPayrollRunAction` dispatches `PayrollPaid`).
- Protected by `feature:hrm` and `hrm.*` RBAC permissions; seeded via `HrmDatabaseSeeder`.
