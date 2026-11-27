@extends('admin.layouts.app')

@section('title', __('Deals Pipeline'))

@section('content')
<div class="page-wrapper">
    <div class="page-content">
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">{{ __('CRM') }}</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.crm.dashboard') }}">{{ __('CRM Analytics') }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ __('Deals Pipeline') }}</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                @can('crm.deals.manage')
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createDealModal">
                    <i class="bx bx-plus me-1"></i> {{ __('New Deal') }}
                </button>
                @endcan
            </div>
        </div>

        <div class="card radius-10">
            <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
                <h6 class="mb-0">{{ __('Sales Opportunities') }}</h6>
                <select id="dealStageFilter" class="form-select form-select-sm" style="width: 180px;">
                    <option value="">{{ __('All Stages') }}</option>
                    <option value="qualified">{{ __('Qualified') }}</option>
                    <option value="proposal">{{ __('Proposal') }}</option>
                    <option value="negotiation">{{ __('Negotiation') }}</option>
                    <option value="won">{{ __('Won') }}</option>
                    <option value="lost">{{ __('Lost') }}</option>
                </select>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="crmDealsTable" class="table table-striped table-bordered w-100">
                        <thead>
                            <tr>
                                <th>{{ __('Deal #') }}</th>
                                <th>{{ __('Title') }}</th>
                                <th>{{ __('Customer') }}</th>
                                <th>{{ __('Owner') }}</th>
                                <th>{{ __('Value') }}</th>
                                <th>{{ __('Prob.') }}</th>
                                <th>{{ __('Close Date') }}</th>
                                <th>{{ __('Stage') }}</th>
                                <th>{{ __('Move Stage') }}</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Deal Modal -->
<div class="modal fade" id="createDealModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="createDealForm">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Create Deal Opportunity') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label">{{ __('Deal Title *') }}</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('Value ($) *') }}</label>
                            <input type="number" step="0.01" min="0" name="value_dollars" class="form-control" value="10000.00" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('Customer') }}</label>
                            <select name="customer_id" class="form-select">
                                <option value="">--</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->code }} â€” {{ $customer->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('Linked Lead') }}</label>
                            <select name="lead_id" class="form-select">
                                <option value="">--</option>
                                @foreach($leads as $lead)
                                    <option value="{{ $lead->id }}">{{ $lead->lead_code }} â€” {{ $lead->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('Stage') }} *</label>
                            <select name="stage" class="form-select" required>
                                <option value="qualified">{{ __('Qualified (25%)') }}</option>
                                <option value="proposal">{{ __('Proposal (50%)') }}</option>
                                <option value="negotiation">{{ __('Negotiation (75%)') }}</option>
                                <option value="won">{{ __('Won (100%)') }}</option>
                                <option value="lost">{{ __('Lost (0%)') }}</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('Expected Close Date') }}</label>
                            <input type="date" name="expected_close_date" class="form-control" value="{{ now()->addDays(30)->toDateString() }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('Owner') }}</label>
                            <select name="owner_id" class="form-select">
                                @foreach($owners as $owner)
                                    <option value="{{ $owner->id }}">{{ $owner->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">{{ __('Notes') }}</label>
                            <textarea name="notes" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('Create Deal') }}</button>
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
    const dealsBaseUrl = @json(url('/admin/crm/deals'));

    const stageBadge = st => {
        const map = { qualified: 'primary', proposal: 'info', negotiation: 'warning', won: 'success', lost: 'danger' };
        return `<span class="badge bg-${map[st] || 'secondary'} text-uppercase">${st}</span>`;
    };

    const table = $('#crmDealsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: @json(route('admin.crm.deals.data')),
            data: d => { d.stage = document.getElementById('dealStageFilter').value; }
        },
        columns: [
            { data: 'deal_code', name: 'deal_code' },
            { data: 'title', name: 'title' },
            { data: 'customer_name', name: 'customer_name', orderable: false },
            { data: 'owner_name', name: 'owner_name', orderable: false },
            { data: 'value_amount', name: 'value_amount', render: d => fmtMoney(d) },
            { data: 'probability', name: 'probability', render: d => `${d}%` },
            { data: 'expected_close_date', name: 'expected_close_date' },
            { data: 'stage', name: 'stage', render: d => stageBadge(d) },
            {
                data: null,
                orderable: false,
                searchable: false,
                render: row => `
                    <select class="form-select form-select-sm deal-stage-select" data-id="${row.id}" style="min-width: 130px;">
                        ${['qualified', 'proposal', 'negotiation', 'won', 'lost'].map(s => `<option value="${s}" ${row.stage === s ? 'selected' : ''}>${s.toUpperCase()}</option>`).join('')}
                    </select>`
            }
        ]
    });

    document.getElementById('dealStageFilter').addEventListener('change', () => table.ajax.reload());

    document.getElementById('createDealForm').addEventListener('submit', async function (e) {
        e.preventDefault();
        const fd = new FormData(this);
        const valueCents = Math.round((parseFloat(fd.get('value_dollars')) || 0) * 100);
        try {
            const res = await fetch(@json(route('admin.crm.deals.store')), {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                body: JSON.stringify({
                    title: fd.get('title'),
                    value_amount: valueCents,
                    customer_id: fd.get('customer_id') || null,
                    lead_id: fd.get('lead_id') || null,
                    stage: fd.get('stage'),
                    expected_close_date: fd.get('expected_close_date') || null,
                    owner_id: fd.get('owner_id') || null,
                    notes: fd.get('notes') || null
                })
            });
            const data = await res.json();
            if (!res.ok) throw new Error(data.message || 'Failed to create deal.');
            bootstrap.Modal.getInstance(document.getElementById('createDealModal')).hide();
            this.reset();
            Swal.fire(@json(__('Deal Created')), data.message, 'success');
            table.ajax.reload(null, false);
        } catch (err) {
            Swal.fire(@json(__('Error')), err.message, 'error');
        }
    });

    document.getElementById('crmDealsTable').addEventListener('change', async function (e) {
        if (!e.target.classList.contains('deal-stage-select')) return;
        const id = e.target.dataset.id;
        const stage = e.target.value;
        try {
            const res = await fetch(`${dealsBaseUrl}/${id}/stage`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                body: JSON.stringify({ stage })
            });
            const data = await res.json();
            if (!res.ok) throw new Error(data.message || @json(__('Action failed.')));
            Swal.fire(@json(__('Stage Updated')), data.message, 'success');
            table.ajax.reload(null, false);
        } catch (err) {
            Swal.fire(@json(__('Error')), err.message, 'error');
            table.ajax.reload(null, false);
        }
    });
});
</script>
@endpush
