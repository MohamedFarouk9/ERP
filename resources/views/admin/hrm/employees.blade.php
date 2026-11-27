@extends('admin.layouts.app')
@section('title', __('Employees'))

@section('content')
<div class="page-wrapper">
    <div class="page-content">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <h4 class="mb-0">{{ __('Employees') }}</h4>
            </div>
            @can('hrm.employees.create')
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createEmployeeModal">
                    <i class="bx bx-user-plus"></i> {{ __('Add Employee') }}
                </button>
            @endcan
        </div>

        <div class="card radius-10">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="employeesDataTable" class="table table-striped table-bordered align-middle w-100">
                        <thead class="table-light">
                            <tr>
                                <th>{{ __('Code') }}</th>
                                <th>{{ __('Full Name') }}</th>
                                <th>{{ __('Department') }}</th>
                                <th>{{ __('Job Title') }}</th>
                                <th>{{ __('Base Salary') }}</th>
                                <th>{{ __('Hire Date') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th class="text-end">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Employee Modal -->
<div class="modal fade" id="createEmployeeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form class="modal-content ajax-form" method="POST" action="{{ route('admin.hrm.employees.store') }}">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Add Employee') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body row g-3">
                <div class="col-md-4"><label class="form-label">{{ __('Employee Code *') }}</label><input type="text" name="employee_code" class="form-control" required placeholder="EMP-010"></div>
                <div class="col-md-4"><label class="form-label">{{ __('First Name *') }}</label><input type="text" name="first_name" class="form-control" required></div>
                <div class="col-md-4"><label class="form-label">{{ __('Last Name *') }}</label><input type="text" name="last_name" class="form-control" required></div>
                <div class="col-md-6"><label class="form-label">{{ __('Email *') }}</label><input type="email" name="email" class="form-control" required></div>
                <div class="col-md-6"><label class="form-label">{{ __('Phone') }}</label><input type="text" name="phone" class="form-control"></div>
                <div class="col-md-6"><label class="form-label">{{ __('Job Title *') }}</label><input type="text" name="job_title" class="form-control" required></div>
                <div class="col-md-6">
                    <label class="form-label">{{ __('Department') }}</label>
                    <select name="department_id" class="form-select">
                        <option value="">{{ __('-- Optional --') }}</option>
                        @foreach($departments as $d)<option value="{{ $d->id }}">{{ $d->code }} - {{ $d->name }}</option>@endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">{{ __('Employment Type *') }}</label>
                    <select name="employment_type" class="form-select" required>
                        <option value="full_time">{{ __('Full Time') }}</option>
                        <option value="part_time">{{ __('Part Time') }}</option>
                        <option value="contract">{{ __('Contract') }}</option>
                        <option value="intern">{{ __('Intern') }}</option>
                    </select>
                </div>
                <div class="col-md-4"><label class="form-label">{{ __('Hire Date *') }}</label><input type="date" name="hire_date" class="form-control" required value="{{ date('Y-m-d') }}"></div>
                <div class="col-md-4"><label class="form-label">{{ __('Monthly Salary (Cents) *') }}</label><input type="number" name="base_salary" class="form-control" required min="0" value="500000"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __("Cancel") }}</button>
                <button type="submit" class="btn btn-primary">{{ __('Create Employee') }}</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Employee Modal -->
