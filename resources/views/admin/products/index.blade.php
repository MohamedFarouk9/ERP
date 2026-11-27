@extends('admin.layouts.app')
@section('title', __('Inventory Products'))

@section('content')
<div class="wrapper">
    <div class="page-wrapper">
        <div class="page-content">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <h4 class="mb-0">{{ __('Inventory Products') }}</h4>
                </div>
                <div class="d-flex gap-2">
                    @can('inventory.products.create')
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createProductModal">
                            <i class="bx bx-plus"></i> {{ __('Add Product') }}
                        </button>
                    @endcan
                </div>
            </div>

            <div class="card radius-10">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="productsDataTable" class="table table-striped table-bordered align-middle w-100">
                            <thead class="table-light">
                                <tr>
                                    <th>{{ __('SKU') }}</th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Category') }}</th>
                                    <th>{{ __('Cost') }}</th>
                                    <th>{{ __('Selling Price') }}</th>
                                    <th>{{ __('On Hand') }}</th>
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
</div>

<!-- Create Product Modal -->
<div class="modal fade" id="createProductModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form class="modal-content ajax-form" method="POST" action="{{ route('admin.products.store') }}">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Add New Product') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body row g-3">
                <div class="col-md-4">
                    <label class="form-label">{{ __('SKU *') }}</label>
                    <input type="text" name="sku" class="form-control" required placeholder="SKU-001">
                </div>
                <div class="col-md-8">
                    <label class="form-label">{{ __('Product Name *') }}</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">{{ __('Category') }}</label>
                    <select name="category_id" class="form-select">
                        <option value="">{{ __('-- None --') }}</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">{{ __('Unit') }}</label>
                    <input type="text" name="unit" class="form-control" value="pcs">
                </div>
                <div class="col-md-3">
                    <label class="form-label">{{ __('Reorder Level') }}</label>
                    <input type="number" name="reorder_level" class="form-control" value="10" min="0">
                </div>
                <div class="col-md-6">
                    <label class="form-label">{{ __('Cost Price (Minor Units / Cents) *') }}</label>
                    <input type="number" name="cost_price" class="form-control" value="0" min="0" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">{{ __('Selling Price (Minor Units / Cents) *') }}</label>
                    <input type="number" name="selling_price" class="form-control" value="0" min="0" required>
                </div>
                <div class="col-12">
                    <label class="form-label">{{ __('Description') }}</label>
                    <textarea name="description" class="form-control" rows="2"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __("Cancel") }}</button>
                <button type="submit" class="btn btn-primary">{{ __('Create Product') }}</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Product Modal -->
<div class="modal fade" id="editProductModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form id="editProductForm" class="modal-content ajax-form" method="POST" action="">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Edit Product') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body row g-3">
                <div class="col-md-4">
                    <label class="form-label">{{ __('SKU *') }}</label>
                    <input type="text" name="sku" id="edit_sku" class="form-control" required>
                </div>
                <div class="col-md-8">
                    <label class="form-label">{{ __('Product Name *') }}</label>
                    <input type="text" name="name" id="edit_name" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">{{ __('Category') }}</label>
                    <select name="category_id" id="edit_category_id" class="form-select">
                        <option value="">{{ __('-- None --') }}</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">{{ __('Unit') }}</label>
                    <input type="text" name="unit" id="edit_unit" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label">{{ __('Reorder Level') }}</label>
                    <input type="number" name="reorder_level" id="edit_reorder_level" class="form-control" min="0">
                </div>
                <div class="col-md-6">
                    <label class="form-label">{{ __('Cost Price (Cents) *') }}</label>
                    <input type="number" name="cost_price" id="edit_cost_price" class="form-control" min="0" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">{{ __('Selling Price (Cents) *') }}</label>
                    <input type="number" name="selling_price" id="edit_selling_price" class="form-control" min="0" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">{{ __('Status') }}</label>
                    <select name="is_active" id="edit_is_active" class="form-select">
                        <option value="1">{{ __('Active') }}</option>
                        <option value="0">{{ __('Inactive') }}</option>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">{{ __('Description') }}</label>
                    <textarea name="description" id="edit_description" class="form-control" rows="2"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __("Cancel") }}</button>
                <button type="submit" class="btn btn-primary">{{ __('Update Product') }}</button>
            </div>
        </form>
    </div>
