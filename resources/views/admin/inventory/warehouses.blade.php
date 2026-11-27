@extends('admin.layouts.app')
@section('title', __('Warehouses'))

@section('content')
<div class="wrapper">
    <div class="page-wrapper">
        <div class="page-content">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <h4 class="mb-0">{{ __('Warehouses') }}</h4>
                </div>
                @can('inventory.warehouses.create')
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createWarehouseModal">
                        <i class="bx bx-plus"></i> {{ __('Add Warehouse') }}
                    </button>
                @endcan
            </div>

            <div class="card radius-10">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered align-middle erp-datatable w-100">
                            <thead class="table-light">
                                <tr>
                                    <th>{{ __('Code') }}</th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Address') }}</th>
                                    <th>{{ __('Tracked Items') }}</th>
                                    <th>{{ __('Default') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th class="text-end">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($warehouses as $warehouse)
                                    <tr>
                                        <td><code>{{ $warehouse->code }}</code></td>
                                        <td><strong>{{ $warehouse->name }}</strong></td>
                                        <td>{{ $warehouse->address ?? '-' }}</td>
                                        <td>{{ $warehouse->stock_levels_count }}</td>
                                        <td>{!! $warehouse->is_default ? '<span class="badge bg-info text-dark">Default</span>' : '-' !!}</td>
                                        <td>
                                            <span class="badge {{ $warehouse->is_active ? 'bg-success' : 'bg-secondary' }}">
                                                {{ $warehouse->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td class="text-end text-nowrap">
                                            @can('inventory.warehouses.create')
                                                <button class="btn btn-sm btn-outline-primary btn-edit-warehouse"
                                                    data-id="{{ $warehouse->id }}"
                                                    data-code="{{ $warehouse->code }}"
                                                    data-name="{{ $warehouse->name }}"
                                                    data-address="{{ $warehouse->address }}"
                                                    data-default="{{ $warehouse->is_default ? 1 : 0 }}"
                                                    data-active="{{ $warehouse->is_active ? 1 : 0 }}">
                                                    <i class="bx bx-edit"></i>
                                                </button>
                                                <form class="d-inline ajax-delete-form" method="POST" action="{{ route('admin.warehouses.destroy', $warehouse->id) }}" data-title="Delete {{ $warehouse->code }}?">
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

<!-- Create Warehouse Modal -->
<div class="modal fade" id="createWarehouseModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content ajax-form" method="POST" action="{{ route('admin.warehouses.store') }}">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Add Warehouse') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body row g-3">
                <div class="col-md-4">
                    <label class="form-label">{{ __('Code *') }}</label>
                    <input type="text" name="code" class="form-control" required placeholder="WH-01">
                </div>
                <div class="col-md-8">
                    <label class="form-label">{{ __('Name *') }}</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="col-12">
                    <label class="form-label">{{ __('Address') }}</label>
                    <input type="text" name="address" class="form-control">
                </div>
                <div class="col-12">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_default" value="1" id="cw_default">
                        <label class="form-check-label" for="cw_default">Set as Default Warehouse</label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __("Cancel") }}</button>
                <button type="submit" class="btn btn-primary">{{ __('Create Warehouse') }}</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Warehouse Modal -->
<div class="modal fade" id="editWarehouseModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="editWarehouseForm" class="modal-content ajax-form" method="POST" action="">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Edit Warehouse') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body row g-3">
                <div class="col-md-4">
                    <label class="form-label">{{ __('Code *') }}</label>
                    <input type="text" name="code" id="ew_code" class="form-control" required>
                </div>
                <div class="col-md-8">
                    <label class="form-label">{{ __('Name *') }}</label>
                    <input type="text" name="name" id="ew_name" class="form-control" required>
                </div>
                <div class="col-12">
                    <label class="form-label">{{ __('Address') }}</label>
                    <input type="text" name="address" id="ew_address" class="form-control">
                </div>
                <div class="col-md-6">
                    <label class="form-label">{{ __('Default') }}</label>
                    <select name="is_default" id="ew_default" class="form-select">
                        <option value="0">{{ __('No') }}</option>
                        <option value="1">{{ __('Yes') }}</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">{{ __('Status') }}</label>
                    <select name="is_active" id="ew_active" class="form-select">
                        <option value="1">{{ __('Active') }}</option>
                        <option value="0">{{ __('Inactive') }}</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __("Cancel") }}</button>
                <button type="submit" class="btn btn-primary">{{ __('Update Warehouse') }}</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).on('click', '.btn-edit-warehouse', function() {
    var id = $(this).data('id');
    $('#editWarehouseForm').attr('action', "{{ url('/admin/warehouses') }}/" + id);
    $('#ew_code').val($(this).data('code'));
    $('#ew_name').val($(this).data('name'));
    $('#ew_address').val($(this).data('address') || '');
    $('#ew_default').val($(this).data('default'));
    $('#ew_active').val($(this).data('active'));
    new bootstrap.Modal(document.getElementById('editWarehouseModal')).show();
});
</script>
@endpush
