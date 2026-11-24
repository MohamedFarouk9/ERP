@extends('admin.layouts.app')
@section('title', 'Departments')

@section('content')
<div class="page-wrapper">
    <div class="page-content">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <h4 class="mb-0">Departments</h4>
                <small class="text-muted">Manage organizational business units and cost centers</small>
            </div>
            @can('users.departments.create')
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createDeptModal">
                    <i class="bx bx-plus"></i> Add Department
                </button>
            @endcan
        </div>

        <div class="card radius-10">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered align-middle erp-datatable w-100">
                        <thead class="table-light">
                            <tr>
                                <th>Code</th>
                                <th>Department Name</th>
                                <th>Description</th>
                                <th>Teams</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($departments as $dept)
                                <tr>
                                    <td><code>{{ $dept->code }}</code></td>
                                    <td><strong>{{ $dept->name }}</strong></td>
                                    <td>{{ $dept->description ?? '-' }}</td>
                                    <td><span class="badge rounded-pill bg-light-info text-info px-3">{{ $dept->teams_count }} Teams</span></td>
                                    <td class="text-end text-nowrap">
                                        @can('users.departments.create')
                                            <button class="btn btn-sm btn-outline-primary btn-edit-dept"
                                                data-id="{{ $dept->id }}"
                                                data-code="{{ $dept->code }}"
                                                data-name="{{ $dept->name }}"
                                                data-desc="{{ $dept->description }}">
                                                <i class="bx bx-edit"></i>
                                            </button>
                                            <form class="d-inline ajax-delete-form" method="POST" action="{{ route('admin.organization.departments.destroy', $dept->id) }}" data-title="Delete {{ $dept->code }}?">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bx bx-trash"></i></button>
                                            </form>
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

<!-- Create Department Modal -->
<div class="modal fade" id="createDeptModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content ajax-form" method="POST" action="{{ route('admin.organization.departments.store') }}">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Add Department</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body row g-3">
                <div class="col-md-4"><label class="form-label">Code *</label><input type="text" name="code" class="form-control" required placeholder="HR"></div>
                <div class="col-md-8"><label class="form-label">Name *</label><input type="text" name="name" class="form-control" required placeholder="Human Resources"></div>
                <div class="col-12"><label class="form-label">Description</label><textarea name="description" class="form-control" rows="2"></textarea></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Create Department</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Department Modal -->
<div class="modal fade" id="editDeptModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="editDeptForm" class="modal-content ajax-form" method="POST" action="">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title">Edit Department</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body row g-3">
                <div class="col-md-4"><label class="form-label">Code *</label><input type="text" name="code" id="ed_code" class="form-control" required></div>
                <div class="col-md-8"><label class="form-label">Name *</label><input type="text" name="name" id="ed_name" class="form-control" required></div>
                <div class="col-12"><label class="form-label">Description</label><textarea name="description" id="ed_desc" class="form-control" rows="2"></textarea></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Update Department</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).on('click', '.btn-edit-dept', function() {
    $('#editDeptForm').attr('action', "{{ url('/admin/organization/departments') }}/" + $(this).data('id'));
    $('#ed_code').val($(this).data('code'));
    $('#ed_name').val($(this).data('name'));
    $('#ed_desc').val($(this).data('desc') || '');
    new bootstrap.Modal(document.getElementById('editDeptModal')).show();
});
</script>
@endpush
