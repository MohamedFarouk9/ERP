@extends('admin.layouts.app')
@section('title', 'Roles')

@section('content')
<div class="wrapper">
    <div class="page-wrapper">
        <div class="page-content">
            <h4 class="mb-3">Tenant Roles</h4>
            <div class="card radius-10">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Role Name</th>
                                    <th>Slug</th>
                                    <th>Permissions</th>
                                    <th>Users</th>
                                    <th>Type</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($roles as $role)
                                    <tr>
                                        <td><strong>{{ $role->name }}</strong></td>
                                        <td><code>{{ $role->slug }}</code></td>
                                        <td>{{ $role->permissions_count }}</td>
                                        <td>{{ $role->users_count }}</td>
                                        <td>
                                            <span class="badge {{ $role->is_system ? 'bg-primary' : 'bg-secondary' }}">
                                                {{ $role->is_system ? 'System' : 'Custom' }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center text-muted py-4">No roles found.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">{{ $roles->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
