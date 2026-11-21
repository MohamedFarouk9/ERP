@extends('admin.layouts.app')
@section('title', 'Audit Logs')

@section('content')
<div class="wrapper">
    <div class="page-wrapper">
        <div class="page-content">
            <h4 class="mb-3">Compliance Audit Trail</h4>
            <div class="card radius-10">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Timestamp</th>
                                    <th>User</th>
                                    <th>Event</th>
                                    <th>Target</th>
                                    <th>IP Address</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($logs as $log)
                                    <tr>
                                        <td>{{ $log->created_at?->format('Y-m-d H:i:s') }}</td>
                                        <td>{{ $log->user?->name ?? 'System' }}</td>
                                        <td><span class="badge bg-dark text-uppercase">{{ $log->event }}</span></td>
                                        <td><code>{{ class_basename($log->auditable_type) }} #{{ $log->auditable_id }}</code></td>
                                        <td>{{ $log->ip_address ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center text-muted py-4">No audit entries recorded.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">{{ $logs->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
