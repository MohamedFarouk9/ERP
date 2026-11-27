@extends('admin.layouts.app')
@section('title', __('HRM Analytics'))

@section('content')
<div class="page-wrapper">
    <div class="page-content">
        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
            <div>
                <h4 class="mb-0">{{ __('Human Resources & Payroll Analytics') }}</h4>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.hrm.employees.index') }}" class="btn btn-sm btn-outline-primary">{{ __('Employees') }}</a>
                <a href="{{ route('admin.hrm.payroll.index') }}" class="btn btn-sm btn-primary">{{ __('Run Payroll') }}</a>
            </div>
        </div>

        <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
            <div class="col">
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary">{{ __('Active Workforce') }}</p>
                                <h4 class="my-1">{{ number_format($stats['active_employees']) }}</h4>
                                <p class="mb-0 font-13 text-primary">{{ number_format($stats['total_employees']) }} {{ __('Employees') }}</p>
                            </div>
                            <div class="widgets-icons bg-light-primary text-primary ms-auto"><i class='bx bxs-group'></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary">{{ __("Today's Attendance") }}</p>
                                <h4 class="my-1 text-success">{{ $stats['attendance_rate'] }}%</h4>
                                <p class="mb-0 font-13 text-secondary">{{ $stats['present_today'] }} {{ __('Attendance') }}</p>
                            </div>
                            <div class="widgets-icons bg-light-success text-success ms-auto"><i class='bx bxs-user-check'></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary">{{ __('Monthly Base Payroll') }}</p>
                                <h4 class="my-1">${{ number_format($stats['monthly_payroll_liability'] / 100, 2) }}</h4>
                                <p class="mb-0 font-13 text-secondary">{{ __('Paid') }}: ${{ number_format($stats['paid_payroll_total'] / 100, 2) }}</p>
                            </div>
                            <div class="widgets-icons bg-light-warning text-warning ms-auto"><i class='bx bxs-wallet'></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary">{{ __('Pending Leave Requests') }}</p>
                                <h4 class="my-1 text-danger">{{ number_format($stats['pending_leaves']) }}</h4>
                            </div>
                            <div class="widgets-icons bg-light-danger text-danger ms-auto"><i class='bx bxs-calendar-exclamation'></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts -->
        <div class="row">
            <div class="col-xl-8 d-flex">
                <div class="card radius-10 w-100">
                    <div class="card-body">
                        <h6 class="mb-1">Monthly Payroll Cost (Gross vs. Net)</h6>
                        <small class="text-secondary">Historical payroll runs</small>
                        <div id="hrmPayrollChart" style="min-height: 290px;"></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 d-flex">
                <div class="card radius-10 w-100">
                    <div class="card-body">
                        <h6 class="mb-1">Headcount by Department</h6>
                        <small class="text-secondary">Active staff distribution</small>
                        <div id="hrmDeptDonut" class="mt-3" style="min-height: 270px;"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-6 d-flex">
                <div class="card radius-10 w-100">
                    <div class="card-body">
                        <h6 class="mb-3">Recent Leave Requests</h6>
                        <div class="table-responsive">
                            <table class="table align-middle mb-0 table-hover">
                                <thead class="table-light">
                                    <tr><th>{{ __('Employee') }}</th><th>{{ __('Type') }}</th><th>{{ __('Days') }}</th><th>{{ __('Status') }}</th></tr>
                                </thead>
                                <tbody>
                                    @forelse($recentLeaves as $leave)
                                        <tr>
                                            <td><strong>{{ $leave->employee?->fullName() ?? '-' }}</strong></td>
                                            <td class="text-capitalize">{{ $leave->leave_type }}</td>
                                            <td>{{ $leave->days_count }}d</td>
                                            <td><span class="badge rounded-pill {{ $leave->status === 'approved' ? 'bg-light-success text-success' : ($leave->status === 'rejected' ? 'bg-light-danger text-danger' : 'bg-light-warning text-warning') }} text-uppercase px-3">{{ $leave->status }}</span></td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="4" class="text-center text-muted py-3">No leave requests yet.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-6 d-flex">
                <div class="card radius-10 w-100">
                    <div class="card-body">
                        <h6 class="mb-3">Recent Payroll Runs</h6>
                        <div class="table-responsive">
                            <table class="table align-middle mb-0 table-hover">
                                <thead class="table-light">
                                    <tr><th>{{ __('Payroll #') }}</th><th>{{ __('Period') }}</th><th>{{ __('Net Total') }}</th><th>{{ __('Status') }}</th></tr>
                                </thead>
                                <tbody>
                                    @forelse($recentPayrolls as $run)
                                        <tr>
                                            <td><strong>{{ $run->payroll_number }}</strong></td>
                                            <td>{{ $run->period_month }}</td>
                                            <td class="fw-bold">${{ number_format($run->total_net / 100, 2) }}</td>
                                            <td><span class="badge rounded-pill bg-light-success text-success text-uppercase px-3">{{ $run->status }}</span></td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="4" class="text-center text-muted py-3">No payroll runs yet.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof ApexCharts === 'undefined') return;

    new ApexCharts(document.querySelector("#hrmPayrollChart"), {
        series: [
            { name: 'Gross Pay ($)', data: @json($chartData['payroll_gross']) },
            { name: 'Net Pay ($)', data: @json($chartData['payroll_net']) }
        ],
        chart: { type: 'bar', height: 290, toolbar: { show: false }, fontFamily: 'Roboto, sans-serif' },
        colors: ['#14abef', '#02ba5a'],
        plotOptions: { bar: { borderRadius: 4, columnWidth: '40%' } },
        dataLabels: { enabled: false },
        xaxis: { categories: @json($chartData['payroll_months']) },
        yaxis: { labels: { formatter: v => '$' + Number(v).toLocaleString() } }
    }).render();

    new ApexCharts(document.querySelector("#hrmDeptDonut"), {
        series: @json($chartData['dept_counts']),
        labels: @json($chartData['dept_labels']),
        chart: { type: 'donut', height: 270, fontFamily: 'Roboto, sans-serif' },
        colors: ['#14abef', '#02ba5a', '#fba540', '#843cf7', '#f5365c'],
        legend: { position: 'bottom' }
    }).render();
});
</script>
@endpush
