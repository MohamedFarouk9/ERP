@extends('admin.layouts.app')
@section('title', 'ERP Dashboard')

@section('content')
<div class="wrapper">
    <div class="page-wrapper">
        <div class="page-content">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <h4 class="mb-0">Executive Overview</h4>
                    <small class="text-muted">Organization: {{ $tenant->name }} ({{ $tenant->slug }})</small>
                </div>
                <div class="d-flex gap-2">
                    @foreach(['accounting', 'inventory', 'sales', 'payments'] as $mod)
                        <span class="badge {{ ($features[$mod] ?? false) ? 'bg-success' : 'bg-secondary' }}">
                            {{ ucfirst($mod) }}: {{ ($features[$mod] ?? false) ? 'Active' : 'Disabled' }}
                        </span>
                    @endforeach
                </div>
            </div>

            <!-- KPI Cards -->
            <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
                <div class="col">
                    <div class="card radius-10 bg-gradient-deepblue">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <h5 class="mb-0 text-white">{{ number_format($stats['orders_count']) }}</h5>
                                <div class="ms-auto"><i class='bx bx-cart fs-3 text-white'></i></div>
                            </div>
                            <div class="progress my-3 bg-light-transparent" style="height:3px;">
                                <div class="progress-bar bg-white" style="width: 70%"></div>
                            </div>
                            <div class="d-flex align-items-center text-white">
                                <p class="mb-0">Sales Orders</p>
                                <p class="mb-0 ms-auto">{{ $stats['customers_count'] }} Customers</p>
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
                                <div class="progress-bar bg-white" style="width: 75%"></div>
                            </div>
                            <div class="d-flex align-items-center text-white">
                                <p class="mb-0">Invoiced Revenue</p>
                                <p class="mb-0 ms-auto">${{ number_format($stats['payments_cents'] / 100, 2) }} Paid</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="card radius-10 bg-gradient-ohhappiness">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <h5 class="mb-0 text-white">{{ number_format($stats['products_count']) }}</h5>
                                <div class="ms-auto"><i class='bx bx-package fs-3 text-white'></i></div>
                            </div>
                            <div class="progress my-3 bg-light-transparent" style="height:3px;">
                                <div class="progress-bar bg-white" style="width: 65%"></div>
                            </div>
                            <div class="d-flex align-items-center text-white">
                                <p class="mb-0">Catalog Products</p>
                                <p class="mb-0 ms-auto">{{ $stats['low_stock_count'] }} Low Stock</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="card radius-10 bg-gradient-ibiza">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <h5 class="mb-0 text-white">{{ number_format($stats['transactions_count']) }}</h5>
                                <div class="ms-auto"><i class='bx bx-book-open fs-3 text-white'></i></div>
                            </div>
                            <div class="progress my-3 bg-light-transparent" style="height:3px;">
                                <div class="progress-bar bg-white" style="width: 80%"></div>
                            </div>
                            <div class="d-flex align-items-center text-white">
                                <p class="mb-0">Ledger Transactions</p>
                                <p class="mb-0 ms-auto">{{ $stats['accounts_count'] }} Accounts</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity Tables -->
            <div class="row">
                <div class="col-12 col-xl-7 d-flex">
                    <div class="card radius-10 w-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <h6 class="mb-0">Recent Sales Orders</h6>
                                @can('sales.orders.view')
                                    <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-primary ms-auto">View All</a>
                                @endcan
                            </div>
                            <div class="table-responsive">
                                <table class="table align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Order #</th>
                                            <th>Customer</th>
                                            <th>Date</th>
                                            <th>Total</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recentOrders as $order)
                                            <tr>
                                                <td><strong>{{ $order->order_number }}</strong></td>
                                                <td>{{ $order->customer?->name ?? 'N/A' }}</td>
                                                <td>{{ $order->order_date?->format('Y-m-d') }}</td>
                                                <td>${{ number_format($order->total_amount / 100, 2) }}</td>
                                                <td><span class="badge bg-info text-dark text-uppercase">{{ $order->status }}</span></td>
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

                <div class="col-12 col-xl-5 d-flex">
                    <div class="card radius-10 w-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <h6 class="mb-0">Recent Payments</h6>
                                @can('payments.transactions.view')
                                    <a href="{{ route('admin.payments.index') }}" class="btn btn-sm btn-outline-primary ms-auto">View All</a>
                                @endcan
                            </div>
                            <div class="table-responsive">
                                <table class="table align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Payment #</th>
                                            <th>Method</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recentPayments as $payment)
                                            <tr>
                                                <td><strong>{{ $payment->payment_number }}</strong></td>
                                                <td>{{ $payment->paymentMethod?->name ?? 'N/A' }}</td>
                                                <td>${{ number_format($payment->amount / 100, 2) }}</td>
                                                <td><span class="badge bg-success text-uppercase">{{ $payment->status }}</span></td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="4" class="text-center text-muted py-4">No payments recorded yet.</td></tr>
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