<div class="modal fade" id="editEmployeeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form id="editEmployeeForm" class="modal-content ajax-form" method="POST" action="">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Edit Employee') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body row g-3">
                <div class="col-md-4"><label class="form-label">{{ __('Code *') }}</label><input type="text" name="employee_code" id="ee_code" class="form-control" required></div>
                <div class="col-md-4"><label class="form-label">{{ __('First Name *') }}</label><input type="text" name="first_name" id="ee_first" class="form-control" required></div>
                <div class="col-md-4"><label class="form-label">{{ __('Last Name *') }}</label><input type="text" name="last_name" id="ee_last" class="form-control" required></div>
                <div class="col-md-6"><label class="form-label">{{ __('Email *') }}</label><input type="email" name="email" id="ee_email" class="form-control" required></div>
                <div class="col-md-6"><label class="form-label">{{ __('Phone') }}</label><input type="text" name="phone" id="ee_phone" class="form-control"></div>
                <div class="col-md-6"><label class="form-label">{{ __('Job Title *') }}</label><input type="text" name="job_title" id="ee_title" class="form-control" required></div>
                <div class="col-md-6">
                    <label class="form-label">{{ __('Department') }}</label>
                    <select name="department_id" id="ee_dept" class="form-select">
                        <option value="">{{ __('-- Optional --') }}</option>
                        @foreach($departments as $d)<option value="{{ $d->id }}">{{ $d->code }} - {{ $d->name }}</option>@endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">{{ __('Type *') }}</label>
                    <select name="employment_type" id="ee_type" class="form-select" required>
                        <option value="full_time">{{ __('Full Time') }}</option>
                        <option value="part_time">{{ __('Part Time') }}</option>
                        <option value="contract">{{ __('Contract') }}</option>
                        <option value="intern">{{ __('Intern') }}</option>
                    </select>
                </div>
                <div class="col-md-3"><label class="form-label">{{ __('Hire Date *') }}</label><input type="date" name="hire_date" id="ee_hire" class="form-control" required></div>
                <div class="col-md-3"><label class="form-label">{{ __('Salary (Cents) *') }}</label><input type="number" name="base_salary" id="ee_salary" class="form-control" required min="0"></div>
                <div class="col-md-3">
                    <label class="form-label">{{ __('Status *') }}</label>
                    <select name="status" id="ee_status" class="form-select" required>
                        <option value="active">{{ __('Active') }}</option>
                        <option value="on_leave">{{ __('On Leave') }}</option>
                        <option value="terminated">{{ __('Terminated') }}</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __("Cancel") }}</button>
                <button type="submit" class="btn btn-primary">{{ __('Update Employee') }}</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    var baseUrl = "{{ url('/admin/hrm/employees') }}";

    window.erpActiveDataTable = $('#employeesDataTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('admin.hrm.employees.data') }}",
        pageLength: 25,
        columns: [
            { data: 'employee_code', render: d => `<code>${d}</code>` },
            { data: 'full_name', render: d => `<strong>${d}</strong>` },
            { data: 'department_name', orderable: false },
            { data: 'job_title' },
            { data: 'base_salary', render: d => '$' + (d / 100).toFixed(2) },
            { data: 'hire_date' },
            {
                data: 'status',
                render: d => {
                    let cls = d === 'active' ? 'bg-light-success text-success' : (d === 'on_leave' ? 'bg-light-warning text-warning' : 'bg-light-danger text-danger');
                    return `<span class="badge rounded-pill ${cls} text-uppercase px-3">${d.replace('_', ' ')}</span>`;
                }
            },
            {
                data: null,
                orderable: false,
                searchable: false,
                className: 'text-end text-nowrap',
                render: function(row) {
                    let rowJson = encodeURIComponent(JSON.stringify(row));
                    return `
                        <button class="btn btn-sm btn-outline-primary btn-edit-emp" data-row="${rowJson}"><i class="bx bx-edit"></i></button>
                        <form class="d-inline ajax-delete-form" method="POST" action="${baseUrl}/${row.id}" data-title="Delete ${row.employee_code}?">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bx bx-trash"></i></button>
                        </form>
                    `;
                }
            }
        ]
    });

    $(document).on('click', '.btn-edit-emp', function() {
        var r = JSON.parse(decodeURIComponent($(this).data('row')));
        $('#editEmployeeForm').attr('action', baseUrl + '/' + r.id);
        $('#ee_code').val(r.employee_code);
        $('#ee_first').val(r.first_name);
        $('#ee_last').val(r.last_name);
        $('#ee_email').val(r.email);
        $('#ee_phone').val(r.phone);
        $('#ee_title').val(r.job_title);
        $('#ee_dept').val(r.department_id);
        $('#ee_type').val(r.employment_type);
        $('#ee_hire').val(r.hire_date);
        $('#ee_salary').val(r.base_salary);
        $('#ee_status').val(r.status);
        new bootstrap.Modal(document.getElementById('editEmployeeModal')).show();
    });
});
</script>
@endpush
