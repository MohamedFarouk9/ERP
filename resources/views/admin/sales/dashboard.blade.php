@extends('admin.layouts.app')
@section('title', __('Sales Analytics'))

@section('content')
<div class="page-wrapper">
    <div class="page-content">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h4 class="mb-0">{{ __('Sales & Billing Analytics') }}</h4>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-primary">{{ __('Sales Orders') }}</a>
                <a href="{{ route('admin.invoices.index') }}" class="btn btn-sm btn-primary">{{ __('Invoices') }}</a>
            </div>
        </div>

        <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
            <div class="col">
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary">{{ __('Sales Orders') }}</p>
                                <h4 class="my-1">{{ number_format($stats['orders_count']) }}</h4>
                                <p class="mb-0 font-13 text-primary">{{ number_format($stats['confirmed_orders']) }} {{ __('Confirmed') }}</p>
                            </div>
                            <div class="widgets-icons bg-light-primary text-primary ms-auto"><i class='bx bxs-cart'></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary">{{ __('Invoiced Revenue') }}</p>
                                <h4 class="my-1 text-success">${{ number_format($stats['invoiced_revenue'] / 100, 2) }}</h4>
                                <p class="mb-0 font-13 text-secondary">{{ number_format($stats['customers_count']) }} {{ __('Clients') }}</p>
                            </div>
                            <div class="widgets-icons bg-light-success text-success ms-auto"><i class='bx bxs-receipt'></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary">{{ __('Unpaid Receivables') }}</p>
                                <h4 class="my-1 text-warning">${{ number_format($stats['outstanding_receivables'] / 100, 2) }}</h4>
                            </div>
                            <div class="widgets-icons bg-light-warning text-warning ms-auto"><i class='bx bx-time-five'></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary">{{ __('Overdue Invoices') }}</p>
                                <h4 class="my-1 text-danger">{{ number_format($stats['overdue_invoices']) }}</h4>
                            </div>
                            <div class="widgets-icons bg-light-danger text-danger ms-auto"><i class='bx bxs-bell-ring'></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts -->
        <div class="row">
            <div class="col-xl-8 d-flex">
                <div class="card radius-10 w-100">
                    <div class="card-body">
                        <h6 class="mb-1">{{ __('Sales Orders') }} &amp; {{ __('Invoiced Revenue') }}</h6>
                        <div id="salesTrendChart" style="min-height: 300px;"></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 d-flex">
                <div class="card radius-10 w-100">
                    <div class="card-body">
                        <h6 class="mb-1">{{ __('Invoice Status Breakdown') }}</h6>
                        <div id="salesInvDonut" class="mt-3" style="min-height: 280px;"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-5 d-flex">
                <div class="card radius-10 w-100">
                    <div class="card-body">
                        <h6 class="mb-3">{{ __('Top Customers by Billed Revenue') }}</h6>
                        <div class="table-responsive">
                            <table class="table align-middle mb-0 table-hover">
                                <thead class="table-light">
                                    <tr><th>{{ __('Customer') }}</th><th>{{ __('Invoices') }}</th><th class="text-end">{{ __('Total') }}</th></tr>
                                </thead>
                                <tbody>
                                    @forelse($topCustomers as $row)
                                        <tr>
                                            <td><strong>{{ $row->customer?->name ?? '-' }}</strong></td>
                                            <td>{{ $row->invoices_count }}</td>
                                            <td class="text-end fw-bold text-success">${{ number_format($row->total_billed / 100, 2) }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="3" class="text-center text-muted py-3">No customer invoices yet.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-7 d-flex">
                <div class="card radius-10 w-100">
                    <div class="card-body">
                        <h6 class="mb-3">Recent Sales Orders</h6>
                        <div class="table-responsive">
                            <table class="table align-middle mb-0 table-hover">
                                <thead class="table-light">
                                    <tr><th>{{ __('Order #') }}</th><th>{{ __('Customer') }}</th><th>{{ __('Date') }}</th><th>{{ __('Total') }}</th><th>{{ __('Status') }}</th></tr>
                                </thead>
                                <tbody>
                                    @forelse($recentOrders as $order)
                                        <tr>
                                            <td><strong>{{ $order->order_number }}</strong></td>
                                            <td>{{ $order->customer?->name ?? '-' }}</td>
                                            <td>{{ $order->order_date?->format('d M Y') }}</td>
                                            <td class="fw-bold">${{ number_format($order->total_amount / 100, 2) }}</td>
                                            <td><span class="badge rounded-pill bg-light-info text-info text-uppercase px-3">{{ $order->status }}</span></td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="5" class="text-center text-muted py-3">No sales orders yet.</td></tr>
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

    new ApexCharts(document.querySelector("#salesTrendChart"), {
        series: [
            { name: 'Sales Orders ($)', data: @json($chartData['orders_volume']) },
            { name: 'Invoiced ($)', data: @json($chartData['invoiced_volume']) }
        ],
        chart: { type: 'area', height: 300, toolbar: { show: false }, fontFamily: 'Roboto, sans-serif' },
        colors: ['#843cf7', '#02ba5a'],
        dataLabels: { enabled: false },
        stroke: { curve: 'smooth', width: 2.5 },
        xaxis: { categories: @json($chartData['months']) },
        yaxis: { labels: { formatter: v => '$' + Number(v).toLocaleString() } }
    }).render();

    new ApexCharts(document.querySelector("#salesInvDonut"), {
        series: [
            {{ $chartData['invoice_statuses']['paid'] }},
            {{ $chartData['invoice_statuses']['issued'] }},
            {{ $chartData['invoice_statuses']['overdue'] }},
            {{ $chartData['invoice_statuses']['draft'] }}
        ],
        labels: ['Paid', 'Issued', 'Overdue', 'Draft'],
        chart: { type: 'donut', height: 280, fontFamily: 'Roboto, sans-serif' },
        colors: ['#02ba5a', '#14abef', '#f5365c', '#fba540'],
        legend: { position: 'bottom' }
    }).render();
});
</script>
@endpush
