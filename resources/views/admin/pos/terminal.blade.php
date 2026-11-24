@extends('admin.layouts.app')

@section('title', 'POS Terminal')

@section('content')
<div class="page-wrapper">
    <div class="page-content">
        <!-- Top Shift & Action Bar -->
        <div class="card radius-10 mb-3">
            <div class="card-body py-2 px-3 d-flex flex-wrap align-items-center justify-content-between gap-2">
                <div class="d-flex align-items-center gap-3">
                    <h5 class="mb-0"><i class="bx bx-desktop text-primary me-1"></i> POS Terminal</h5>
                    @if($activeShift)
                        <span class="badge bg-success px-3 py-2">
                            <i class="bx bx-check-circle me-1"></i>
                            Shift #{{ $activeShift->shift_number }} &bull; Open
                        </span>
                        <small class="text-muted">
                            Opened {{ optional($activeShift->opened_at)->format('M d, H:i') }}
                        </small>
                    @else
                        <span class="badge bg-danger px-3 py-2">
                            <i class="bx bx-lock-alt me-1"></i> No Active Shift
                        </span>
                    @endif
                </div>
                <div class="d-flex align-items-center gap-2">
                    @if(!$activeShift)
                        @can('pos.shifts.manage')
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#openShiftModal">
                            <i class="bx bx-play-circle me-1"></i> Open Shift
                        </button>
                        @endcan
                    @endif
                    @can('pos.shifts.view')
                    <a href="{{ route('admin.pos.shifts.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bx bx-time-five me-1"></i> Shifts &amp; Sales
                    </a>
                    @endcan
                    <a href="{{ route('admin.pos.dashboard') }}" class="btn btn-outline-info btn-sm">
                        <i class="bx bx-bar-chart-alt-2 me-1"></i> Analytics
                    </a>
                </div>
            </div>
        </div>

        <div class="row g-3">
            <!-- Left: Product Grid -->
            <div class="col-12 col-lg-7">
                <div class="card radius-10 h-100">
                    <div class="card-header bg-transparent">
                        <div class="row g-2 align-items-center">
                            <div class="col-md-6">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text"><i class="bx bx-search"></i></span>
                                    <input type="text" id="posProductSearch" class="form-control" placeholder="Search SKU or product name...">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <select id="posCategoryFilter" class="form-select form-select-sm">
                                    <option value="">All Categories</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" style="max-height: 620px; overflow-y: auto;">
                        <div class="row row-cols-2 row-cols-md-3 g-3" id="posProductGrid">
                            @forelse($products as $product)
                            <div class="col pos-product-card"
                                 data-id="{{ $product->id }}"
                                 data-category="{{ $product->category_id }}"
                                 data-name="{{ strtolower($product->name) }}"
                                 data-sku="{{ strtolower($product->sku) }}">
                                <div class="card border h-100 mb-0 shadow-none cursor-pointer pos-add-item"
                                     role="button"
                                     data-product-id="{{ $product->id }}"
                                     data-product-name="{{ $product->name }}"
                                     data-product-sku="{{ $product->sku }}"
                                     data-product-price="{{ $product->selling_price }}"
                                     data-product-unit="{{ $product->unit }}">
                                    <div class="card-body p-3 d-flex flex-column justify-content-between">
                                        <div>
                                            <span class="badge bg-light-secondary text-secondary mb-1">{{ $product->sku }}</span>
                                            <h6 class="card-title mb-1 font-14">{{ $product->name }}</h6>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between mt-2 pt-2 border-top">
                                            <span class="fw-bold text-primary">${{ number_format(($product->selling_price ?? 0) / 100, 2) }}</span>
                                            <small class="text-muted">{{ $product->unit }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="col-12 text-center text-muted py-5">
                                No active sellable products found.
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Cart & Checkout -->
            <div class="col-12 col-lg-5">
                <div class="card radius-10 h-100">
                    <div class="card-header bg-transparent d-flex align-items-center justify-content-between">
                        <h6 class="mb-0"><i class="bx bx-cart me-1"></i> Current Ticket</h6>
                        <button type="button" class="btn btn-sm btn-outline-danger" id="posClearCartBtn">
                            <i class="bx bx-trash"></i> Clear
                        </button>
                    </div>
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div>
                            <div class="mb-3">
                                <label class="form-label small mb-1">Customer (Optional / Walk-in)</label>
                                <select id="posCustomerId" class="form-select form-select-sm">
                                    <option value="">Walk-in Customer</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}">{{ $customer->code }} — {{ $customer->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="table-responsive" style="max-height: 260px; overflow-y: auto;">
                                <table class="table table-sm align-middle mb-0" id="posCartTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Item</th>
                                            <th style="width: 110px;">Qty</th>
                                            <th class="text-end">Total</th>
                                            <th style="width: 36px;"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="posCartBody">
                                        <tr id="posEmptyCartRow">
                                            <td colspan="4" class="text-center text-muted py-4">
                                                Click a product on the left to add it to the ticket.
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="border-top pt-3 mt-3">
                            <div class="row g-2 mb-2">
                                <div class="col-6">
                                    <label class="form-label small mb-1">Discount ($)</label>
                                    <input type="number" step="0.01" min="0" value="0.00" id="posDiscountInput" class="form-control form-control-sm">
                                </div>
                                <div class="col-6">
                                    <label class="form-label small mb-1">Tax Rate (%)</label>
                                    <input type="number" step="0.5" min="0" max="50" value="10" id="posTaxRateInput" class="form-control form-control-sm">
                                </div>
                            </div>

                            <div class="d-flex justify-content-between small mb-1">
                                <span class="text-muted">Subtotal</span>
                                <span id="posSubtotalDisplay">$0.00</span>
                            </div>
                            <div class="d-flex justify-content-between small mb-1">
                                <span class="text-muted">Discount</span>
                                <span id="posDiscountDisplay" class="text-danger">-$0.00</span>
                            </div>
                            <div class="d-flex justify-content-between small mb-2">
                                <span class="text-muted">Tax</span>
                                <span id="posTaxDisplay">$0.00</span>
                            </div>
                            <div class="d-flex justify-content-between fs-5 fw-bold border-top pt-2 mb-3">
                                <span>Total</span>
                                <span class="text-primary" id="posTotalDisplay">$0.00</span>
                            </div>

                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <label class="form-label small mb-1">Payment Type</label>
                                    <select id="posPaymentType" class="form-select form-select-sm">
                                        <option value="cash">Cash</option>
                                        <option value="card">Card</option>
                                        <option value="split">Split</option>
                                    </select>
                                </div>
                                <div class="col-6">
                                    <label class="form-label small mb-1">Amount Tendered ($)</label>
                                    <input type="number" step="0.01" min="0" value="0.00" id="posTenderedInput" class="form-control form-control-sm">
                                </div>
                            </div>

                            <div class="d-flex justify-content-between small mb-3">
                                <span class="text-muted">Change Due</span>
                                <span class="fw-semibold text-success" id="posChangeDisplay">$0.00</span>
                            </div>

                            <button type="button"
                                    id="posCheckoutBtn"
                                    class="btn btn-success w-100 py-2 fw-semibold"
                                    @if(!$activeShift) disabled @endif>
                                <i class="bx bx-check-double me-1"></i> Complete Sale &amp; Print Receipt
                            </button>
                            @if(!$activeShift)
                                <small class="text-danger d-block text-center mt-1">
                                    Open a shift first to process sales.
                                </small>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Open Shift Modal -->