</div>

<!-- Stock Movement Modal -->
<div class="modal fade" id="stockMovementModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content ajax-form" method="POST" action="{{ route('admin.stock-movements.store') }}">
            @csrf
            <input type="hidden" name="product_id" id="sm_product_id">
            <div class="modal-header">
                <h5 class="modal-title">Record Stock Movement: <span id="sm_product_label"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body row g-3">
                <div class="col-12">
                    <label class="form-label">{{ __('Warehouse *') }}</label>
                    <select name="warehouse_id" class="form-select" required>
                        @foreach($warehouses as $wh)
                            <option value="{{ $wh->id }}">{{ $wh->code }} - {{ $wh->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">{{ __('Movement Type *') }}</label>
                    <select name="type" class="form-select" required>
                        <option value="in">{{ __('Stock IN (+)') }}</option>
                        <option value="out">{{ __('Stock OUT (-)') }}</option>
                        <option value="adjustment">{{ __('Adjustment (+/-)') }}</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">{{ __('Quantity *') }}</label>
                    <input type="number" name="quantity" class="form-control" required value="1">
                </div>
                <div class="col-12">
                    <label class="form-label">{{ __('Notes') }}</label>
                    <input type="text" name="notes" class="form-control" placeholder="e.g. Supplier delivery / Audit adjustment">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __("Cancel") }}</button>
                <button type="submit" class="btn btn-success">{{ __('Record Movement') }}</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    var updateBaseUrl = "{{ url('/admin/products') }}";

    window.erpActiveDataTable = $('#productsDataTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('admin.products.data') }}",
        pageLength: 25,
        columns: [
            { data: 'sku', render: d => `<code>${d}</code>` },
            { data: 'name', render: d => `<strong>${d}</strong>` },
            { data: 'category_name', orderable: false },
            { data: 'cost_price', render: d => '$' + (d / 100).toFixed(2) },
            { data: 'selling_price', render: d => '$' + (d / 100).toFixed(2) },
            { data: 'stock_on_hand', orderable: false, render: (d, t, row) => `${d} ${row.unit}` },
            { data: 'is_active', render: d => d ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-secondary">Inactive</span>' },
            {
                data: null,
                orderable: false,
                searchable: false,
                className: 'text-end text-nowrap',
                render: function(row) {
                    let rowJson = encodeURIComponent(JSON.stringify(row));
                    return `
                        <button class="btn btn-sm btn-outline-success btn-stock-move" data-id="${row.id}" data-sku="${row.sku}" title="Stock Movement"><i class="bx bx-transfer-alt"></i></button>
                        <button class="btn btn-sm btn-outline-primary btn-edit-product" data-row="${rowJson}" title="Edit"><i class="bx bx-edit"></i></button>
                        <form class="d-inline ajax-delete-form" method="POST" action="${updateBaseUrl}/${row.id}" data-title="Delete ${row.sku}?">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete"><i class="bx bx-trash"></i></button>
                        </form>
                    `;
                }
            }
        ]
    });

    $(document).on('click', '.btn-edit-product', function() {
        var row = JSON.parse(decodeURIComponent($(this).data('row')));
        $('#editProductForm').attr('action', updateBaseUrl + '/' + row.id);
        $('#edit_sku').val(row.sku);
        $('#edit_name').val(row.name);
        $('#edit_category_id').val(row.category_id || '');
        $('#edit_unit').val(row.unit);
        $('#edit_reorder_level').val(row.reorder_level);
        $('#edit_cost_price').val(row.cost_price);
        $('#edit_selling_price').val(row.selling_price);
        $('#edit_is_active').val(row.is_active ? '1' : '0');
        $('#edit_description').val(row.description || '');
        new bootstrap.Modal(document.getElementById('editProductModal')).show();
    });

    $(document).on('click', '.btn-stock-move', function() {
        $('#sm_product_id').val($(this).data('id'));
        $('#sm_product_label').text($(this).data('sku'));
        new bootstrap.Modal(document.getElementById('stockMovementModal')).show();
    });
});
</script>
@endpush
