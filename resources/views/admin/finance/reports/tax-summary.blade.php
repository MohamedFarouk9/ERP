@extends('admin.layouts.app')
@section('title', __('Tax Summary'))

@section('content')
<div class="page-wrapper">
    <div class="page-content">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
            <h4 class="mb-0">{{ __('Tax Summary') }}</h4>
            <form method="GET" class="d-flex gap-2 align-items-center d-print-none">
                <input type="date" name="start_date" value="{{ $start }}" class="form-control form-control-sm">
                <input type="date" name="end_date" value="{{ $end }}" class="form-control form-control-sm">
                <button type="submit" class="btn btn-primary btn-sm">{{ __('Filter') }}</button>
                <button type="button" onclick="window.print()" class="btn btn-outline-secondary btn-sm"><i class="bx bx-printer"></i></button>
            </form>
        </div>

        <div class="row row-cols-1 row-cols-md-3 mb-3">
            <div class="col"><div class="card radius-10 border-start border-0 border-4 border-primary"><div class="card-body"><p class="mb-0 text-secondary">{{ __('Output Tax (Collected)') }}</p><h4 class="my-1 text-primary">${{ number_format($outputTax / 100, 2) }}</h4></div></div></div>
            <div class="col"><div class="card radius-10 border-start border-0 border-4 border-success"><div class="card-body"><p class="mb-0 text-secondary">{{ __('Input Tax (Paid)') }}</p><h4 class="my-1 text-success">${{ number_format($inputTax / 100, 2) }}</h4></div></div></div>
            <div class="col"><div class="card radius-10 border-start border-0 border-4 border-danger"><div class="card-body"><p class="mb-0 text-secondary">{{ __('Net Tax Payable') }}</p><h4 class="my-1 text-danger">${{ number_format($netTaxPayable / 100, 2) }}</h4></div></div></div>
        </div>

        <div class="card radius-10">
            <div class="card-header"><h6 class="mb-0">{{ __('Tax Rates') }}</h6></div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>{{ __('Code') }}</th>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Rate (%)') }}</th>
                                <th>{{ __('Tax Scope') }}</th>
                                <th>{{ __('Status') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($taxRates as $tr)
                                <tr>
                                    <td><code>{{ $tr->code }}</code></td>
                                    <td><strong>{{ $tr->name }}</strong></td>
                                    <td>{{ number_format($tr->ratePercent(), 2) }}%</td>
                                    <td class="text-uppercase">{{ $tr->type }}</td>
                                    <td><span class="badge {{ $tr->is_active ? 'bg-success' : 'bg-secondary' }}">{{ $tr->is_active ? __('Active') : __('Inactive') }}</span></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
