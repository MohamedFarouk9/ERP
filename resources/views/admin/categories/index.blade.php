@extends('admin.layouts.app')
@section('title', 'Product Categories')

@section('content')
<div class="wrapper">
    <div class="page-wrapper">
        <div class="page-content">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <h4 class="mb-0">Product Categories</h4>
                    <small class="text-muted">Hierarchical product catalog categories</small>
                </div>
                @can('inventory.categories.create')
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createCategoryModal">
                        <i class="bx bx-plus"></i> Add Category
                    </button>
                @endcan
            </div>

            <div class="card radius-10">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered align-middle erp-datatable w-100">
                            <thead class="table-light">
                                <tr>
                                    <th>Name</th>
                                    <th>Slug</th>
                                    <th>Parent</th>
                                    <th>Products</th>
                                    <th>Status</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($categories as $category)
                                    <tr>
                                        <td><strong>{{ $category->name }}</strong></td>
                                        <td><code>{{ $category->slug }}</code></td>
                                        <td>{{ $category->parent?->name ?? '-' }}</td>
                                        <td>{{ $category->products_count }}</td>
                                        <td>
                                            <span class="badge {{ $category->is_active ? 'bg-success' : 'bg-secondary' }}">
                                                {{ $category->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td class="text-end text-nowrap">
                                            @can('inventory.categories.create')
                                                <button class="btn btn-sm btn-outline-primary btn-edit-category"
                                                    data-id="{{ $category->id }}"
                                                    data-name="{{ $category->name }}"
                                                    data-slug="{{ $category->slug }}"
                                                    data-parent="{{ $category->parent_id }}"
                                                    data-active="{{ $category->is_active ? 1 : 0 }}"
                                                    data-description="{{ $category->description }}">
                                                    <i class="bx bx-edit"></i>
                                                </button>
                                                <form class="d-inline ajax-delete-form" method="POST" action="{{ route('admin.categories.destroy', $category->id) }}" data-title="Delete {{ $category->name }}?">
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
</div>

<!-- Create Category Modal -->
<div class="modal fade" id="createCategoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content ajax-form" method="POST" action="{{ route('admin.categories.store') }}">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Add Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body row g-3">
                <div class="col-12">
                    <label class="form-label">Name *</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="col-12">
                    <label class="form-label">Slug (optional)</label>
                    <input type="text" name="slug" class="form-control" placeholder="auto-generated-from-name">
                </div>
                <div class="col-12">
                    <label class="form-label">Parent Category</label>
                    <select name="parent_id" class="form-select">
                        <option value="">-- Root Category --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="2"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Create Category</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Category Modal -->
<div class="modal fade" id="editCategoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="editCategoryForm" class="modal-content ajax-form" method="POST" action="">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title">Edit Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body row g-3">
                <div class="col-12">
                    <label class="form-label">Name *</label>
                    <input type="text" name="name" id="ec_name" class="form-control" required>
                </div>
                <div class="col-12">
                    <label class="form-label">Slug</label>
                    <input type="text" name="slug" id="ec_slug" class="form-control">
                </div>
                <div class="col-12">
                    <label class="form-label">Parent Category</label>
                    <select name="parent_id" id="ec_parent" class="form-select">
                        <option value="">-- Root Category --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">Status</label>
                    <select name="is_active" id="ec_active" class="form-select">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">Description</label>
                    <textarea name="description" id="ec_description" class="form-control" rows="2"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Update Category</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).on('click', '.btn-edit-category', function() {
    var id = $(this).data('id');
    $('#editCategoryForm').attr('action', "{{ url('/admin/categories') }}/" + id);
    $('#ec_name').val($(this).data('name'));
    $('#ec_slug').val($(this).data('slug'));
    $('#ec_parent').val($(this).data('parent') || '');
    $('#ec_active').val($(this).data('active'));
    $('#ec_description').val($(this).data('description') || '');
    new bootstrap.Modal(document.getElementById('editCategoryModal')).show();
});
</script>
@endpush
