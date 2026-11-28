@extends('admin.layouts.app')
@section('title', __('Expenses'))

@section('content')
<div class="page-wrapper">
    <div class="page-content">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <h4 class="mb-0">{{ __('Expenses') }}</h4>
            </div>
            @can('finance.expenses.manage')
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createExpenseModal">
                    <i class="bx bx-plus me-1"></i> {{ __('Record Expense') }}
                </button>
            @endcan
        </div>

        <div class="card radius-10">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="expensesDataTable" class="table table-striped table-bordered align-middle w-100">
                        <thead class="table-light">
                            <tr>
                                <th>{{ __('Expense #') }}</th>
                                <th>{{ __('Date') }}</th>
                                <th>{{ __('Category') }}</th>
                                <th>{{ __('Payee') }}</th>
                                <th>{{ __('Subtotal') }}</th>
                                <th>{{ __('Tax') }}</th>
                                <th>{{ __('Total') }}</th>
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

<!-- Record Expense Modal -->
<div class="modal fade" id="createExpenseModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="createExpenseForm">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Record Expense') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body row g-3">
                    <div class="col-md-6">
                        <label class="form-label">{{ __('Category') }} *</label>
                        <select name="expense_category_id" class="form-select" required>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->code }} â€” {{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">{{ __('Payee') }} *</label>
                        <input type="text" name="payee" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">{{ __('Expense Date') }} *</label>
                        <input type="date" name="expense_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">{{ __('Amount') }} ($) *</label>
                        <input type="number" step="0.01" min="0.01" name="amount_dollars" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">{{ __('Taxes') }}</label>
                        <select name="tax_rate_id" class="form-select">
                            <option value="">{{ __('-- None --') }}</option>
                            @foreach($taxRates as $tr)
                                <option value="{{ $tr->id }}">{{ $tr->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">{{ __('Method *') }}</label>
                        <select name="payment_method" class="form-select" required>
                            <option value="bank_transfer">{{ __('Bank Transfer') }}</option>
                            <option value="cash">{{ __('Cash') }}</option>
                            <option value="card">{{ __('Card') }}</option>
                            <option value="cheque">{{ __('Cheque') }}</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">{{ __('Reference') }}</label>
                        <input type="text" name="reference" class="form-control">
                    </div>
                    <div class="col-12">
                        <label class="form-label">{{ __('Notes') }}</label>
                        <textarea name="notes" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    const fmt = c => '$' + (Number(c || 0) / 100).toFixed(2);
    const expBaseUrl = @json(url('/admin/finance/expenses'));

    const table = $('#expensesDataTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: @json(route('admin.finance.expenses.data')),
        columns: [
            { data: 'expense_number', name: 'expense_number' },
            { data: 'expense_date', name: 'expense_date' },
            { data: 'category_name', name: 'category_name', orderable: false },
            { data: 'payee', name: 'payee' },
            { data: 'amount', name: 'amount', render: d => fmt(d) },
            { data: 'tax_amount', name: 'tax_amount', render: d => fmt(d) },
            { data: 'total_amount', name: 'total_amount', render: d => `<strong>${fmt(d)}</strong>` },
            {
                data: 'status',
                name: 'status',
                render: d => d === 'paid'
                    ? `<span class="badge bg-success">${@json(__('Paid'))}</span>`
                    : `<span class="badge bg-warning text-dark">${@json(__('Draft'))}</span>`
            },
            {
                data: null,
                orderable: false,
                searchable: false,
                className: 'text-end text-nowrap',
                render: row => row.status === 'paid'
                    ? `<span class="text-success small"><i class="bx bx-check-double"></i> ${@json(__('Paid'))}</span>`
                    : `<button type="button" class="btn btn-sm btn-outline-success btn-pay-expense me-1" data-id="${row.id}"><i class="bx bx-dollar"></i> ${@json(__('Mark Paid'))}</button>
                       <button type="button" class="btn btn-sm btn-outline-danger btn-del-expense" data-id="${row.id}"><i class="bx bx-trash"></i></button>`
            }
        ]
    });

    document.getElementById('createExpenseForm').addEventListener('submit', async function (e) {
        e.preventDefault();
        const fd = new FormData(this);
        const amountCents = Math.round((parseFloat(fd.get('amount_dollars')) || 0) * 100);
        try {
            const res = await fetch(@json(route('admin.finance.expenses.store')), {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                body: JSON.stringify({
                    expense_category_id: fd.get('expense_category_id'),
                    tax_rate_id: fd.get('tax_rate_id') || null,
                    payee: fd.get('payee'),
                    expense_date: fd.get('expense_date'),
                    amount: amountCents,
                    payment_method: fd.get('payment_method'),
                    reference: fd.get('reference') || null,
                    notes: fd.get('notes') || null
                })
            });
            const data = await res.json();
            if (!res.ok) throw new Error(data.message || @json(__('Action failed.')));
            bootstrap.Modal.getInstance(document.getElementById('createExpenseModal')).hide();
            this.reset();
            Swal.fire(@json(__('Success')), data.message, 'success');
            table.ajax.reload(null, false);
        } catch (err) {
            Swal.fire(@json(__('Error')), err.message, 'error');
        }
    });

    document.getElementById('expensesDataTable').addEventListener('click', async function (e) {
        const payBtn = e.target.closest('.btn-pay-expense');
        const delBtn = e.target.closest('.btn-del-expense');
        if (payBtn) {
            try {
                const res = await fetch(`${expBaseUrl}/${payBtn.dataset.id}/pay`, {
                    method: 'POST',
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken }
                });
                const data = await res.json();
                if (!res.ok) throw new Error(data.message || @json(__('Action failed.')));
                Swal.fire(@json(__('Done!')), data.message, 'success');
                table.ajax.reload(null, false);
            } catch (err) {
                Swal.fire(@json(__('Error')), err.message, 'error');
            }
        } else if (delBtn) {
            const conf = await Swal.fire({
                title: @json(__('Are you sure?')),
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: @json(__('Yes, delete it!')),
                cancelButtonText: @json(__('Cancel'))
            });
            if (!conf.isConfirmed) return;
            try {
                const res = await fetch(`${expBaseUrl}/${delBtn.dataset.id}`, {
                    method: 'DELETE',
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken }
                });
                const data = await res.json();
                if (!res.ok) throw new Error(data.message || @json(__('Action failed.')));
                Swal.fire(@json(__('Deleted!')), data.message, 'success');
                table.ajax.reload(null, false);
            } catch (err) {
                Swal.fire(@json(__('Error')), err.message, 'error');
            }
        }
    });
});
</script>
@endpush
