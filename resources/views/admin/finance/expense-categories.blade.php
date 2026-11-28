@extends('admin.layouts.app')
@section('title', __('Expense Categories'))

@section('content')
<div class="page-wrapper">
    <div class="page-content">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <h4 class="mb-0">{{ __('Expense Categories') }}</h4>
            </div>
            @can('finance.budgets.manage')
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createExpCatModal">
                    <i class="bx bx-plus me-1"></i> {{ __('Add Expense Category') }}
                </button>
            @endcan
        </div>

        <div class="card radius-10">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered align-middle erp-datatable w-100">
                        <thead class="table-light">
                            <tr>
                                <th>{{ __('Code') }}</th>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Linked Ledger Account') }}</th>
                                <th>{{ __('Expenses') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th class="text-end">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($categories as $cat)
                                <tr>
                                    <td><code>{{ $cat->code }}</code></td>
                                    <td><strong>{{ $cat->name }}</strong></td>
                                    <td>{{ $cat->account ? ($cat->account->code . ' - ' . $cat->account->name) : '-' }}</td>
                                    <td>{{ $cat->expenses_count }}</td>
                                    <td><span class="badge {{ $cat->is_active ? 'bg-success' : 'bg-secondary' }}">{{ $cat->is_active ? __('Active') : __('Inactive') }}</span></td>
                                    <td class="text-end">
                                        @can('finance.budgets.manage')
                                            <form class="d-inline ajax-delete-form" method="POST" action="{{ route('admin.finance.expense-categories.destroy', $cat->id) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bx bx-trash"></i></button>
                                            </form>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Expense Category Modal -->
<div class="modal fade" id="createExpCatModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content ajax-form" method="POST" action="{{ route('admin.finance.expense-categories.store') }}">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Add Expense Category') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body row g-3">
                <div class="col-md-4">
                    <label class="form-label">{{ __('Code *') }}</label>
                    <input type="text" name="code" class="form-control" required placeholder="EXP-TRV">
                </div>
                <div class="col-md-8">
                    <label class="form-label">{{ __('Name *') }}</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="col-12">
                    <label class="form-label">{{ __('Linked Ledger Account') }}</label>
                    <select name="account_id" class="form-select">
                        <option value="">{{ __('-- Optional --') }}</option>
                        @foreach($expenseAccounts as $acc)
                            <option value="{{ $acc->id }}">{{ $acc->code }} - {{ $acc->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">{{ __('Description') }}</label>
                    <input type="text" name="description" class="form-control">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
            </div>
        </form>
    </div>
</div>
@endsection
