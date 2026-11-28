@extends('admin.layouts.app')
@section('title', __('Income Summary'))

@section('content')
<div class="page-wrapper">
    <div class="page-content">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
            <h4 class="mb-0">{{ __('Income Summary') }}</h4>
            <form method="GET" class="d-flex gap-2 align-items-center d-print-none">
                <input type="date" name="start_date" value="{{ $start }}" class="form-control form-control-sm">
                <input type="date" name="end_date" value="{{ $end }}" class="form-control form-control-sm">
                <button type="submit" class="btn btn-primary btn-sm">{{ __('Filter') }}</button>
                <button type="button" onclick="window.print()" class="btn btn-outline-secondary btn-sm"><i class="bx bx-printer"></i></button>
            </form>
        </div>

        <div class="row row-cols-1 row-cols-md-3 mb-3">
            <div class="col"><div class="card radius-10"><div class="card-body"><p class="mb-0 text-secondary">{{ __('B2B Invoiced Income') }}</p><h4 class="my-1 text-primary">${{ number_format($summary['invoice_revenue'] / 100, 2) }}</h4></div></div></div>
            <div class="col"><div class="card radius-10"><div class="card-body"><p class="mb-0 text-secondary">{{ __('POS Retail Income') }}</p><h4 class="my-1 text-info">${{ number_format($summary['pos_revenue'] / 100, 2) }}</h4></div></div></div>
            <div class="col"><div class="card radius-10"><div class="card-body"><p class="mb-0 text-secondary">{{ __('Total Income') }}</p><h4 class="my-1 text-success">${{ number_format($summary['total_income'] / 100, 2) }}</h4></div></div></div>
        </div>

        <div class="row">
            <div class="col-lg-6 d-flex">
                <div class="card radius-10 w-100">
                    <div class="card-header"><h6 class="mb-0">{{ __('Invoices') }}</h6></div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr><th>{{ __('Invoice #') }}</th><th>{{ __('Customer') }}</th><th class="text-end">{{ __('Total') }}</th></tr>
                                </thead>
                                <tbody>
                                    @forelse($recentInvoices as $inv)
                                        <tr>
                                            <td><strong>{{ $inv->invoice_number }}</strong></td>
                                            <td>{{ $inv->customer?->name ?? '-' }}</td>
                                            <td class="text-end text-success fw-semibold">${{ number_format($inv->total_amount / 100, 2) }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="3" class="text-center text-muted py-3">-</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 d-flex">
                <div class="card radius-10 w-100">
                    <div class="card-header"><h6 class="mb-0">{{ __('Point of Sale') }}</h6></div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr><th>{{ __('Receipt #') }}</th><th>{{ __('Date') }}</th><th class="text-end">{{ __('Total') }}</th></tr>
                                </thead>
                                <tbody>
                                    @forelse($recentPos as $s)
                                        <tr>
                                            <td><strong>{{ $s->receipt_number }}</strong></td>
                                            <td>{{ optional($s->sale_date)->format('Y-m-d') }}</td>
                                            <td class="text-end text-info fw-semibold">${{ number_format($s->total_amount / 100, 2) }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="3" class="text-center text-muted py-3">-</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
