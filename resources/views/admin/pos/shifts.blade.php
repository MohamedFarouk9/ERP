@extends('admin.layouts.app')

@section('title', 'POS Shifts & Sales')

@section('content')
<div class="page-wrapper">
    <div class="page-content">
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Point of Sale</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.pos.dashboard') }}">POS Analytics</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Shifts &amp; Sales</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto d-flex gap-2">
                @can('pos.shifts.manage')
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#openShiftModal">
                    <i class="bx bx-play-circle me-1"></i> Open Shift
                </button>
                @endcan
                @can('pos.terminal.view')
                <a href="{{ route('admin.pos.terminal') }}" class="btn btn-outline-success btn-sm">
                    <i class="bx bx-desktop me-1"></i> POS Terminal
                </a>
                @endcan
            </div>
        </div>

        <!-- Register Shifts Table -->
        <div class="card radius-10 mb-4">
            <div class="card-header">
                <h6 class="mb-0">Register Shifts</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="posShiftsTable" class="table table-striped table-bordered w-100">
                        <thead>
                            <tr>
                                <th>Shift #</th>
                                <th>Cashier</th>
                                <th>Opened At</th>
                                <th>Closed At</th>
                                <th>Opening Cash</th>
                                <th>Expected Cash</th>
                                <th>Counted Cash</th>
                                <th>Sales</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

        <!-- POS Sales Table -->
        <div class="card radius-10">
            <div class="card-header">
                <h6 class="mb-0">POS Sales History</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="posSalesTable" class="table table-striped table-bordered w-100">
                        <thead>
                            <tr>
                                <th>Receipt #</th>
                                <th>Date</th>
                                <th>Cashier</th>
                                <th>Payment</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Receipt</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Open Shift Modal -->
<div class="modal fade" id="openShiftModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="shiftsPageOpenForm">
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

<!-- Close Shift Modal -->
<div class="modal fade" id="closeShiftModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="closeShiftForm">
                <input type="hidden" id="closeShiftId">
                <div class="modal-header">
                    <h5 class="modal-title">Close POS Shift</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Counted Cash in Drawer ($)</label>
                        <input type="number" step="0.01" min="0" id="closeCountedCashDollars" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Closing Notes (Optional)</label>
                        <textarea id="closeShiftNotes" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">Close Shift</button>
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

    const fmtMoney = cents => '$' + (Number(cents || 0) / 100).toFixed(2);
    const receiptBaseUrl = @json(url('/admin/pos/receipts'));

    const shiftsTable = $('#posShiftsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: @json(route('admin.pos.shifts.data')),
        columns: [
            { data: 'shift_number', name: 'shift_number' },
            { data: 'cashier_name', name: 'cashier_name', orderable: false },
            { data: 'opened_at', name: 'opened_at' },
            { data: 'closed_at', name: 'closed_at' },
            { data: 'opening_cash', name: 'opening_cash', render: d => fmtMoney(d) },
            { data: 'expected_cash', name: 'expected_cash', render: d => fmtMoney(d) },
            { data: 'counted_cash', name: 'counted_cash', render: d => d !== null && d !== undefined ? fmtMoney(d) : '-' },
            { data: 'sales_count', name: 'sales_count', orderable: false },
            {
                data: 'status',
                name: 'status',
                orderable: false,
                searchable: false,
                render: d => d === 'open'
                    ? '<span class="badge bg-success">Open</span>'
                    : '<span class="badge bg-secondary">Closed</span>'
            },
            {
                data: null,
                orderable: false,
                searchable: false,
                render: row => row.status === 'open'
                    ? `<button type="button" class="btn btn-sm btn-warning btn-close-shift" data-id="${row.id}" data-expected="${(Number(row.expected_cash || 0) / 100).toFixed(2)}"><i class="bx bx-lock-alt me-1"></i>Close</button>`
                    : '<span class="text-muted small">Reconciled</span>'
            }
        ]
    });

    $('#posSalesTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: @json(route('admin.pos.sales.data')),
        columns: [
            { data: 'receipt_number', name: 'receipt_number' },
            { data: 'sale_date', name: 'sale_date' },
            { data: 'cashier_name', name: 'cashier_name', orderable: false },
            {
                data: 'payment_type',
                name: 'payment_type',
                render: d => `<span class="badge bg-light-info text-info text-uppercase">${d || '-'}</span>`
            },
            { data: 'total_amount', name: 'total_amount', render: d => fmtMoney(d) },
            {
                data: 'status',
                name: 'status',
                render: d => `<span class="badge bg-success text-uppercase">${d || 'completed'}</span>`
            },
            {
                data: 'id',
                orderable: false,
                searchable: false,
                render: id => `<a href="${receiptBaseUrl}/${id}" target="_blank" class="btn btn-sm btn-outline-primary"><i class="bx bx-printer me-1"></i>Receipt</a>`
            }
        ]
    });

    document.getElementById('posShiftsTable').addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-close-shift');
        if (!btn) return;
        document.getElementById('closeShiftId').value = btn.dataset.id;
        document.getElementById('closeCountedCashDollars').value = btn.dataset.expected || '0.00';
        document.getElementById('closeShiftNotes').value = '';
        new bootstrap.Modal(document.getElementById('closeShiftModal')).show();
    });

    document.getElementById('closeShiftForm').addEventListener('submit', async function (e) {
        e.preventDefault();
        const id = document.getElementById('closeShiftId').value;
        const countedCents = Math.round((parseFloat(document.getElementById('closeCountedCashDollars').value) || 0) * 100);
        try {
            const res = await fetch(@json(url('/admin/pos/shifts')) + '/' + id + '/close', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    counted_cash: countedCents,
                    notes: document.getElementById('closeShiftNotes').value || null
                })
            });
            const data = await res.json();
            if (!res.ok) throw new Error(data.message || 'Failed to close shift.');
            bootstrap.Modal.getInstance(document.getElementById('closeShiftModal')).hide();
            Swal.fire('Shift Closed', data.message || 'Shift closed successfully.', 'success');
            shiftsTable.ajax.reload(null, false);
        } catch (err) {
            Swal.fire('Error', err.message, 'error');
        }
    });

    document.getElementById('shiftsPageOpenForm').addEventListener('submit', async function (e) {
        e.preventDefault();
        const fd = new FormData(this);
        const openingCents = Math.round((parseFloat(fd.get('opening_cash_dollars')) || 0) * 100);
        try {
            const res = await fetch(@json(route('admin.pos.shifts.open')), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    shift_number: fd.get('shift_number'),
                    warehouse_id: fd.get('warehouse_id'),
                    opening_cash: openingCents
                })
            });
            const data = await res.json();
            if (!res.ok) throw new Error(data.message || 'Failed to open shift.');
            bootstrap.Modal.getInstance(document.getElementById('openShiftModal')).hide();
            Swal.fire('Shift Opened', data.message || 'Shift opened successfully.', 'success');
            shiftsTable.ajax.reload(null, false);
        } catch (err) {
            Swal.fire('Error', err.message, 'error');
        }
    });
});
</script>
@endpush
