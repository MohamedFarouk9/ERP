<?php

declare(strict_types=1);

namespace Modules\Core\Services;

use Modules\Core\Models\TenantFeature;

class FeatureManager
{
    /**
     * @var array<string, array<string, bool>>
     */
    protected array $cache = [];

    public function __construct(
        protected TenantContext $tenantContext
    ) {}

    public function isEnabled(string $featureSlug, ?string $tenantId = null): bool
    {
        $tenantId = $tenantId ?? $this->tenantContext->id();

        if (! $tenantId) {
            return false;
        }

        if (isset($this->cache[$tenantId][$featureSlug])) {
            return $this->cache[$tenantId][$featureSlug];
        }

        $this->loadFeaturesForTenant($tenantId);

        return $this->cache[$tenantId][$featureSlug] ?? false;
    }

    public function enable(string $featureSlug, ?string $tenantId = null, array $metadata = []): void
    {
        $tenantId = $tenantId ?? $this->tenantContext->id();

        if (! $tenantId) {
            return;
        }

        TenantFeature::withoutGlobalScopes()->updateOrCreate(
            [
                'tenant_id' => $tenantId,
                'feature_slug' => $featureSlug,
            ],
            [
                'is_enabled' => true,
                'metadata' => $metadata ?: null,
            ]
        );

        $this->cache[$tenantId][$featureSlug] = true;
    }

    public function disable(string $featureSlug, ?string $tenantId = null): void
    {
        $tenantId = $tenantId ?? $this->tenantContext->id();

        if (! $tenantId) {
            return;
        }

        TenantFeature::withoutGlobalScopes()->updateOrCreate(
            [
                'tenant_id' => $tenantId,
                'feature_slug' => $featureSlug,
            ],
            [
                'is_enabled' => false,
            ]
        );

        $this->cache[$tenantId][$featureSlug] = false;
    }

    /**
     * @return array<string, bool>
     */
    public function all(?string $tenantId = null): array
    {
        $tenantId = $tenantId ?? $this->tenantContext->id();

        if (! $tenantId) {
            return [];
        }

        if (! isset($this->cache[$tenantId])) {
            $this->loadFeaturesForTenant($tenantId);
        }

        return $this->cache[$tenantId] ?? [];
    }

    public function flushCache(?string $tenantId = null): void
    {
        if ($tenantId !== null) {
            unset($this->cache[$tenantId]);
        } else {
            $this->cache = [];
        }
    }

    protected function loadFeaturesForTenant(string $tenantId): void
    {
        $features = TenantFeature::withoutGlobalScopes()
            ->where('tenant_id', $tenantId)
            ->get();

        $this->cache[$tenantId] = [];

        foreach ($features as $feature) {
            $this->cache[$tenantId][$feature->feature_slug] = (bool) $feature->is_enabled;
        }
    }
}
