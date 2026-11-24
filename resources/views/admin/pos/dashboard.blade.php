@extends('admin.layouts.app')

@section('title', 'POS Analytics')

@section('content')
<div class="page-wrapper">
    <div class="page-content">
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Point of Sale</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">POS Analytics</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto d-flex gap-2">
                @can('pos.terminal.view')
                <a href="{{ route('admin.pos.terminal') }}" class="btn btn-primary btn-sm px-3">
                    <i class="bx bx-desktop me-1"></i> Open POS Terminal
                </a>
                @endcan
                @can('pos.shifts.view')
                <a href="{{ route('admin.pos.shifts.index') }}" class="btn btn-outline-secondary btn-sm px-3">
                    <i class="bx bx-time-five me-1"></i> Shifts &amp; Sales
                </a>
                @endcan
            </div>
        </div>

        <!-- KPI Row -->
        <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
            <div class="col">
                <div class="card radius-10 border-start border-0 border-4 border-info">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary">Today's Revenue</p>
                                <h4 class="my-1 text-info">${{ number_format(($stats['today_revenue'] ?? 0) / 100, 2) }}</h4>
                                <p class="mb-0 font-13">{{ $stats['today_tickets'] ?? 0 }} tickets today</p>
                            </div>
                            <div class="widgets-icons-2 rounded-circle bg-gradient-blues text-white ms-auto">
                                <i class="bx bx-dollar-circle"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card radius-10 border-start border-0 border-4 border-success">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary">Total POS Revenue</p>
                                <h4 class="my-1 text-success">${{ number_format(($stats['total_revenue'] ?? 0) / 100, 2) }}</h4>
                                <p class="mb-0 font-13">{{ $stats['total_tickets'] ?? 0 }} total tickets</p>
                            </div>
                            <div class="widgets-icons-2 rounded-circle bg-gradient-ohhappiness text-white ms-auto">
                                <i class="bx bx-cart"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card radius-10 border-start border-0 border-4 border-warning">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary">Average Ticket</p>
                                <h4 class="my-1 text-warning">${{ number_format(($stats['avg_ticket'] ?? 0) / 100, 2) }}</h4>
                                <p class="mb-0 font-13">Per completed sale</p>
                            </div>
                            <div class="widgets-icons-2 rounded-circle bg-gradient-burning text-white ms-auto">
                                <i class="bx bx-receipt"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card radius-10 border-start border-0 border-4 border-primary">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary">Open Shifts</p>
                                <h4 class="my-1 text-primary">{{ $stats['open_shifts'] ?? 0 }}</h4>
                                <p class="mb-0 font-13">Active registers</p>
                            </div>
                            <div class="widgets-icons-2 rounded-circle bg-gradient-scooter text-white ms-auto">
                                <i class="bx bx-store"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row">
            <div class="col-12 col-lg-8 d-flex">
                <div class="card radius-10 w-100">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <div>
                                <h6 class="mb-0">Daily POS Sales (Last 14 Days)</h6>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="posDailySalesChart" style="height: 320px;"></div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-4 d-flex">
                <div class="card radius-10 w-100">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <div>
                                <h6 class="mb-0">Payment Mix</h6>
                            </div>
                        </div>
                    </div>
                    <div class="card-body d-flex align-items-center justify-content-center">
                        <div id="posPaymentMixChart" class="w-100"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tables Row -->
        <div class="row">
            <div class="col-12 col-lg-5 d-flex">
                <div class="card radius-10 w-100">
                    <div class="card-header">
                        <h6 class="mb-0">Top Selling Items</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Product</th>
                                        <th class="text-end">Qty</th>
                                        <th class="text-end">Revenue</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($topItems as $item)
                                    <tr>
                                        <td>
                                            <div class="fw-semibold">{{ $item->product_name }}</div>
                                            <small class="text-muted">{{ $item->sku }}</small>
                                        </td>
                                        <td class="text-end">{{ number_format($item->qty) }}</td>
                                        <td class="text-end fw-semibold">${{ number_format(($item->total ?? 0) / 100, 2) }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-4">No POS sales recorded yet.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-7 d-flex">
                <div class="card radius-10 w-100">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h6 class="mb-0">Recent Receipts</h6>
                        @can('pos.shifts.view')
                        <a href="{{ route('admin.pos.shifts.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                        @endcan
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Receipt #</th>
                                        <th>Date</th>
                                        <th>Cashier</th>
                                        <th>Payment</th>
                                        <th class="text-end">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentSales as $sale)
                                    <tr>
                                        <td>
                                            <a href="{{ route('admin.pos.receipt', $sale->id) }}" target="_blank" class="fw-semibold text-primary">
                                                {{ $sale->receipt_number }}
                                            </a>
                                        </td>
                                        <td>{{ optional($sale->sale_date)->format('M d, H:i') }}</td>
                                        <td>{{ $sale->cashier->name ?? 'System' }}</td>
                                        <td>
                                            <span class="badge bg-light-info text-info text-uppercase">{{ $sale->payment_type }}</span>
                                        </td>
                                        <td class="text-end fw-semibold">${{ number_format(($sale->total_amount ?? 0) / 100, 2) }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">No recent receipts found.</td>
                                    </tr>
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
    const days = @json($chartData['days'] ?? []);
    const dailySalesRaw = @json($chartData['daily_sales'] ?? []);
    const dailySales = dailySalesRaw.map(v => Number((v / 100).toFixed(2)));

    new ApexCharts(document.querySelector('#posDailySalesChart'), {
        series: [{ name: 'POS Revenue ($)', data: dailySales }],
        chart: { type: 'area', height: 310, toolbar: { show: false } },
        dataLabels: { enabled: false },
        stroke: { curve: 'smooth', width: 3 },
        colors: ['#008cff'],
        fill: {
            type: 'gradient',
            gradient: { shadeIntensity: 1, opacityFrom: 0.45, opacityTo: 0.05 }
        },
        xaxis: { categories: days },
        yaxis: {
            labels: { formatter: val => '$' + Number(val).toFixed(0) }
        },
        tooltip: {
            y: { formatter: val => '$' + Number(val).toFixed(2) }
        }
    }).render();

    const mix = @json($chartData['payment_mix'] ?? ['cash' => 0, 'card' => 0, 'split' => 0]);
    const mixValues = [
        Number(((mix.cash || 0) / 100).toFixed(2)),
        Number(((mix.card || 0) / 100).toFixed(2)),
        Number(((mix.split || 0) / 100).toFixed(2))
    ];

    new ApexCharts(document.querySelector('#posPaymentMixChart'), {
        series: mixValues,
        labels: ['Cash', 'Card', 'Split'],
        chart: { type: 'donut', height: 300 },
        colors: ['#15ca20', '#008cff', '#ffc107'],
        legend: { position: 'bottom' },
        tooltip: {
            y: { formatter: val => '$' + Number(val).toFixed(2) }
        }
    }).render();
});
</script>
@endpush
