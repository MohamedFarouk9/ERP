<?php

declare(strict_types=1);

namespace Modules\Core\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Core\Models\Tenant;
use Modules\Core\Services\TenantContext;

/**
 * Trait for Eloquent models that belong to a tenant.
 *
 * Automatically sets tenant_id on creation and applies a global scope
 * to filter queries by the current tenant context.
 *
 * @mixin Model
 */
trait BelongsToTenant
{
    public static function bootBelongsToTenant(): void
    {
        static::creating(function (Model $model): void {
            if (empty($model->getAttribute('tenant_id'))) {
                /** @var TenantContext $context */
                $context = app(TenantContext::class);

                if ($context->has()) {
                    $model->setAttribute('tenant_id', $context->id());
                }
            }
        });

        static::addGlobalScope('tenant', function (Builder $builder): void {
            /** @var TenantContext $context */
            $context = app(TenantContext::class);

            if ($context->has()) {
                $builder->where(
                    $builder->getModel()->qualifyColumn('tenant_id'),
                    $context->id(),
                );
            }
        });
    }

    /**
     * Get the tenant that owns this model.
     *
     * @return BelongsTo<Tenant, $this>
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Scope the query to a specific tenant.
     *
     * @param  Builder<static>  $query
     */
    public function scopeForTenant(Builder $query, string $tenantId): Builder
    {
        return $query->where(
            $this->qualifyColumn('tenant_id'),
            $tenantId,
        );
    }
}
