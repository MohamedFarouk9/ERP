@extends('admin.layouts.app')
@section('title', 'Invoices')

@section('content')
<div class="wrapper">
    <div class="page-wrapper">
        <div class="page-content">
            <h4 class="mb-3">Invoices</h4>
            <div class="card radius-10">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Invoice #</th>
                                    <th>Customer</th>
                                    <th>Invoice Date</th>
                                    <th>Due Date</th>
                                    <th>Total</th>
                                    <th>Paid</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($invoices as $invoice)
                                    <tr>
                                        <td><strong>{{ $invoice->invoice_number }}</strong></td>
                                        <td>{{ $invoice->customer?->name ?? '-' }}</td>
                                        <td>{{ $invoice->invoice_date?->format('Y-m-d') }}</td>
                                        <td>{{ $invoice->due_date?->format('Y-m-d') }}</td>
                                        <td>${{ number_format($invoice->total_amount / 100, 2) }}</td>
                                        <td>${{ number_format($invoice->amount_paid / 100, 2) }}</td>
                                        <td><span class="badge bg-primary text-uppercase">{{ $invoice->status }}</span></td>
                                    </tr>
                                @empty
                                    <tr><td colspan="7" class="text-center text-muted py-4">No invoices found.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">{{ $invoices->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
