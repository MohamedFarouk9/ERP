@extends('admin.layouts.app')
@section('title', 'Payments')

@section('content')
<div class="wrapper">
    <div class="page-wrapper">
        <div class="page-content">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <h4 class="mb-0">Payments & Payment Methods</h4>
                    <small class="text-muted">Manage payment channels, record transactions, and mark settlements completed</small>
                </div>
                <div class="d-flex gap-2">
                    @can('payments.methods.create')
                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#createMethodModal">
                            <i class="bx bx-wallet"></i> Add Method
                        </button>
                    @endcan
                    @can('payments.transactions.create')
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createPaymentModal">
                            <i class="bx bx-plus"></i> Record Payment
                        </button>
                    @endcan
                </div>
            </div>

            <!-- Payment Methods Row -->
            <div class="row mb-3">
                @foreach($methods as $method)
                    <div class="col-md-3 mb-2">
                        <div class="card radius-10 h-100">
                            <div class="card-body d-flex align-items-start justify-content-between">
                                <div>
                                    <h6 class="mb-1">{{ $method->name }}</h6>
                                    <small class="text-muted d-block"><code>{{ $method->code }}</code> · {{ ucfirst(str_replace('_', ' ', $method->type)) }}</small>
                                    <span class="badge mt-1 {{ $method->is_active ? 'bg-success' : 'bg-secondary' }}">{{ $method->is_active ? 'Active' : 'Inactive' }}</span>
                                </div>
                                @can('payments.methods.create')
                                    <div class="d-flex gap-1">
                                        <button class="btn btn-sm btn-outline-primary btn-edit-method"
                                            data-id="{{ $method->id }}"
                                            data-code="{{ $method->code }}"
                                            data-name="{{ $method->name }}"
                                            data-type="{{ $method->type }}"
                                            data-account="{{ $method->account_id }}"
                                            data-active="{{ $method->is_active ? 1 : 0 }}">
                                            <i class="bx bx-edit"></i>
                                        </button>
                                        <form class="d-inline ajax-delete-form" method="POST" action="{{ route('admin.payments.methods.destroy', $method->id) }}" data-title="Delete {{ $method->code }}?">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bx bx-trash"></i></button>
                                        </form>
                                    </div>
                                @endcan
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Payments DataTable -->
            <div class="card radius-10">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="paymentsDataTable" class="table table-striped table-bordered align-middle w-100">
                            <thead class="table-light">
                                <tr>
                                    <th>Payment #</th>
                                    <th>Method</th>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Reference</th>
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

