@extends('admin.layouts.app')
@section('title', 'Refunds')

@section('content')
<div class="wrapper">
    <div class="page-wrapper">
        <div class="page-content">
            <h4 class="mb-3">Payment Refunds</h4>
            <div class="card radius-10">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
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
                            <tbody>
                                @forelse($refunds as $refund)
                                    <tr>
                                        <td><strong>{{ $refund->refund_number }}</strong></td>
                                        <td>{{ $refund->payment?->payment_number ?? '-' }}</td>
                                        <td>{{ $refund->refund_date?->format('Y-m-d') }}</td>
                                        <td>${{ number_format($refund->amount / 100, 2) }}</td>
                                        <td>{{ $refund->reason ?? '-' }}</td>
                                        <td><span class="badge bg-warning text-dark text-uppercase">{{ $refund->status }}</span></td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="text-center text-muted py-4">No refunds processed.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">{{ $refunds->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
