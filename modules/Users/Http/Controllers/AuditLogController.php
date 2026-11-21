<?php

declare(strict_types=1);

namespace Modules\Users\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Users\Models\AuditLog;

class AuditLogController extends Controller
{
    public function index(): View
    {
        return view('admin.audit-logs.index');
    }

    public function data(Request $request): JsonResponse
    {
        $draw = (int) $request->input('draw', 1);
        $start = (int) $request->input('start', 0);
        $length = min((int) $request->input('length', 25), 100);
        $search = trim((string) $request->input('search.value', ''));

        $baseQuery = AuditLog::query()->with(['user:id,name']);
        $recordsTotal = AuditLog::count();

        if ($search !== '') {
            $baseQuery->where(function ($q) use ($search) {
                $q->where('event', 'like', "%{$search}%")
                    ->orWhere('auditable_type', 'like', "%{$search}%")
                    ->orWhere('ip_address', 'like', "%{$search}%");
            });
        }

        $recordsFiltered = (clone $baseQuery)->count();

        $columns = ['created_at', 'user_id', 'event', 'auditable_type', 'ip_address'];
        $orderColIdx = (int) $request->input('order.0.column', 0);
        $orderDir = $request->input('order.0.dir', 'desc') === 'asc' ? 'asc' : 'desc';
        $orderCol = $columns[$orderColIdx] ?? 'created_at';

        $rows = $baseQuery->orderBy($orderCol, $orderDir)
            ->skip($start)
            ->take($length)
            ->get();

        $data = $rows->map(fn (AuditLog $log) => [
            'created_at' => $log->created_at?->format('Y-m-d H:i:s') ?? '-',
            'user_name' => $log->user?->name ?? 'System',
            'event' => $log->event,
            'target' => class_basename($log->auditable_type).' #'.$log->auditable_id,
            'ip_address' => $log->ip_address ?? '-',
        ]);

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ]);
    }
}
