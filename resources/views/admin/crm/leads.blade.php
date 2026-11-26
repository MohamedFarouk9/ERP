@extends('admin.layouts.app')

@section('title', 'CRM Leads')

@section('content')
<div class="page-wrapper">
    <div class="page-content">
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">CRM</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.crm.dashboard') }}">CRM Analytics</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Leads</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                @can('crm.leads.manage')
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createLeadModal">
                    <i class="bx bx-plus me-1"></i> New Lead
                </button>
                @endcan
            </div>
        </div>

        <div class="card radius-10">
            <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
                <h6 class="mb-0">Lead Directory</h6>
                <select id="leadStatusFilter" class="form-select form-select-sm" style="width: 180px;">
                    <option value="">All Statuses</option>
                    <option value="new">New</option>
                    <option value="contacted">Contacted</option>
                    <option value="qualified">Qualified</option>
                    <option value="converted">Converted</option>
                    <option value="lost">Lost</option>
                </select>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="crmLeadsTable" class="table table-striped table-bordered w-100">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Name</th>
                                <th>Company</th>
                                <th>Email</th>
                                <th>Source</th>
                                <th>Est. Value</th>
                                <th>Owner</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Lead Modal -->
<div class="modal fade" id="createLeadModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="createLeadForm">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Lead</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Contact Name *</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Company</label>
                            <input type="text" name="company" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Source *</label>
                            <select name="source" class="form-select" required>
                                <option value="website">Website</option>
                                <option value="referral">Referral</option>
                                <option value="cold_call">Cold Call</option>
                                <option value="social">Social Media</option>
                                <option value="event">Event</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Estimated Value ($)</label>
                            <input type="number" step="0.01" min="0" name="estimated_value_dollars" class="form-control" value="5000.00">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Owner</label>
                            <select name="owner_id" class="form-select">
                                @foreach($owners as $owner)
                                    <option value="{{ $owner->id }}">{{ $owner->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Notes</label>
                            <textarea name="notes" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Lead</button>
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
    const convertBaseUrl = @json(url('/admin/crm/leads'));

    const statusBadge = st => {
        const map = { new: 'primary', contacted: 'info', qualified: 'warning', converted: 'success', lost: 'secondary' };
        return `<span class="badge bg-${map[st] || 'secondary'} text-uppercase">${st}</span>`;
    };

    const table = $('#crmLeadsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: @json(route('admin.crm.leads.data')),
            data: d => { d.status = document.getElementById('leadStatusFilter').value; }
        },
        columns: [
            { data: 'lead_code', name: 'lead_code' },
            { data: 'name', name: 'name' },
            { data: 'company', name: 'company' },
            { data: 'email', name: 'email' },
            { data: 'source', name: 'source', render: d => `<span class="badge bg-light-secondary text-secondary text-uppercase">${d}</span>` },
            { data: 'estimated_value', name: 'estimated_value', render: d => fmtMoney(d) },
            { data: 'owner_name', name: 'owner_name', orderable: false },
            { data: 'status', name: 'status', render: d => statusBadge(d) },
            {
                data: null,
                orderable: false,
                searchable: false,
                render: row => row.status === 'converted'
                    ? `<span class="text-success small fw-semibold"><i class="bx bx-check-circle me-1"></i>${row.customer_name || 'Customer'}</span>`
                    : `<button type="button" class="btn btn-sm btn-outline-success btn-convert-lead" data-id="${row.id}" data-name="${row.name}"><i class="bx bx-transfer me-1"></i>Convert</button>`
            }
        ]
    });

    document.getElementById('leadStatusFilter').addEventListener('change', () => table.ajax.reload());

    document.getElementById('createLeadForm').addEventListener('submit', async function (e) {
        e.preventDefault();
        const fd = new FormData(this);
        const estCents = Math.round((parseFloat(fd.get('estimated_value_dollars')) || 0) * 100);
        try {
            const res = await fetch(@json(route('admin.crm.leads.store')), {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                body: JSON.stringify({
                    name: fd.get('name'),
                    company: fd.get('company') || null,
                    email: fd.get('email') || null,
                    phone: fd.get('phone') || null,
                    source: fd.get('source'),
                    estimated_value: estCents,
                    owner_id: fd.get('owner_id') || null,
                    notes: fd.get('notes') || null
                })
            });
            const data = await res.json();
            if (!res.ok) throw new Error(data.message || 'Failed to save lead.');
            bootstrap.Modal.getInstance(document.getElementById('createLeadModal')).hide();
            this.reset();
            Swal.fire('Lead Created', data.message, 'success');
            table.ajax.reload(null, false);
        } catch (err) {
            Swal.fire('Error', err.message, 'error');
        }
    });

    document.getElementById('crmLeadsTable').addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-convert-lead');
        if (!btn) return;
        Swal.fire({
            title: 'Convert Lead to Customer?',
            text: `This will create a B2B Customer and an open Deal for ${btn.dataset.name}.`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, Convert'
        }).then(async result => {
            if (!result.isConfirmed) return;
            try {
                const res = await fetch(`${convertBaseUrl}/${btn.dataset.id}/convert`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ create_deal: true })
                });
                const data = await res.json();
                if (!res.ok) throw new Error(data.message || 'Conversion failed.');
                Swal.fire('Converted!', data.message, 'success');
                table.ajax.reload(null, false);
            } catch (err) {
                Swal.fire('Error', err.message, 'error');
            }
        });
    });
});
</script>
@endpush
