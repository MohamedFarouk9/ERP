@extends('admin.layouts.app')
@section('title', 'Staff Directory')

@section('content')
<div class="page-wrapper">
    <div class="page-content">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <h4 class="mb-0">Staff Directory</h4>
                <small class="text-muted">Manage organization employees, active accounts, and assigned RBAC roles</small>
            </div>
            @can('users.roles.assign')
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createStaffModal">
                    <i class="bx bx-user-plus"></i> Add Staff Member
                </button>
            @endcan
        </div>

        <div class="card radius-10">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="staffDataTable" class="table table-striped table-bordered align-middle w-100">
                        <thead class="table-light">
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Assigned Role</th>
                                <th>Status</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Staff Modal -->
<div class="modal fade" id="createStaffModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content ajax-form" method="POST" action="{{ route('admin.organization.staff.store') }}">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Add Staff Member</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body row g-3">
                <div class="col-12"><label class="form-label">Full Name *</label><input type="text" name="name" class="form-control" required></div>
                <div class="col-md-6"><label class="form-label">Email *</label><input type="email" name="email" class="form-control" required></div>
                <div class="col-md-6"><label class="form-label">Phone</label><input type="text" name="phone" class="form-control"></div>
                <div class="col-md-6"><label class="form-label">Initial Password *</label><input type="password" name="password" class="form-control" required minlength="8"></div>
                <div class="col-md-6">
                    <label class="form-label">Primary Role</label>
                    <select name="role_id" class="form-select">
                        <option value="">-- Optional --</option>
                        @foreach($roles as $r)<option value="{{ $r->id }}">{{ $r->name }}</option>@endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Create Staff</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Staff Modal -->
<div class="modal fade" id="editStaffModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="editStaffForm" class="modal-content ajax-form" method="POST" action="">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title">Edit Staff Member</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body row g-3">
                <div class="col-12"><label class="form-label">Full Name *</label><input type="text" name="name" id="est_name" class="form-control" required></div>
                <div class="col-md-6"><label class="form-label">Email *</label><input type="email" name="email" id="est_email" class="form-control" required></div>
                <div class="col-md-6"><label class="form-label">Phone</label><input type="text" name="phone" id="est_phone" class="form-control"></div>
                <div class="col-md-6">
                    <label class="form-label">Primary Role</label>
                    <select name="role_id" id="est_role" class="form-select">
                        <option value="">-- Optional --</option>
                        @foreach($roles as $r)<option value="{{ $r->id }}">{{ $r->name }}</option>@endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Status</label>
                    <select name="is_active" id="est_active" class="form-select"><option value="1">Active</option><option value="0">Inactive</option></select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Update Staff</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    var baseUrl = "{{ url('/admin/organization/staff') }}";

    window.erpActiveDataTable = $('#staffDataTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('admin.organization.staff.data') }}",
        pageLength: 25,
        columns: [
            { data: 'name', render: d => `<strong>${d}</strong>` },
            { data: 'email' },
            { data: 'phone', render: d => d || '-' },
            { data: 'role_names', orderable: false, render: d => `<span class="badge rounded-pill bg-light-primary text-primary px-3">${d}</span>` },
            { data: 'is_active', render: d => d ? '<span class="badge rounded-pill bg-light-success text-success px-3">Active</span>' : '<span class="badge rounded-pill bg-light-secondary text-secondary px-3">Inactive</span>' },
            {
                data: null,
                orderable: false,
                searchable: false,
                className: 'text-end',
                render: function(row) {
                    let rowJson = encodeURIComponent(JSON.stringify(row));
                    return `<button class="btn btn-sm btn-outline-primary btn-edit-staff" data-row="${rowJson}"><i class="bx bx-edit"></i></button>`;
                }
            }
        ]
    });

    $(document).on('click', '.btn-edit-staff', function() {
        var r = JSON.parse(decodeURIComponent($(this).data('row')));
        $('#editStaffForm').attr('action', baseUrl + '/' + r.id);
        $('#est_name').val(r.name);
        $('#est_email').val(r.email);
        $('#est_phone').val(r.phone);
        $('#est_role').val(r.role_id || '');
        $('#est_active').val(r.is_active ? '1' : '0');
        new bootstrap.Modal(document.getElementById('editStaffModal')).show();
    });
});
</script>
@endpush
