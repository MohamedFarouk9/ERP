@extends('admin.layouts.app')
@section('title', __('Profit & Loss'))

@section('content')
<div class="page-wrapper">
    <div class="page-content">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
            <h4 class="mb-0">{{ __('Profit & Loss') }}</h4>
            <form method="GET" class="d-flex gap-2 align-items-center d-print-none">
                <input type="date" name="start_date" value="{{ $start }}" class="form-control form-control-sm">
                <input type="date" name="end_date" value="{{ $end }}" class="form-control form-control-sm">
                <button type="submit" class="btn btn-primary btn-sm">{{ __('Filter') }}</button>
                <button type="button" onclick="window.print()" class="btn btn-outline-secondary btn-sm"><i class="bx bx-printer"></i></button>
            </form>
        </div>

        <div class="row row-cols-1 row-cols-md-4 mb-3">
            <div class="col"><div class="card radius-10"><div class="card-body"><p class="mb-0 text-secondary">{{ __('Total Income') }}</p><h4 class="my-1 text-primary">${{ number_format($totalRevenue / 100, 2) }}</h4></div></div></div>
            <div class="col"><div class="card radius-10"><div class="card-body"><p class="mb-0 text-secondary">{{ __('Gross Profit') }}</p><h4 class="my-1 text-info">${{ number_format($grossProfit / 100, 2) }}</h4></div></div></div>
            <div class="col"><div class="card radius-10"><div class="card-body"><p class="mb-0 text-secondary">{{ __('Net Profit') }}</p><h4 class="my-1 {{ $netProfit >= 0 ? 'text-success' : 'text-danger' }}">${{ number_format($netProfit / 100, 2) }}</h4></div></div></div>
            <div class="col"><div class="card radius-10"><div class="card-body"><p class="mb-0 text-secondary">{{ __('Net Margin') }}</p><h4 class="my-1">{{ $netMargin }}%</h4></div></div></div>
        </div>

        <div class="card radius-10">
            <div class="card-body">
                <table class="table table-bordered align-middle mb-0">
                    <tbody>
                        <tr class="table-light"><th colspan="2">{{ __('Total Income') }}</th></tr>
                        <tr><td>{{ __('B2B Invoiced Income') }}</td><td class="text-end">${{ number_format($b2bRevenue / 100, 2) }}</td></tr>
                        <tr><td>{{ __('POS Retail Income') }}</td><td class="text-end">${{ number_format($posRevenue / 100, 2) }}</td></tr>
                        <tr class="fw-bold"><td>{{ __('Total Income') }}</td><td class="text-end text-primary">${{ number_format($totalRevenue / 100, 2) }}</td></tr>

                        <tr class="table-light"><th colspan="2">{{ __('Vendor Bills') }}</th></tr>
                        <tr><td>{{ __('Vendor Bills') }}</td><td class="text-end text-danger">-${{ number_format($cogsBills / 100, 2) }}</td></tr>
                        <tr class="fw-bold"><td>{{ __('Gross Profit') }}</td><td class="text-end text-info">${{ number_format($grossProfit / 100, 2) }}</td></tr>

                        <tr class="table-light"><th colspan="2">{{ __('Operating Expenses') }}</th></tr>
                        @foreach($expenseBreakdown as $eb)
                            <tr><td>{{ $eb->name }}</td><td class="text-end text-danger">-${{ number_format($eb->amount / 100, 2) }}</td></tr>
                        @endforeach
                        <tr><td>{{ __('Payroll Expenses') }}</td><td class="text-end text-danger">-${{ number_format($payrollExpenses / 100, 2) }}</td></tr>
                        <tr class="table-light fs-5 fw-bold">
                            <td>{{ __('Net Profit') }}</td>
                            <td class="text-end {{ $netProfit >= 0 ? 'text-success' : 'text-danger' }}">${{ number_format($netProfit / 100, 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
