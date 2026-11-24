@extends('admin.layouts.app')
@section('title', 'Purchase Orders')

@section('content')
<div class="wrapper">
    <div class="page-wrapper">
        <div class="page-content">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <h4 class="mb-0">Purchase Orders</h4>
                    <small class="text-muted">Order stock from suppliers and auto-increment Inventory on Goods Receipt</small>
                </div>
                @can('procurement.orders.create')
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createPoModal">
                        <i class="bx bx-plus"></i> New Purchase Order
                    </button>
                @endcan
            </div>

            <div class="card radius-10">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="posDataTable" class="table table-striped table-bordered align-middle w-100">
                            <thead class="table-light">
                                <tr>
                                    <th>PO #</th>
                                    <th>Supplier</th>
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

<!-- Create PO Modal -->
<div class="modal fade" id="createPoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <form class="modal-content ajax-form" method="POST" action="{{ route('admin.procurement.orders.store') }}">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Create Purchase Order</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3 mb-3">
                    <div class="col-md-3"><label class="form-label">PO # *</label><input type="text" name="po_number" class="form-control" required value="PO-{{ date('YmdHis') }}"></div>
                    <div class="col-md-3">
                        <label class="form-label">Supplier *</label>
                        <select name="supplier_id" class="form-select" required>
                            @foreach($suppliers as $s)<option value="{{ $s->id }}">{{ $s->code }} - {{ $s->name }}</option>@endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Receiving Warehouse *</label>
                        <select name="warehouse_id" class="form-select" required>
                            @foreach($warehouses as $w)<option value="{{ $w->id }}">{{ $w->code }} - {{ $w->name }}</option>@endforeach
                        </select>
                    </div>
                    <div class="col-md-3"><label class="form-label">Order Date *</label><input type="date" name="order_date" class="form-control" required value="{{ date('Y-m-d') }}"></div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="mb-0">Line Items (Unit Cost in Cents)</h6>
                    <button type="button" id="addPoItemBtn" class="btn btn-sm btn-outline-primary"><i class="bx bx-plus"></i> Add Item</button>
                </div>
                <table class="table table-bordered align-middle" id="poItemsTable">
                    <thead class="table-light">
                        <tr><th>Product *</th><th style="width:15%">Qty *</th><th style="width:20%">Unit Cost (Cents) *</th><th style="width:15%">Tax (bps)</th><th style="width:50px"></th></tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <select name="items[0][product_id]" class="form-select po-prod" required>
                                    @foreach($products as $p)<option value="{{ $p->id }}" data-cost="{{ $p->cost_price }}">{{ $p->sku }} - {{ $p->name }}</option>@endforeach
                                </select>
                            </td>
                            <td><input type="number" name="items[0][quantity]" class="form-control" value="5" min="1" required></td>
                            <td><input type="number" name="items[0][unit_cost]" class="form-control po-cost" value="{{ $products->first()?->cost_price ?? 0 }}" min="0" required></td>
                            <td><input type="number" name="items[0][tax_rate]" class="form-control" value="1000" min="0"></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Create Purchase Order</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    var baseUrl = "{{ url('/admin/procurement/purchase-orders') }}";
    var poIdx = 1;
    var prodOpts = `@foreach($products as $p)<option value="{{ $p->id }}" data-cost="{{ $p->cost_price }}">{{ $p->sku }} - {{ $p->name }}</option>@endforeach`;

    window.erpActiveDataTable = $('#posDataTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('admin.procurement.orders.data') }}",
        pageLength: 25,
        order: [[2, 'desc']],
        columns: [
            { data: 'po_number', render: d => `<strong>${d}</strong>` },
            { data: 'supplier_name', orderable: false },
            { data: 'order_date' },
            { data: 'items_count' },
            { data: 'total_amount', render: d => '$' + (d / 100).toFixed(2) },
            {
                data: 'status',
                render: d => {
                    let cls = d === 'received' ? 'bg-success' : (d === 'ordered' ? 'bg-primary' : 'bg-secondary');
                    return `<span class="badge ${cls} text-uppercase">${d}</span>`;
                }
            },
            {
                data: null,
                orderable: false,
                searchable: false,
                className: 'text-end text-nowrap',
                render: function(row) {
                    if (row.status === 'ordered' || row.status === 'draft') {
                        return `
                            <form class="d-inline ajax-action-form" method="POST" action="${baseUrl}/${row.id}/receive" data-title="Receive ${row.po_number}?" data-text="Goods will be automatically added to Warehouse stock!" data-confirm-text="Yes, receive goods!">
                                <button type="submit" class="btn btn-sm btn-outline-success"><i class="bx bx-package"></i> Receive</button>
                            </form>
                            <form class="d-inline ajax-delete-form" method="POST" action="${baseUrl}/${row.id}" data-title="Delete ${row.po_number}?">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bx bx-trash"></i></button>
                            </form>
                        `;
                    }
                    return '<span class="text-muted small">Received</span>';
                }
            }
        ]
    });

    $(document).on('change', '.po-prod', function() {
        $(this).closest('tr').find('.po-cost').val($(this).find(':selected').data('cost') || 0);
    });

    $('#addPoItemBtn').on('click', function() {
        $('#poItemsTable tbody').append(`
            <tr>
                <td><select name="items[${poIdx}][product_id]" class="form-select po-prod" required>${prodOpts}</select></td>
                <td><input type="number" name="items[${poIdx}][quantity]" class="form-control" value="1" min="1" required></td>
                <td><input type="number" name="items[${poIdx}][unit_cost]" class="form-control po-cost" value="0" min="0" required></td>
                <td><input type="number" name="items[${poIdx}][tax_rate]" class="form-control" value="1000" min="0"></td>
                <td><button type="button" class="btn btn-sm btn-outline-danger btn-rm-po"><i class="bx bx-x"></i></button></td>
            </tr>
        `);
        poIdx++;
    });

    $(document).on('click', '.btn-rm-po', function() { $(this).closest('tr').remove(); });
});
</script>
@endpush
