<?php

declare(strict_types=1);

namespace Modules\Crm\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Crm\Actions\ConvertLeadToCustomerAction;
use Modules\Crm\Actions\CreateLeadAction;
use Modules\Crm\Actions\UpdateLeadAction;
use Modules\Crm\Http\Requests\StoreLeadRequest;
use Modules\Crm\Http\Requests\UpdateLeadRequest;
use Modules\Crm\Models\CrmLead;

class LeadController extends Controller
{
    public function index(): View
    {
        $owners = User::orderBy('name')->get(['id', 'name']);

        return view('admin.crm.leads', compact('owners'));
    }

    public function data(Request $request): JsonResponse
    {
        $draw = (int) $request->input('draw', 1);
        $start = (int) $request->input('start', 0);
        $length = min((int) $request->input('length', 25), 100);
        $search = trim((string) $request->input('search.value', ''));
        $status = trim((string) $request->input('status', ''));

        $baseQuery = CrmLead::query()->with(['owner:id,name', 'convertedCustomer:id,code,name']);
        $recordsTotal = CrmLead::count();

        if ($status !== '') {
            $baseQuery->where('status', $status);
        }

        if ($search !== '') {
            $baseQuery->where(function ($q) use ($search) {
                $q->where('lead_code', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('company', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $recordsFiltered = (clone $baseQuery)->count();
        $rows = $baseQuery->latest()->skip($start)->take($length)->get();

        $data = $rows->map(fn (CrmLead $l) => [
            'id' => $l->id,
            'lead_code' => $l->lead_code,
            'name' => $l->name,
            'company' => $l->company ?? '-',
            'email' => $l->email ?? '-',
            'phone' => $l->phone ?? '-',
            'source' => $l->source,
            'estimated_value' => $l->estimated_value,
            'owner_name' => $l->owner?->name ?? '-',
            'status' => $l->status,
            'customer_name' => $l->convertedCustomer?->name,
        ]);

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ]);
    }

    public function store(StoreLeadRequest $request, CreateLeadAction $action): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();
        $lead = $action->execute($request->validated(), $user);

        return response()->json([
            'message' => "Lead [{$lead->lead_code}] created successfully.",
            'data' => $lead,
        ], 201);
    }

    public function update(UpdateLeadRequest $request, string $id, UpdateLeadAction $action): JsonResponse
    {
        $lead = CrmLead::findOrFail($id);
        $updated = $action->execute($lead, $request->validated());

        return response()->json([
            'message' => "Lead [{$updated->lead_code}] updated.",
            'data' => $updated,
        ]);
    }

    public function convert(Request $request, string $id, ConvertLeadToCustomerAction $action): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();
        $lead = CrmLead::findOrFail($id);
        $result = $action->execute($lead, $user, $request->boolean('create_deal', true));

        return response()->json([
            'message' => "Lead [{$lead->lead_code}] converted to Customer [{$result['customer']->code}]!",
            'data' => $result,
        ]);
    }
}
