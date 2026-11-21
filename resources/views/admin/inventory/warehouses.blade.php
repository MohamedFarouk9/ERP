@extends('admin.layouts.app')
@section('title', 'Warehouses')

@section('content')
<div class="wrapper">
    <div class="page-wrapper">
        <div class="page-content">
            <h4 class="mb-3">Warehouses</h4>
            <div class="card radius-10">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Code</th>
                                    <th>Name</th>
                                    <th>Address</th>
                                    <th>Stock Items</th>
                                    <th>Default</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($warehouses as $warehouse)
                                    <tr>
                                        <td><code>{{ $warehouse->code }}</code></td>
                                        <td><strong>{{ $warehouse->name }}</strong></td>
                                        <td>{{ $warehouse->address ?? '-' }}</td>
                                        <td>{{ $warehouse->stock_levels_count }}</td>
                                        <td>{!! $warehouse->is_default ? '<span class="badge bg-info text-dark">Default</span>' : '-' !!}</td>
                                        <td>
                                            <span class="badge {{ $warehouse->is_active ? 'bg-success' : 'bg-secondary' }}">
                                                {{ $warehouse->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="text-center text-muted py-4">No warehouses found.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">{{ $warehouses->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
