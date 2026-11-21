<?php

declare(strict_types=1);

namespace Modules\Sales\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Sales\Actions\CreateInvoiceAction;
use Modules\Sales\Actions\IssueInvoiceAction;
use Modules\Sales\Http\Requests\StoreInvoiceRequest;
use Modules\Sales\Models\Invoice;

class InvoiceController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Invoice::query()->with(['customer', 'items']);

        if ($request->has('status')) {
            $query->where('status', $request->query('status'));
        }

        $invoices = $query->orderByDesc('invoice_date')->paginate(25);

        return response()->json($invoices);
    }

    public function store(StoreInvoiceRequest $request, CreateInvoiceAction $action): JsonResponse
    {
        $invoice = $action->execute($request->validated());

        return response()->json([
            'message' => 'Draft invoice created successfully.',
            'data' => $invoice,
        ], 201);
    }

    public function show(string $id): JsonResponse
    {
        $invoice = Invoice::with(['customer', 'salesOrder', 'items', 'issuer'])->findOrFail($id);

        return response()->json([
            'data' => $invoice,
        ]);
    }

    public function issue(Request $request, string $id, IssueInvoiceAction $action): JsonResponse
    {
        $invoice = Invoice::with('items')->findOrFail($id);

        /** @var User $user */
        $user = $request->user();

        $issuedInvoice = $action->execute($invoice, $user);

        return response()->json([
            'message' => 'Invoice issued successfully.',
            'data' => $issuedInvoice,
        ]);
    }
}
