@extends('admin.layouts.app')
@section('title', __('Barcode Print'))

@push('styles')
<style>
    .barcode-label-card {
        border: 1px dashed rgba(128, 128, 128, 0.45);
        border-radius: 8px;
        padding: 12px;
        text-align: center;
        page-break-inside: avoid;
    }
    .barcode-svg-box {
        background: #ffffff;
        border-radius: 6px;
        padding: 6px 8px;
        display: inline-block;
        max-width: 100%;
    }
    .barcode-svg-box svg {
        max-width: 100%;
        height: auto;
    }
    @media print {
        .sidebar-wrapper, .topbar, .page-footer, .switcher-wrapper, .d-print-none { display: none !important; }
        .page-wrapper { margin: 0 !important; padding: 0 !important; }
        .page-content { padding: 0 !important; }
        #barcodePrintArea { width: 100% !important; max-width: 100% !important; flex: 0 0 100% !important; }
        .card { border: 0 !important; box-shadow: none !important; background: #fff !important; }
        .barcode-label-card { color: #000 !important; border: 1px dashed #ccc; }
    }
</style>
@endpush

@section('content')
<div class="page-wrapper">
    <div class="page-content">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3 d-print-none">
            <h4 class="mb-0"><i class="bx bx-barcode me-1 text-primary"></i> {{ __('Barcode Print') }}</h4>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.pos.qrcodes') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bx bx-qr me-1"></i> {{ __('QR Code Print') }}
                </a>
                <button type="button" onclick="window.print()" class="btn btn-primary btn-sm">
                    <i class="bx bx-printer me-1"></i> {{ __('Print Labels') }}
                </button>
            </div>
        </div>

        <div class="row g-3">
            <!-- Left Controls -->
            <div class="col-12 col-lg-4 d-print-none">
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">{{ __('Product') }}</label>
                            <select id="bcProductSelect" class="form-select">
                                @foreach($products as $p)
                                    <option value="{{ $p->id }}" data-sku="{{ $p->sku }}" data-name="{{ $p->name }}" data-price="{{ number_format($p->selling_price / 100, 2) }}">
                                        {{ $p->sku }} — {{ $p->name }} (${{ number_format($p->selling_price / 100, 2) }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label class="form-label">{{ __('Barcode Type') }}</label>
                                <select id="bcFormat" class="form-select">
                                    <option value="CODE128" @selected($settings->barcode_type === 'CODE128')>Code 128</option>
                                    <option value="CODE39" @selected($settings->barcode_type === 'CODE39')>Code 39</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="form-label">{{ __('Quantity') }}</label>
                                <input type="number" id="bcQuantity" class="form-control" value="8" min="1" max="100">
                            </div>
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label class="form-label">{{ __('Paper Size') }}</label>
                                <select id="bcPaperSize" class="form-select">
                                    <option value="A4" @selected($settings->label_paper_size === 'A4')>A4</option>
                                    <option value="thermal_80mm" @selected($settings->label_paper_size === 'thermal_80mm')>Thermal 80mm</option>
                                    <option value="thermal_58mm" @selected($settings->label_paper_size === 'thermal_58mm')>Thermal 58mm</option>
                                    <option value="roll_50x30" @selected($settings->label_paper_size === 'roll_50x30')>Label Roll 50x30mm</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="form-label">{{ __('Per Row') }}</label>
                                <select id="bcPerRow" class="form-select">
                                    <option value="2">2</option>
                                    <option value="3" selected>3</option>
                                    <option value="4">4</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check mb-2">
                                <input class="form-check-input bc-opt" type="checkbox" id="bcShowName" checked>
                                <label class="form-check-label" for="bcShowName">{{ __('Show Product Name') }}</label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input bc-opt" type="checkbox" id="bcShowPrice" checked>
                                <label class="form-check-label" for="bcShowPrice">{{ __('Show Price') }}</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input bc-opt" type="checkbox" id="bcShowSku" checked>
                                <label class="form-check-label" for="bcShowSku">{{ __('Show SKU') }}</label>
                            </div>
                        </div>

                        <button type="button" id="bcGenerateBtn" class="btn btn-primary w-100">
                            <i class="bx bx-refresh me-1"></i> {{ __('Generate Preview') }}
                        </button>
                    </div>
                </div>
            </div>

            <!-- Right Live Preview -->
            <div class="col-12 col-lg-8" id="barcodePrintArea">
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="row g-3" id="barcodeGrid"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.6/dist/JsBarcode.all.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    function renderBarcodes() {
        const select = document.getElementById('bcProductSelect');
        const opt = select.options[select.selectedIndex];
        if (!opt) return;

        const sku = opt.dataset.sku || 'SKU-001';
        const name = opt.dataset.name || '';
        const price = opt.dataset.price || '0.00';
        const format = document.getElementById('bcFormat').value;
        const qty = Math.min(100, Math.max(1, parseInt(document.getElementById('bcQuantity').value, 10) || 1));
        const perRow = parseInt(document.getElementById('bcPerRow').value, 10) || 3;
        const showName = document.getElementById('bcShowName').checked;
        const showPrice = document.getElementById('bcShowPrice').checked;
        const showSku = document.getElementById('bcShowSku').checked;

        const colClass = perRow === 2 ? 'col-6' : (perRow === 4 ? 'col-6 col-md-3' : 'col-6 col-md-4');
        const grid = document.getElementById('barcodeGrid');
        grid.innerHTML = '';

        for (let i = 0; i < qty; i++) {
            const col = document.createElement('div');
            col.className = colClass;
            const svgId = 'bc_svg_' + i;
            col.innerHTML = `
                <div class="barcode-label-card">
                    <div class="barcode-svg-box mb-2">
                        <svg id="${svgId}"></svg>
                    </div>
                    ${showName ? `<div class="fw-semibold small text-truncate">${name}</div>` : ''}
                    ${showPrice ? `<div class="fw-bold text-primary">$${price}</div>` : ''}
                </div>
            `;
            grid.appendChild(col);

            try {
                JsBarcode('#' + svgId, sku, {
                    format: format,
                    width: 1.7,
                    height: 48,
                    displayValue: showSku,
                    fontSize: 12,
                    margin: 4,
                    background: '#ffffff',
                    lineColor: '#000000'
                });
            } catch (e) {
                JsBarcode('#' + svgId, sku, { format: 'CODE128', width: 1.7, height: 48, displayValue: showSku });
            }
        }
    }

    document.getElementById('bcGenerateBtn').addEventListener('click', renderBarcodes);
    ['bcProductSelect', 'bcFormat', 'bcQuantity', 'bcPerRow'].forEach(id => {
        document.getElementById(id).addEventListener('change', renderBarcodes);
    });
    document.querySelectorAll('.bc-opt').forEach(el => el.addEventListener('change', renderBarcodes));

    renderBarcodes();
});
</script>
@endpush
