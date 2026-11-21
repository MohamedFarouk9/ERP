@extends('admin.layouts.app')
@section('title', 'Payment Refunds')

@section('content')
<div class="wrapper">
    <div class="page-wrapper">
        <div class="page-content">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <h4 class="mb-0">Payment Refunds</h4>
                    <small class="text-muted">Process partial or full refunds with strict balance enforcement</small>
                </div>
                @can('payments.refunds.create')
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createRefundModal">
                        <i class="bx bx-revision"></i> Process Refund
                    </button>
                @endcan
            </div>

            <div class="card radius-10">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="refundsDataTable" class="table table-striped table-bordered align-middle w-100">
                            <thead class="table-light">
                                <tr>
                                    <th>Refund #</th>
                                    <th>Payment #</th>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Reason</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Process Refund Modal -->
<div class="modal fade" id="createRefundModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content ajax-form" method="POST" action="{{ route('admin.payments.refunds.store') }}">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Process Payment Refund</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body row g-3">
                <div class="col-12">
                    <label class="form-label">Completed Payment *</label>
                    <select name="payment_id" class="form-select" required>
                        @foreach($completedPayments as $pay)
                            <option value="{{ $pay->id }}">{{ $pay->payment_number }} (${{ number_format($pay->amount / 100, 2) }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Refund # *</label>
                    <input type="text" name="refund_number" class="form-control" required value="REF-{{ date('YmdHis') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Refund Date *</label>
                    <input type="date" name="refund_date" class="form-control" required value="{{ date('Y-m-d') }}">
                </div>
                <div class="col-12">
                    <label class="form-label">Refund Amount (Cents) *</label>
                    <input type="number" name="amount" class="form-control" required min="1" value="1000">
                </div>
                <div class="col-12">
                    <label class="form-label">Reason</label>
                    <textarea name="reason" class="form-control" rows="2" placeholder="Customer return / billing correction"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-danger">Process Refund</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    window.erpActiveDataTable = $('#refundsDataTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('admin.payments.refunds.data') }}",
        pageLength: 25,
        order: [[2, 'desc']],
        columns: [
            { data: 'refund_number', render: d => `<strong>${d}</strong>` },
            { data: 'payment_number', orderable: false },
            { data: 'refund_date' },
            { data: 'amount', render: d => '$' + (d / 100).toFixed(2) },
            { data: 'reason' },
            { data: 'status', render: d => `<span class="badge bg-warning text-dark text-uppercase">${d}</span>` }
        ]
    });
});
</script>
@endpush