<div class="modal fade" id="openShiftModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="openShiftForm">
                <div class="modal-header">
                    <h5 class="modal-title">Open POS Shift</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Shift Number</label>
                        <input type="text" name="shift_number" class="form-control" value="SH-{{ now()->format('Ymd-His') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Warehouse</label>
                        <select name="warehouse_id" class="form-select" required>
                            @foreach($warehouses as $warehouse)
                                <option value="{{ $warehouse->id }}">{{ $warehouse->code }} — {{ $warehouse->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Opening Cash Float ($)</label>
                        <input type="number" step="0.01" min="0" name="opening_cash_dollars" class="form-control" value="200.00" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Open Shift</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    const activeShiftId = @json($activeShift->id ?? null);
    let cart = [];

    const fmt = cents => '$' + (cents / 100).toFixed(2);

    function filterProducts() {
        const term = (document.getElementById('posProductSearch').value || '').trim().toLowerCase();
        const cat = document.getElementById('posCategoryFilter').value;
        document.querySelectorAll('.pos-product-card').forEach(card => {
            const matchText = !term || card.dataset.name.includes(term) || card.dataset.sku.includes(term);
            const matchCat = !cat || card.dataset.category === cat;
            card.style.display = (matchText && matchCat) ? '' : 'none';
        });
    }

    document.getElementById('posProductSearch').addEventListener('input', filterProducts);
    document.getElementById('posCategoryFilter').addEventListener('change', filterProducts);

    function recalcTotals() {
        const subtotalCents = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        const discountCents = Math.min(subtotalCents, Math.max(0, Math.round((parseFloat(document.getElementById('posDiscountInput').value) || 0) * 100)));
        const taxableCents = Math.max(0, subtotalCents - discountCents);
        const taxRatePct = Math.max(0, parseFloat(document.getElementById('posTaxRateInput').value) || 0);
        const taxCents = Math.round(taxableCents * (taxRatePct / 100));
        const totalCents = taxableCents + taxCents;

        const tenderedInput = document.getElementById('posTenderedInput');
        if (document.getElementById('posPaymentType').value === 'card') {
            tenderedInput.value = (totalCents / 100).toFixed(2);
        }
        const tenderedCents = Math.max(0, Math.round((parseFloat(tenderedInput.value) || 0) * 100));
        const changeCents = Math.max(0, tenderedCents - totalCents);

        document.getElementById('posSubtotalDisplay').textContent = fmt(subtotalCents);
        document.getElementById('posDiscountDisplay').textContent = '-' + fmt(discountCents);
        document.getElementById('posTaxDisplay').textContent = fmt(taxCents);
        document.getElementById('posTotalDisplay').textContent = fmt(totalCents);
        document.getElementById('posChangeDisplay').textContent = fmt(changeCents);

        return { subtotalCents, discountCents, taxRateBps: Math.round(taxRatePct * 100), totalCents, tenderedCents };
    }

    function renderCart() {
        const tbody = document.getElementById('posCartBody');
        tbody.innerHTML = '';
        if (cart.length === 0) {
            tbody.innerHTML = '<tr id="posEmptyCartRow"><td colspan="4" class="text-center text-muted py-4">Click a product on the left to add it to the ticket.</td></tr>';
            recalcTotals();
            return;
        }

        cart.forEach((item, idx) => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>
                    <div class="fw-semibold small">${item.name}</div>
                    <small class="text-muted">${fmt(item.price)} / ${item.unit}</small>
                </td>
                <td>
                    <div class="input-group input-group-sm">
                        <button class="btn btn-outline-secondary pos-qty-dec" data-idx="${idx}" type="button">-</button>
                        <input type="number" class="form-control text-center pos-qty-input" data-idx="${idx}" value="${item.quantity}" min="1">
                        <button class="btn btn-outline-secondary pos-qty-inc" data-idx="${idx}" type="button">+</button>
                    </div>
                </td>
                <td class="text-end fw-semibold small">${fmt(item.price * item.quantity)}</td>
                <td class="text-end">
                    <button class="btn btn-sm text-danger p-0 pos-remove-item" data-idx="${idx}" type="button"><i class="bx bx-x fs-5"></i></button>
                </td>
            `;
            tbody.appendChild(tr);
        });

        recalcTotals();
    }

    document.querySelectorAll('.pos-add-item').forEach(el => {
        el.addEventListener('click', function () {
            const id = this.dataset.productId;
            const existing = cart.find(i => i.id === id);
            if (existing) {
                existing.quantity += 1;
            } else {
                cart.push({
                    id,
                    name: this.dataset.productName,
                    sku: this.dataset.productSku,
                    price: parseInt(this.dataset.productPrice, 10) || 0,
                    unit: this.dataset.productUnit || 'unit',
                    quantity: 1
                });
            }
            renderCart();
        });
    });

    document.getElementById('posCartBody').addEventListener('click', function (e) {
        const dec = e.target.closest('.pos-qty-dec');
        const inc = e.target.closest('.pos-qty-inc');
        const rem = e.target.closest('.pos-remove-item');
        if (dec) {
            const idx = parseInt(dec.dataset.idx, 10);
            if (cart[idx].quantity > 1) cart[idx].quantity -= 1;
            else cart.splice(idx, 1);
            renderCart();
        } else if (inc) {
            const idx = parseInt(inc.dataset.idx, 10);
            cart[idx].quantity += 1;
            renderCart();
        } else if (rem) {
            const idx = parseInt(rem.dataset.idx, 10);
            cart.splice(idx, 1);
            renderCart();
        }
    });

    document.getElementById('posCartBody').addEventListener('change', function (e) {
        if (e.target.classList.contains('pos-qty-input')) {
            const idx = parseInt(e.target.dataset.idx, 10);
            cart[idx].quantity = Math.max(1, parseInt(e.target.value, 10) || 1);
            renderCart();
        }
    });

    ['posDiscountInput', 'posTaxRateInput', 'posTenderedInput', 'posPaymentType'].forEach(id => {
        document.getElementById(id).addEventListener('input', recalcTotals);
        document.getElementById(id).addEventListener('change', recalcTotals);
    });

    document.getElementById('posClearCartBtn').addEventListener('click', function () {
        cart = [];
        document.getElementById('posDiscountInput').value = '0.00';
        document.getElementById('posTenderedInput').value = '0.00';
        renderCart();
    });

    document.getElementById('posCheckoutBtn').addEventListener('click', async function () {
        if (!activeShiftId) {
            Swal.fire('Shift Required', 'Please open a POS shift first.', 'warning');
            return;
        }
        if (cart.length === 0) {
            Swal.fire('Empty Cart', 'Add at least one item before checking out.', 'warning');
            return;
        }
        const totals = recalcTotals();
        if (totals.tenderedCents < totals.totalCents) {
            Swal.fire('Insufficient Payment', 'Amount tendered must be at least ' + fmt(totals.totalCents) + '.', 'warning');
            return;
        }

        const btn = this;
        btn.disabled = true;
        try {
            const response = await fetch(@json(route('admin.pos.checkout')), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    pos_shift_id: activeShiftId,
                    customer_id: document.getElementById('posCustomerId').value || null,
                    payment_type: document.getElementById('posPaymentType').value,
                    amount_tendered: totals.tenderedCents,
                    discount_amount: totals.discountCents,
                    tax_rate_bps: totals.taxRateBps,
                    items: cart.map(i => ({ product_id: i.id, quantity: i.quantity }))
                })
            });
            const data = await response.json();
            if (!response.ok) {
                throw new Error(data.message || 'Checkout failed.');
            }
            cart = [];
            document.getElementById('posDiscountInput').value = '0.00';
            document.getElementById('posTenderedInput').value = '0.00';
            renderCart();

            Swal.fire({
                icon: 'success',
                title: 'Sale Completed',
                text: data.message || 'Receipt generated.',
                showCancelButton: true,
                confirmButtonText: 'View Receipt',
                cancelButtonText: 'New Sale'
            }).then(result => {
                if (result.isConfirmed && data.receipt_url) {
                    window.open(data.receipt_url, '_blank');
                }
            });
        } catch (err) {
            Swal.fire('Error', err.message, 'error');
        } finally {
            btn.disabled = false;
        }
    });

    const openShiftForm = document.getElementById('openShiftForm');
    if (openShiftForm) {
        openShiftForm.addEventListener('submit', async function (e) {
            e.preventDefault();
            const fd = new FormData(this);
            const openingCashCents = Math.round((parseFloat(fd.get('opening_cash_dollars')) || 0) * 100);
            try {
                const response = await fetch(@json(route('admin.pos.shifts.open')), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        shift_number: fd.get('shift_number'),
                        warehouse_id: fd.get('warehouse_id'),
                        opening_cash: openingCashCents
                    })
                });
                const data = await response.json();
                if (!response.ok) {
                    throw new Error(data.message || 'Failed to open shift.');
                }
                Swal.fire('Shift Opened', data.message || 'Ready for sales.', 'success').then(() => {
                    window.location.reload();
                });
            } catch (err) {
                Swal.fire('Error', err.message, 'error');
            }
        });
    }
});
</script>
@endpush
