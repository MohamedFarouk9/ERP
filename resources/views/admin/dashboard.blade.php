@extends('admin.layouts.app')
@section('title', __('Executive ERP Overview'))

@section('content')
<div class="page-wrapper">
    <div class="page-content">
        <!-- Header Bar with Module Status Pills -->
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-4">
            <div>
                <h4 class="mb-0">{{ __('Executive ERP Overview') }}</h4>
                <p class="mb-0 font-13 text-secondary">
                    <i class="bx bx-buildings me-1"></i>{{ $tenant->name }} Â· {{ number_format($stats['staff_count']) }} {{ __('Staff Members') }}
                </p>
            </div>
            <div class="d-flex flex-wrap gap-2">
                @foreach(['sales', 'inventory', 'procurement', 'accounting', 'payments'] as $mod)
                    @php $active = (bool) ($features[$mod] ?? false); @endphp
                    <span class="badge rounded-pill {{ $active ? 'bg-light-success text-success' : 'bg-light-secondary text-secondary' }} px-3 py-2">
                        <i class="bx bxs-circle me-1" style="font-size: 8px;"></i>{{ __(ucfirst($mod)) }}
                    </span>
                @endforeach
            </div>
        </div>

        <!-- Row 1: Rukada Gradient KPI Cards -->
        <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
            <div class="col">
                <div class="card radius-10 bg-gradient-deepblue">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <h5 class="mb-0 text-white">{{ number_format($stats['orders_count']) }}</h5>
                            <div class="ms-auto"><i class='bx bx-cart fs-3 text-white'></i></div>
                        </div>
                        <div class="progress my-3 bg-light-transparent" style="height:3px;">
                            <div class="progress-bar bg-white" role="progressbar" style="width: {{ $stats['fulfillment_rate'] }}%"></div>
                        </div>
                        <div class="d-flex align-items-center text-white">
                            <p class="mb-0">{{ __('Total Sales Orders') }}</p>
                            <p class="mb-0 ms-auto">{{ $stats['fulfillment_rate'] }}%<span><i class='bx bx-check-double ms-1'></i></span></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card radius-10 bg-gradient-orange">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <h5 class="mb-0 text-white">${{ number_format($stats['revenue_cents'] / 100, 2) }}</h5>
                            <div class="ms-auto"><i class='bx bx-dollar fs-3 text-white'></i></div>
                        </div>
                        <div class="progress my-3 bg-light-transparent" style="height:3px;">
                            <div class="progress-bar bg-white" role="progressbar" style="width: {{ $stats['collection_rate'] }}%"></div>
                        </div>
                        <div class="d-flex align-items-center text-white">
                            <p class="mb-0">{{ __('Invoiced Revenue') }}</p>
                            <p class="mb-0 ms-auto">{{ $stats['collection_rate'] }}% {{ __('Paid') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card radius-10 bg-gradient-ohhappiness">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <h5 class="mb-0 text-white">${{ number_format($stats['payments_cents'] / 100, 2) }}</h5>
                            <div class="ms-auto"><i class='bx bx-wallet fs-3 text-white'></i></div>
                        </div>
                        <div class="progress my-3 bg-light-transparent" style="height:3px;">
                            <div class="progress-bar bg-white" role="progressbar" style="width: 75%"></div>
                        </div>
                        <div class="d-flex align-items-center text-white">
                            <p class="mb-0">{{ __('Cash Collected') }}</p>
                            <p class="mb-0 ms-auto">{{ number_format($stats['customers_count']) }} {{ __('Clients') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card radius-10 bg-gradient-ibiza">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <h5 class="mb-0 text-white">{{ number_format($stats['products_count']) }}</h5>
                            <div class="ms-auto"><i class='bx bx-package fs-3 text-white'></i></div>
                        </div>
                        <div class="progress my-3 bg-light-transparent" style="height:3px;">
                            <div class="progress-bar bg-white" role="progressbar" style="width: {{ $stats['healthy_stock_rate'] }}%"></div>
                        </div>
                        <div class="d-flex align-items-center text-white">
                            <p class="mb-0">{{ __('Catalog SKUs') }}</p>
                            <p class="mb-0 ms-auto">{{ $stats['low_stock_count'] }} {{ __('Alerts') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Row 2: 6-Month Financial Performance Chart + Order Pipeline Donut -->
        <div class="row">
            <div class="col-12 col-lg-8 d-flex">
                <div class="card radius-10 w-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2">
                            <div>
                                <h6 class="mb-0">{{ __('6-Month Financial Performance') }}</h6>
                                <small class="text-secondary">{{ __('Invoiced Revenue vs. Cash Collected vs. Procurement Spend') }}</small>
                            </div>
                        </div>
                        <div id="execFinancialChart" style="min-height: 320px;"></div>
                    </div>
                    <div class="row row-cols-1 row-cols-md-3 g-0 row-group text-center border-top">
                        <div class="col">
                            <div class="p-3">
                                <h5 class="mb-0">${{ number_format(array_sum($chartData['revenue']), 2) }}</h5>
                                <small class="mb-0 text-secondary">{{ __('6M Billed Revenue') }}</small>
                            </div>
                        </div>
                        <div class="col">
                            <div class="p-3">
                                <h5 class="mb-0 text-success">${{ number_format(array_sum($chartData['collected']), 2) }}</h5>
                                <small class="mb-0 text-secondary">{{ __('6M Cash Inflow') }}</small>
                            </div>
                        </div>
                        <div class="col">
                            <div class="p-3">
                                <h5 class="mb-0 text-danger">${{ number_format(array_sum($chartData['spend']), 2) }}</h5>
                                <small class="mb-0 text-secondary">{{ __('6M Purchasing Spend') }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-4 d-flex">
                <div class="card radius-10 overflow-hidden w-100">
                    <div class="card-body">
                        <h6 class="mb-1">{{ __('Order Pipeline Breakdown') }}</h6>
                        <small class="text-secondary">{{ __('Distribution across lifecycle stages') }}</small>
                        <div id="execOrderDonut" class="my-3" style="min-height: 230px;"></div>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-items-center mb-0">
                            <tbody>
                                <tr>
                                    <td><i class="bx bxs-circle me-2" style="color: #14abef"></i>{{ __('Confirmed') }}</td>
                                    <td class="fw-bold text-end">{{ number_format($chartData['order_statuses']['confirmed']) }}</td>
                                </tr>
                                <tr>
                                    <td><i class="bx bxs-circle me-2" style="color: #02ba5a"></i>{{ __('Fulfilled') }}</td>
                                    <td class="fw-bold text-end">{{ number_format($chartData['order_statuses']['fulfilled']) }}</td>
                                </tr>
                                <tr>
                                    <td><i class="bx bxs-circle me-2" style="color: #fba540"></i>{{ __('Draft') }}</td>
                                    <td class="fw-bold text-end">{{ number_format($chartData['order_statuses']['draft']) }}</td>
                                </tr>
                                <tr>
                                    <td><i class="bx bxs-circle me-2" style="color: #f5365c"></i>{{ __('Cancelled') }}</td>
                                    <td class="fw-bold text-end">{{ number_format($chartData['order_statuses']['cancelled']) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Row 3: Rukada Operational Health Gauges + Top Selling Products -->
        <div class="row">
            <div class="col-12 col-xl-5 d-flex">
                <div class="card radius-10 w-100">
                    <div class="card-body">
                        <h6 class="mb-3">{{ __('Operational Health Indicators') }}</h6>
                        <div class="mb-4">
                            <div class="d-flex justify-content-between mb-1">
                                <span>{{ __('Invoice Collection Efficiency') }}</span>
                                <span class="fw-bold text-success">{{ $stats['collection_rate'] }}%</span>
                            </div>
                            <div class="progress" style="height: 7px;">
                                <div class="progress-bar bg-gradient-ohhappiness" style="width: {{ $stats['collection_rate'] }}%"></div>
                            </div>
                        </div>
                        <div class="mb-4">
                            <div class="d-flex justify-content-between mb-1">
                                <span>{{ __('Order Confirmation & Fulfillment Rate') }}</span>
                                <span class="fw-bold text-primary">{{ $stats['fulfillment_rate'] }}%</span>
                            </div>
                            <div class="progress" style="height: 7px;">
                                <div class="progress-bar bg-gradient-deepblue" style="width: {{ $stats['fulfillment_rate'] }}%"></div>
                            </div>
                        </div>
                        <div class="mb-4">
                            <div class="d-flex justify-content-between mb-1">
                                <span>{{ __('Healthy Inventory Stock Ratio') }}</span>
                                <span class="fw-bold text-info">{{ $stats['healthy_stock_rate'] }}%</span>
                            </div>
                            <div class="progress" style="height: 7px;">
                                <div class="progress-bar bg-gradient-scooter" style="width: {{ $stats['healthy_stock_rate'] }}%"></div>
                            </div>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between text-secondary font-13">
                            <span>{{ __('General Ledger Accounts') }}: <strong>{{ $stats['accounts_count'] }}</strong></span>
                            <span>{{ __('Posted Entries') }}: <strong>{{ number_format($stats['transactions_count']) }}</strong></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-7 d-flex">
                <div class="card radius-10 w-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div>
                                <h6 class="mb-0">{{ __('Top Selling Catalog Products') }}</h6>
                                <small class="text-secondary">{{ __('Ranked by cumulative sales order revenue') }}</small>
                            </div>
                            @can('inventory.products.view')
                                <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-light ms-auto">{{ __('Catalog') }} <i class="bx {{ app()->getLocale() === 'ar' ? 'bx-left-arrow-alt' : 'bx-right-arrow-alt' }}"></i></a>
                            @endcan
                        </div>
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>{{ __('SKU') }}</th>
                                        <th>{{ __('Product') }}</th>
                                        <th class="text-end">{{ __('Units Sold') }}</th>
                                        <th class="text-end">{{ __('Revenue') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($topProducts as $tp)
                                        @php $prod = $productMap[$tp->product_id] ?? null; @endphp
                                        <tr>
                                            <td><code>{{ $prod?->sku ?? 'N/A' }}</code></td>
                                            <td><strong>{{ $prod?->name ?? 'Catalog Item' }}</strong></td>
                                            <td class="text-end">{{ number_format((int) $tp->units_sold) }}</td>
                                            <td class="text-end fw-bold text-success">${{ number_format(((int) $tp->total_sales) / 100, 2) }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="4" class="text-center text-muted py-4">No product sales recorded yet.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Row 4: Orders Summary (Rukada Rounded-Pill Style from vertical/index.html) -->
        <div class="card radius-10">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div>
                        <h5 class="mb-0">Recent Orders Summary</h5>
                    </div>
                    @can('sales.orders.view')
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-primary ms-auto">Manage All Orders</a>
                    @endcan
                </div>
                <hr>
                <div class="table-responsive">
                    <table class="table align-middle mb-0 table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>{{ __('Order #') }}</th>
                                <th>{{ __('Customer') }}</th>
                                <th>{{ __('Order Date') }}</th>
                                <th>{{ __('Amount') }}</th>
                                <th>{{ __('Status') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentOrders as $order)
                                @php
                                    $pill = match($order->status) {
                                        'confirmed', 'fulfilled' => 'bg-light-success text-success',
                                        'cancelled' => 'bg-light-danger text-danger',
                                        default => 'bg-light-info text-info',
                                    };
                                @endphp
                                <tr>
                                    <td><strong>{{ $order->order_number }}</strong></td>
                                    <td>{{ $order->customer?->name ?? 'N/A' }}</td>
                                    <td>{{ $order->order_date?->format('d M Y') }}</td>
                                    <td class="fw-bold">${{ number_format($order->total_amount / 100, 2) }}</td>
                                    <td style="width: 150px;">
                                        <div class="badge rounded-pill {{ $pill }} w-100 text-uppercase">{{ $order->status }}</div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center text-muted py-4">No sales orders yet.</td></tr>
                            @endforelse
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
document.addEventListener('DOMContentLoaded', function() {
    if (typeof ApexCharts === 'undefined') return;

    new ApexCharts(document.querySelector("#execFinancialChart"), {
        series: [
            { name: 'Invoiced Revenue ($)', data: @json($chartData['revenue']) },
            { name: 'Cash Collected ($)', data: @json($chartData['collected']) },
            { name: 'Procurement Spend ($)', data: @json($chartData['spend']) }
        ],
        chart: { type: 'area', height: 320, toolbar: { show: false }, fontFamily: 'Roboto, sans-serif' },
        colors: ['#14abef', '#02ba5a', '#f5365c'],
        dataLabels: { enabled: false },
        stroke: { curve: 'smooth', width: 2.5 },
        fill: { type: 'gradient', gradient: { opacityFrom: 0.35, opacityTo: 0.05 } },
        xaxis: { categories: @json($chartData['months']) },
        yaxis: { labels: { formatter: val => '$' + Number(val).toLocaleString() } },
        tooltip: { y: { formatter: val => '$' + Number(val).toLocaleString() } },
        legend: { position: 'top' }
    }).render();

    new ApexCharts(document.querySelector("#execOrderDonut"), {
        series: [
            {{ $chartData['order_statuses']['confirmed'] }},
            {{ $chartData['order_statuses']['fulfilled'] }},
            {{ $chartData['order_statuses']['draft'] }},
            {{ $chartData['order_statuses']['cancelled'] }}
        ],
        labels: ['Confirmed', 'Fulfilled', 'Draft', 'Cancelled'],
        chart: { type: 'donut', height: 240, fontFamily: 'Roboto, sans-serif' },
        colors: ['#14abef', '#02ba5a', '#fba540', '#f5365c'],
        legend: { show: false },
        plotOptions: { pie: { donut: { size: '70%' } } }
    }).render();
});
</script>
@endpush
