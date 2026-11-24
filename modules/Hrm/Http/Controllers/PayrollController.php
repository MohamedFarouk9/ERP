<?php

declare(strict_types=1);

namespace Modules\Hrm\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Hrm\Actions\GeneratePayrollRunAction;
use Modules\Hrm\Actions\PayPayrollRunAction;
use Modules\Hrm\Http\Requests\StorePayrollRunRequest;
use Modules\Hrm\Models\PayrollRun;

class PayrollController extends Controller
{
    public function index(): View
    {
        $runs = PayrollRun::withCount('items')->orderByDesc('period_month')->get();

        return view('admin.hrm.payroll', compact('runs'));
    }

    public function store(StorePayrollRunRequest $request, GeneratePayrollRunAction $action): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $run = $action->execute($request->validated(), $user);

        return response()->json([
            'message' => "Payroll run [{$run->payroll_number}] generated for {$run->items->count()} employees.",
            'data' => $run,
        ], 201);
    }

    public function show(string $id): JsonResponse
    {
        $run = PayrollRun::with(['items.employee'])->findOrFail($id);

        return response()->json(['data' => $run]);
    }

    public function pay(Request $request, string $id, PayPayrollRunAction $action): JsonResponse
    {
        $run = PayrollRun::findOrFail($id);

        /** @var User $user */
        $user = $request->user();

        $paid = $action->execute($run, $user);

        return response()->json([
            'message' => "Payroll run [{$paid->payroll_number}] marked as paid.",
            'data' => $paid,
        ]);
    }
}
