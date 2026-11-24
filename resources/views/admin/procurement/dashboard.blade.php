@extends('admin.layouts.app')
@section('title', 'Procurement Analytics Dashboard')

@section('content')
<div class="page-wrapper">
    <div class="page-content">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h4 class="mb-0">Procurement & Payables Analytics</h4>
                <p class="mb-0 font-13 text-secondary">Vendor directory, purchase order spend, goods receipt, and AP liabilities</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.procurement.suppliers.index') }}" class="btn btn-sm btn-outline-primary">Suppliers</a>
                <a href="{{ route('admin.procurement.orders.index') }}" class="btn btn-sm btn-primary">Purchase Orders</a>
            </div>
        </div>

        <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
            <div class="col">
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary">Active Suppliers</p>
                                <h4 class="my-1">{{ number_format($stats['suppliers_count']) }}</h4>
                                <p class="mb-0 font-13 text-primary">Approved vendor base</p>
                            </div>
                            <div class="widgets-icons bg-light-primary text-primary ms-auto"><i class='bx bxs-buildings'></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary">Open Purchase Orders</p>
                                <h4 class="my-1 text-warning">{{ number_format($stats['open_pos_count']) }}</h4>
                                <p class="mb-0 font-13 text-secondary">{{ number_format($stats['received_pos_count']) }} Received</p>
                            </div>
                            <div class="widgets-icons bg-light-warning text-warning ms-auto"><i class='bx bxs-shopping-bag'></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary">Received Spend</p>
                                <h4 class="my-1 text-success">${{ number_format($stats['total_spend_cents'] / 100, 2) }}</h4>
                                <p class="mb-0 font-13 text-success">Stocked into warehouses</p>
                            </div>
                            <div class="widgets-icons bg-light-success text-success ms-auto"><i class='bx bxs-truck'></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary">Unpaid Vendor Bills</p>
                                <h4 class="my-1 text-danger">${{ number_format($stats['unpaid_bills_cents'] / 100, 2) }}</h4>
                                <p class="mb-0 font-13 text-secondary">{{ number_format($stats['bills_count']) }} Total Bills</p>
                            </div>
                            <div class="widgets-icons bg-light-danger text-danger ms-auto"><i class='bx bxs-file-doc'></i></div>
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
                        <h6 class="mb-1">6-Month Purchase Order Spend vs. Vendor Bills</h6>
                        <small class="text-secondary">Monthly supply chain commitments and vendor invoicing</small>
                        <div id="procSpendChart" style="min-height: 300px;"></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 d-flex">
                <div class="card radius-10 w-100">
                    <div class="card-body">
                        <h6 class="mb-1">Purchase Order Status</h6>
                        <small class="text-secondary">Received, Ordered, Draft & Cancelled</small>
                        <div id="procStatusDonut" class="mt-3" style="min-height: 280px;"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-6 d-flex">
                <div class="card radius-10 w-100">
                    <div class="card-body">
                        <h6 class="mb-3">Recent Purchase Orders</h6>
                        <div class="table-responsive">
                            <table class="table align-middle mb-0 table-hover">
                                <thead class="table-light">
                                    <tr><th>PO #</th><th>Supplier</th><th>Total</th><th>Status</th></tr>
                                </thead>
                                <tbody>
                                    @forelse($recentOrders as $po)
                                        <tr>
                                            <td><strong>{{ $po->po_number }}</strong></td>
                                            <td>{{ $po->supplier?->name ?? '-' }}</td>
                                            <td class="fw-bold">${{ number_format($po->total_amount / 100, 2) }}</td>
                                            <td><span class="badge rounded-pill bg-light-info text-info text-uppercase px-3">{{ $po->status }}</span></td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="4" class="text-center text-muted py-3">No purchase orders yet.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-6 d-flex">
                <div class="card radius-10 w-100">
                    <div class="card-body">
                        <h6 class="mb-3">Recent Vendor Bills</h6>
                        <div class="table-responsive">
                            <table class="table align-middle mb-0 table-hover">
                                <thead class="table-light">
                                    <tr><th>Bill #</th><th>Supplier</th><th>Due</th><th>Total</th><th>Status</th></tr>
                                </thead>
                                <tbody>
                                    @forelse($recentBills as $bill)
                                        <tr>
                                            <td><strong>{{ $bill->bill_number }}</strong></td>
                                            <td>{{ $bill->supplier?->name ?? '-' }}</td>
                                            <td>{{ $bill->due_date?->format('d M Y') }}</td>
                                            <td class="fw-bold">${{ number_format($bill->total_amount / 100, 2) }}</td>
                                            <td><span class="badge rounded-pill bg-light-primary text-primary text-uppercase px-3">{{ $bill->status }}</span></td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="5" class="text-center text-muted py-3">No vendor bills yet.</td></tr>
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

    new ApexCharts(document.querySelector("#procSpendChart"), {
        series: [
            { name: 'PO Spend ($)', data: @json($chartData['po_spend']) },
            { name: 'Vendor Bills ($)', data: @json($chartData['bills_volume']) }
        ],
        chart: { type: 'bar', height: 300, toolbar: { show: false }, fontFamily: 'Roboto, sans-serif' },
        colors: ['#14abef', '#fba540'],
        plotOptions: { bar: { borderRadius: 4, columnWidth: '45%' } },
        dataLabels: { enabled: false },
        xaxis: { categories: @json($chartData['months']) },
        yaxis: { labels: { formatter: v => '$' + Number(v).toLocaleString() } }
    }).render();

    new ApexCharts(document.querySelector("#procStatusDonut"), {
        series: [
            {{ $chartData['po_statuses']['received'] }},
            {{ $chartData['po_statuses']['ordered'] }},
            {{ $chartData['po_statuses']['draft'] }},
            {{ $chartData['po_statuses']['cancelled'] }}
        ],
        labels: ['Received', 'Ordered', 'Draft', 'Cancelled'],
        chart: { type: 'donut', height: 280, fontFamily: 'Roboto, sans-serif' },
        colors: ['#02ba5a', '#14abef', '#fba540', '#f5365c'],
        legend: { position: 'bottom' }
    }).render();
});
</script>
@endpush
