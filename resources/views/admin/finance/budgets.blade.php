@extends('admin.layouts.app')
@section('title', __('Budgeting'))

@section('content')
<div class="page-wrapper">
    <div class="page-content">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <h4 class="mb-0">{{ __('Budgeting') }}</h4>
            </div>
            @can('finance.budgets.manage')
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createBudgetModal">
                    <i class="bx bx-plus me-1"></i> {{ __('Add Budget') }}
                </button>
            @endcan
        </div>

        <div class="row row-cols-1 row-cols-md-3 mb-3">
            <div class="col">
                <div class="card radius-10 border-start border-0 border-4 border-primary">
                    <div class="card-body">
                        <p class="mb-0 text-secondary">{{ __('Total Budgeted') }}</p>
                        <h4 class="my-1 text-primary">${{ number_format(($summary['total_budget'] ?? 0) / 100, 2) }}</h4>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card radius-10 border-start border-0 border-4 border-warning">
                    <div class="card-body">
                        <p class="mb-0 text-secondary">{{ __('Actual Spent') }}</p>
                        <h4 class="my-1 text-warning">${{ number_format(($summary['total_spent'] ?? 0) / 100, 2) }}</h4>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card radius-10 border-start border-0 border-4 border-danger">
                    <div class="card-body">
                        <p class="mb-0 text-secondary">{{ __('Over Budget') }}</p>
                        <h4 class="my-1 text-danger">{{ $summary['over_budget_count'] ?? 0 }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="card radius-10">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered align-middle erp-datatable w-100">
                        <thead class="table-light">
                            <tr>
                                <th>{{ __('Budget Name') }}</th>
                                <th>{{ __('Category') }}</th>
                                <th>{{ __('Period') }}</th>
                                <th>{{ __('Budget Amount') }}</th>
                                <th>{{ __('Actual Spent') }}</th>
                                <th>{{ __('Remaining') }}</th>
                                <th>{{ __('Utilization') }}</th>
                                <th class="text-end">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($budgets as $b)
                                @php $pct = min(100, $b->utilization_pct); $over = $b->actual_spent > $b->budget_amount; @endphp
                                <tr>
                                    <td><strong>{{ $b->name }}</strong></td>
                                    <td>{{ $b->category?->name ?? '-' }}</td>
                                    <td><code>{{ $b->period_month }}</code></td>
                                    <td>${{ number_format($b->budget_amount / 100, 2) }}</td>
                                    <td class="{{ $over ? 'text-danger fw-bold' : '' }}">${{ number_format($b->actual_spent / 100, 2) }}</td>
                                    <td class="{{ $b->remaining < 0 ? 'text-danger' : 'text-success' }}">${{ number_format($b->remaining / 100, 2) }}</td>
                                    <td style="min-width: 140px;">
                                        <div class="progress" style="height: 8px;">
                                            <div class="progress-bar {{ $over ? 'bg-danger' : ($pct > 80 ? 'bg-warning' : 'bg-success') }}" style="width: {{ $pct }}%"></div>
                                        </div>
                                        <small class="text-muted">{{ $b->utilization_pct }}%</small>
                                    </td>
                                    <td class="text-end">
                                        @can('finance.budgets.manage')
                                            <form class="d-inline ajax-delete-form" method="POST" action="{{ route('admin.finance.budgets.destroy', $b->id) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bx bx-trash"></i></button>
                                            </form>
                                        @endcan
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

<!-- Add Budget Modal -->
<div class="modal fade" id="createBudgetModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="createBudgetForm">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Add Budget') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body row g-3">
                    <div class="col-12">
                        <label class="form-label">{{ __('Budget Name') }} *</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">{{ __('Category') }} *</label>
                        <select name="expense_category_id" class="form-select" required>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">{{ __('Period (YYYY-MM) *') }}</label>
                        <input type="text" name="period_month" class="form-control" value="{{ date('Y-m') }}" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">{{ __('Budget Amount') }} ($) *</label>
                        <input type="number" step="0.01" min="1" name="budget_dollars" class="form-control" value="2500.00" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
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
    document.getElementById('createBudgetForm').addEventListener('submit', async function (e) {
        e.preventDefault();
        const fd = new FormData(this);
        const cents = Math.round((parseFloat(fd.get('budget_dollars')) || 0) * 100);
        try {
            const res = await fetch(@json(route('admin.finance.budgets.store')), {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                body: JSON.stringify({
                    name: fd.get('name'),
                    expense_category_id: fd.get('expense_category_id'),
                    period_month: fd.get('period_month'),
                    budget_amount: cents
                })
            });
            const data = await res.json();
            if (!res.ok) throw new Error(data.message || @json(__('Action failed.')));
            Swal.fire(@json(__('Success')), data.message, 'success').then(() => window.location.reload());
        } catch (err) {
            Swal.fire(@json(__('Error')), err.message, 'error');
        }
    });
});
</script>
@endpush
