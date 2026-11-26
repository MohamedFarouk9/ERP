@extends('admin.layouts.app')

@section('title', 'CRM Analytics')

@section('content')
<div class="page-wrapper">
    <div class="page-content">
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">CRM</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">CRM Analytics</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto d-flex gap-2">
                @can('crm.leads.view')
                <a href="{{ route('admin.crm.leads.index') }}" class="btn btn-primary btn-sm px-3">
                    <i class="bx bx-user-plus me-1"></i> Leads
                </a>
                @endcan
                @can('crm.deals.view')
                <a href="{{ route('admin.crm.deals.index') }}" class="btn btn-outline-success btn-sm px-3">
                    <i class="bx bx-briefcase me-1"></i> Deals Pipeline
                </a>
                @endcan
                @can('crm.activities.view')
                <a href="{{ route('admin.crm.activities.index') }}" class="btn btn-outline-secondary btn-sm px-3">
                    <i class="bx bx-calendar-check me-1"></i> Activities
                </a>
                @endcan
            </div>
        </div>

        <!-- KPI Row -->
        <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
            <div class="col">
                <div class="card radius-10 border-start border-0 border-4 border-primary">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary">Total Leads</p>
                                <h4 class="my-1 text-primary">{{ $stats['total_leads'] ?? 0 }}</h4>
                                <p class="mb-0 font-13">{{ $stats['conversion_rate'] ?? 0 }}% conversion rate</p>
                            </div>
                            <div class="widgets-icons-2 rounded-circle bg-gradient-scooter text-white ms-auto">
                                <i class="bx bx-user-voice"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card radius-10 border-start border-0 border-4 border-info">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary">Open Pipeline</p>
                                <h4 class="my-1 text-info">${{ number_format(($stats['open_pipeline_value'] ?? 0) / 100, 2) }}</h4>
                                <p class="mb-0 font-13">Active opportunities</p>
                            </div>
                            <div class="widgets-icons-2 rounded-circle bg-gradient-blues text-white ms-auto">
                                <i class="bx bx-trending-up"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card radius-10 border-start border-0 border-4 border-success">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary">Won Revenue</p>
                                <h4 class="my-1 text-success">${{ number_format(($stats['won_revenue'] ?? 0) / 100, 2) }}</h4>
                                <p class="mb-0 font-13">{{ $stats['win_rate'] ?? 0 }}% win rate</p>
                            </div>
                            <div class="widgets-icons-2 rounded-circle bg-gradient-ohhappiness text-white ms-auto">
                                <i class="bx bx-trophy"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card radius-10 border-start border-0 border-4 border-warning">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary">Open Activities</p>
                                <h4 class="my-1 text-warning">{{ $stats['open_activities'] ?? 0 }}</h4>
                                <p class="mb-0 font-13">Scheduled follow-ups</p>
                            </div>
                            <div class="widgets-icons-2 rounded-circle bg-gradient-burning text-white ms-auto">
                                <i class="bx bx-phone-call"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row">
            <div class="col-12 col-lg-8 d-flex">
                <div class="card radius-10 w-100">
                    <div class="card-header">
                        <h6 class="mb-0">Pipeline Value by Stage</h6>
                    </div>
                    <div class="card-body">
                        <div id="crmStageChart" style="height: 310px;"></div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-4 d-flex">
                <div class="card radius-10 w-100">
                    <div class="card-header">
                        <h6 class="mb-0">Leads by Source</h6>
                    </div>
                    <div class="card-body d-flex align-items-center justify-content-center">
                        <div id="crmSourceChart" class="w-100"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tables Row -->
        <div class="row">
            <div class="col-12 col-lg-6 d-flex">
                <div class="card radius-10 w-100">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h6 class="mb-0">Top Open Deals</h6>
                        <a href="{{ route('admin.crm.deals.index') }}" class="btn btn-sm btn-outline-primary">View Pipeline</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Deal</th>
                                        <th>Stage</th>
                                        <th class="text-end">Value</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($topDeals as $deal)
                                    <tr>
                                        <td>
                                            <div class="fw-semibold">{{ $deal->title }}</div>
                                            <small class="text-muted">{{ $deal->customer->name ?? 'Prospect' }} &bull; {{ $deal->probability }}%</small>
                                        </td>
                                        <td><span class="badge bg-light-info text-info text-uppercase">{{ $deal->stage }}</span></td>
                                        <td class="text-end fw-semibold">${{ number_format(($deal->value_amount ?? 0) / 100, 2) }}</td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="3" class="text-center text-muted py-4">No open deals yet.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6 d-flex">
                <div class="card radius-10 w-100">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h6 class="mb-0">Upcoming Activities</h6>
                        <a href="{{ route('admin.crm.activities.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Type</th>
                                        <th>Subject</th>
                                        <th>Due</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($upcomingActivities as $act)
                                    <tr>
                                        <td><span class="badge bg-light-primary text-primary text-uppercase">{{ $act->type }}</span></td>
                                        <td>
                                            <div class="fw-semibold">{{ $act->subject }}</div>
                                            <small class="text-muted">{{ $act->deal->title ?? $act->lead->name ?? '-' }}</small>
                                        </td>
                                        <td>{{ optional($act->due_at)->format('M d, H:i') }}</td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="3" class="text-center text-muted py-4">No upcoming activities.</td></tr>
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
document.addEventListener('DOMContentLoaded', function () {
    const stageLabels = @json($chartData['stage_labels'] ?? []);
    const stageValuesRaw = @json($chartData['stage_values'] ?? []);
    const stageValues = stageValuesRaw.map(v => Number((v / 100).toFixed(2)));

    new ApexCharts(document.querySelector('#crmStageChart'), {
        series: [{ name: 'Deal Value ($)', data: stageValues }],
        chart: { type: 'bar', height: 300, toolbar: { show: false } },
        plotOptions: { bar: { borderRadius: 6, columnWidth: '48%' } },
        colors: ['#008cff'],
        dataLabels: { enabled: false },
        xaxis: { categories: stageLabels },
        yaxis: { labels: { formatter: val => '$' + Number(val).toFixed(0) } },
        tooltip: { y: { formatter: val => '$' + Number(val).toFixed(2) } }
    }).render();

    const sourceLabels = @json($chartData['source_labels'] ?? []);
    const sourceCounts = @json($chartData['source_counts'] ?? []);

    new ApexCharts(document.querySelector('#crmSourceChart'), {
        series: sourceCounts,
        labels: sourceLabels,
        chart: { type: 'donut', height: 290 },
        colors: ['#008cff', '#15ca20', '#ffc107', '#fd3550', '#6f42c1'],
        legend: { position: 'bottom' }
    }).render();
});
</script>
@endpush
