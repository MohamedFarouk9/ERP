@extends('admin.layouts.app')
@section('title', 'Sales Dashboard')

@section('content')
<div class="wrapper">
    <div class="page-wrapper">
        <div class="page-content">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <h4 class="mb-0">Sales Dashboard</h4>
                    <small class="text-muted">Order volume, billed revenue, outstanding receivables, and top customers</small>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-primary">Sales Orders</a>
                    <a href="{{ route('admin.invoices.index') }}" class="btn btn-sm btn-primary">Invoices</a>
                </div>
            </div>

            <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
                <div class="col">
                    <div class="card radius-10 bg-gradient-deepblue">
                        <div class="card-body text-white">
                            <div class="d-flex align-items-center">
                                <h5 class="mb-0 text-white">{{ number_format($stats['orders_count']) }}</h5>
                                <div class="ms-auto"><i class='bx bx-shopping-bag fs-3'></i></div>
                            </div>
                            <p class="mb-0 mt-2">Total Sales Orders</p>
                            <small>{{ $stats['confirmed_orders'] }} Confirmed · {{ $stats['customers_count'] }} Customers</small>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card radius-10 bg-gradient-ohhappiness">
                        <div class="card-body text-white">
                            <div class="d-flex align-items-center">
                                <h5 class="mb-0 text-white">${{ number_format($stats['invoiced_revenue'] / 100, 2) }}</h5>
                                <div class="ms-auto"><i class='bx bx-receipt fs-3'></i></div>
                            </div>
                            <p class="mb-0 mt-2">Invoiced Revenue</p>
                            <small>Issued, Paid & Overdue</small>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card radius-10 bg-gradient-orange">
                        <div class="card-body text-white">
                            <div class="d-flex align-items-center">
                                <h5 class="mb-0 text-white">${{ number_format($stats['outstanding_receivables'] / 100, 2) }}</h5>
                                <div class="ms-auto"><i class='bx bx-time-five fs-3'></i></div>
                            </div>
                            <p class="mb-0 mt-2">Outstanding Receivables</p>
                            <small>Unpaid invoice balance</small>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card radius-10 bg-gradient-ibiza">
                        <div class="card-body text-white">
                            <div class="d-flex align-items-center">
                                <h5 class="mb-0 text-white">{{ number_format($stats['overdue_invoices']) }}</h5>
                                <div class="ms-auto"><i class='bx bx-alarm-exclamation fs-3'></i></div>
                            </div>
                            <p class="mb-0 mt-2">Overdue Invoices</p>
                            <small>Require collection follow-up</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-5 d-flex">
                    <div class="card radius-10 w-100">
                        <div class="card-body">
                            <h6 class="mb-3">Top Customers by Billed Revenue</h6>
                            <div class="table-responsive">
                                <table class="table align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Customer</th>
                                            <th>Invoices</th>
                                            <th class="text-end">Total Billed</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($topCustomers as $row)
                                            <tr>
                                                <td><strong>{{ $row->customer?->name ?? '-' }}</strong></td>
                                                <td>{{ $row->invoices_count }}</td>
                                                <td class="text-end">${{ number_format($row->total_billed / 100, 2) }}</td>
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
                                                <td>{{ $order->customer?->name ?? '-' }}</td>
                                                <td>{{ $order->order_date?->format('Y-m-d') }}</td>
                                                <td>${{ number_format($order->total_amount / 100, 2) }}</td>
                                                <td><span class="badge bg-info text-dark text-uppercase">{{ $order->status }}</span></td>
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
</div>
@endsection
