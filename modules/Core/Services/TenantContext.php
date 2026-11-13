<?php

declare(strict_types=1);

namespace Modules\Core\Services;

use Modules\Core\Models\Tenant;
use RuntimeException;

/**
 * Singleton service that holds the current tenant for the request lifecycle.
 *
 * Registered as a singleton in the container so every consumer shares the
 * same resolved tenant instance within a single request.
 */
final class TenantContext
{
    private ?Tenant $tenant = null;

    /**
     * Set the current tenant for this request.
     */
    public function set(Tenant $tenant): void
    {
        $this->tenant = $tenant;
    }

    /**
     * Get the current tenant, or null if none is set.
     */
    public function get(): ?Tenant
    {
        return $this->tenant;
    }

    /**
     * Get the current tenant or throw if none is set.
     *
     * @throws RuntimeException
     */
    public function require(): Tenant
    {
        if ($this->tenant === null) {
            throw new RuntimeException('No tenant has been set in the current context.');
        }

        return $this->tenant;
    }

    /**
     * Get the current tenant ID, or null if none is set.
     */
    public function id(): ?string
    {
        return $this->tenant?->id;
    }

    /**
     * Determine whether a tenant has been set.
     */
    public function has(): bool
    {
        return $this->tenant !== null;
    }

    /**
     * Clear the current tenant from context.
     */
    public function reset(): void
    {
        $this->tenant = null;
    }
}
