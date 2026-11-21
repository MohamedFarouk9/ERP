@extends('admin.layouts.app')
@section('title', 'Customers')

@section('content')
<div class="wrapper">
    <div class="page-wrapper">
        <div class="page-content">
            <h4 class="mb-3">Customers</h4>
            <div class="card radius-10">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Code</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Orders</th>
                                    <th>Invoices</th>
                                    <th>Credit Limit</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($customers as $customer)
                                    <tr>
                                        <td><code>{{ $customer->code }}</code></td>
                                        <td><strong>{{ $customer->name }}</strong></td>
                                        <td>{{ $customer->email ?? '-' }}</td>
                                        <td>{{ $customer->sales_orders_count }}</td>
                                        <td>{{ $customer->invoices_count }}</td>
                                        <td>${{ number_format($customer->credit_limit / 100, 2) }}</td>
                                        <td>
                                            <span class="badge {{ $customer->is_active ? 'bg-success' : 'bg-secondary' }}">
                                                {{ $customer->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="7" class="text-center text-muted py-4">No customers found.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">{{ $customers->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
