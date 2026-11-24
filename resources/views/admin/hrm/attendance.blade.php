@extends('admin.layouts.app')
@section('title', 'Attendance Log')

@section('content')
<div class="page-wrapper">
    <div class="page-content">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <h4 class="mb-0">Attendance & Timekeeping</h4>
                <small class="text-muted">Daily employee check-in/out logs and work status tracking</small>
            </div>
            @can('hrm.attendance.create')
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#recordAttendanceModal">
                    <i class="bx bx-time"></i> Record Attendance
                </button>
            @endcan
        </div>

        <div class="card radius-10">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="attendanceDataTable" class="table table-striped table-bordered align-middle w-100">
                        <thead class="table-light">
                            <tr>
                                <th>Employee</th>
                                <th>Work Date</th>
                                <th>Check In</th>
                                <th>Check Out</th>
                                <th>Status</th>
                                <th>Notes</th>
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
                <h5 class="modal-title">Record Attendance</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body row g-3">
                <div class="col-12">
                    <label class="form-label">Employee *</label>
                    <select name="employee_id" class="form-select" required>
                        @foreach($employees as $e)<option value="{{ $e->id }}">{{ $e->employee_code }} - {{ $e->fullName() }}</option>@endforeach
                    </select>
                </div>
                <div class="col-md-6"><label class="form-label">Work Date *</label><input type="date" name="work_date" class="form-control" required value="{{ date('Y-m-d') }}"></div>
                <div class="col-md-6">
                    <label class="form-label">Status *</label>
                    <select name="status" class="form-select" required>
                        <option value="present">Present</option>
                        <option value="remote">Remote</option>
                        <option value="late">Late</option>
                        <option value="half_day">Half Day</option>
                        <option value="absent">Absent</option>
                    </select>
                </div>
                <div class="col-md-6"><label class="form-label">Check In</label><input type="time" name="check_in" class="form-control" value="09:00"></div>
                <div class="col-md-6"><label class="form-label">Check Out</label><input type="time" name="check_out" class="form-control" value="17:00"></div>
                <div class="col-12"><label class="form-label">Notes</label><input type="text" name="notes" class="form-control"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Attendance</button>
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
