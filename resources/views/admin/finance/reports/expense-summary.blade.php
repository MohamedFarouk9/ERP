@extends('admin.layouts.app')
@section('title', __('Expense Summary'))

@section('content')
<div class="page-wrapper">
    <div class="page-content">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
            <h4 class="mb-0">{{ __('Expense Summary') }}</h4>
            <form method="GET" class="d-flex gap-2 align-items-center d-print-none">
                <input type="date" name="start_date" value="{{ $start }}" class="form-control form-control-sm">
                <input type="date" name="end_date" value="{{ $end }}" class="form-control form-control-sm">
                <button type="submit" class="btn btn-primary btn-sm">{{ __('Filter') }}</button>
                <button type="button" onclick="window.print()" class="btn btn-outline-secondary btn-sm"><i class="bx bx-printer"></i></button>
            </form>
        </div>

        <div class="row row-cols-1 row-cols-md-3 mb-3">
            <div class="col"><div class="card radius-10"><div class="card-body"><p class="mb-0 text-secondary">{{ __('Operating Expenses') }}</p><h4 class="my-1 text-danger">${{ number_format($operatingTotal / 100, 2) }}</h4></div></div></div>
            <div class="col"><div class="card radius-10"><div class="card-body"><p class="mb-0 text-secondary">{{ __('Vendor Bills') }}</p><h4 class="my-1 text-warning">${{ number_format($vendorBillsTotal / 100, 2) }}</h4></div></div></div>
            <div class="col"><div class="card radius-10"><div class="card-body"><p class="mb-0 text-secondary">{{ __('Payroll Expenses') }}</p><h4 class="my-1 text-info">${{ number_format($payrollTotal / 100, 2) }}</h4></div></div></div>
        </div>

        <div class="card radius-10">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>{{ __('Code') }}</th>
                                <th>{{ __('Expense Category') }}</th>
                                <th class="text-end">{{ __('Subtotal') }}</th>
                                <th class="text-end">{{ __('Tax') }}</th>
                                <th class="text-end">{{ __('Total') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($byCategory as $row)
                                <tr>
                                    <td><code>{{ $row->code }}</code></td>
                                    <td><strong>{{ $row->name }}</strong></td>
                                    <td class="text-end">${{ number_format($row->subtotal / 100, 2) }}</td>
                                    <td class="text-end">${{ number_format($row->tax / 100, 2) }}</td>
                                    <td class="text-end fw-bold">${{ number_format($row->total / 100, 2) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center text-muted py-4">-</td></tr>
                            @endforelse
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <th colspan="4" class="text-end">{{ __('Total Outgoing') }}</th>
                                <th class="text-end text-danger">${{ number_format(($operatingTotal + $vendorBillsTotal + $payrollTotal) / 100, 2) }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
