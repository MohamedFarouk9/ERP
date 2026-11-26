<?php

declare(strict_types=1);

namespace Modules\Crm\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Crm\Actions\CreateDealAction;
use Modules\Crm\Actions\UpdateDealStageAction;
use Modules\Crm\Http\Requests\StoreDealRequest;
use Modules\Crm\Http\Requests\UpdateDealStageRequest;
use Modules\Crm\Models\CrmDeal;
use Modules\Crm\Models\CrmLead;
use Modules\Sales\Models\Customer;

class DealController extends Controller
{
    public function index(): View
    {
        $customers = Customer::where('is_active', true)->orderBy('name')->get(['id', 'code', 'name']);
        $leads = CrmLead::orderBy('name')->get(['id', 'lead_code', 'name']);
        $owners = User::orderBy('name')->get(['id', 'name']);

        return view('admin.crm.deals', compact('customers', 'leads', 'owners'));
    }

    public function data(Request $request): JsonResponse
    {
        $draw = (int) $request->input('draw', 1);
        $start = (int) $request->input('start', 0);
        $length = min((int) $request->input('length', 25), 100);
        $search = trim((string) $request->input('search.value', ''));
        $stage = trim((string) $request->input('stage', ''));

        $baseQuery = CrmDeal::query()->with(['customer:id,name', 'owner:id,name']);
        $recordsTotal = CrmDeal::count();

        if ($stage !== '') {
            $baseQuery->where('stage', $stage);
        }

        if ($search !== '') {
            $baseQuery->where(function ($q) use ($search) {
                $q->where('deal_code', 'like', "%{$search}%")
                    ->orWhere('title', 'like', "%{$search}%");
            });
        }

        $recordsFiltered = (clone $baseQuery)->count();
        $rows = $baseQuery->latest()->skip($start)->take($length)->get();

        $data = $rows->map(fn (CrmDeal $d) => [
            'id' => $d->id,
            'deal_code' => $d->deal_code,
            'title' => $d->title,
            'customer_name' => $d->customer?->name ?? 'Unassigned',
            'owner_name' => $d->owner?->name ?? '-',
            'value_amount' => $d->value_amount,
            'probability' => $d->probability,
            'stage' => $d->stage,
            'expected_close_date' => $d->expected_close_date?->format('Y-m-d') ?? '-',
        ]);

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ]);
    }

    public function store(StoreDealRequest $request, CreateDealAction $action): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();
        $deal = $action->execute($request->validated(), $user);

        return response()->json([
            'message' => "Deal [{$deal->deal_code}] created successfully.",
            'data' => $deal,
        ], 201);
    }

    public function updateStage(UpdateDealStageRequest $request, string $id, UpdateDealStageAction $action): JsonResponse
    {
        $deal = CrmDeal::findOrFail($id);
        $updated = $action->execute($deal, (string) $request->validated('stage'));

        return response()->json([
            'message' => "Deal [{$updated->deal_code}] moved to [".strtoupper($updated->stage).'].',
            'data' => $updated,
        ]);
    }
}
