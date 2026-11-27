@extends('admin.layouts.app')
@section('title', __('Chart of Accounts'))

@section('content')
<div class="wrapper">
    <div class="page-wrapper">
        <div class="page-content">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <h4 class="mb-0">{{ __('Chart of Accounts') }}</h4>
                </div>
                @can('accounting.accounts.create')
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createAccountModal">
                        <i class="bx bx-plus"></i> {{ __('Add Account') }}
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
                                    <th>{{ __('Account Name') }}</th>
                                    <th>{{ __('Type') }}</th>
                                    <th>{{ __('Normal Balance') }}</th>
                                    <th>{{ __('Currency') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th class="text-end">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($accounts as $account)
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
                                        <td class="text-end text-nowrap">
                                            @can('accounting.accounts.create')
                                                <button class="btn btn-sm btn-outline-primary btn-edit-account"
                                                    data-id="{{ $account->id }}"
                                                    data-code="{{ $account->code }}"
                                                    data-name="{{ $account->name }}"
                                                    data-type="{{ $account->type }}"
                                                    data-balance="{{ $account->normal_balance }}"
                                                    data-parent="{{ $account->parent_id }}"
                                                    data-currency="{{ $account->currency_code }}"
                                                    data-active="{{ $account->is_active ? 1 : 0 }}"
                                                    data-description="{{ $account->description }}">
                                                    <i class="bx bx-edit"></i>
                                                </button>
                                                <form class="d-inline ajax-delete-form" method="POST" action="{{ route('admin.accounting.accounts.destroy', $account->id) }}" data-title="Delete Account {{ $account->code }}?">
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
</div>

<!-- Create Account Modal -->
<div class="modal fade" id="createAccountModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form class="modal-content ajax-form" method="POST" action="{{ route('admin.accounting.accounts.store') }}">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Add Ledger Account') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body row g-3">
                <div class="col-md-4">
                    <label class="form-label">{{ __('Account Code *') }}</label>
                    <input type="text" name="code" class="form-control" required placeholder="e.g. 1020">
                </div>
                <div class="col-md-8">
                    <label class="form-label">{{ __('Account Name *') }}</label>
                    <input type="text" name="name" class="form-control" required placeholder="e.g. Petty Cash">
                </div>
                <div class="col-md-4">
                    <label class="form-label">{{ __('Type *') }}</label>
                    <select name="type" class="form-select" required>
                        <option value="asset">{{ __('Asset') }}</option>
                        <option value="liability">{{ __('Liability') }}</option>
                        <option value="equity">{{ __('Equity') }}</option>
                        <option value="revenue">{{ __('Revenue') }}</option>
                        <option value="expense">{{ __('Expense') }}</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">{{ __('Normal Balance') }}</label>
                    <select name="normal_balance" class="form-select">
                        <option value="debit">{{ __('Debit') }}</option>
                        <option value="credit">{{ __('Credit') }}</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">{{ __('Currency') }}</label>
                    <select name="currency_code" class="form-select">
                        @foreach($currencies as $cur)
                            <option value="{{ $cur->code }}">{{ $cur->code }} ({{ $cur->symbol }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">{{ __('Parent Account') }}</label>
                    <select name="parent_id" class="form-select">
                        <option value="">{{ __('-- Root Account --') }}</option>
                        @foreach($accounts as $acc)
                            <option value="{{ $acc->id }}">{{ $acc->code }} - {{ $acc->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">{{ __('Description') }}</label>
                    <textarea name="description" class="form-control" rows="2"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __("Cancel") }}</button>
                <button type="submit" class="btn btn-primary">{{ __('Create Account') }}</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Account Modal -->
<div class="modal fade" id="editAccountModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form id="editAccountForm" class="modal-content ajax-form" method="POST" action="">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Edit Ledger Account') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body row g-3">
                <div class="col-md-4">
                    <label class="form-label">{{ __('Account Code *') }}</label>
                    <input type="text" name="code" id="ea_code" class="form-control" required>
                </div>
                <div class="col-md-8">
                    <label class="form-label">{{ __('Account Name *') }}</label>
                    <input type="text" name="name" id="ea_name" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">{{ __('Type *') }}</label>
                    <select name="type" id="ea_type" class="form-select" required>
                        <option value="asset">{{ __('Asset') }}</option>
                        <option value="liability">{{ __('Liability') }}</option>
                        <option value="equity">{{ __('Equity') }}</option>
                        <option value="revenue">{{ __('Revenue') }}</option>
                        <option value="expense">{{ __('Expense') }}</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">{{ __('Normal Balance *') }}</label>
                    <select name="normal_balance" id="ea_balance" class="form-select" required>
                        <option value="debit">{{ __('Debit') }}</option>
                        <option value="credit">{{ __('Credit') }}</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">{{ __('Currency') }}</label>
                    <select name="currency_code" id="ea_currency" class="form-select">
                        @foreach($currencies as $cur)
                            <option value="{{ $cur->code }}">{{ $cur->code }} ({{ $cur->symbol }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">{{ __('Parent Account') }}</label>
                    <select name="parent_id" id="ea_parent" class="form-select">
                        <option value="">{{ __('-- Root Account --') }}</option>
                        @foreach($accounts as $acc)
                            <option value="{{ $acc->id }}">{{ $acc->code }} - {{ $acc->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">{{ __('Status') }}</label>
                    <select name="is_active" id="ea_active" class="form-select">
                        <option value="1">{{ __('Active') }}</option>
                        <option value="0">{{ __('Inactive') }}</option>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">{{ __('Description') }}</label>
                    <textarea name="description" id="ea_description" class="form-control" rows="2"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __("Cancel") }}</button>
                <button type="submit" class="btn btn-primary">{{ __('Update Account') }}</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).on('click', '.btn-edit-account', function() {
    var id = $(this).data('id');
    $('#editAccountForm').attr('action', "{{ url('/admin/accounting/accounts') }}/" + id);
    $('#ea_code').val($(this).data('code'));
    $('#ea_name').val($(this).data('name'));
    $('#ea_type').val($(this).data('type'));
    $('#ea_balance').val($(this).data('balance'));
    $('#ea_parent').val($(this).data('parent') || '');
    $('#ea_currency').val($(this).data('currency'));
    $('#ea_active').val($(this).data('active'));
    $('#ea_description').val($(this).data('description') || '');
    new bootstrap.Modal(document.getElementById('editAccountModal')).show();
});
</script>
@endpush
