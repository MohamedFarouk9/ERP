@extends('admin.layouts.app')
@section('title', __('Compliance Audit Trail'))

@section('content')
<div class="wrapper">
    <div class="page-wrapper">
        <div class="page-content">
            <div class="mb-3">
                <h4 class="mb-0">{{ __('Compliance Audit Trail') }}</h4>
            </div>
            <div class="card radius-10">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="auditLogsDataTable" class="table table-striped table-bordered align-middle w-100">
                            <thead class="table-light">
                                <tr>
                                    <th>{{ __('Timestamp') }}</th>
                                    <th>{{ __('User') }}</th>
                                    <th>{{ __('Event') }}</th>
                                    <th>{{ __('Target') }}</th>
                                    <th>{{ __('IP Address') }}</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#auditLogsDataTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('admin.audit-logs.data') }}",
        pageLength: 25,
        order: [[0, 'desc']],
        columns: [
            { data: 'created_at' },
            { data: 'user_name', orderable: false },
            { data: 'event', render: d => `<span class="badge bg-dark text-uppercase">${d}</span>` },
            { data: 'target', render: d => `<code>${d}</code>` },
            { data: 'ip_address' }
        ]
    });
});
</script>
@endpush
