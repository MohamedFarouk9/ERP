@extends('admin.layouts.app')
@section('title', 'Inventory Dashboard')

@section('content')
<div class="wrapper">
    <div class="page-wrapper">
        <div class="page-content">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <h4 class="mb-0">Inventory Dashboard</h4>
                    <small class="text-muted">Stock valuation, warehouse distribution, low-stock alerts, and live movements</small>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.warehouses.index') }}" class="btn btn-sm btn-outline-primary">Warehouses</a>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-primary">Manage Products</a>
                </div>
            </div>

            <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
                <div class="col">
                    <div class="card radius-10 bg-gradient-deepblue">
                        <div class="card-body text-white">
                            <div class="d-flex align-items-center">
                                <h5 class="mb-0 text-white">{{ number_format($stats['products_count']) }}</h5>
                                <div class="ms-auto"><i class='bx bx-package fs-3'></i></div>
                            </div>
                            <p class="mb-0 mt-2">Catalog Products</p>
                            <small>{{ $stats['categories_count'] }} Categories · {{ $stats['warehouses_count'] }} Warehouses</small>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card radius-10 bg-gradient-ohhappiness">
                        <div class="card-body text-white">
                            <div class="d-flex align-items-center">
                                <h5 class="mb-0 text-white">{{ number_format($stats['total_units']) }}</h5>
                                <div class="ms-auto"><i class='bx bx-cube fs-3'></i></div>
                            </div>
                            <p class="mb-0 mt-2">Total Units On Hand</p>
                            <small>Across all active warehouses</small>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card radius-10 bg-gradient-orange">
                        <div class="card-body text-white">
                            <div class="d-flex align-items-center">
                                <h5 class="mb-0 text-white">${{ number_format($stats['inventory_valuation'] / 100, 2) }}</h5>
                                <div class="ms-auto"><i class='bx bx-dollar fs-3'></i></div>
                            </div>
                            <p class="mb-0 mt-2">Inventory Valuation (Cost)</p>
                            <small>Based on unit cost price</small>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card radius-10 bg-gradient-ibiza">
                        <div class="card-body text-white">
                            <div class="d-flex align-items-center">
                                <h5 class="mb-0 text-white">{{ number_format($stats['low_stock_count']) }}</h5>
                                <div class="ms-auto"><i class='bx bx-error fs-3'></i></div>
                            </div>
                            <p class="mb-0 mt-2">Low Stock Alerts</p>
                            <small>At or below reorder level</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-6 d-flex">
                    <div class="card radius-10 w-100">
                        <div class="card-body">
                            <h6 class="mb-3">Low Stock Alerts</h6>
                            <div class="table-responsive">
                                <table class="table align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>SKU</th>
                                            <th>Product</th>
                                            <th>Warehouse</th>
                                            <th>On Hand</th>
                                            <th>Reorder</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($lowStockItems as $item)
                                            <tr>
                                                <td><code>{{ $item->product?->sku }}</code></td>
                                                <td>{{ $item->product?->name }}</td>
                                                <td>{{ $item->warehouse?->code }}</td>
                                                <td><span class="badge bg-danger">{{ $item->quantity_on_hand }}</span></td>
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
                                <table class="table align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Product</th>
                                            <th>Warehouse</th>
                                            <th>Type</th>
                                            <th>Qty</th>
                                            <th>Reference</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recentMovements as $mv)
                                            <tr>
                                                <td><code>{{ $mv->product?->sku }}</code></td>
                                                <td>{{ $mv->warehouse?->code }}</td>
                                                <td><span class="badge {{ $mv->type === 'in' ? 'bg-success' : ($mv->type === 'out' ? 'bg-danger' : 'bg-info text-dark') }} text-uppercase">{{ $mv->type }}</span></td>
                                                <td>{{ $mv->quantity }}</td>
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
</div>
@endsection
