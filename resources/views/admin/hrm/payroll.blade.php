@extends('admin.layouts.app')
@section('title', __('Payroll Runs'))

@section('content')
<div class="page-wrapper">
    <div class="page-content">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <h4 class="mb-0">{{ __('Payroll Runs & Payslips') }}</h4>
            </div>
            @can('hrm.payroll.create')
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#generatePayrollModal">
                    <i class="bx bx-calculator"></i> {{ __('Generate Payroll Run') }}
                </button>
            @endcan
        </div>

        <div class="card radius-10">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered align-middle erp-datatable w-100">
                        <thead class="table-light">
                            <tr>
                                <th>{{ __('Payroll #') }}</th>
                                <th>{{ __('Period') }}</th>
                                <th>{{ __('Pay Date') }}</th>
                                <th>{{ __('Staff') }}</th>
                                <th>{{ __('Total Gross') }}</th>
                                <th>{{ __('Deductions') }}</th>
                                <th>{{ __('Total Net') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th class="text-end">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($runs as $run)
                                <tr>
                                    <td><strong>{{ $run->payroll_number }}</strong></td>
                                    <td>{{ $run->period_month }}</td>
                                    <td>{{ $run->pay_date?->format('Y-m-d') }}</td>
                                    <td>{{ $run->items_count }}</td>
                                    <td>${{ number_format($run->total_gross / 100, 2) }}</td>
                                    <td class="text-danger">-${{ number_format($run->total_deductions / 100, 2) }}</td>
                                    <td class="fw-bold text-success">${{ number_format($run->total_net / 100, 2) }}</td>
                                    <td><span class="badge rounded-pill {{ $run->status === 'paid' ? 'bg-light-success text-success' : 'bg-light-warning text-warning' }} text-uppercase px-3">{{ $run->status }}</span></td>
                                    <td class="text-end text-nowrap">
                                        <button class="btn btn-sm btn-outline-info btn-view-payslips" data-id="{{ $run->id }}" title="View Payslips"><i class="bx bx-show"></i></button>
                                        @if($run->status !== 'paid')
                                            @can('hrm.payroll.pay')
                                                <form class="d-inline ajax-action-form" method="POST" action="{{ route('admin.hrm.payroll.pay', $run->id) }}" data-title="Pay {{ $run->payroll_number }}?" data-text="Total Net Disbursement: ${{ number_format($run->total_net / 100, 2) }}" data-confirm-text="Yes, disburse!">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-success"><i class="bx bx-dollar"></i> Pay</button>
                                                </form>
                                            @endcan
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Generate Payroll Modal -->
<div class="modal fade" id="generatePayrollModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content ajax-form" method="POST" action="{{ route('admin.hrm.payroll.store') }}">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Generate Monthly Payroll Run') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body row g-3">
                <div class="col-md-6"><label class="form-label">{{ __('Payroll # *') }}</label><input type="text" name="payroll_number" class="form-control" required value="PAYROLL-{{ date('Ym') }}"></div>
                <div class="col-md-6"><label class="form-label">{{ __('Period (YYYY-MM) *') }}</label><input type="text" name="period_month" class="form-control" required value="{{ date('Y-m') }}"></div>
                <div class="col-md-4"><label class="form-label">{{ __('Pay Date *') }}</label><input type="date" name="pay_date" class="form-control" required value="{{ date('Y-m-t') }}"></div>
                <div class="col-md-4"><label class="form-label">{{ __('Allowances (bps)') }}</label><input type="number" name="default_allowance_bps" class="form-control" value="1000" min="0"></div>
                <div class="col-md-4"><label class="form-label">{{ __('Tax/Ded (bps)') }}</label><input type="number" name="default_deduction_bps" class="form-control" value="1200" min="0"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __("Cancel") }}</button>
                <button type="submit" class="btn btn-primary">{{ __('Generate Payroll') }}</button>
            </div>
        </form>
    </div>
</div>

<!-- View Payslips Modal -->
<div class="modal fade" id="viewPayslipsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Payroll Payslips: <span id="vp_num"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr><th>{{ __('Employee') }}</th><th class="text-end">{{ __('Base') }}</th><th class="text-end">{{ __('Allowances') }}</th><th class="text-end">{{ __('Deductions') }}</th><th class="text-end">{{ __('Net Pay') }}</th></tr>
                    </thead>
                    <tbody id="vp_items"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).on('click', '.btn-view-payslips', function() {
    $.get("{{ url('/admin/hrm/payroll') }}/" + $(this).data('id'), function(res) {
        let r = res.data;
        $('#vp_num').text(r.payroll_number + ' (' + r.period_month + ')');
        let html = '';
        (r.items || []).forEach(i => {
            html += `<tr>
                <td><strong>${i.employee?.first_name || ''} ${i.employee?.last_name || ''}</strong></td>
                <td class="text-end">$${(i.base_salary / 100).toFixed(2)}</td>
                <td class="text-end text-success">+$${(i.allowances / 100).toFixed(2)}</td>
                <td class="text-end text-danger">-$${(i.deductions / 100).toFixed(2)}</td>
                <td class="text-end fw-bold">$${(i.net_pay / 100).toFixed(2)}</td>
            </tr>`;
        });
        $('#vp_items').html(html);
        new bootstrap.Modal(document.getElementById('viewPayslipsModal')).show();
    });
});
</script>
@endpush
