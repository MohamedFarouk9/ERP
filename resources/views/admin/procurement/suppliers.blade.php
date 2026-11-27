@extends('admin.layouts.app')
@section('title', __('Suppliers'))

@section('content')
<div class="wrapper">
    <div class="page-wrapper">
        <div class="page-content">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <h4 class="mb-0">{{ __('Suppliers') }}</h4>
                </div>
                @can('procurement.suppliers.create')
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createSupplierModal">
                        <i class="bx bx-plus"></i> {{ __('Add Supplier') }}
                    </button>
                @endcan
            </div>

            <div class="card radius-10">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="suppliersDataTable" class="table table-striped table-bordered align-middle w-100">
                            <thead class="table-light">
                                <tr>
                                    <th>{{ __('Code') }}</th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Email') }}</th>
                                    <th>{{ __('Terms') }}</th>
                                    <th>{{ __('POs') }}</th>
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

<!-- Create Supplier Modal -->
<div class="modal fade" id="createSupplierModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form class="modal-content ajax-form" method="POST" action="{{ route('admin.procurement.suppliers.store') }}">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Add Supplier') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body row g-3">
                <div class="col-md-4"><label class="form-label">{{ __('Code *') }}</label><input type="text" name="code" class="form-control" required placeholder="SUP-100"></div>
                <div class="col-md-8"><label class="form-label">{{ __('Supplier Name *') }}</label><input type="text" name="name" class="form-control" required></div>
                <div class="col-md-6"><label class="form-label">{{ __('Email') }}</label><input type="email" name="email" class="form-control"></div>
                <div class="col-md-6"><label class="form-label">{{ __('Phone') }}</label><input type="text" name="phone" class="form-control"></div>
                <div class="col-md-6"><label class="form-label">{{ __('Tax Number') }}</label><input type="text" name="tax_number" class="form-control"></div>
                <div class="col-md-6"><label class="form-label">{{ __('Payment Terms (Days)') }}</label><input type="number" name="payment_terms_days" class="form-control" value="30" min="0"></div>
                <div class="col-12"><label class="form-label">{{ __('Address') }}</label><input type="text" name="address" class="form-control"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __("Cancel") }}</button>
                <button type="submit" class="btn btn-primary">{{ __('Create Supplier') }}</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Supplier Modal -->
<div class="modal fade" id="editSupplierModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form id="editSupplierForm" class="modal-content ajax-form" method="POST" action="">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Edit Supplier') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body row g-3">
                <div class="col-md-4"><label class="form-label">{{ __('Code *') }}</label><input type="text" name="code" id="es_code" class="form-control" required></div>
                <div class="col-md-8"><label class="form-label">{{ __('Name *') }}</label><input type="text" name="name" id="es_name" class="form-control" required></div>
                <div class="col-md-6"><label class="form-label">{{ __('Email') }}</label><input type="email" name="email" id="es_email" class="form-control"></div>
                <div class="col-md-6"><label class="form-label">{{ __('Phone') }}</label><input type="text" name="phone" id="es_phone" class="form-control"></div>
                <div class="col-md-4"><label class="form-label">{{ __('Tax Number') }}</label><input type="text" name="tax_number" id="es_tax" class="form-control"></div>
                <div class="col-md-4"><label class="form-label">{{ __('Terms (Days)') }}</label><input type="number" name="payment_terms_days" id="es_terms" class="form-control" min="0"></div>
                <div class="col-md-4">
                    <label class="form-label">{{ __('Status') }}</label>
                    <select name="is_active" id="es_active" class="form-select"><option value="1">{{ __('Active') }}</option><option value="0">{{ __('Inactive') }}</option></select>
                </div>
                <div class="col-12"><label class="form-label">{{ __('Address') }}</label><input type="text" name="address" id="es_address" class="form-control"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __("Cancel") }}</button>
                <button type="submit" class="btn btn-primary">{{ __('Update Supplier') }}</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    var baseUrl = "{{ url('/admin/procurement/suppliers') }}";

    window.erpActiveDataTable = $('#suppliersDataTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('admin.procurement.suppliers.data') }}",
        pageLength: 25,
        columns: [
            { data: 'code', render: d => `<code>${d}</code>` },
            { data: 'name', render: d => `<strong>${d}</strong>` },
            { data: 'email', render: d => d || '-' },
            { data: 'payment_terms_days', render: d => `Net ${d}d` },
            { data: 'purchase_orders_count' },
            { data: 'is_active', render: d => d ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-secondary">Inactive</span>' },
            {
                data: null,
                orderable: false,
                searchable: false,
                className: 'text-end text-nowrap',
                render: function(row) {
                    let rowJson = encodeURIComponent(JSON.stringify(row));
                    return `
                        <button class="btn btn-sm btn-outline-primary btn-edit-sup" data-row="${rowJson}"><i class="bx bx-edit"></i></button>
                        <form class="d-inline ajax-delete-form" method="POST" action="${baseUrl}/${row.id}" data-title="Delete ${row.code}?">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bx bx-trash"></i></button>
                        </form>
                    `;
                }
            }
        ]
    });

    $(document).on('click', '.btn-edit-sup', function() {
        var r = JSON.parse(decodeURIComponent($(this).data('row')));
        $('#editSupplierForm').attr('action', baseUrl + '/' + r.id);
        $('#es_code').val(r.code);
        $('#es_name').val(r.name);
        $('#es_email').val(r.email);
        $('#es_phone').val(r.phone);
        $('#es_tax').val(r.tax_number);
        $('#es_terms').val(r.payment_terms_days);
        $('#es_active').val(r.is_active ? '1' : '0');
        $('#es_address').val(r.address);
        new bootstrap.Modal(document.getElementById('editSupplierModal')).show();
    });
});
</script>
@endpush
