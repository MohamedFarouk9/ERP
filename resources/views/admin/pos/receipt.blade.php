@extends('admin.layouts.app')

@section('title', __('Point of Sale Receipt') . ' ' . $sale->receipt_number)

@php
    $printerType = $printSettings->printer_type ?? 'thermal_80mm';
    $maxWidth = match ($printerType) {
        'thermal_58mm' => '260px',
        'thermal_80mm' => '360px',
        default => '480px',
    };
    $storeName = ($printSettings && $printSettings->store_name)
        ? $printSettings->store_name
        : ($tenant->name ?? config('app.name'));
    $showCashier = $printSettings ? (bool) $printSettings->show_cashier : true;
    $showCustomer = $printSettings ? (bool) $printSettings->show_customer : true;
    $showTaxBreakdown = $printSettings ? (bool) $printSettings->show_tax_breakdown : true;
    $showBarcode = $printSettings ? (bool) $printSettings->show_barcode_on_receipt : true;
    $autoPrint = $printSettings ? (bool) $printSettings->auto_print_receipt : false;
    $footerMessage = ($printSettings && $printSettings->footer_message)
        ? $printSettings->footer_message
        : __('Thank you for your business!');
@endphp

@push('styles')
<style>
@media print {
    body * { visibility: hidden; }
    #posReceiptCard, #posReceiptCard * { visibility: visible; }
    #posReceiptCard {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        margin: 0 auto !important;
        box-shadow: none !important;
        border: none !important;
    }
}
</style>
@endpush

@section('content')
<div class="page-wrapper">
    <div class="page-content">
        <div class="d-flex justify-content-between align-items-center mb-3 d-print-none">
            <div class="d-flex gap-2">
                <a href="{{ route('admin.pos.terminal') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bx bx-arrow-back me-1"></i> {{ __('Back to Terminal') }}
                </a>
                <a href="{{ route('admin.pos.orders.index') }}" class="btn btn-outline-info btn-sm">
                    <i class="bx bx-receipt me-1"></i> {{ __('POS Orders') }}
                </a>
            </div>
            <button type="button" onclick="window.print()" class="btn btn-primary btn-sm">
                <i class="bx bx-printer me-1"></i> {{ __('Print Receipt') }}
            </button>
        </div>

        <div id="posReceiptCard" class="card radius-10 mx-auto" style="max-width: {{ $maxWidth }};">
            <div class="card-body p-4">
                <div class="text-center border-bottom pb-3 mb-3">
                    <h4 class="mb-1 fw-bold">{{ $storeName }}</h4>
                    @if($printSettings && $printSettings->store_address)
                        <div class="text-muted small">{{ $printSettings->store_address }}</div>
                    @endif
                    @if($printSettings && $printSettings->store_phone)
                        <div class="text-muted small">{{ $printSettings->store_phone }}</div>
                    @endif
                    @if($printSettings && $printSettings->tax_number)
                        <div class="text-muted small">{{ __('Tax Number') }}: {{ $printSettings->tax_number }}</div>
                    @endif
                    <div class="text-muted small mt-1">{{ __('Point of Sale Receipt') }}</div>
                    <div class="mt-2 fw-semibold">{{ $sale->receipt_number }}</div>
                    <div class="text-muted small">{{ optional($sale->sale_date)->format('M d, Y H:i:s') }}</div>
                </div>

                <div class="small mb-3">
                    @if($showCashier)
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">{{ __('Cashier') }}:</span>
                        <span>{{ $sale->cashier->name ?? 'System' }}</span>
                    </div>
                    @endif
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">{{ __('Shift') }}:</span>
                        <span>{{ $sale->shift->shift_number ?? '-' }}</span>
                    </div>
                    @if($showCustomer)
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">{{ __('Customer') }}:</span>
                        <span>{{ $sale->customer->name ?? __('Walk-in Customer') }}</span>
                    </div>
                    @endif
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
                    @if($showTaxBreakdown)
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">{{ __('Tax') }}</span>
                        <span>${{ number_format(($sale->tax_amount ?? 0) / 100, 2) }}</span>
                    </div>
                    @endif
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

                @if($showBarcode)
                <div class="text-center mt-3 pt-2 border-top">
                    <svg id="receiptBarcodeSvg" class="mx-auto"></svg>
                </div>
                @endif

                <div class="text-center border-top pt-3 mt-3 text-muted small">
                    {{ $footerMessage }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@if($showBarcode)
<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.6/dist/JsBarcode.all.min.js"></script>
@endif
<script>
document.addEventListener('DOMContentLoaded', function() {
    @if($showBarcode)
    if (typeof JsBarcode !== 'undefined') {
        JsBarcode('#receiptBarcodeSvg', @json($sale->receipt_number), {
            format: 'CODE128',
            width: 1.5,
            height: 40,
            displayValue: true,
            fontSize: 11,
            margin: 4
        });
    }
    @endif
    @if($autoPrint)
    setTimeout(function() { window.print(); }, 300);
    @endif
});
</script>
@endpush
