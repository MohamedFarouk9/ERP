@extends('admin.layouts.app')
@section('title', __('Inventory Analytics'))

@section('content')
<div class="page-wrapper">
    <div class="page-content">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h4 class="mb-0">{{ __('Inventory & Warehouse Analytics') }}</h4>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.warehouses.index') }}" class="btn btn-sm btn-outline-primary">{{ __('Warehouses') }}</a>
                <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-primary">{{ __('Products Catalog') }}</a>
            </div>
        </div>

        <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
            <div class="col">
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary">{{ __('Catalog SKUs') }}</p>
                                <h4 class="my-1">{{ number_format($stats['products_count']) }}</h4>
                                <p class="mb-0 font-13 text-primary">{{ $stats['categories_count'] }} {{ __('Categories') }}</p>
                            </div>
                            <div class="widgets-icons bg-light-primary text-primary ms-auto"><i class='bx bxs-package'></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary">{{ __('Total Units On Hand') }}</p>
                                <h4 class="my-1 text-success">{{ number_format($stats['total_units']) }}</h4>
                                <p class="mb-0 font-13 text-secondary">{{ $stats['warehouses_count'] }} {{ __('Warehouses') }}</p>
                            </div>
                            <div class="widgets-icons bg-light-success text-success ms-auto"><i class='bx bxs-cube'></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary">{{ __('Stock Cost Valuation') }}</p>
                                <h4 class="my-1">${{ number_format($stats['inventory_valuation'] / 100, 2) }}</h4>
                            </div>
                            <div class="widgets-icons bg-light-warning text-warning ms-auto"><i class='bx bx-dollar'></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary">{{ __('Low Stock Alerts') }}</p>
                                <h4 class="my-1 text-danger">{{ number_format($stats['low_stock_count']) }}</h4>
                            </div>
                            <div class="widgets-icons bg-light-danger text-danger ms-auto"><i class='bx bxs-error'></i></div>
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
                        <h6 class="mb-1">{{ __('Units On Hand by Warehouse') }}</h6>
                        <div id="invWarehouseBar" style="min-height: 290px;"></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 d-flex">
                <div class="card radius-10 w-100">
                    <div class="card-body">
                        <h6 class="mb-1">{{ __('Stock Movement Volume') }}</h6>
                        <div id="invMovementDonut" class="mt-3" style="min-height: 270px;"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-6 d-flex">
                <div class="card radius-10 w-100">
                    <div class="card-body">
                        <h6 class="mb-3">{{ __('Low Stock Replenishment Alerts') }}</h6>
                        <div class="table-responsive">
                            <table class="table align-middle mb-0 table-hover">
                                <thead class="table-light">
                                    <tr><th>{{ __('SKU') }}</th><th>{{ __('Product') }}</th><th>{{ __('Warehouse') }}</th><th>{{ __('Qty') }}</th><th>{{ __('Alerts') }}</th></tr>
                                </thead>
                                <tbody>
                                    @forelse($lowStockItems as $item)
                                        <tr>
                                            <td><code>{{ $item->product?->sku }}</code></td>
                                            <td>{{ $item->product?->name }}</td>
                                            <td>{{ $item->warehouse?->code }}</td>
                                            <td><span class="badge rounded-pill bg-light-danger text-danger px-3">{{ $item->quantity_on_hand }}</span></td>
                                            <td>{{ $item->product?->reorder_level }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="5" class="text-center text-muted py-3">All stock levels are healthy.</td></tr>
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
                        <h6 class="mb-3">Recent Stock Movements</h6>
                        <div class="table-responsive">
                            <table class="table align-middle mb-0 table-hover">
                                <thead class="table-light">
                                    <tr><th>{{ __('SKU') }}</th><th>{{ __('Warehouse') }}</th><th>{{ __('Type') }}</th><th>{{ __('Qty') }}</th><th>{{ __('Source') }}</th></tr>
                                </thead>
                                <tbody>
                                    @forelse($recentMovements as $mv)
                                        <tr>
                                            <td><code>{{ $mv->product?->sku }}</code></td>
                                            <td>{{ $mv->warehouse?->code }}</td>
                                            <td><span class="badge rounded-pill {{ $mv->type === 'in' ? 'bg-light-success text-success' : ($mv->type === 'out' ? 'bg-light-danger text-danger' : 'bg-light-info text-info') }} text-uppercase px-3">{{ $mv->type }}</span></td>
                                            <td class="fw-bold">{{ $mv->quantity }}</td>
                                            <td>{{ $mv->reference_type ?? '-' }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="5" class="text-center text-muted py-3">No stock movements recorded.</td></tr>
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

    new ApexCharts(document.querySelector("#invWarehouseBar"), {
        series: [{ name: 'Units On Hand', data: @json($chartData['warehouse_units']) }],
        chart: { type: 'bar', height: 290, toolbar: { show: false }, fontFamily: 'Roboto, sans-serif' },
        colors: ['#14abef'],
        plotOptions: { bar: { borderRadius: 6, columnWidth: '40%' } },
        dataLabels: { enabled: false },
        xaxis: { categories: @json($chartData['warehouse_labels']) }
    }).render();

    new ApexCharts(document.querySelector("#invMovementDonut"), {
        series: [
            {{ $chartData['movements']['in'] }},
            {{ $chartData['movements']['out'] }},
            {{ $chartData['movements']['adjustment'] }}
        ],
        labels: ['Stock IN', 'Stock OUT', 'Adjustments'],
        chart: { type: 'donut', height: 270, fontFamily: 'Roboto, sans-serif' },
        colors: ['#02ba5a', '#f5365c', '#fba540'],
        legend: { position: 'bottom' }
    }).render();
});
</script>
@endpush
