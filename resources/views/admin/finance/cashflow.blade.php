@extends('admin.layouts.app')
@section('title', __('Cashflow'))

@section('content')
<div class="page-wrapper">
    <div class="page-content">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h4 class="mb-0">{{ __('Cashflow') }}</h4>
            <button type="button" onclick="window.print()" class="btn btn-outline-primary btn-sm d-print-none">
                <i class="bx bx-printer me-1"></i> {{ __('Print Report') }}
            </button>
        </div>

        <div class="row row-cols-1 row-cols-md-3 mb-3">
            <div class="col">
                <div class="card radius-10 border-start border-0 border-4 border-success">
                    <div class="card-body">
                        <p class="mb-0 text-secondary">{{ __('Total Cash Inflows') }}</p>
                        <h4 class="my-1 text-success">${{ number_format(($stats['total_inflow'] ?? 0) / 100, 2) }}</h4>
                        <small class="text-muted">{{ __('Payments & Methods') }} + {{ __('Point of Sale') }}</small>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card radius-10 border-start border-0 border-4 border-danger">
                    <div class="card-body">
                        <p class="mb-0 text-secondary">{{ __('Total Cash Outflows') }}</p>
                        <h4 class="my-1 text-danger">${{ number_format(($stats['total_outflow'] ?? 0) / 100, 2) }}</h4>
                        <small class="text-muted">{{ __('Expenses') }} + {{ __('Vendor Bills') }} + {{ __('Payroll Runs') }}</small>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card radius-10 border-start border-0 border-4 border-primary">
                    <div class="card-body">
                        <p class="mb-0 text-secondary">{{ __('Net Cashflow') }}</p>
                        <h4 class="my-1 {{ ($stats['net_cashflow'] ?? 0) >= 0 ? 'text-primary' : 'text-danger' }}">
                            ${{ number_format(($stats['net_cashflow'] ?? 0) / 100, 2) }}
                        </h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="card radius-10 mb-3">
            <div class="card-header"><h6 class="mb-0">{{ __('6-Month Cashflow Trend') }}</h6></div>
            <div class="card-body"><div id="cashflowTrendChart" style="height: 320px;"></div></div>
        </div>

        <div class="row">
            <div class="col-lg-6 d-flex">
                <div class="card radius-10 w-100">
                    <div class="card-header"><h6 class="mb-0 text-success">{{ __('Recent Cash Inflows') }}</h6></div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr><th>{{ __('Payment #') }}</th><th>{{ __('Date') }}</th><th class="text-end">{{ __('Amount') }}</th></tr>
                                </thead>
                                <tbody>
                                    @forelse($recentPayments as $p)
                                        <tr>
                                            <td><strong>{{ $p->payment_number }}</strong></td>
                                            <td>{{ optional($p->payment_date)->format('Y-m-d') }}</td>
                                            <td class="text-end text-success fw-semibold">+${{ number_format($p->amount / 100, 2) }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="3" class="text-center text-muted py-3">-</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 d-flex">
                <div class="card radius-10 w-100">
                    <div class="card-header"><h6 class="mb-0 text-danger">{{ __('Recent Cash Outflows') }}</h6></div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr><th>{{ __('Expense #') }}</th><th>{{ __('Payee') }}</th><th class="text-end">{{ __('Amount') }}</th></tr>
                                </thead>
                                <tbody>
                                    @forelse($recentExpenses as $e)
                                        <tr>
                                            <td><strong>{{ $e->expense_number }}</strong></td>
                                            <td>{{ $e->payee }}</td>
                                            <td class="text-end text-danger fw-semibold">-${{ number_format($e->total_amount / 100, 2) }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="3" class="text-center text-muted py-3">-</td></tr>
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
document.addEventListener('DOMContentLoaded', function () {
    new ApexCharts(document.querySelector('#cashflowTrendChart'), {
        series: [
            { name: @json(__('Total Cash Inflows')), data: @json($chartData['inflows']) },
            { name: @json(__('Total Cash Outflows')), data: @json($chartData['outflows']) },
            { name: @json(__('Net Cashflow')), data: @json($chartData['net']) }
        ],
        chart: { type: 'bar', height: 310, toolbar: { show: false } },
        colors: ['#15ca20', '#fd3550', '#008cff'],
        plotOptions: { bar: { borderRadius: 4, columnWidth: '55%' } },
        dataLabels: { enabled: false },
        xaxis: { categories: @json($chartData['months']) },
        yaxis: { labels: { formatter: v => '$' + Number(v).toFixed(0) } },
        tooltip: { y: { formatter: v => '$' + Number(v).toFixed(2) } }
    }).render();
});
</script>
@endpush
