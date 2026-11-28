@extends('admin.layouts.app')
@section('title', __('Inventory Dashboard'))

@push('styles')
<style>
    .inv-dash-card {
        border-radius: 12px;
        border: 1px solid rgba(148, 163, 184, 0.16);
        transition: border-color .15s ease;
    }
    .inv-icon-box {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        color: #fff;
        flex-shrink: 0;
    }
    .inv-icon-emerald { background: #0ea572; }
    .inv-icon-orange { background: #f97316; }
    .inv-soft-emerald { background: rgba(14, 165, 114, 0.15); color: #10b981; }
    .inv-soft-danger { background: rgba(239, 68, 68, 0.15); color: #ef4444; }
    .inv-soft-warning { background: rgba(245, 158, 11, 0.15); color: #f59e0b; }
    .inv-list-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 12px 0;
        border-bottom: 1px solid rgba(148, 163, 184, 0.12);
    }
    .inv-list-row:last-child { border-bottom: 0; padding-bottom: 0; }
    .inv-list-row:first-child { padding-top: 4px; }
    .inv-avatar-tile {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: rgba(148, 163, 184, 0.12);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }
    .inv-ring-wrap {
        position: relative;
        width: 44px;
        height: 44px;
        flex-shrink: 0;
    }
    .inv-ring-wrap svg { transform: rotate(-90deg); }
    .inv-ring-label {
        position: absolute;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        font-weight: 600;
    }
    .inv-year-pill {
        font-size: 12px;
        padding: 4px 10px;
        border-radius: 8px;
        border: 1px solid rgba(148, 163, 184, 0.22);
        background: transparent;
        color: inherit;
    }
    @media print {
        .d-print-none { display: none !important; }
    }
</style>
@endpush

@section('content')
<div class="page-wrapper">
    <div class="page-content">
        <!-- Top Header -->
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-4">
            <h4 class="mb-0 fw-bold">{{ __('Inventory Dashboard') }}</h4>
            <div class="d-flex align-items-center gap-2 flex-wrap d-print-none">
                <form method="GET" action="{{ route('admin.inventory.dashboard') }}" class="d-inline-flex">
                    <select name="year" class="form-select form-select-sm" onchange="this.form.submit()" style="width: 100px;">
                        @for($y = now()->year + 1; $y >= now()->year - 3; $y--)
                            <option value="{{ $y }}" {{ $year === $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </form>
                <button type="button" onclick="window.print()" class="btn btn-sm btn-outline-secondary">
                    <i class="bx bx-download me-1"></i> {{ __('Export') }}
                </button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-primary">
                    <i class="bx bx-plus me-1"></i> {{ __('Add Inventory') }}
                </a>
            </div>
        </div>

        <!-- Row 1: Stacked Sparkline KPIs | Category Distribution | Product Stock Levels -->
        <div class="row g-3 mb-3">
            <!-- Stacked Sparkline KPI Cards -->
            <div class="col-xl-3 col-lg-6 d-flex flex-column gap-3">
                <div class="card inv-dash-card mb-0 flex-fill">
                    <div class="card-body pb-2 d-flex flex-column justify-content-between">
                        <div class="d-flex align-items-start justify-content-between mb-2">
                            <div>
                                <div class="text-secondary small mb-1">{{ __('Total Stock') }}</div>
                                <div class="d-flex align-items-center gap-2">
                                    <h3 class="mb-0 fw-bold">{{ number_format($stats['total_units']) }}</h3>
                                    <span class="badge {{ $stats['stock_growth_pct'] >= 0 ? 'inv-soft-emerald' : 'inv-soft-danger' }}">
                                        {{ $stats['stock_growth_pct'] >= 0 ? '+' : '' }}{{ number_format($stats['stock_growth_pct'], 2) }}%
                                    </span>
                                </div>
                            </div>
                            <span class="inv-icon-box inv-icon-emerald">
                                <i class="bx bx-cube"></i>
                            </span>
                        </div>
                        <div id="invTotalStockSpark" style="min-height: 75px;"></div>
                    </div>
                </div>

                <div class="card inv-dash-card mb-0 flex-fill">
                    <div class="card-body pb-2 d-flex flex-column justify-content-between">
                        <div class="d-flex align-items-start justify-content-between mb-2">
                            <div>
                                <div class="text-secondary small mb-1">{{ __('Inventory Value') }}</div>
                                <div class="d-flex align-items-center gap-2">
                                    <h3 class="mb-0 fw-bold">${{ number_format($stats['inventory_valuation'] / 100, 0) }}</h3>
                                    @if($stats['low_stock_count'] > 0)
                                        <span class="badge inv-soft-danger">{{ $stats['low_stock_count'] }} {{ __('Low') }}</span>
                                    @else
                                        <span class="badge inv-soft-emerald">{{ __('Healthy') }}</span>
                                    @endif
                                </div>
                            </div>
                            <span class="inv-icon-box inv-icon-orange">
                                <i class="bx bx-dollar-circle"></i>
                            </span>
                        </div>
                        <div id="invValueSpark" style="min-height: 75px;"></div>
                    </div>
                </div>
            </div>

            <!-- Category Distribution -->
            <div class="col-xl-4 col-lg-6 d-flex">
                <div class="card inv-dash-card mb-0 w-100">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <h6 class="mb-0 fw-bold">{{ __('Category Distribution') }}</h6>
                            <span class="inv-year-pill">{{ $year }}</span>
                        </div>
                        <div id="invCategoryBar" class="flex-fill" style="min-height: 255px;"></div>
                        <div class="d-flex align-items-center gap-2 small text-secondary pt-2">
                            <span style="width: 8px; height: 8px; border-radius: 50%; background: #d946ef; display: inline-block;"></span>
                            <span>
                                {{ __('Catalog Summary') }}:
                                <strong class="text-success">{{ $stats['products_count'] }} {{ __('Products') }}</strong>
                                · {{ $stats['categories_count'] }} {{ __('Categories') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Stock Levels -->
            <div class="col-xl-5 col-lg-12 d-flex">
                <div class="card inv-dash-card mb-0 w-100">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <h6 class="mb-0 fw-bold">{{ __('Product Stock Levels') }}</h6>
                            <span class="inv-year-pill">{{ $year }}</span>
                        </div>
                        <div id="invStockLevelsCombo" class="flex-fill" style="min-height: 280px;"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Row 2: Suppliers | Warehouse -->
        <div class="row g-3 mb-3">
            <!-- Suppliers Card -->
            <div class="col-xl-6 d-flex">
                <div class="card inv-dash-card mb-0 w-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h6 class="mb-0 fw-bold">{{ __('Suppliers') }}</h6>
                            @if(Route::has('admin.suppliers.index'))
                                <a href="{{ route('admin.suppliers.index') }}" class="btn btn-sm btn-outline-secondary">
                                    {{ __('View All') }} <i class="bx bx-chevron-right"></i>
                                </a>
                            @endif
                        </div>

                        @php
                            $supplierIcons = ['bx-laptop text-warning', 'bx-headphone text-info', 'bx-package text-danger', 'bx-store text-primary', 'bx-box text-success'];
                        @endphp

                        @forelse($suppliers as $idx => $supplier)
                            <div class="inv-list-row">
                                <div class="d-flex align-items-center gap-3 min-w-0">
                                    <div class="inv-avatar-tile">
                                        <i class="bx {{ $supplierIcons[$idx % count($supplierIcons)] }}"></i>
                                    </div>
                                    <div class="text-truncate">
                                        <div class="small text-secondary">#{{ $supplier->code }}</div>
                                        <div class="fw-semibold text-truncate">{{ $supplier->name }}</div>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <div class="small text-secondary">{{ __('Goods Supplied') }}</div>
                                    <div class="fw-bold">${{ number_format(((int) ($supplier->supplied_total ?? 0)) / 100, 0) }}</div>
                                </div>
                                <div>
                                    @if($supplier->is_active)
                                        <span class="badge inv-soft-emerald px-3 py-2">{{ __('Active') }}</span>
                                    @else
                                        <span class="badge inv-soft-danger px-3 py-2">{{ __('Inactive') }}</span>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-secondary py-4">{{ __('No suppliers recorded yet.') }}</div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Warehouse Card -->
            <div class="col-xl-6 d-flex">
                <div class="card inv-dash-card mb-0 w-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h6 class="mb-0 fw-bold">{{ __('Warehouses') }}</h6>
                            <a href="{{ route('admin.warehouses.index') }}" class="btn btn-sm btn-outline-secondary">
                                {{ __('View All') }} <i class="bx bx-chevron-right"></i>
                            </a>
                        </div>

                        @php
                            $ringColors = ['#0ea572', '#f97316', '#3b82f6', '#ec4899', '#8b5cf6'];
                            $circumference = 2 * 3.14159 * 18;
                        @endphp

                        @forelse($warehouses as $idx => $wh)
                            @php
                                $strokeColor = $ringColors[$idx % count($ringColors)];
                                $dashOffset = $circumference - ($wh['percent'] / 100) * $circumference;
                            @endphp
                            <div class="inv-list-row">
                                <div class="min-w-0" style="flex: 1 1 38%;">
                                    <div class="small text-secondary">#{{ $wh['code'] }}</div>
                                    <div class="fw-semibold text-truncate">{{ $wh['name'] }}</div>
                                </div>
                                <div class="min-w-0 d-none d-sm-block" style="flex: 1 1 32%;">
                                    <div class="small text-secondary">{{ __('Location') }}</div>
                                    <div class="fw-medium text-truncate">{{ $wh['address'] }}</div>
                                </div>
                                <div class="text-end" style="flex: 0 0 auto;">
                                    <div class="small text-secondary">{{ __('On Hand') }}</div>
                                    <div class="fw-bold">{{ number_format($wh['qty']) }}</div>
                                </div>
                                <div class="inv-ring-wrap">
                                    <svg width="44" height="44" viewBox="0 0 44 44">
                                        <circle cx="22" cy="22" r="18" fill="none" stroke="rgba(148,163,184,0.2)" stroke-width="4" />
                                        <circle cx="22" cy="22" r="18" fill="none" stroke="{{ $strokeColor }}" stroke-width="4"
                                            stroke-linecap="round"
                                            stroke-dasharray="{{ number_format($circumference, 2, '.', '') }}"
                                            stroke-dashoffset="{{ number_format($dashOffset, 2, '.', '') }}" />
                                    </svg>
                                    <div class="inv-ring-label">{{ $wh['percent'] }}%</div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-secondary py-4">{{ __('No warehouses found.') }}</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Row 3: Full-Width Inventory Value Trend Area Chart -->
        <div class="row g-3 mb-3">
            <div class="col-12">
                <div class="card inv-dash-card mb-0">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h6 class="mb-0 fw-bold">{{ __('Inventory Value') }}</h6>
                            <span class="inv-year-pill">{{ $year }}</span>
                        </div>
                        <div id="invValueTrendArea" style="min-height: 290px;"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Row 4: Inventory Products Table -->
        <div class="row g-3">
            <div class="col-12">
                <div class="card inv-dash-card mb-0">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h6 class="mb-0 fw-bold">{{ __('Inventory Products') }}</h6>
                            <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-outline-secondary">
                                {{ __('View All') }} <i class="bx bx-chevron-right"></i>
                            </a>
                        </div>
                        <div class="table-responsive">
                            <table class="table align-middle mb-0 table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>{{ __('SKU') }}</th>
                                        <th>{{ __('Product') }}</th>
                                        <th>{{ __('Category') }}</th>
                                        <th>{{ __('Unit') }}</th>
                                        <th class="text-end">{{ __('Stock Qty') }}</th>
                                        <th class="text-end">{{ __('Selling Price') }}</th>
                                        <th class="text-end">{{ __('Cost Price') }}</th>
                                        <th class="text-center">{{ __('Status') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($inventoryProducts as $product)
                                        @php
                                            $qty = (int) ($product->total_qty ?? 0);
                                            $reorder = (int) ($product->reorder_level ?? 0);
                                        @endphp
                                        <tr>
                                            <td><span class="fw-semibold text-primary">{{ $product->sku }}</span></td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="inv-avatar-tile" style="width: 32px; height: 32px; font-size: 15px;">
                                                        <i class="bx bx-package"></i>
                                                    </div>
                                                    <span class="fw-semibold">{{ $product->name }}</span>
                                                </div>
                                            </td>
                                            <td class="text-secondary">{{ $product->category?->name ?? '-' }}</td>
                                            <td class="text-secondary">{{ $product->unit ?? 'pcs' }}</td>
                                            <td class="text-end fw-semibold">{{ number_format($qty) }}</td>
                                            <td class="text-end">${{ number_format(($product->selling_price ?? 0) / 100, 2) }}</td>
                                            <td class="text-end">${{ number_format(($product->cost_price ?? 0) / 100, 2) }}</td>
                                            <td class="text-center">
                                                @if($qty <= 0)
                                                    <span class="badge inv-soft-danger px-2 py-1">{{ __('Out of Stock') }}</span>
                                                @elseif($qty <= $reorder)
                                                    <span class="badge inv-soft-warning px-2 py-1">{{ __('Low Stock') }}</span>
                                                @else
                                                    <span class="badge inv-soft-emerald px-2 py-1">{{ __('In Stock') }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center text-secondary py-4">{{ __('No products found.') }}</td>
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
    if (typeof ApexCharts === 'undefined') return;

    const isDark = document.documentElement.classList.contains('dark-theme');
    const labelColor = isDark ? '#94a3b8' : '#64748b';
    const gridColor = isDark ? 'rgba(148, 163, 184, 0.12)' : 'rgba(15, 23, 42, 0.08)';
    const months = @json($chartData['months']);
    const stockSeries = @json($chartData['monthly_stock_series']);
    const valueSeries = @json($chartData['monthly_value_series']);
    const monthlyIn = @json($chartData['monthly_in']);
    const monthlyOut = @json($chartData['monthly_out']);
    const catLabels = @json($chartData['category_labels']);
    const catValues = @json($chartData['category_values']);

    // 1. Total Stock Sparkline
    new ApexCharts(document.querySelector('#invTotalStockSpark'), {
        series: [{ name: @json(__('Total Stock')), data: stockSeries }],
        chart: { type: 'area', height: 75, sparkline: { enabled: true } },
        stroke: { curve: 'smooth', width: 2 },
        colors: ['#0ea572'],
        fill: {
            type: 'gradient',
            gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0.02, stops: [0, 100] }
        },
        tooltip: { theme: isDark ? 'dark' : 'light' }
    }).render();

    // 2. Inventory Value Sparkline
    new ApexCharts(document.querySelector('#invValueSpark'), {
        series: [{ name: @json(__('Inventory Value')), data: valueSeries }],
        chart: { type: 'area', height: 75, sparkline: { enabled: true } },
        stroke: { curve: 'smooth', width: 2 },
        colors: ['#f97316'],
        fill: {
            type: 'gradient',
            gradient: { shadeIntensity: 1, opacityFrom: 0.35, opacityTo: 0.02, stops: [0, 100] }
        },
        tooltip: {
            theme: isDark ? 'dark' : 'light',
            y: { formatter: (val) => '$' + Number(val).toLocaleString() }
        }
    }).render();

    // 3. Category Distribution Horizontal Bar
    new ApexCharts(document.querySelector('#invCategoryBar'), {
        series: [{ name: @json(__('Stock Units')), data: catValues.length ? catValues : [0] }],
        chart: { type: 'bar', height: 255, toolbar: { show: false } },
        colors: ['#0ea572'],
        plotOptions: {
            bar: { horizontal: true, borderRadius: 4, barHeight: '55%' }
        },
        dataLabels: { enabled: false },
        grid: { borderColor: gridColor, xaxis: { lines: { show: true } }, yaxis: { lines: { show: false } } },
        xaxis: {
            categories: catLabels.length ? catLabels : [@json(__('Uncategorized'))],
            labels: { style: { colors: labelColor } }
        },
        yaxis: { labels: { style: { colors: labelColor } } },
        tooltip: { theme: isDark ? 'dark' : 'light' }
    }).render();

    // 4. Product Stock Levels (Column + Smooth Line Combo)
    new ApexCharts(document.querySelector('#invStockLevelsCombo'), {
        series: [
            { name: @json(__('Stock In')), type: 'column', data: monthlyIn },
            { name: @json(__('Stock Out')), type: 'line', data: monthlyOut }
        ],
        chart: { height: 280, type: 'line', toolbar: { show: false } },
        colors: ['#0ea572', '#f97316'],
        stroke: { width: [0, 2.5], curve: 'smooth' },
        plotOptions: { bar: { borderRadius: 4, columnWidth: '42%' } },
        dataLabels: { enabled: false },
        grid: { borderColor: gridColor, strokeDashArray: 4 },
        xaxis: { categories: months, labels: { style: { colors: labelColor } } },
        yaxis: { labels: { style: { colors: labelColor } } },
        legend: {
            position: 'bottom',
            labels: { colors: labelColor },
            markers: { radius: 12 }
        },
        tooltip: { theme: isDark ? 'dark' : 'light' }
    }).render();

    // 5. Full-Width Inventory Value Spline Area Chart
    new ApexCharts(document.querySelector('#invValueTrendArea'), {
        series: [{ name: @json(__('Inventory Value')), data: valueSeries }],
        chart: { type: 'area', height: 290, toolbar: { show: false } },
        colors: ['#0ea572'],
        stroke: { curve: 'smooth', width: 2.5 },
        fill: {
            type: 'gradient',
            gradient: { shadeIntensity: 1, opacityFrom: 0.35, opacityTo: 0.02, stops: [0, 95] }
        },
        dataLabels: { enabled: false },
        grid: { borderColor: gridColor, strokeDashArray: 4 },
        xaxis: { categories: months, labels: { style: { colors: labelColor } } },
        yaxis: {
            labels: {
                style: { colors: labelColor },
                formatter: (val) => '$' + Number(val).toLocaleString()
            }
        },
        tooltip: {
            theme: isDark ? 'dark' : 'light',
            y: { formatter: (val) => '$' + Number(val).toLocaleString() }
        }
    }).render();
});
</script>
@endpush
