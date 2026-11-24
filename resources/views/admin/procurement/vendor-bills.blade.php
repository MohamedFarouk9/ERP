@extends('admin.layouts.app')
@section('title', 'Vendor Bills')

@section('content')
<div class="wrapper">
    <div class="page-wrapper">
        <div class="page-content">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <h4 class="mb-0">Vendor Bills (Accounts Payable)</h4>
                    <small class="text-muted">Record supplier invoices, approve for payment, and track settlement</small>
                </div>
                @can('procurement.bills.create')
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createBillModal">
                        <i class="bx bx-plus"></i> Record Vendor Bill
                    </button>
                @endcan
            </div>

            <div class="card radius-10">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="billsDataTable" class="table table-striped table-bordered align-middle w-100">
                            <thead class="table-light">
                                <tr>
                                    <th>Bill #</th>
                                    <th>Supplier</th>
                                    <th>Bill Date</th>
                                    <th>Due Date</th>
                                    <th>Total</th>
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

<!-- Create Vendor Bill Modal -->
<div class="modal fade" id="createBillModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form class="modal-content ajax-form" method="POST" action="{{ route('admin.procurement.bills.store') }}">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Record Vendor Bill</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body row g-3">
                <div class="col-md-4"><label class="form-label">Bill # *</label><input type="text" name="bill_number" class="form-control" required value="BILL-{{ date('YmdHis') }}"></div>
                <div class="col-md-4">
                    <label class="form-label">Supplier *</label>
                    <select name="supplier_id" class="form-select" required>
                        @foreach($suppliers as $s)<option value="{{ $s->id }}">{{ $s->code }} - {{ $s->name }}</option>@endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Purchase Order (Optional)</label>
                    <select name="purchase_order_id" class="form-select">
                        <option value="">-- None --</option>
                        @foreach($purchaseOrders as $po)<option value="{{ $po->id }}">{{ $po->po_number }}</option>@endforeach
                    </select>
                </div>
                <div class="col-md-4"><label class="form-label">Bill Date *</label><input type="date" name="bill_date" class="form-control" required value="{{ date('Y-m-d') }}"></div>
                <div class="col-md-4"><label class="form-label">Due Date *</label><input type="date" name="due_date" class="form-control" required value="{{ date('Y-m-d', strtotime('+30 days')) }}"></div>
                <div class="col-md-4"><label class="form-label">Subtotal (Cents) *</label><input type="number" name="subtotal" class="form-control" required min="1" value="50000"></div>
                <div class="col-md-4"><label class="form-label">Tax (Cents)</label><input type="number" name="tax_amount" class="form-control" min="0" value="5000"></div>
                <div class="col-md-8"><label class="form-label">Notes</label><input type="text" name="notes" class="form-control"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Draft Bill</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    var baseUrl = "{{ url('/admin/procurement/vendor-bills') }}";

    window.erpActiveDataTable = $('#billsDataTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('admin.procurement.bills.data') }}",
        pageLength: 25,
        order: [[2, 'desc']],
        columns: [
            { data: 'bill_number', render: d => `<strong>${d}</strong>` },
            { data: 'supplier_name', orderable: false },
            { data: 'bill_date' },
            { data: 'due_date' },
            { data: 'total_amount', render: d => '$' + (d / 100).toFixed(2) },
            {
                data: 'status',
                render: d => {
                    let cls = d === 'paid' ? 'bg-success' : (d === 'approved' ? 'bg-primary' : 'bg-warning text-dark');
                    return `<span class="badge ${cls} text-uppercase">${d}</span>`;
                }
            },
            {
                data: null,
                orderable: false,
                searchable: false,
                className: 'text-end text-nowrap',
                render: function(row) {
                    if (row.status === 'draft') {
                        return `
                            <form class="d-inline ajax-action-form" method="POST" action="${baseUrl}/${row.id}/approve" data-title="Approve ${row.bill_number}?" data-confirm-text="Yes, approve!">
                                <button type="submit" class="btn btn-sm btn-outline-primary"><i class="bx bx-check"></i> Approve</button>
                            </form>
                            <form class="d-inline ajax-delete-form" method="POST" action="${baseUrl}/${row.id}" data-title="Delete ${row.bill_number}?">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bx bx-trash"></i></button>
                            </form>
                        `;
                    } else if (row.status === 'approved') {
                        return `
                            <form class="d-inline ajax-action-form" method="POST" action="${baseUrl}/${row.id}/pay" data-title="Mark ${row.bill_number} as Paid?" data-confirm-text="Yes, mark paid!">
                                <button type="submit" class="btn btn-sm btn-outline-success"><i class="bx bx-dollar"></i> Mark Paid</button>
                            </form>
                        `;
                    }
                    return '<span class="text-muted small">Paid</span>';
                }
            }
        ]
    });
});
</script>
@endpush
