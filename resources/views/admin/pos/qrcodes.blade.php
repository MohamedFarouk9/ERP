@extends('admin.layouts.app')
@section('title', __('QR Code Print'))

@push('styles')
<style>
    .qr-label-card {
        border: 1px dashed rgba(128, 128, 128, 0.45);
        border-radius: 8px;
        padding: 14px;
        text-align: center;
        page-break-inside: avoid;
    }
    .qr-box {
        background: #ffffff;
        padding: 8px;
        border-radius: 6px;
        display: inline-block;
    }
    @media print {
        .sidebar-wrapper, .topbar, .page-footer, .switcher-wrapper, .d-print-none { display: none !important; }
        .page-wrapper { margin: 0 !important; padding: 0 !important; }
        .page-content { padding: 0 !important; }
        #qrPrintArea { width: 100% !important; max-width: 100% !important; flex: 0 0 100% !important; }
        .card { border: 0 !important; box-shadow: none !important; background: #fff !important; }
        .qr-label-card { color: #000 !important; border: 1px dashed #ccc; }
    }
</style>
@endpush

@section('content')
<div class="page-wrapper">
    <div class="page-content">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3 d-print-none">
            <h4 class="mb-0"><i class="bx bx-qr me-1 text-primary"></i> {{ __('QR Code Print') }}</h4>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.pos.barcodes') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bx bx-barcode me-1"></i> {{ __('Barcode Print') }}
                </a>
                <button type="button" onclick="window.print()" class="btn btn-primary btn-sm">
                    <i class="bx bx-printer me-1"></i> {{ __('Print Labels') }}
                </button>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-12 col-lg-4 d-print-none">
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">{{ __('Product') }}</label>
                            <select id="qrProductSelect" class="form-select">
                                @foreach($products as $p)
                                    <option value="{{ $p->id }}" data-sku="{{ $p->sku }}" data-name="{{ $p->name }}" data-price="{{ number_format($p->selling_price / 100, 2) }}">
                                        {{ $p->sku }} — {{ $p->name }} (${{ number_format($p->selling_price / 100, 2) }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label class="form-label">{{ __('Quantity') }}</label>
                                <input type="number" id="qrQuantity" class="form-control" value="8" min="1" max="100">
                            </div>
                            <div class="col-6">
                                <label class="form-label">{{ __('QR Size') }}</label>
                                <select id="qrSize" class="form-select">
                                    <option value="84">84px</option>
                                    <option value="110" selected>110px</option>
                                    <option value="140">140px</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check mb-2">
                                <input class="form-check-input qr-opt" type="checkbox" id="qrShowName" checked>
                                <label class="form-check-label" for="qrShowName">{{ __('Show Product Name') }}</label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input qr-opt" type="checkbox" id="qrShowPrice" checked>
                                <label class="form-check-label" for="qrShowPrice">{{ __('Show Price') }}</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input qr-opt" type="checkbox" id="qrShowSku" checked>
                                <label class="form-check-label" for="qrShowSku">{{ __('Show SKU') }}</label>
                            </div>
                        </div>

                        <button type="button" id="qrGenerateBtn" class="btn btn-primary w-100">
                            <i class="bx bx-refresh me-1"></i> {{ __('Generate Preview') }}
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-8" id="qrPrintArea">
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="row g-3" id="qrGrid"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    function renderQrs() {
        const select = document.getElementById('qrProductSelect');
        const opt = select.options[select.selectedIndex];
        if (!opt) return;

        const sku = opt.dataset.sku || 'SKU-001';
        const name = opt.dataset.name || '';
        const price = opt.dataset.price || '0.00';
        const qty = Math.min(100, Math.max(1, parseInt(document.getElementById('qrQuantity').value, 10) || 1));
        const size = parseInt(document.getElementById('qrSize').value, 10) || 110;
        const showName = document.getElementById('qrShowName').checked;
        const showPrice = document.getElementById('qrShowPrice').checked;
        const showSku = document.getElementById('qrShowSku').checked;

        const payload = `${sku} | ${name} | $${price}`;
        const grid = document.getElementById('qrGrid');
        grid.innerHTML = '';

        for (let i = 0; i < qty; i++) {
            const col = document.createElement('div');
            col.className = 'col-6 col-md-4 col-lg-3';
            const boxId = 'qr_box_' + i;
            col.innerHTML = `
                <div class="qr-label-card">
                    <div class="qr-box mb-2" id="${boxId}"></div>
                    ${showName ? `<div class="fw-semibold small text-truncate">${name}</div>` : ''}
                    ${showSku ? `<div class="text-muted small"><code>${sku}</code></div>` : ''}
                    ${showPrice ? `<div class="fw-bold text-primary">$${price}</div>` : ''}
                </div>
            `;
            grid.appendChild(col);

            new QRCode(document.getElementById(boxId), {
                text: payload,
                width: size,
                height: size,
                colorDark: '#000000',
                colorLight: '#ffffff'
            });
        }
    }

    document.getElementById('qrGenerateBtn').addEventListener('click', renderQrs);
    ['qrProductSelect', 'qrQuantity', 'qrSize'].forEach(id => {
        document.getElementById(id).addEventListener('change', renderQrs);
    });
    document.querySelectorAll('.qr-opt').forEach(el => el.addEventListener('change', renderQrs));

    renderQrs();
});
</script>
@endpush
