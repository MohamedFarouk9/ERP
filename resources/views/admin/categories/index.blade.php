@extends('admin.layouts.app')
@section('title', 'Categories')

@section('content')
<div class="wrapper">
    <div class="page-wrapper">
        <div class="page-content">
            <h4 class="mb-3">Product Categories</h4>
            <div class="card radius-10">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Name</th>
                                    <th>Slug</th>
                                    <th>Parent</th>
                                    <th>Products</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($categories as $category)
                                    <tr>
                                        <td><strong>{{ $category->name }}</strong></td>
                                        <td><code>{{ $category->slug }}</code></td>
                                        <td>{{ $category->parent?->name ?? '-' }}</td>
                                        <td>{{ $category->products_count }}</td>
                                        <td>
                                            <span class="badge {{ $category->is_active ? 'bg-success' : 'bg-secondary' }}">
                                                {{ $category->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center text-muted py-4">No categories found.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">{{ $categories->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
