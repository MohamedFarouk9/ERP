<?php

declare(strict_types=1);

namespace Modules\Crm\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Crm\Actions\CompleteActivityAction;
use Modules\Crm\Actions\CreateActivityAction;
use Modules\Crm\Http\Requests\StoreActivityRequest;
use Modules\Crm\Models\CrmActivity;
use Modules\Crm\Models\CrmDeal;
use Modules\Crm\Models\CrmLead;

class ActivityController extends Controller
{
    public function index(): View
    {
        $leads = CrmLead::orderBy('name')->get(['id', 'lead_code', 'name']);
        $deals = CrmDeal::orderBy('title')->get(['id', 'deal_code', 'title']);
        $users = User::orderBy('name')->get(['id', 'name']);

        return view('admin.crm.activities', compact('leads', 'deals', 'users'));
    }

    public function data(Request $request): JsonResponse
    {
        $draw = (int) $request->input('draw', 1);
        $start = (int) $request->input('start', 0);
        $length = min((int) $request->input('length', 25), 100);
        $search = trim((string) $request->input('search.value', ''));

        $baseQuery = CrmActivity::query()->with(['lead:id,name', 'deal:id,title', 'assignee:id,name']);
        $recordsTotal = CrmActivity::count();

        if ($search !== '') {
            $baseQuery->where('subject', 'like', "%{$search}%");
        }

        $recordsFiltered = (clone $baseQuery)->count();
        $rows = $baseQuery->orderBy('status', 'desc')->orderBy('due_at')->skip($start)->take($length)->get();

        $data = $rows->map(fn (CrmActivity $a) => [
            'id' => $a->id,
            'type' => $a->type,
            'subject' => $a->subject,
            'related' => $a->deal?->title ?? $a->lead?->name ?? '-',
            'assignee_name' => $a->assignee?->name ?? '-',
            'due_at' => $a->due_at?->format('Y-m-d H:i') ?? '-',
            'status' => $a->status,
        ]);

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ]);
    }

    public function store(StoreActivityRequest $request, CreateActivityAction $action): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();
        $activity = $action->execute($request->validated(), $user);

        return response()->json([
            'message' => 'CRM activity scheduled.',
            'data' => $activity,
        ], 201);
    }

    public function complete(string $id, CompleteActivityAction $action): JsonResponse
    {
        $activity = CrmActivity::findOrFail($id);
        $completed = $action->execute($activity);

        return response()->json([
            'message' => 'Activity marked as completed.',
            'data' => $completed,
        ]);
    }
}
