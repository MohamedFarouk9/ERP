@extends('admin.layouts.app')
@section('title', 'Invoices')

@section('content')
<div class="wrapper">
    <div class="page-wrapper">
        <div class="page-content">
            <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                <div>
                    <h4 class="mb-0">Invoices</h4>
                    <small class="text-muted">Full invoicing lifecycle: draft, issue, print/PDF, quick payment collection & cancellation</small>
                </div>
                <div class="d-flex gap-2">
                    @can('sales.invoices.create')
                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#fromOrderModal">
                            <i class="bx bx-transfer"></i> Generate from Order
                        </button>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createInvoiceModal">
                            <i class="bx bx-plus"></i> New Invoice
                        </button>
                    @endcan
                </div>
            </div>

            <!-- Status Filter Tabs -->
            <ul class="nav nav-pills mb-3" id="invoiceStatusTabs">
                <li class="nav-item"><a class="nav-link active" href="#" data-status="">All</a></li>
                <li class="nav-item"><a class="nav-link" href="#" data-status="draft">Draft</a></li>
                <li class="nav-item"><a class="nav-link" href="#" data-status="issued">Issued</a></li>
                <li class="nav-item"><a class="nav-link" href="#" data-status="paid">Paid</a></li>
                <li class="nav-item"><a class="nav-link" href="#" data-status="overdue">Overdue</a></li>
                <li class="nav-item"><a class="nav-link" href="#" data-status="cancelled">Cancelled</a></li>
            </ul>

            <div class="card radius-10">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="invoicesDataTable" class="table table-striped table-bordered align-middle w-100">
                            <thead class="table-light">
                                <tr>
                                    <th>Invoice #</th>
                                    <th>Customer</th>
                                    <th>Invoice Date</th>
                                    <th>Due Date</th>
                                    <th>Total</th>
                                    <th>Paid</th>
                                    <th>Balance</th>
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

<!-- Generate Invoice from Sales Order Modal -->
<div class="modal fade" id="fromOrderModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="fromOrderForm" class="modal-content ajax-form" method="POST" action="">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Generate Invoice from Confirmed Order</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <label class="form-label">Select Confirmed Sales Order *</label>
                <select id="fromOrderSelect" class="form-select" required>
                    <option value="">-- Choose Order --</option>
                    @foreach($orders as $ord)
                        <option value="{{ $ord->id }}">{{ $ord->order_number }} — {{ $ord->customer?->name }} (${{ number_format($ord->total_amount / 100, 2) }})</option>
                    @endforeach
                </select>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Generate Draft Invoice</button>
            </div>
        </form>
    </div>
</div>

