<?php

declare(strict_types=1);

namespace Modules\Users\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Core\Services\TenantContext;
use Modules\Users\Models\AuditLog;

final class AuditLogController extends Controller
{
    public function index(Request $request, TenantContext $tenantContext): JsonResponse
    {
        $tenantId = $tenantContext->require()->id;

        $query = AuditLog::where('tenant_id', $tenantId)
            ->latest('created_at');

        if ($request->has('event')) {
            $query->where('event', (string) $request->input('event'));
        }

        if ($request->has('user_id')) {
            $query->where('user_id', (string) $request->input('user_id'));
        }

        $logs = $query->paginate(30);

        return response()->json($logs, 200);
    }
}

