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
 * Resolves the tenant from the authenticated user's tenant_id and
 * stores it in TenantContext for the remainder of the request.
 */
final class ResolveTenantMiddleware
{
    public function __construct(
        private readonly TenantContext $tenantContext,
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // If no authenticated user, let auth middleware handle it.
        if ($user === null) {
            return $next($request);
        }

        $tenantId = $user->tenant_id ?? null;

        if ($tenantId === null) {
            return new JsonResponse(
                ['message' => 'Your account is not associated with any tenant.'],
                Response::HTTP_FORBIDDEN,
            );
        }

        $tenant = Tenant::query()
            ->where('id', $tenantId)
            ->where('status', Tenant::ACTIVE)
            ->first();

        if ($tenant === null) {
            return new JsonResponse(
                ['message' => 'Tenant not found or is not active.'],
                Response::HTTP_FORBIDDEN,
            );
        }

        $this->tenantContext->set($tenant);

        return $next($request);
    }
}
