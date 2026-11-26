@extends('admin.layouts.app')

@section('title', 'CRM Activities')

@section('content')
<div class="page-wrapper">
    <div class="page-content">
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">CRM</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.crm.dashboard') }}">CRM Analytics</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Activities</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                @can('crm.activities.manage')
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createActivityModal">
                    <i class="bx bx-plus me-1"></i> Schedule Activity
                </button>
                @endcan
            </div>
        </div>

        <div class="card radius-10">
            <div class="card-header">
                <h6 class="mb-0">Calls, Meetings &amp; Follow-up Tasks</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="crmActivitiesTable" class="table table-striped table-bordered w-100">
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th>Subject</th>
                                <th>Related Lead / Deal</th>
                                <th>Assigned To</th>
                                <th>Due At</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Activity Modal -->
<div class="modal fade" id="createActivityModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="createActivityForm">
                <div class="modal-header">
                    <h5 class="modal-title">Schedule CRM Activity</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Activity Type *</label>
                        <select name="type" class="form-select" required>
                            <option value="call">Phone Call</option>
                            <option value="email">Email</option>
                            <option value="meeting">Meeting</option>
                            <option value="task">Task</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Subject *</label>
                        <input type="text" name="subject" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Related Lead</label>
                        <select name="lead_id" class="form-select">
                            <option value="">None</option>
                            @foreach($leads as $lead)
                                <option value="{{ $lead->id }}">{{ $lead->lead_code }} — {{ $lead->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Related Deal</label>
                        <select name="deal_id" class="form-select">
                            <option value="">None</option>
                            @foreach($deals as $deal)
                                <option value="{{ $deal->id }}">{{ $deal->deal_code }} — {{ $deal->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label">Assigned To</label>
                            <select name="assigned_to" class="form-select">
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Due Date &amp; Time</label>
                            <input type="datetime-local" name="due_at" class="form-control" value="{{ now()->addDay()->format('Y-m-d\TH:i') }}">
                        </div>
                    </div>
                    <div class="mb-0">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Schedule</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    const actBaseUrl = @json(url('/admin/crm/activities'));

    const table = $('#crmActivitiesTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: @json(route('admin.crm.activities.data')),
        columns: [
            { data: 'type', name: 'type', render: d => `<span class="badge bg-light-primary text-primary text-uppercase">${d}</span>` },
            { data: 'subject', name: 'subject' },
            { data: 'related', name: 'related', orderable: false },
            { data: 'assignee_name', name: 'assignee_name', orderable: false },
            { data: 'due_at', name: 'due_at' },
            {
                data: 'status',
                name: 'status',
                render: d => d === 'completed'
                    ? '<span class="badge bg-success">COMPLETED</span>'
                    : '<span class="badge bg-warning text-dark">OPEN</span>'
            },
            {
                data: null,
                orderable: false,
                searchable: false,
                render: row => row.status === 'completed'
                    ? '<span class="text-muted small">Done</span>'
                    : `<button type="button" class="btn btn-sm btn-outline-success btn-complete-activity" data-id="${row.id}"><i class="bx bx-check me-1"></i>Complete</button>`
            }
        ]
    });

    document.getElementById('createActivityForm').addEventListener('submit', async function (e) {
        e.preventDefault();
        const fd = new FormData(this);
        try {
            const res = await fetch(@json(route('admin.crm.activities.store')), {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                body: JSON.stringify({
                    type: fd.get('type'),
                    subject: fd.get('subject'),
                    lead_id: fd.get('lead_id') || null,
                    deal_id: fd.get('deal_id') || null,
                    assigned_to: fd.get('assigned_to') || null,
                    due_at: fd.get('due_at') || null,
                    notes: fd.get('notes') || null
                })
            });
            const data = await res.json();
            if (!res.ok) throw new Error(data.message || 'Failed to schedule activity.');
            bootstrap.Modal.getInstance(document.getElementById('createActivityModal')).hide();
            this.reset();
            Swal.fire('Scheduled', data.message, 'success');
            table.ajax.reload(null, false);
        } catch (err) {
            Swal.fire('Error', err.message, 'error');
        }
    });

    document.getElementById('crmActivitiesTable').addEventListener('click', async function (e) {
        const btn = e.target.closest('.btn-complete-activity');
        if (!btn) return;
        try {
            const res = await fetch(`${actBaseUrl}/${btn.dataset.id}/complete`, {
                method: 'POST',
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken }
            });
            const data = await res.json();
            if (!res.ok) throw new Error(data.message || 'Failed to complete activity.');
            Swal.fire('Completed', data.message, 'success');
            table.ajax.reload(null, false);
        } catch (err) {
            Swal.fire('Error', err.message, 'error');
        }
    });
});
</script>
@endpush
