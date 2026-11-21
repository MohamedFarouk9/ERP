@extends('admin.layouts.app')
@section('title', 'Chart of Accounts')

@section('content')
<div class="wrapper">
    <div class="page-wrapper">
        <div class="page-content">
            <h4 class="mb-3">Chart of Accounts</h4>
            <div class="card radius-10">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Code</th>
                                    <th>Account Name</th>
                                    <th>Type</th>
                                    <th>Normal Balance</th>
                                    <th>Currency</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($accounts as $account)
                                    <tr>
                                        <td><code>{{ $account->code }}</code></td>
                                        <td><strong>{{ $account->name }}</strong></td>
                                        <td class="text-capitalize">{{ $account->type }}</td>
                                        <td class="text-capitalize">{{ $account->normal_balance }}</td>
                                        <td>{{ $account->currency_code }}</td>
                                        <td>
                                            <span class="badge {{ $account->is_active ? 'bg-success' : 'bg-secondary' }}">
                                                {{ $account->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="text-center text-muted py-4">No accounts found.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">{{ $accounts->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
