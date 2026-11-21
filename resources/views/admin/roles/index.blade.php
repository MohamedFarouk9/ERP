@extends('admin.layouts.app')
@section('title', 'Tenant Roles')

@section('content')
<div class="wrapper">
    <div class="page-wrapper">
        <div class="page-content">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <h4 class="mb-0">Tenant Roles & RBAC</h4>
                    <small class="text-muted">Manage roles and assign granular module permissions</small>
                </div>
                @can('users.roles.create')
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createRoleModal">
                        <i class="bx bx-plus"></i> Add Role
                    </button>
                @endcan
            </div>

            <div class="card radius-10">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered align-middle erp-datatable w-100">
                            <thead class="table-light">
                                <tr>
                                    <th>Role Name</th>
                                    <th>Slug</th>
                                    <th>Permissions</th>
                                    <th>Assigned Users</th>
                                    <th>Type</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($roles as $role)
                                    <tr>
                                        <td><strong>{{ $role->name }}</strong></td>
                                        <td><code>{{ $role->slug }}</code></td>
                                        <td>{{ $role->permissions_count }}</td>
                                        <td>{{ $role->users_count }}</td>
                                        <td>
                                            <span class="badge {{ $role->is_system ? 'bg-primary' : 'bg-secondary' }}">
                                                {{ $role->is_system ? 'System' : 'Custom' }}
                                            </span>
                                        </td>
                                        <td class="text-end text-nowrap">
                                            @can('users.roles.create')
                                                <button class="btn btn-sm btn-outline-primary btn-edit-role"
                                                    data-id="{{ $role->id }}"
                                                    data-name="{{ $role->name }}"
                                                    data-slug="{{ $role->slug }}"
                                                    data-description="{{ $role->description }}"
                                                    data-perms='@json($role->permissions->pluck("id"))'>
                                                    <i class="bx bx-edit"></i>
                                                </button>
                                                @if(! $role->is_system)
                                                    <form class="d-inline ajax-delete-form" method="POST" action="{{ route('admin.roles.destroy', $role->id) }}" data-title="Delete role {{ $role->name }}?">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bx bx-trash"></i></button>
                                                    </form>
                                                @endif
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Role Modal -->
<div class="modal fade" id="createRoleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form class="modal-content ajax-form" method="POST" action="{{ route('admin.roles.store') }}">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Create Role</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Role Name *</label>
                        <input type="text" name="name" class="form-control" required placeholder="Inventory Clerk">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Slug</label>
                        <input type="text" name="slug" class="form-control" placeholder="inventory-clerk">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Description</label>
                        <input type="text" name="description" class="form-control">
                    </div>
                </div>
                <h6 class="mb-2">Assign Permissions</h6>
                <div class="row">
                    @foreach($groupedPermissions as $mod => $perms)
                        <div class="col-md-6 mb-2">
                            <div class="border rounded p-2">
                                <strong class="text-uppercase small d-block mb-1">{{ $mod }}</strong>
                                @foreach($perms as $perm)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $perm->id }}" id="cp_{{ $perm->id }}">
                                        <label class="form-check-label small" for="cp_{{ $perm->id }}">{{ $perm->name }} (<code>{{ $perm->slug }}</code>)</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Create Role</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Role Modal -->
<div class="modal fade" id="editRoleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form id="editRoleForm" class="modal-content ajax-form" method="POST" action="">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title">Edit Role & Permissions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Role Name *</label>
                        <input type="text" name="name" id="er_name" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Slug</label>
                        <input type="text" name="slug" id="er_slug" class="form-control">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Description</label>
                        <input type="text" name="description" id="er_description" class="form-control">
                    </div>
                </div>
                <h6 class="mb-2">Permissions</h6>
                <div class="row">
                    @foreach($groupedPermissions as $mod => $perms)
                        <div class="col-md-6 mb-2">
                            <div class="border rounded p-2">
                                <strong class="text-uppercase small d-block mb-1">{{ $mod }}</strong>
                                @foreach($perms as $perm)
                                    <div class="form-check">
                                        <input class="form-check-input er-perm-cb" type="checkbox" name="permissions[]" value="{{ $perm->id }}" id="ep_{{ $perm->id }}">
                                        <label class="form-check-label small" for="ep_{{ $perm->id }}">{{ $perm->name }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Update Role</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).on('click', '.btn-edit-role', function() {
    var id = $(this).data('id');
    var perms = $(this).data('perms') || [];
    $('#editRoleForm').attr('action', "{{ url('/admin/roles') }}/" + id);
    $('#er_name').val($(this).data('name'));
    $('#er_slug').val($(this).data('slug'));
    $('#er_description').val($(this).data('description') || '');
    $('.er-perm-cb').prop('checked', false);
    perms.forEach(function(pid) {
        $('#ep_' + pid).prop('checked', true);
    });
    new bootstrap.Modal(document.getElementById('editRoleModal')).show();
});
</script>
@endpush
