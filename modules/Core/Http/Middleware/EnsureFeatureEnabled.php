<?php

declare(strict_types=1);

namespace Modules\Core\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\Core\Services\FeatureManager;
use Symfony\Component\HttpFoundation\Response;

class EnsureFeatureEnabled
{
    public function __construct(
        protected FeatureManager $featureManager
    ) {}

    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string $feature): Response
    {
        if (! $this->featureManager->isEnabled($feature)) {
            return response()->json([
                'message' => "Feature [{$feature}] is not enabled for your account.",
                'feature' => $feature,
            ], Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
