@extends('admin.layouts.app')
@section('title', __('Accounting Transactions'))

@section('content')
<div class="wrapper">
    <div class="page-wrapper">
        <div class="page-content">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <h4 class="mb-0">{{ __('Accounting Transactions') }}</h4>
                </div>
                @can('accounting.transactions.create')
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createTransactionModal">
                        <i class="bx bx-plus"></i> {{ __('New Transaction') }}
                    </button>
                @endcan
            </div>

            <div class="card radius-10">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="transactionsDataTable" class="table table-striped table-bordered align-middle w-100">
                            <thead class="table-light">
                                <tr>
                                    <th>{{ __('Transaction #') }}</th>
                                    <th>{{ __('Date') }}</th>
                                    <th>{{ __('Reference') }}</th>
                                    <th>{{ __('Notes') }}</th>
                                    <th>{{ __('Total Amount') }}</th>
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

<!-- Create Double-Entry Transaction Modal -->
<div class="modal fade" id="createTransactionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <form class="modal-content ajax-form" method="POST" action="{{ route('admin.accounting.transactions.store') }}">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Create Draft Accounting Transaction') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3 mb-3">
                    <div class="col-md-3">
                        <label class="form-label">{{ __('Transaction # *') }}</label>
                        <input type="text" name="transaction_number" class="form-control" required value="TXN-{{ date('YmdHis') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">{{ __('Date *') }}</label>
                        <input type="date" name="transaction_date" class="form-control" required value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">{{ __('Financial Period') }}</label>
                        <select name="financial_period_id" class="form-select">
                            <option value="">{{ __('-- Optional --') }}</option>
                            @foreach($periods as $p)
                                <option value="{{ $p->id }}">{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">{{ __('Reference Type') }}</label>
                        <input type="text" name="reference_type" class="form-control" value="Manual">
                    </div>
                    <div class="col-12">
                        <label class="form-label">{{ __('Notes') }}</label>
                        <input type="text" name="notes" class="form-control" placeholder="Description of this ledger entry">
                    </div>
                </div>

                <div class="d-flex align-items-center justify-content-between mb-2">
                    <h6 class="mb-0">Transaction Lines (Amounts in Minor Units / Cents)</h6>
                    <button type="button" id="addTxnLineBtn" class="btn btn-sm btn-outline-primary"><i class="bx bx-plus"></i> Add Line</button>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered align-middle" id="txnLinesTable">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 35%">{{ __('Account *') }}</th>
                                <th style="width: 20%">{{ __('Debit (Cents)') }}</th>
                                <th style="width: 20%">{{ __('Credit (Cents)') }}</th>
                                <th>{{ __('Line Description') }}</th>
                                <th style="width: 50px"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <select name="lines[0][account_id]" class="form-select" required>
                                        @foreach($accounts as $acc)
                                            <option value="{{ $acc->id }}">{{ $acc->code }} - {{ $acc->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td><input type="number" name="lines[0][debit]" class="form-control txn-debit" value="0" min="0" required></td>
                                <td><input type="number" name="lines[0][credit]" class="form-control txn-credit" value="0" min="0" required></td>
                                <td><input type="text" name="lines[0][description]" class="form-control"></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>
                                    <select name="lines[1][account_id]" class="form-select" required>
                                        @foreach($accounts as $acc)
                                            <option value="{{ $acc->id }}">{{ $acc->code }} - {{ $acc->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td><input type="number" name="lines[1][debit]" class="form-control txn-debit" value="0" min="0" required></td>
                                <td><input type="number" name="lines[1][credit]" class="form-control txn-credit" value="0" min="0" required></td>
                                <td><input type="text" name="lines[1][description]" class="form-control"></td>
                                <td></td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr class="table-light fw-bold">
                                <td class="text-end">Totals:</td>
                                <td id="txnTotalDebit">$0.00</td>
                                <td id="txnTotalCredit">$0.00</td>
                                <td colspan="2" id="txnBalanceBadge"><span class="badge bg-warning text-dark">Enter balanced amounts</span></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __("Cancel") }}</button>
                <button type="submit" class="btn btn-primary">{{ __('Save Draft Transaction') }}</button>
            </div>
        </form>
    </div>
</div>

<!-- View Transaction Modal -->
<div class="modal fade" id="viewTransactionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Transaction Details: <span id="vt_number"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="mb-2"><strong>Date:</strong> <span id="vt_date"></span> | <strong>Status:</strong> <span id="vt_status" class="badge bg-primary text-uppercase"></span></p>
                <p class="mb-3"><strong>Notes:</strong> <span id="vt_notes"></span></p>
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('Account') }}</th>
                            <th>{{ __('Description') }}</th>
                            <th class="text-end">{{ __('Debit') }}</th>
                            <th class="text-end">{{ __('Credit') }}</th>
                        </tr>
                    </thead>
                    <tbody id="vt_lines"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    var txnBaseUrl = "{{ url('/admin/accounting/transactions') }}";
    var lineIndex = 2;
    var accountOptionsHtml = `@foreach($accounts as $acc)<option value="{{ $acc->id }}">{{ $acc->code }} - {{ $acc->name }}</option>@endforeach`;

    window.erpActiveDataTable = $('#transactionsDataTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('admin.accounting.transactions.data') }}",
        pageLength: 25,
        order: [[1, 'desc']],
        columns: [
            { data: 'transaction_number', render: d => `<strong>${d}</strong>` },
            { data: 'transaction_date' },
            { data: 'reference_type' },
            { data: 'notes' },
            { data: 'total_debit', orderable: false, render: d => '$' + (d / 100).toFixed(2) },
            {
                data: 'status',
                render: d => {
                    let cls = d === 'posted' ? 'bg-success' : (d === 'draft' ? 'bg-warning text-dark' : 'bg-secondary');
                    return `<span class="badge ${cls} text-uppercase">${d}</span>`;
                }
            },
            {
                data: null,
                orderable: false,
                searchable: false,
                className: 'text-end text-nowrap',
                render: function(row) {
                    let btns = `<button class="btn btn-sm btn-outline-info btn-view-txn" data-id="${row.id}" title="View Lines"><i class="bx bx-show"></i></button> `;
                    if (row.status === 'draft') {
                        btns += `
                            <form class="d-inline ajax-action-form" method="POST" action="${txnBaseUrl}/${row.id}/post" data-title="Post ${row.transaction_number}?" data-text="Posted ledger entries become immutable." data-confirm-text="Yes, post it!">
                                <button type="submit" class="btn btn-sm btn-outline-success" title="Post to Ledger"><i class="bx bx-check-circle"></i> Post</button>
                            </form>
                        `;
                    }
                    return btns;
                }
            }
        ]
    });

    function recalcTxnTotals() {
        let deb = 0, cred = 0;
        $('.txn-debit').each(function() { deb += parseInt($(this).val() || 0, 10); });
        $('.txn-credit').each(function() { cred += parseInt($(this).val() || 0, 10); });
        $('#txnTotalDebit').text('$' + (deb / 100).toFixed(2));
        $('#txnTotalCredit').text('$' + (cred / 100).toFixed(2));
        if (deb > 0 && deb === cred) {
            $('#txnBalanceBadge').html('<span class="badge bg-success">Balanced</span>');
        } else {
            $('#txnBalanceBadge').html('<span class="badge bg-danger">Unbalanced (Diff: $' + (Math.abs(deb - cred) / 100).toFixed(2) + ')</span>');
        }
    }

    $(document).on('input', '.txn-debit, .txn-credit', recalcTxnTotals);

    $('#addTxnLineBtn').on('click', function() {
        $('#txnLinesTable tbody').append(`
            <tr>
                <td><select name="lines[${lineIndex}][account_id]" class="form-select" required>${accountOptionsHtml}</select></td>
                <td><input type="number" name="lines[${lineIndex}][debit]" class="form-control txn-debit" value="0" min="0" required></td>
                <td><input type="number" name="lines[${lineIndex}][credit]" class="form-control txn-credit" value="0" min="0" required></td>
                <td><input type="text" name="lines[${lineIndex}][description]" class="form-control"></td>
                <td><button type="button" class="btn btn-sm btn-outline-danger btn-remove-line"><i class="bx bx-x"></i></button></td>
            </tr>
        `);
        lineIndex++;
    });

    $(document).on('click', '.btn-remove-line', function() {
        $(this).closest('tr').remove();
        recalcTxnTotals();
    });

    $(document).on('click', '.btn-view-txn', function() {
        let id = $(this).data('id');
        $.get(txnBaseUrl + '/' + id, function(res) {
            let t = res.data;
            $('#vt_number').text(t.transaction_number);
            $('#vt_date').text(t.transaction_date ? t.transaction_date.substring(0, 10) : '-');
            $('#vt_status').text(t.status);
            $('#vt_notes').text(t.notes || '-');
            let html = '';
            (t.lines || []).forEach(l => {
                html += `<tr>
                    <td><code>${l.account?.code || ''}</code> ${l.account?.name || ''}</td>
                    <td>${l.description || '-'}</td>
                    <td class="text-end">$${(l.debit / 100).toFixed(2)}</td>
                    <td class="text-end">$${(l.credit / 100).toFixed(2)}</td>
                </tr>`;
            });
            $('#vt_lines').html(html);
            new bootstrap.Modal(document.getElementById('viewTransactionModal')).show();
        });
    });
});
</script>
@endpush
