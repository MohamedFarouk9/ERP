<?php

declare(strict_types=1);

namespace Modules\Users\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Modules\Users\Models\AuditLog;
use Modules\Users\Models\Permission;
use Modules\Users\Models\Role;

class AccessControlWebController extends Controller
{
    public function roles(): View
    {
        $roles = Role::withCount(['permissions', 'users'])->orderBy('name')->paginate(25);

        return view('admin.roles.index', compact('roles'));
    }

    public function permissions(): View
    {
        $permissions = Permission::orderBy('module')->orderBy('resource')->get()->groupBy('module');

        return view('admin.permissions.index', compact('permissions'));
    }

    public function auditLogs(): View
    {
        $logs = AuditLog::with('user')->latest('created_at')->paginate(30);

        return view('admin.audit-logs.index', compact('logs'));
    }
}