<!-- Create Invoice Modal -->
<div class="modal fade" id="createInvoiceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <form class="modal-content ajax-form" method="POST" action="{{ route('admin.invoices.store') }}">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Create Draft Invoice</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3 mb-3">
                    <div class="col-md-3">
                        <label class="form-label">Invoice # *</label>
                        <input type="text" name="invoice_number" class="form-control" required value="INV-{{ date('YmdHis') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Customer *</label>
                        <select name="customer_id" class="form-select" required>
                            @foreach($customers as $c)
                                <option value="{{ $c->id }}">{{ $c->code }} - {{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Invoice Date *</label>
                        <input type="date" name="invoice_date" class="form-control" required value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Due Date *</label>
                        <input type="date" name="due_date" class="form-control" required value="{{ date('Y-m-d', strtotime('+30 days')) }}">
                    </div>
                </div>

                <div class="d-flex align-items-center justify-content-between mb-2">
                    <h6 class="mb-0">Invoice Lines (Amounts in Cents)</h6>
                    <button type="button" id="addInvLineBtn" class="btn btn-sm btn-outline-primary"><i class="bx bx-plus"></i> Add Line</button>
                </div>

                <table class="table table-bordered align-middle" id="invLinesTable">
                    <thead class="table-light">
                        <tr>
                            <th>Description *</th>
                            <th style="width: 15%">Qty *</th>
                            <th style="width: 20%">Unit Price (Cents) *</th>
                            <th style="width: 20%">Tax (Cents)</th>
                            <th style="width: 50px"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><input type="text" name="items[0][description]" class="form-control" required placeholder="Service or Product"></td>
                            <td><input type="number" name="items[0][quantity]" class="form-control" value="1" min="1" required></td>
                            <td><input type="number" name="items[0][unit_price]" class="form-control" value="10000" min="0" required></td>
                            <td><input type="number" name="items[0][tax_amount]" class="form-control" value="1000" min="0"></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Draft Invoice</button>
            </div>
        </form>
    </div>
</div>

<!-- Quick Record Payment Modal -->
<div class="modal fade" id="quickPayModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content ajax-form" method="POST" action="{{ route('admin.payments.store') }}">
            @csrf
            <input type="hidden" name="invoice_id" id="qp_invoice_id">
            <input type="hidden" name="customer_id" id="qp_customer_id">
            <div class="modal-header">
                <h5 class="modal-title">Record Payment for <span id="qp_invoice_num"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body row g-3">
                <div class="col-md-6">
                    <label class="form-label">Payment # *</label>
                    <input type="text" name="payment_number" id="qp_pay_num" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Date *</label>
                    <input type="date" name="payment_date" class="form-control" required value="{{ date('Y-m-d') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Method *</label>
                    <select name="payment_method_id" class="form-select" required>
                        @foreach($paymentMethods as $pm)
                            <option value="{{ $pm->id }}">{{ $pm->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Amount (Cents) *</label>
                    <input type="number" name="amount" id="qp_amount" class="form-control" required min="1">
                </div>
                <div class="col-12">
                    <label class="form-label">Reference</label>
                    <input type="text" name="reference" class="form-control" placeholder="Bank transfer / receipt ref">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-success">Record Payment</button>
            </div>
        </form>
    </div>
</div>

<!-- View Invoice Modal -->
<div class="modal fade" id="viewInvoiceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Invoice: <span id="vi_number"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="mb-2"><strong>Customer:</strong> <span id="vi_customer"></span> | <strong>Status:</strong> <span id="vi_status" class="badge bg-primary text-uppercase"></span></p>
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Description</th>
                            <th class="text-end">Qty</th>
                            <th class="text-end">Unit Price</th>
                            <th class="text-end">Tax</th>
                            <th class="text-end">Line Total</th>
                        </tr>
                    </thead>
                    <tbody id="vi_items"></tbody>
                </table>
                <div class="d-flex justify-content-between align-items-center">
                    <a id="vi_print_btn" href="#" target="_blank" class="btn btn-outline-dark btn-sm"><i class="bx bx-printer"></i> Print / PDF</a>
                    <div class="fw-bold fs-5">Total: <span id="vi_total"></span></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    var baseUrl = "{{ url('/admin/invoices') }}";
    var ordersBaseUrl = "{{ url('/admin/orders') }}";
    var currentStatus = '';
    var invIdx = 1;

    window.erpActiveDataTable = $('#invoicesDataTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('admin.invoices.data') }}",
            data: function(d) { d.status = currentStatus; }
        },
        pageLength: 25,
        order: [[2, 'desc']],
        columns: [
            { data: 'invoice_number', render: d => `<strong>${d}</strong>` },
            { data: 'customer_name', orderable: false },
            { data: 'invoice_date' },
            { data: 'due_date' },
            { data: 'total_amount', render: d => '$' + (d / 100).toFixed(2) },
            { data: 'amount_paid', render: d => '$' + (d / 100).toFixed(2) },
            { data: 'balance_due', orderable: false, render: d => d > 0 ? `<span class="text-danger fw-bold">$${(d / 100).toFixed(2)}</span>` : '<span class="text-success">$0.00</span>' },
            {
                data: 'status',
                render: d => {
                    let cls = d === 'paid' ? 'bg-success' : (d === 'issued' ? 'bg-primary' : (d === 'overdue' ? 'bg-danger' : (d === 'cancelled' ? 'bg-secondary' : 'bg-warning text-dark')));
                    return `<span class="badge ${cls} text-uppercase">${d}</span>`;
                }
            },
            {
                data: null,
                orderable: false,
                searchable: false,
                className: 'text-end text-nowrap',
                render: function(row) {
                    let btns = `<button class="btn btn-sm btn-outline-info btn-view-inv" data-id="${row.id}" title="View"><i class="bx bx-show"></i></button> `;
                    btns += `<a href="${baseUrl}/${row.id}/print" target="_blank" class="btn btn-sm btn-outline-dark" title="Print / PDF"><i class="bx bx-printer"></i></a> `;
                    if (row.status === 'draft') {
                        btns += `
                            <form class="d-inline ajax-action-form" method="POST" action="${baseUrl}/${row.id}/issue" data-title="Issue ${row.invoice_number}?" data-text="This posts an AR/Revenue entry to Accounting." data-confirm-text="Yes, issue!">
                                <button type="submit" class="btn btn-sm btn-outline-success" title="Issue"><i class="bx bx-send"></i></button>
                            </form>
                            <form class="d-inline ajax-delete-form" method="POST" action="${baseUrl}/${row.id}" data-title="Delete ${row.invoice_number}?">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete"><i class="bx bx-trash"></i></button>
                            </form>
                        `;
                    } else if (row.status === 'issued' || row.status === 'overdue') {
                        if (row.balance_due > 0) {
                            btns += `<button class="btn btn-sm btn-outline-success btn-quick-pay" data-id="${row.id}" data-cust="${row.customer_id}" data-num="${row.invoice_number}" data-bal="${row.balance_due}" title="Record Payment"><i class="bx bx-dollar"></i></button> `;
                        }
                        if (row.amount_paid === 0) {
                            btns += `
                                <form class="d-inline ajax-action-form" method="POST" action="${baseUrl}/${row.id}/cancel" data-title="Cancel ${row.invoice_number}?" data-confirm-text="Yes, cancel!">
                                    <button type="submit" class="btn btn-sm btn-outline-secondary" title="Cancel"><i class="bx bx-x-circle"></i></button>
                                </form>
                            `;
                        }
                    }
                    return btns;
                }
            }
        ]
    });

    $('#invoiceStatusTabs a').on('click', function(e) {
        e.preventDefault();
        $('#invoiceStatusTabs a').removeClass('active');
        $(this).addClass('active');
        currentStatus = $(this).data('status');
        window.erpActiveDataTable.ajax.reload();
    });

    $('#fromOrderSelect').on('change', function() {
        var oid = $(this).val();
        $('#fromOrderForm').attr('action', oid ? `${ordersBaseUrl}/${oid}/to-invoice` : '');
    });

    $(document).on('click', '.btn-quick-pay', function() {
        $('#qp_invoice_id').val($(this).data('id'));
        $('#qp_customer_id').val($(this).data('cust'));
        $('#qp_invoice_num').text($(this).data('num'));
        $('#qp_amount').val($(this).data('bal'));
        $('#qp_pay_num').val('PAY-' + Date.now().toString().slice(-8));
        new bootstrap.Modal(document.getElementById('quickPayModal')).show();
    });

    $('#addInvLineBtn').on('click', function() {
        $('#invLinesTable tbody').append(`
            <tr>
                <td><input type="text" name="items[${invIdx}][description]" class="form-control" required></td>
                <td><input type="number" name="items[${invIdx}][quantity]" class="form-control" value="1" min="1" required></td>
                <td><input type="number" name="items[${invIdx}][unit_price]" class="form-control" value="0" min="0" required></td>
                <td><input type="number" name="items[${invIdx}][tax_amount]" class="form-control" value="0" min="0"></td>
                <td><button type="button" class="btn btn-sm btn-outline-danger btn-rm-inv"><i class="bx bx-x"></i></button></td>
            </tr>
        `);
        invIdx++;
    });

    $(document).on('click', '.btn-rm-inv', function() { $(this).closest('tr').remove(); });

    $(document).on('click', '.btn-view-inv', function() {
        var id = $(this).data('id');
        $.get(baseUrl + '/' + id, function(res) {
            let i = res.data;
            $('#vi_number').text(i.invoice_number);
            $('#vi_customer').text(i.customer?.name || '-');
            $('#vi_status').text(i.status);
            $('#vi_total').text('$' + (i.total_amount / 100).toFixed(2));
            $('#vi_print_btn').attr('href', baseUrl + '/' + id + '/print');
            let html = '';
            (i.items || []).forEach(line => {
                html += `<tr>
                    <td>${line.description}</td>
                    <td class="text-end">${line.quantity}</td>
                    <td class="text-end">$${(line.unit_price / 100).toFixed(2)}</td>
                    <td class="text-end">$${(line.tax_amount / 100).toFixed(2)}</td>
                    <td class="text-end">$${(line.line_total / 100).toFixed(2)}</td>
                </tr>`;
            });
            $('#vi_items').html(html);
            new bootstrap.Modal(document.getElementById('viewInvoiceModal')).show();
        });
    });
});
</script>
@endpush
