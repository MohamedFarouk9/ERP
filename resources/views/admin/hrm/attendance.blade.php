@extends('admin.layouts.app')
@section('title', __('Attendance'))

@section('content')
<div class="page-wrapper">
    <div class="page-content">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <h4 class="mb-0">{{ __('Attendance & Timekeeping') }}</h4>
            </div>
            @can('hrm.attendance.create')
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#recordAttendanceModal">
                    <i class="bx bx-time"></i> {{ __('Record Attendance') }}
                </button>
            @endcan
        </div>

        <div class="card radius-10">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="attendanceDataTable" class="table table-striped table-bordered align-middle w-100">
                        <thead class="table-light">
                            <tr>
                                <th>{{ __('Employee') }}</th>
                                <th>{{ __('Work Date') }}</th>
                                <th>{{ __('Check In') }}</th>
                                <th>{{ __('Check Out') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Notes') }}</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Record Attendance Modal -->
<div class="modal fade" id="recordAttendanceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content ajax-form" method="POST" action="{{ route('admin.hrm.attendance.store') }}">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Record Attendance') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body row g-3">
                <div class="col-12">
                    <label class="form-label">{{ __('Employee *') }}</label>
                    <select name="employee_id" class="form-select" required>
                        @foreach($employees as $e)<option value="{{ $e->id }}">{{ $e->employee_code }} - {{ $e->fullName() }}</option>@endforeach
                    </select>
                </div>
                <div class="col-md-6"><label class="form-label">{{ __('Work Date *') }}</label><input type="date" name="work_date" class="form-control" required value="{{ date('Y-m-d') }}"></div>
                <div class="col-md-6">
                    <label class="form-label">{{ __('Status *') }}</label>
                    <select name="status" class="form-select" required>
                        <option value="present">{{ __('Present') }}</option>
                        <option value="remote">{{ __('Remote') }}</option>
                        <option value="late">{{ __('Late') }}</option>
                        <option value="half_day">{{ __('Half Day') }}</option>
                        <option value="absent">{{ __('Absent') }}</option>
                    </select>
                </div>
                <div class="col-md-6"><label class="form-label">{{ __('Check In') }}</label><input type="time" name="check_in" class="form-control" value="09:00"></div>
                <div class="col-md-6"><label class="form-label">{{ __('Check Out') }}</label><input type="time" name="check_out" class="form-control" value="17:00"></div>
                <div class="col-12"><label class="form-label">{{ __('Notes') }}</label><input type="text" name="notes" class="form-control"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __("Cancel") }}</button>
                <button type="submit" class="btn btn-primary">{{ __('Save Attendance') }}</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    window.erpActiveDataTable = $('#attendanceDataTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('admin.hrm.attendance.data') }}",
        pageLength: 25,
        columns: [
            { data: 'employee_name', orderable: false, render: d => `<strong>${d}</strong>` },
            { data: 'work_date' },
            { data: 'check_in' },
            { data: 'check_out' },
            {
                data: 'status',
                render: d => {
                    let cls = d === 'present' || d === 'remote' ? 'bg-light-success text-success' : (d === 'late' || d === 'half_day' ? 'bg-light-warning text-warning' : 'bg-light-danger text-danger');
                    return `<span class="badge rounded-pill ${cls} text-uppercase px-3">${d.replace('_', ' ')}</span>`;
                }
            },
            { data: 'notes' }
        ]
    });
});
</script>
@endpush
