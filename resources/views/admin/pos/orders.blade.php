@extends('admin.layouts.app')
@section('title', __('POS Orders'))

@section('content')
<div class="page-wrapper">
    <div class="page-content">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
            <h4 class="mb-0">{{ __('POS Orders') }}</h4>
            <div class="d-flex gap-2">
                @can('pos.terminal.view')
                    <a href="{{ route('admin.pos.terminal') }}" class="btn btn-primary btn-sm">
                        <i class="bx bx-desktop me-1"></i> {{ __('Open POS Terminal') }}
                    </a>
                @endcan
                @can('pos.shifts.view')
                    <a href="{{ route('admin.pos.shifts.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bx bx-time-five me-1"></i> {{ __('Register Shifts') }}
                    </a>
                @endcan
            </div>
        </div>

        <div class="row row-cols-1 row-cols-md-4 mb-3">
            <div class="col"><div class="card radius-10 border-start border-0 border-4 border-primary"><div class="card-body"><p class="mb-0 text-secondary">{{ __('POS Orders') }}</p><h4 class="my-1 text-primary">{{ number_format($stats['total_orders']) }}</h4></div></div></div>
            <div class="col"><div class="card radius-10 border-start border-0 border-4 border-info"><div class="card-body"><p class="mb-0 text-secondary">{{ __("Today's Orders") }}</p><h4 class="my-1 text-info">{{ number_format($stats['today_orders']) }}</h4></div></div></div>
            <div class="col"><div class="card radius-10 border-start border-0 border-4 border-success"><div class="card-body"><p class="mb-0 text-secondary">{{ __('Total POS Revenue') }}</p><h4 class="my-1 text-success">${{ number_format($stats['total_revenue'] / 100, 2) }}</h4></div></div></div>
            <div class="col"><div class="card radius-10 border-start border-0 border-4 border-warning"><div class="card-body"><p class="mb-0 text-secondary">{{ __('Cash') }}</p><h4 class="my-1 text-warning">${{ number_format($stats['cash_revenue'] / 100, 2) }}</h4></div></div></div>
        </div>

        <div class="card radius-10">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="posOrdersTable" class="table table-striped table-bordered align-middle w-100">
                        <thead class="table-light">
                            <tr>
                                <th>{{ __('Receipt #') }}</th>
                                <th>{{ __('Date') }}</th>
                                <th>{{ __('Cashier') }}</th>
                                <th>{{ __('Payment') }}</th>
                                <th>{{ __('Total') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th class="text-end">{{ __('Print Receipt') }}</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const fmt = c => '$' + (Number(c || 0) / 100).toFixed(2);
    const receiptBase = @json(url('/admin/pos/receipts'));

    $('#posOrdersTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: @json(route('admin.pos.sales.data')),
        columns: [
            { data: 'receipt_number', name: 'receipt_number', render: d => `<strong>${d}</strong>` },
            { data: 'sale_date', name: 'sale_date' },
            { data: 'cashier_name', name: 'cashier_name', orderable: false },
            { data: 'payment_type', name: 'payment_type', render: d => `<span class="badge bg-light-info text-info text-uppercase">${d || '-'}</span>` },
            { data: 'total_amount', name: 'total_amount', render: d => `<strong>${fmt(d)}</strong>` },
            { data: 'status', name: 'status', render: () => `<span class="badge bg-success">${@json(__('Completed'))}</span>` },
            {
                data: 'id',
                orderable: false,
                searchable: false,
                className: 'text-end',
                render: id => `<a href="${receiptBase}/${id}" target="_blank" class="btn btn-sm btn-outline-primary"><i class="bx bx-printer me-1"></i>${@json(__('Print Receipt'))}</a>`
            }
        ]
    });
});
</script>
@endpush
