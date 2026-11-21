@extends('admin.layouts.app')
@section('title', 'Accounting Transactions')

@section('content')
<div class="wrapper">
    <div class="page-wrapper">
        <div class="page-content">
            <h4 class="mb-3">Accounting Transactions</h4>
            <div class="card radius-10">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Transaction #</th>
                                    <th>Date</th>
                                    <th>Reference</th>
                                    <th>Notes</th>
                                    <th>Status</th>
                                    <th>Lines</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transactions as $txn)
                                    <tr>
                                        <td><strong>{{ $txn->transaction_number }}</strong></td>
                                        <td>{{ $txn->transaction_date?->format('Y-m-d') }}</td>
                                        <td>{{ $txn->reference_type ?? '-' }}</td>
                                        <td>{{ $txn->notes ?? '-' }}</td>
                                        <td><span class="badge bg-primary text-uppercase">{{ $txn->status }}</span></td>
                                        <td>{{ $txn->lines->count() }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="text-center text-muted py-4">No transactions found.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">{{ $transactions->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
