@extends('admin.layouts.app')
@section('title', 'Finance Dashboard')

@section('content')
<div class="wrapper">
    <div class="page-wrapper">
        <div class="page-content">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <h4 class="mb-0">Finance Dashboard</h4>
                    <small class="text-muted">General ledger volume, cash collections, refunds, and account distribution</small>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.accounting.accounts') }}" class="btn btn-sm btn-outline-primary">Chart of Accounts</a>
                    <a href="{{ route('admin.accounting.transactions') }}" class="btn btn-sm btn-primary">Ledger Transactions</a>
                </div>
            </div>

            <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
                <div class="col">
                    <div class="card radius-10 bg-gradient-deepblue">
                        <div class="card-body text-white">
                            <div class="d-flex align-items-center">
                                <h5 class="mb-0 text-white">${{ number_format($stats['total_ledger_volume'] / 100, 2) }}</h5>
                                <div class="ms-auto"><i class='bx bx-book-open fs-3'></i></div>
                            </div>
                            <p class="mb-0 mt-2">Posted Ledger Volume</p>
                            <small>{{ $stats['posted_transactions'] }} Posted · {{ $stats['draft_transactions'] }} Draft</small>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card radius-10 bg-gradient-ohhappiness">
                        <div class="card-body text-white">
                            <div class="d-flex align-items-center">
                                <h5 class="mb-0 text-white">${{ number_format($stats['payments_collected'] / 100, 2) }}</h5>
                                <div class="ms-auto"><i class='bx bx-dollar-circle fs-3'></i></div>
                            </div>
                            <p class="mb-0 mt-2">Payments Collected</p>
                            <small>Completed settlements</small>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card radius-10 bg-gradient-ibiza">
                        <div class="card-body text-white">
                            <div class="d-flex align-items-center">
                                <h5 class="mb-0 text-white">${{ number_format($stats['refunds_total'] / 100, 2) }}</h5>
                                <div class="ms-auto"><i class='bx bx-revision fs-3'></i></div>
                            </div>
                            <p class="mb-0 mt-2">Total Refunds</p>
                            <small>Net Cash: ${{ number_format(($stats['payments_collected'] - $stats['refunds_total']) / 100, 2) }}</small>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card radius-10 bg-gradient-orange">
                        <div class="card-body text-white">
                            <div class="d-flex align-items-center">
                                <h5 class="mb-0 text-white">{{ $stats['accounts_count'] }}</h5>
                                <div class="ms-auto"><i class='bx bx-layer fs-3'></i></div>
                            </div>
                            <p class="mb-0 mt-2">Ledger Accounts</p>
                            <small>Assets: {{ $accountsByType['asset'] ?? 0 }} · Revenue: {{ $accountsByType['revenue'] ?? 0 }}</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-7 d-flex">
                    <div class="card radius-10 w-100">
                        <div class="card-body">
                            <h6 class="mb-3">Recent Ledger Transactions</h6>
                            <div class="table-responsive">
                                <table class="table align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Transaction #</th>
                                            <th>Date</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recentTransactions as $txn)
                                            <tr>
                                                <td><strong>{{ $txn->transaction_number }}</strong></td>
                                                <td>{{ $txn->transaction_date?->format('Y-m-d') }}</td>
                                                <td>${{ number_format($txn->lines->sum('debit') / 100, 2) }}</td>
                                                <td><span class="badge bg-primary text-uppercase">{{ $txn->status }}</span></td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="4" class="text-center text-muted py-3">No transactions yet.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-5 d-flex">
                    <div class="card radius-10 w-100">
                        <div class="card-body">
                            <h6 class="mb-3">Recent Payments</h6>
                            <div class="table-responsive">
                                <table class="table align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Payment #</th>
                                            <th>Method</th>
                                            <th>Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recentPayments as $pay)
                                            <tr>
                                                <td><strong>{{ $pay->payment_number }}</strong></td>
                                                <td>{{ $pay->paymentMethod?->name ?? '-' }}</td>
                                                <td>${{ number_format($pay->amount / 100, 2) }}</td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="3" class="text-center text-muted py-3">No payments yet.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
