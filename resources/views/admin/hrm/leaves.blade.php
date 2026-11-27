@extends('admin.layouts.app')
@section('title', __('Leave Requests'))

@section('content')
<div class="page-wrapper">
    <div class="page-content">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <h4 class="mb-0">{{ __('Leave Requests & Approvals') }}</h4>
            </div>
            @can('hrm.leaves.create')
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createLeaveModal">
                    <i class="bx bx-calendar-plus"></i> {{ __('Request Leave') }}
                </button>
            @endcan
        </div>

        <div class="card radius-10">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="leavesDataTable" class="table table-striped table-bordered align-middle w-100">
                        <thead class="table-light">
                            <tr>
                                <th>{{ __('Employee') }}</th>
                                <th>{{ __('Type') }}</th>
                                <th>{{ __('Start Date') }}</th>
                                <th>{{ __('End Date') }}</th>
                                <th>{{ __('Days') }}</th>
                                <th>{{ __('Reason') }}</th>
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

<!-- Create Leave Modal -->
<div class="modal fade" id="createLeaveModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content ajax-form" method="POST" action="{{ route('admin.hrm.leaves.store') }}">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Submit Leave Request') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body row g-3">
                <div class="col-12">
                    <label class="form-label">{{ __('Employee *') }}</label>
                    <select name="employee_id" class="form-select" required>
                        @foreach($employees as $e)<option value="{{ $e->id }}">{{ $e->employee_code }} - {{ $e->fullName() }}</option>@endforeach
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">{{ __('Leave Type *') }}</label>
                    <select name="leave_type" class="form-select" required>
                        <option value="annual">{{ __('Annual Leave') }}</option>
                        <option value="sick">{{ __('Sick Leave') }}</option>
                        <option value="emergency">{{ __('Emergency Leave') }}</option>
                        <option value="unpaid">{{ __('Unpaid Leave') }}</option>
                        <option value="maternity">{{ __('Maternity / Paternity') }}</option>
                    </select>
                </div>
                <div class="col-md-6"><label class="form-label">{{ __('Start Date *') }}</label><input type="date" name="start_date" class="form-control" required value="{{ date('Y-m-d') }}"></div>
                <div class="col-md-6"><label class="form-label">{{ __('End Date *') }}</label><input type="date" name="end_date" class="form-control" required value="{{ date('Y-m-d', strtotime('+2 days')) }}"></div>
                <div class="col-12"><label class="form-label">{{ __('Reason') }}</label><textarea name="reason" class="form-control" rows="2"></textarea></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __("Cancel") }}</button>
                <button type="submit" class="btn btn-primary">{{ __('Submit Request') }}</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    var baseUrl = "{{ url('/admin/hrm/leaves') }}";

    window.erpActiveDataTable = $('#leavesDataTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('admin.hrm.leaves.data') }}",
        pageLength: 25,
        columns: [
            { data: 'employee_name', orderable: false, render: d => `<strong>${d}</strong>` },
            { data: 'leave_type', render: d => `<span class="text-capitalize">${d}</span>` },
            { data: 'start_date' },
            { data: 'end_date' },
            { data: 'days_count', render: d => `${d}d` },
            { data: 'reason' },
            {
                data: 'status',
                render: d => {
                    let cls = d === 'approved' ? 'bg-light-success text-success' : (d === 'rejected' ? 'bg-light-danger text-danger' : 'bg-light-warning text-warning');
                    return `<span class="badge rounded-pill ${cls} text-uppercase px-3">${d}</span>`;
                }
            },
            {
                data: null,
                orderable: false,
                searchable: false,
                className: 'text-end text-nowrap',
                render: function(row) {
                    if (row.status === 'pending') {
                        return `
                            <form class="d-inline ajax-action-form" method="POST" action="${baseUrl}/${row.id}/approve" data-title="Approve leave?" data-confirm-text="Approve">
                                <button type="submit" class="btn btn-sm btn-outline-success"><i class="bx bx-check"></i></button>
                            </form>
                            <form class="d-inline ajax-action-form" method="POST" action="${baseUrl}/${row.id}/reject" data-title="Reject leave?" data-confirm-text="Reject">
                                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bx bx-x"></i></button>
                            </form>
                        `;
                    }
                    return '<span class="text-muted small">Reviewed</span>';
                }
            }
        ]
    });
});
</script>
@endpush
