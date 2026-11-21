@extends('admin.layouts.app')
@section('title', 'Sales Orders')

@section('content')
<div class="wrapper">
    <div class="page-wrapper">
        <div class="page-content">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <h4 class="mb-0">Sales Orders</h4>
                    <small class="text-muted">Create multi-item sales orders and confirm for fulfillment</small>
                </div>
                @can('sales.orders.create')
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createOrderModal">
                        <i class="bx bx-plus"></i> New Sales Order
                    </button>
                @endcan
            </div>

            <div class="card radius-10">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="ordersDataTable" class="table table-striped table-bordered align-middle w-100">
                            <thead class="table-light">
                                <tr>
                                    <th>Order #</th>
                                    <th>Customer</th>
                                    <th>Order Date</th>
                                    <th>Items</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Order Modal -->
<div class="modal fade" id="createOrderModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <form class="modal-content ajax-form" method="POST" action="{{ route('admin.orders.store') }}">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Create Draft Sales Order</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Order # *</label>
                        <input type="text" name="order_number" class="form-control" required value="SO-{{ date('YmdHis') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Customer *</label>
                        <select name="customer_id" class="form-select" required>
                            @foreach($customers as $c)
                                <option value="{{ $c->id }}">{{ $c->code }} - {{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Warehouse</label>
                        <select name="warehouse_id" class="form-select">
                            <option value="">-- Optional --</option>
                            @foreach($warehouses as $w)
                                <option value="{{ $w->id }}">{{ $w->code }} - {{ $w->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Order Date *</label>
                        <input type="date" name="order_date" class="form-control" required value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Discount (Cents)</label>
                        <input type="number" name="discount_amount" class="form-control" value="0" min="0">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Notes</label>
                        <input type="text" name="notes" class="form-control">
                    </div>
                </div>

                <div class="d-flex align-items-center justify-content-between mb-2">
                    <h6 class="mb-0">Order Items (Prices in Cents, Tax in Basis Points e.g. 1000 = 10%)</h6>
                    <button type="button" id="addOrderItemBtn" class="btn btn-sm btn-outline-primary"><i class="bx bx-plus"></i> Add Item</button>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered align-middle" id="orderItemsTable">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 35%">Product *</th>
                                <th style="width: 15%">Qty *</th>
                                <th style="width: 20%">Unit Price (Cents) *</th>
                                <th style="width: 15%">Tax Rate (bps)</th>
                                <th style="width: 50px"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <select name="items[0][product_id]" class="form-select order-product-select" required>
                                        @foreach($products as $p)
                                            <option value="{{ $p->id }}" data-price="{{ $p->selling_price }}">{{ $p->sku }} - {{ $p->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td><input type="number" name="items[0][quantity]" class="form-control" value="1" min="1" required></td>
                                <td><input type="number" name="items[0][unit_price]" class="form-control order-unit-price" value="{{ $products->first()?->selling_price ?? 0 }}" min="0" required></td>
                                <td><input type="number" name="items[0][tax_rate]" class="form-control" value="1000" min="0"></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Draft Order</button>
            </div>
        </form>
    </div>
</div>

<!-- View Order Modal -->
<div class="modal fade" id="viewOrderModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Sales Order: <span id="vo_number"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="mb-2"><strong>Customer:</strong> <span id="vo_customer"></span> | <strong>Status:</strong> <span id="vo_status" class="badge bg-info text-dark text-uppercase"></span></p>
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Description</th>
                            <th class="text-end">Qty</th>
                            <th class="text-end">Unit Price</th>
                            <th class="text-end">Tax</th>
                            <th class="text-end">Line Total</th>
                        </tr>
                    </thead>
                    <tbody id="vo_items"></tbody>
                </table>
                <div class="text-end fw-bold fs-5">Total: <span id="vo_total"></span></div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    var baseUrl = "{{ url('/admin/orders') }}";
    var itemIndex = 1;
    var productOptions = `@foreach($products as $p)<option value="{{ $p->id }}" data-price="{{ $p->selling_price }}">{{ $p->sku }} - {{ $p->name }}</option>@endforeach`;

    window.erpActiveDataTable = $('#ordersDataTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('admin.orders.data') }}",
        pageLength: 25,
        order: [[2, 'desc']],
        columns: [
            { data: 'order_number', render: d => `<strong>${d}</strong>` },
            { data: 'customer_name', orderable: false },
            { data: 'order_date' },
            { data: 'items_count' },
            { data: 'total_amount', render: d => '$' + (d / 100).toFixed(2) },
            {
                data: 'status',
                render: d => {
                    let cls = d === 'confirmed' || d === 'fulfilled' ? 'bg-success' : (d === 'draft' ? 'bg-warning text-dark' : 'bg-secondary');
                    return `<span class="badge ${cls} text-uppercase">${d}</span>`;
                }
            },
            {
                data: null,
                orderable: false,
                searchable: false,
                className: 'text-end text-nowrap',
                render: function(row) {
                    let btns = `<button class="btn btn-sm btn-outline-info btn-view-order" data-id="${row.id}"><i class="bx bx-show"></i></button> `;
                    if (row.status === 'draft') {
                        btns += `
                            <form class="d-inline ajax-action-form" method="POST" action="${baseUrl}/${row.id}/confirm" data-title="Confirm ${row.order_number}?" data-confirm-text="Yes, confirm!">
                                <button type="submit" class="btn btn-sm btn-outline-success"><i class="bx bx-check"></i> Confirm</button>
                            </form>
                            <form class="d-inline ajax-delete-form" method="POST" action="${baseUrl}/${row.id}" data-title="Delete ${row.order_number}?">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bx bx-trash"></i></button>
                            </form>
                        `;
                    }
                    return btns;
                }
            }
        ]
    });

    $(document).on('change', '.order-product-select', function() {
        var price = $(this).find(':selected').data('price') || 0;
        $(this).closest('tr').find('.order-unit-price').val(price);
    });

    $('#addOrderItemBtn').on('click', function() {
        $('#orderItemsTable tbody').append(`
            <tr>
                <td><select name="items[${itemIndex}][product_id]" class="form-select order-product-select" required>${productOptions}</select></td>
                <td><input type="number" name="items[${itemIndex}][quantity]" class="form-control" value="1" min="1" required></td>
                <td><input type="number" name="items[${itemIndex}][unit_price]" class="form-control order-unit-price" value="0" min="0" required></td>
                <td><input type="number" name="items[${itemIndex}][tax_rate]" class="form-control" value="1000" min="0"></td>
                <td><button type="button" class="btn btn-sm btn-outline-danger btn-remove-item"><i class="bx bx-x"></i></button></td>
            </tr>
        `);
        itemIndex++;
    });

    $(document).on('click', '.btn-remove-item', function() {
        $(this).closest('tr').remove();
    });

    $(document).on('click', '.btn-view-order', function() {
        $.get(baseUrl + '/' + $(this).data('id'), function(res) {
            let o = res.data;
            $('#vo_number').text(o.order_number);
            $('#vo_customer').text(o.customer?.name || '-');
            $('#vo_status').text(o.status);
            $('#vo_total').text('$' + (o.total_amount / 100).toFixed(2));
            let html = '';
            (o.items || []).forEach(i => {
                html += `<tr>
                    <td>${i.description || i.product_id}</td>
                    <td class="text-end">${i.quantity}</td>
                    <td class="text-end">$${(i.unit_price / 100).toFixed(2)}</td>
                    <td class="text-end">$${(i.tax_amount / 100).toFixed(2)}</td>
                    <td class="text-end">$${(i.line_total / 100).toFixed(2)}</td>
                </tr>`;
            });
            $('#vo_items').html(html);
            new bootstrap.Modal(document.getElementById('viewOrderModal')).show();
        });
    });
});
</script>
@endpush
