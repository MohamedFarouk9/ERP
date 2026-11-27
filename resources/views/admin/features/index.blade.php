@extends('admin.layouts.app')
@section('title', __('Module Feature Flags'))

@section('content')
<div class="wrapper">
    <div class="page-wrapper">
        <div class="page-content">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <h4 class="mb-0">{{ __('Module Feature Flags') }}</h4>
                    <small class="text-muted">{{ __('Enable or disable ERP modules dynamically for this tenant without touching code') }}</small>
                </div>
            </div>

            <div class="card radius-10">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered align-middle erp-datatable w-100">
                            <thead class="table-light">
                                <tr>
                                    <th>{{ __('Module') }}</th>
                                    <th>{{ __('Feature Slug') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th class="text-end">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($availableModules as $slug => $label)
                                    @php $enabled = (bool) ($statuses[$slug] ?? false); @endphp
                                    <tr>
                                        <td><strong>{{ __($label) }}</strong></td>
                                        <td><code>{{ $slug }}</code></td>
                                        <td>
                                            <span class="badge {{ $enabled ? 'bg-success' : 'bg-secondary' }}">
                                                {{ $enabled ? __('Enabled') : __('Disabled') }}
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            @can('core.features.manage')
                                                <form method="POST"
                                                    action="{{ route('admin.features.toggle', $slug) }}"
                                                    class="d-inline ajax-action-form"
                                                    data-title="{{ $enabled ? __('Disable') : __('Enable') }} {{ __($label) }}?"
                                                    data-text="{{ __('Do you want to proceed with this action?') }}"
                                                    data-confirm-text="{{ __('Yes, proceed!') }}">
                                                    @csrf
                                                    <input type="hidden" name="is_enabled" value="{{ $enabled ? 0 : 1 }}">
                                                    <button type="submit" class="btn btn-sm {{ $enabled ? 'btn-outline-danger' : 'btn-outline-success' }}">
                                                        {{ $enabled ? __('Disable') : __('Enable') }}
                                                    </button>
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
</div>
@endsection
