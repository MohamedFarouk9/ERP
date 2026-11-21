@extends('admin.layouts.app')
@section('title', 'Sales Orders')

@section('content')
<div class="wrapper">
    <div class="page-wrapper">
        <div class="page-content">
            <h4 class="mb-3">Sales Orders</h4>
            <div class="card radius-10">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Order #</th>
                                    <th>Customer</th>
                                    <th>Order Date</th>
                                    <th>Items</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $order)
                                    <tr>
                                        <td><strong>{{ $order->order_number }}</strong></td>
                                        <td>{{ $order->customer?->name ?? '-' }}</td>
                                        <td>{{ $order->order_date?->format('Y-m-d') }}</td>
                                        <td>{{ $order->items->count() }}</td>
                                        <td>${{ number_format($order->total_amount / 100, 2) }}</td>
                                        <td><span class="badge bg-info text-dark text-uppercase">{{ $order->status }}</span></td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="text-center text-muted py-4">No sales orders found.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">{{ $orders->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
