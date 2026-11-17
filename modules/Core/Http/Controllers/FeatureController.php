<?php

declare(strict_types=1);

namespace Modules\Core\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Core\Services\FeatureManager;

class FeatureController extends Controller
{
    public function __construct(
        protected FeatureManager $featureManager
    ) {}

    public function index(): JsonResponse
    {
        return response()->json([
            'data' => $this->featureManager->all(),
        ]);
    }

    public function show(string $feature): JsonResponse
    {
        return response()->json([
            'feature' => $feature,
            'is_enabled' => $this->featureManager->isEnabled($feature),
        ]);
    }

    public function update(Request $request, string $feature): JsonResponse
    {
        $validated = $request->validate([
            'is_enabled' => ['required', 'boolean'],
            'metadata' => ['nullable', 'array'],
        ]);

        if ($validated['is_enabled']) {
            $this->featureManager->enable($feature, null, $validated['metadata'] ?? []);
        } else {
            $this->featureManager->disable($feature);
        }

        return response()->json([
            'message' => 'Feature status updated successfully.',
            'feature' => $feature,
            'is_enabled' => (bool) $validated['is_enabled'],
        ]);
    }
}
