@extends('admin.layouts.app')

@section('title', __('Point of Sale Receipt') . ' ' . $sale->receipt_number)

@section('content')
<div class="page-wrapper">
    <div class="page-content">
        <div class="d-flex justify-content-between align-items-center mb-3 d-print-none">
            <a href="{{ route('admin.pos.terminal') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bx bx-arrow-back me-1"></i> {{ __('Back to Terminal') }}
            </a>
            <button type="button" onclick="window.print()" class="btn btn-primary btn-sm">
                <i class="bx bx-printer me-1"></i> {{ __('Print Receipt') }}
            </button>
        </div>

        <div class="card radius-10 mx-auto" style="max-width: 480px;">
            <div class="card-body p-4">
                <div class="text-center border-bottom pb-3 mb-3">
                    <h4 class="mb-1 fw-bold">{{ $tenant->name ?? config('app.name') }}</h4>
                    <div class="text-muted small">{{ __('Point of Sale Receipt') }}</div>
                    <div class="mt-2 fw-semibold">{{ $sale->receipt_number }}</div>
                    <div class="text-muted small">{{ optional($sale->sale_date)->format('M d, Y H:i:s') }}</div>
                </div>

                <div class="small mb-3">
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">{{ __('Cashier') }}:</span>
                        <span>{{ $sale->cashier->name ?? 'System' }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">{{ __('Shift') }}:</span>
                        <span>{{ $sale->shift->shift_number ?? '-' }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">{{ __('Customer') }}:</span>
                        <span>{{ $sale->customer->name ?? __('Walk-in Customer') }}</span>
                    </div>
                </div>

                <table class="table table-sm border-top border-bottom mb-3">
                    <thead>
                        <tr>
                            <th>{{ __('Item') }}</th>
                            <th class="text-center">{{ __('Qty') }}</th>
                            <th class="text-end">{{ __('Price') }}</th>
                            <th class="text-end">{{ __('Total') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sale->items as $item)
                        <tr>
                            <td>
                                <div class="small fw-semibold">{{ $item->product_name }}</div>
                                <small class="text-muted">{{ $item->sku }}</small>
                            </td>
                            <td class="text-center">{{ $item->quantity }}</td>
                            <td class="text-end">${{ number_format(($item->unit_price ?? 0) / 100, 2) }}</td>
                            <td class="text-end">${{ number_format(($item->line_total ?? 0) / 100, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="small">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted">{{ __('Subtotal') }}</span>
                        <span>${{ number_format(($sale->subtotal ?? 0) / 100, 2) }}</span>
                    </div>
                    @if(($sale->discount_amount ?? 0) > 0)
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted">{{ __('Discount') }}</span>
                        <span class="text-danger">-${{ number_format(($sale->discount_amount ?? 0) / 100, 2) }}</span>
                    </div>
                    @endif
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">{{ __('Tax') }}</span>
                        <span>${{ number_format(($sale->tax_amount ?? 0) / 100, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between fs-6 fw-bold border-top pt-2 mb-2">
                        <span>{{ __('Total') }}</span>
                        <span>${{ number_format(($sale->total_amount ?? 0) / 100, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted">{{ __('Payment') }} ({{ strtoupper($sale->payment_type) }})</span>
                        <span>${{ number_format(($sale->amount_tendered ?? 0) / 100, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">{{ __('Change') }}</span>
                        <span>${{ number_format(($sale->change_due ?? 0) / 100, 2) }}</span>
                    </div>
                </div>

                <div class="text-center border-top pt-3 mt-4 text-muted small">
                    {{ __('Thank you for your business!') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
