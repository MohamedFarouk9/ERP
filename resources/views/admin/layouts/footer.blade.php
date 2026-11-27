<footer class="page-footer">
    <p class="mb-0">{{ __('Copyright') }} © {{ date('Y') }} {{ app(\Modules\Core\Services\TenantContext::class)->get()?->name ?? __('Enterprise ERP') }}. {{ __('All rights reserved.') }}</p>
</footer>
