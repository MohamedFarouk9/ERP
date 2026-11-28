<?php

declare(strict_types=1);

namespace Modules\Finance\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Modules\Finance\Actions\CreateTaxRateAction;
use Modules\Finance\Http\Requests\StoreTaxRateRequest;
use Modules\Finance\Models\TaxRate;

class TaxRateController extends Controller
{
    public function index(): View
    {
        $taxRates = TaxRate::orderBy('code')->get();

        return view('admin.finance.taxes', compact('taxRates'));
    }

    public function store(StoreTaxRateRequest $request, CreateTaxRateAction $action): JsonResponse
    {
        $taxRate = $action->execute($request->validated());

        return response()->json([
            'message' => "Tax rate [{$taxRate->code}] created successfully.",
            'data' => $taxRate,
        ], 201);
    }

    public function destroy(string $id): JsonResponse
    {
        $taxRate = TaxRate::findOrFail($id);
        $code = $taxRate->code;
        $taxRate->delete();

        return response()->json([
            'message' => "Tax rate [{$code}] deleted successfully.",
        ]);
    }
}