<!-- Create Payment Method Modal -->
<div class="modal fade" id="createMethodModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content ajax-form" method="POST" action="{{ route('admin.payments.methods.store') }}">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Add Payment Method</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body row g-3">
                <div class="col-md-5">
                    <label class="form-label">Code *</label>
                    <input type="text" name="code" class="form-control" required placeholder="STRIPE-01">
                </div>
                <div class="col-md-7">
                    <label class="form-label">Name *</label>
                    <input type="text" name="name" class="form-control" required placeholder="Stripe Card Gateway">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Type *</label>
                    <select name="type" class="form-select" required>
                        <option value="cash">Cash</option>
                        <option value="bank_transfer">Bank Transfer</option>
                        <option value="card">Card</option>
                        <option value="cheque">Cheque</option>
                        <option value="wallet">Wallet</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Linked Ledger Account</label>
                    <select name="account_id" class="form-select">
                        <option value="">-- Optional --</option>
                        @foreach($accounts as $acc)
                            <option value="{{ $acc->id }}">{{ $acc->code }} - {{ $acc->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Create Method</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Payment Method Modal -->
<div class="modal fade" id="editMethodModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="editMethodForm" class="modal-content ajax-form" method="POST" action="">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title">Edit Payment Method</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body row g-3">
                <div class="col-md-5">
                    <label class="form-label">Code *</label>
                    <input type="text" name="code" id="em_code" class="form-control" required>
                </div>
                <div class="col-md-7">
                    <label class="form-label">Name *</label>
                    <input type="text" name="name" id="em_name" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Type *</label>
                    <select name="type" id="em_type" class="form-select" required>
                        <option value="cash">Cash</option>
                        <option value="bank_transfer">Bank Transfer</option>
                        <option value="card">Card</option>
                        <option value="cheque">Cheque</option>
                        <option value="wallet">Wallet</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Status</label>
                    <select name="is_active" id="em_active" class="form-select">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">Linked Ledger Account</label>
                    <select name="account_id" id="em_account" class="form-select">
                        <option value="">-- Optional --</option>
                        @foreach($accounts as $acc)
                            <option value="{{ $acc->id }}">{{ $acc->code }} - {{ $acc->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Update Method</button>
            </div>
        </form>
    </div>
</div>

<!-- Record Payment Modal -->
<div class="modal fade" id="createPaymentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form class="modal-content ajax-form" method="POST" action="{{ route('admin.payments.store') }}">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Record New Payment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body row g-3">
                <div class="col-md-4">
                    <label class="form-label">Payment # *</label>
                    <input type="text" name="payment_number" class="form-control" required value="PAY-{{ date('YmdHis') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Method *</label>
                    <select name="payment_method_id" class="form-select" required>
                        @foreach($methods->where('is_active', true) as $m)
                            <option value="{{ $m->id }}">{{ $m->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Date *</label>
                    <input type="date" name="payment_date" class="form-control" required value="{{ date('Y-m-d') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Amount (Cents) *</label>
                    <input type="number" name="amount" class="form-control" required min="1" value="10000">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Customer</label>
                    <select name="customer_id" class="form-select">
                        <option value="">-- Optional --</option>
                        @foreach($customers as $c)
                            <option value="{{ $c->id }}">{{ $c->code }} - {{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Invoice</label>
                    <select name="invoice_id" class="form-select">
                        <option value="">-- Optional --</option>
                        @foreach($invoices as $inv)
                            <option value="{{ $inv->id }}">{{ $inv->invoice_number }} (${{ number_format($inv->total_amount / 100, 2) }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">External Reference</label>
                    <input type="text" name="reference" class="form-control" placeholder="e.g. WIRE-9921">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Notes</label>
                    <input type="text" name="notes" class="form-control">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Record Payment</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    var baseUrl = "{{ url('/admin/payments') }}";

    window.erpActiveDataTable = $('#paymentsDataTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('admin.payments.data') }}",
        pageLength: 25,
        order: [[2, 'desc']],
        columns: [
            { data: 'payment_number', render: d => `<strong>${d}</strong>` },
            { data: 'method_name', orderable: false },
            { data: 'payment_date' },
            { data: 'amount', render: d => '$' + (d / 100).toFixed(2) },
            { data: 'reference' },
            {
                data: 'status',
                render: d => {
                    let cls = d === 'completed' ? 'bg-success' : (d === 'pending' ? 'bg-warning text-dark' : (d === 'refunded' ? 'bg-info text-dark' : 'bg-danger'));
                    return `<span class="badge ${cls} text-uppercase">${d}</span>`;
                }
            },
            {
                data: null,
                orderable: false,
                searchable: false,
                className: 'text-end text-nowrap',
                render: function(row) {
                    if (row.status === 'pending') {
                        return `
                            <form class="d-inline ajax-action-form" method="POST" action="${baseUrl}/${row.id}/complete" data-title="Complete ${row.payment_number}?" data-confirm-text="Yes, complete!">
                                <button type="submit" class="btn btn-sm btn-outline-success"><i class="bx bx-check"></i> Complete</button>
                            </form>
                            <form class="d-inline ajax-delete-form" method="POST" action="${baseUrl}/${row.id}" data-title="Delete ${row.payment_number}?">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bx bx-trash"></i></button>
                            </form>
                        `;
                    }
                    return '<span class="text-muted small">Settled</span>';
                }
            }
        ]
    });

    $(document).on('click', '.btn-edit-method', function() {
        var id = $(this).data('id');
        $('#editMethodForm').attr('action', baseUrl + '/methods/' + id);
        $('#em_code').val($(this).data('code'));
        $('#em_name').val($(this).data('name'));
        $('#em_type').val($(this).data('type'));
        $('#em_account').val($(this).data('account') || '');
        $('#em_active').val($(this).data('active'));
        new bootstrap.Modal(document.getElementById('editMethodModal')).show();
    });
});
</script>
@endpush
