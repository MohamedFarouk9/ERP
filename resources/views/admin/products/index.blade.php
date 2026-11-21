@extends('admin.layouts.app')
@section('title', 'Products')

@section('content')
<div class="wrapper">
    <div class="page-wrapper">
        <div class="page-content">
            <h4 class="mb-3">Inventory Products</h4>
            <div class="card radius-10">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>SKU</th>
                                    <th>Name</th>
                                    <th>Category</th>
                                    <th>Cost</th>
                                    <th>Selling Price</th>
                                    <th>Stock On Hand</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($products as $product)
                                    <tr>
                                        <td><code>{{ $product->sku }}</code></td>
                                        <td><strong>{{ $product->name }}</strong></td>
                                        <td>{{ $product->category?->name ?? '-' }}</td>
                                        <td>${{ number_format($product->cost_price / 100, 2) }}</td>
                                        <td>${{ number_format($product->selling_price / 100, 2) }}</td>
                                        <td>{{ $product->stockLevels->sum('quantity_on_hand') }} {{ $product->unit }}</td>
                                        <td>
                                            <span class="badge {{ $product->is_active ? 'bg-success' : 'bg-secondary' }}">
                                                {{ $product->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="7" class="text-center text-muted py-4">No products found.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">{{ $products->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
