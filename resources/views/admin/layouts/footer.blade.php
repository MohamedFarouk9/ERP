<footer class="page-footer">
    <p class="mb-0">Copyright © {{ date('Y') }} {{ app(\Modules\Core\Services\TenantContext::class)->get()?->name ?? 'Enterprise ERP' }}. All rights reserved.</p>
</footer>
