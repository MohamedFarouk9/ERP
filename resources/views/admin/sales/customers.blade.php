@extends('admin.layouts.app')
@section('title', 'Customers')

@section('content')
<div class="wrapper">
    <div class="page-wrapper">
        <div class="page-content">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <h4 class="mb-0">Customers</h4>
                    <small class="text-muted">Manage B2B & retail customer accounts and credit limits</small>
                </div>
                @can('sales.customers.create')
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createCustomerModal">
                        <i class="bx bx-plus"></i> Add Customer
                    </button>
                @endcan
            </div>

            <div class="card radius-10">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="customersDataTable" class="table table-striped table-bordered align-middle w-100">
                            <thead class="table-light">
                                <tr>
                                    <th>Code</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Orders</th>
                                    <th>Invoices</th>
                                    <th>Credit Limit</th>
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
</div>

<!-- Create Customer Modal -->
<div class="modal fade" id="createCustomerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form class="modal-content ajax-form" method="POST" action="{{ route('admin.customers.store') }}">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Add New Customer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body row g-3">
                <div class="col-md-4">
                    <label class="form-label">Code *</label>
                    <input type="text" name="code" class="form-control" required placeholder="CUST-100">
                </div>
                <div class="col-md-8">
                    <label class="form-label">Customer Name *</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-control">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tax Number</label>
                    <input type="text" name="tax_number" class="form-control">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Credit Limit (Cents)</label>
                    <input type="number" name="credit_limit" class="form-control" value="0" min="0">
                </div>
                <div class="col-12">
                    <label class="form-label">Billing Address</label>
                    <input type="text" name="billing_address" class="form-control">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Create Customer</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Customer Modal -->
<div class="modal fade" id="editCustomerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form id="editCustomerForm" class="modal-content ajax-form" method="POST" action="">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title">Edit Customer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body row g-3">
                <div class="col-md-4">
                    <label class="form-label">Code *</label>
                    <input type="text" name="code" id="ecust_code" class="form-control" required>
                </div>
                <div class="col-md-8">
                    <label class="form-label">Customer Name *</label>
                    <input type="text" name="name" id="ecust_name" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" id="ecust_email" class="form-control">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" id="ecust_phone" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tax Number</label>
                    <input type="text" name="tax_number" id="ecust_tax" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Credit Limit (Cents)</label>
                    <input type="number" name="credit_limit" id="ecust_credit" class="form-control" min="0">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Status</label>
                    <select name="is_active" id="ecust_active" class="form-select">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">Billing Address</label>
                    <input type="text" name="billing_address" id="ecust_address" class="form-control">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Update Customer</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    var baseUrl = "{{ url('/admin/customers') }}";

    window.erpActiveDataTable = $('#customersDataTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('admin.customers.data') }}",
        pageLength: 25,
        columns: [
            { data: 'code', render: d => `<code>${d}</code>` },
            { data: 'name', render: d => `<strong>${d}</strong>` },
            { data: 'email', render: d => d || '-' },
            { data: 'sales_orders_count' },
            { data: 'invoices_count' },
            { data: 'credit_limit', render: d => '$' + (d / 100).toFixed(2) },
            { data: 'is_active', render: d => d ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-secondary">Inactive</span>' },
            {
                data: null,
                orderable: false,
                searchable: false,
                className: 'text-end text-nowrap',
                render: function(row) {
                    let rowJson = encodeURIComponent(JSON.stringify(row));
                    return `
                        <button class="btn btn-sm btn-outline-primary btn-edit-customer" data-row="${rowJson}"><i class="bx bx-edit"></i></button>
                        <form class="d-inline ajax-delete-form" method="POST" action="${baseUrl}/${row.id}" data-title="Delete ${row.code}?">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bx bx-trash"></i></button>
                        </form>
                    `;
                }
            }
        ]
    });

    $(document).on('click', '.btn-edit-customer', function() {
        var r = JSON.parse(decodeURIComponent($(this).data('row')));
        $('#editCustomerForm').attr('action', baseUrl + '/' + r.id);
        $('#ecust_code').val(r.code);
        $('#ecust_name').val(r.name);
        $('#ecust_email').val(r.email);
        $('#ecust_phone').val(r.phone);
        $('#ecust_tax').val(r.tax_number);
        $('#ecust_credit').val(r.credit_limit);
        $('#ecust_active').val(r.is_active ? '1' : '0');
        $('#ecust_address').val(r.billing_address);
        new bootstrap.Modal(document.getElementById('editCustomerModal')).show();
    });
});
</script>
@endpush
