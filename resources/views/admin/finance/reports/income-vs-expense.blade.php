@extends('admin.layouts.app')
@section('title', __('Income vs Expense'))

@section('content')
<div class="page-wrapper">
    <div class="page-content">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h4 class="mb-0">{{ __('Income vs Expense') }}</h4>
            <button type="button" onclick="window.print()" class="btn btn-outline-secondary btn-sm d-print-none">
                <i class="bx bx-printer me-1"></i> {{ __('Print Report') }}
            </button>
        </div>

        <div class="card radius-10 mb-3">
            <div class="card-body">
                <div id="incomeVsExpenseChart" style="height: 320px;"></div>
            </div>
        </div>

        <div class="card radius-10">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>{{ __('Month') }}</th>
                                <th class="text-end">{{ __('Income') }}</th>
                                <th class="text-end">{{ __('Expenses') }}</th>
                                <th class="text-end">{{ __('Net') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rows as $r)
                                <tr>
                                    <td><strong>{{ $r->month }}</strong></td>
                                    <td class="text-end text-success">${{ number_format($r->income / 100, 2) }}</td>
                                    <td class="text-end text-danger">${{ number_format($r->expense / 100, 2) }}</td>
                                    <td class="text-end fw-bold {{ $r->net >= 0 ? 'text-primary' : 'text-danger' }}">${{ number_format($r->net / 100, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    new ApexCharts(document.querySelector('#incomeVsExpenseChart'), {
        series: [
            { name: @json(__('Income')), data: @json($chartData['income']) },
            { name: @json(__('Expenses')), data: @json($chartData['expense']) }
        ],
        chart: { type: 'area', height: 310, toolbar: { show: false } },
        colors: ['#15ca20', '#fd3550'],
        stroke: { curve: 'smooth', width: 3 },
        dataLabels: { enabled: false },
        xaxis: { categories: @json($chartData['months']) },
        yaxis: { labels: { formatter: v => '$' + Number(v).toFixed(0) } },
        tooltip: { y: { formatter: v => '$' + Number(v).toFixed(2) } }
    }).render();
});
</script>
@endpush
