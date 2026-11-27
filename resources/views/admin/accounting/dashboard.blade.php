@extends('admin.layouts.app')
@section('title', __('Finance Analytics'))

@section('content')
<div class="page-wrapper">
    <div class="page-content">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h4 class="mb-0">{{ __('Finance & Treasury Analytics') }}</h4>
                <p class="mb-0 font-13 text-secondary">{{ __('General ledger volume, cash inflows, refunds, and chart of accounts breakdown') }}</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.accounting.accounts') }}" class="btn btn-sm btn-outline-primary">{{ __('Chart of Accounts') }}</a>
                <a href="{{ route('admin.accounting.transactions') }}" class="btn btn-sm btn-primary">{{ __('Ledger Transactions') }}</a>
            </div>
        </div>

        <!-- Rukada Icon Widget Cards -->
        <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
            <div class="col">
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary">{{ __('Posted Ledger Volume') }}</p>
                                <h4 class="my-1">${{ number_format($stats['total_ledger_volume'] / 100, 2) }}</h4>
                                <p class="mb-0 font-13 text-primary">{{ number_format($stats['posted_transactions']) }} Â· {{ $stats['draft_transactions'] }} {{ __('Draft') }}</p>
                            </div>
                            <div class="widgets-icons bg-light-primary text-primary ms-auto"><i class='bx bxs-book-content'></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary">{{ __('Cash Collected') }}</p>
                                <h4 class="my-1 text-success">${{ number_format($stats['payments_collected'] / 100, 2) }}</h4>
                                <p class="mb-0 font-13 text-success"><i class='bx bxs-up-arrow align-middle'></i> {{ __('Completed') }}</p>
                            </div>
                            <div class="widgets-icons bg-light-success text-success ms-auto"><i class='bx bxs-wallet'></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary">{{ __('Processed Refunds') }}</p>
                                <h4 class="my-1 text-danger">${{ number_format($stats['refunds_total'] / 100, 2) }}</h4>
                                <p class="mb-0 font-13 text-secondary">${{ number_format(($stats['payments_collected'] - $stats['refunds_total']) / 100, 2) }}</p>
                            </div>
                            <div class="widgets-icons bg-light-danger text-danger ms-auto"><i class='bx bx-revision'></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary">{{ __('Active Ledger Accounts') }}</p>
                                <h4 class="my-1">{{ $stats['accounts_count'] }}</h4>
                                <p class="mb-0 font-13 text-warning">{{ __('Double-entry balanced') }}</p>
                            </div>
                            <div class="widgets-icons bg-light-warning text-warning ms-auto"><i class='bx bxs-layer'></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row">
            <div class="col-xl-8 d-flex">
                <div class="card radius-10 w-100">
                    <div class="card-body">
                        <h6 class="mb-1">{{ __('6-Month Cash Inflow vs. Refunds') }}</h6>
                        <div id="financeCashChart" style="min-height: 300px;"></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 d-flex">
                <div class="card radius-10 w-100">
                    <div class="card-body">
                        <h6 class="mb-1">{{ __('Chart of Accounts') }}</h6>
                        <div id="financeAccountsDonut" class="mt-3" style="min-height: 280px;"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tables Row -->
        <div class="row">
            <div class="col-xl-7 d-flex">
                <div class="card radius-10 w-100">
                    <div class="card-body">
                        <h6 class="mb-3">{{ __('Recent Ledger Transactions') }}</h6>
                        <div class="table-responsive">
                            <table class="table align-middle mb-0 table-hover">
                                <thead class="table-light">
                                    <tr><th>{{ __('Transaction #') }}</th><th>{{ __('Date') }}</th><th>{{ __('Total Amount') }}</th><th>{{ __('Status') }}</th></tr>
                                </thead>
                                <tbody>
                                    @forelse($recentTransactions as $txn)
                                        <tr>
                                            <td><strong>{{ $txn->transaction_number }}</strong></td>
                                            <td>{{ $txn->transaction_date?->format('d M Y') }}</td>
                                            <td class="fw-bold">${{ number_format($txn->lines->sum('debit') / 100, 2) }}</td>
                                            <td><span class="badge rounded-pill bg-light-success text-success text-uppercase px-3">{{ $txn->status }}</span></td>
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
                            <table class="table align-middle mb-0 table-hover">
                                <thead class="table-light">
                                    <tr><th>{{ __('Payment #') }}</th><th>{{ __('Method') }}</th><th class="text-end">{{ __('Amount') }}</th></tr>
                                </thead>
                                <tbody>
                                    @forelse($recentPayments as $pay)
                                        <tr>
                                            <td><strong>{{ $pay->payment_number }}</strong></td>
                                            <td>{{ $pay->paymentMethod?->name ?? '-' }}</td>
                                            <td class="text-end fw-bold text-success">+${{ number_format($pay->amount / 100, 2) }}</td>
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
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof ApexCharts === 'undefined') return;

    new ApexCharts(document.querySelector("#financeCashChart"), {
        series: [
            { name: 'Cash Collected ($)', data: @json($chartData['inflow']) },
            { name: 'Refunds ($)', data: @json($chartData['refunds']) }
        ],
        chart: { type: 'bar', height: 300, toolbar: { show: false }, fontFamily: 'Roboto, sans-serif' },
        colors: ['#02ba5a', '#f5365c'],
        plotOptions: { bar: { borderRadius: 4, columnWidth: '45%' } },
        dataLabels: { enabled: false },
        xaxis: { categories: @json($chartData['months']) },
        yaxis: { labels: { formatter: v => '$' + Number(v).toLocaleString() } }
    }).render();

    new ApexCharts(document.querySelector("#financeAccountsDonut"), {
        series: [
            {{ $chartData['account_types']['asset'] }},
            {{ $chartData['account_types']['liability'] }},
            {{ $chartData['account_types']['equity'] }},
            {{ $chartData['account_types']['revenue'] }},
            {{ $chartData['account_types']['expense'] }}
        ],
        labels: ['Asset', 'Liability', 'Equity', 'Revenue', 'Expense'],
        chart: { type: 'donut', height: 280, fontFamily: 'Roboto, sans-serif' },
        colors: ['#14abef', '#fba540', '#843cf7', '#02ba5a', '#f5365c'],
        legend: { position: 'bottom' }
    }).render();
});
</script>
@endpush
