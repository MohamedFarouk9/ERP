@extends('admin.layouts.app')
@section('title', 'Payments')

@section('content')
<div class="wrapper">
    <div class="page-wrapper">
        <div class="page-content">
            <h4 class="mb-3">Payments</h4>

            <div class="row mb-3">
                @foreach($methods as $method)
                    <div class="col-md-3">
                        <div class="card radius-10">
                            <div class="card-body">
                                <h6 class="mb-1">{{ $method->name }}</h6>
                                <small class="text-muted"><code>{{ $method->code }}</code> · {{ ucfirst(str_replace('_', ' ', $method->type)) }}</small>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="card radius-10">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Payment #</th>
                                    <th>Method</th>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Reference</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($payments as $payment)
                                    <tr>
                                        <td><strong>{{ $payment->payment_number }}</strong></td>
                                        <td>{{ $payment->paymentMethod?->name ?? '-' }}</td>
                                        <td>{{ $payment->payment_date?->format('Y-m-d') }}</td>
                                        <td>${{ number_format($payment->amount / 100, 2) }}</td>
                                        <td>{{ $payment->reference ?? '-' }}</td>
                                        <td><span class="badge bg-success text-uppercase">{{ $payment->status }}</span></td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="text-center text-muted py-4">No payments found.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">{{ $payments->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
