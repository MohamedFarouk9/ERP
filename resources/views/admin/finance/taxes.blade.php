@extends('admin.layouts.app')
@section('title', __('Taxes'))

@section('content')
<div class="page-wrapper">
    <div class="page-content">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <h4 class="mb-0">{{ __('Tax Rates') }}</h4>
            </div>
            @can('finance.budgets.manage')
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createTaxModal">
                    <i class="bx bx-plus me-1"></i> {{ __('Add Tax Rate') }}
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
                                <th>{{ __('Rate (%)') }}</th>
                                <th>{{ __('Tax Scope') }}</th>
                                <th>{{ __('Default') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th class="text-end">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($taxRates as $tr)
                                <tr>
                                    <td><code>{{ $tr->code }}</code></td>
                                    <td><strong>{{ $tr->name }}</strong></td>
                                    <td class="fw-bold">{{ number_format($tr->ratePercent(), 2) }}%</td>
                                    <td class="text-uppercase">{{ $tr->type }}</td>
                                    <td>{!! $tr->is_default ? '<span class="badge bg-info text-dark">' . __('Default') . '</span>' : '-' !!}</td>
                                    <td><span class="badge {{ $tr->is_active ? 'bg-success' : 'bg-secondary' }}">{{ $tr->is_active ? __('Active') : __('Inactive') }}</span></td>
                                    <td class="text-end">
                                        @can('finance.budgets.manage')
                                            <form class="d-inline ajax-delete-form" method="POST" action="{{ route('admin.finance.taxes.destroy', $tr->id) }}">
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

<!-- Add Tax Rate Modal -->
<div class="modal fade" id="createTaxModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content ajax-form" method="POST" action="{{ route('admin.finance.taxes.store') }}">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Add Tax Rate') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body row g-3">
                <div class="col-md-4">
                    <label class="form-label">{{ __('Code *') }}</label>
                    <input type="text" name="code" class="form-control" required placeholder="VAT-14">
                </div>
                <div class="col-md-8">
                    <label class="form-label">{{ __('Name *') }}</label>
                    <input type="text" name="name" class="form-control" required placeholder="VAT 14%">
                </div>
                <div class="col-md-6">
                    <label class="form-label">{{ __('Rate (bps)') }} *</label>
                    <input type="number" name="rate_bps" class="form-control" value="1500" min="0" max="10000" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">{{ __('Tax Scope') }} *</label>
                    <select name="type" class="form-select" required>
                        <option value="both">{{ __('Both (Sales & Purchase)') }}</option>
                        <option value="sales">{{ __('Sales') }}</option>
                        <option value="purchase">{{ __('Purchasing') }}</option>
                    </select>
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
