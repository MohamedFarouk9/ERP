<?php

declare(strict_types=1);

namespace Modules\Core\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Core\Models\Tenant;
use Modules\Core\Services\TenantContext;
use Symfony\Component\HttpFoundation\Response;

/**
 * Ensures the resolved tenant in TenantContext is active.
 *
 * This middleware should be applied after ResolveTenantMiddleware.
 */
final class EnsureTenantIsActive
{
    public function __construct(
        private readonly TenantContext $tenantContext,
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        if (! $this->tenantContext->has()) {
            return new JsonResponse(
                ['message' => 'No tenant context available.'],
                Response::HTTP_FORBIDDEN,
            );
        }

        $tenant = $this->tenantContext->get();

        if ($tenant === null || ! $tenant->isActive()) {
            $status = $tenant?->status;

            $message = match ($status) {
                Tenant::SUSPENDED => 'Your account has been suspended. Please contact support.',
                Tenant::DEACTIVATED => 'Your account has been deactivated. Please contact support.',
                default => 'Your account is not currently active. Please contact support.',
            };

            return new JsonResponse(
                ['message' => $message],
                Response::HTTP_FORBIDDEN,
            );
        }

        return $next($request);
    }
}
