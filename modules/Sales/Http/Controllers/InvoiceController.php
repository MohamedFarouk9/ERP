<?php

declare(strict_types=1);

namespace Modules\Sales\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Sales\Actions\CreateInvoiceAction;
use Modules\Sales\Actions\IssueInvoiceAction;
use Modules\Sales\Http\Requests\StoreInvoiceRequest;
use Modules\Sales\Models\Customer;
use Modules\Sales\Models\Invoice;
use Modules\Sales\Models\SalesOrder;

class InvoiceController extends Controller
{
    public function index(): View
    {
        $customers = Customer::where('is_active', true)->orderBy('name')->get(['id', 'code', 'name']);
        $orders = SalesOrder::orderByDesc('order_date')->take(50)->get(['id', 'order_number']);

        return view('admin.sales.invoices', compact('customers', 'orders'));
    }

    public function data(Request $request): JsonResponse
    {
        $draw = (int) $request->input('draw', 1);
        $start = (int) $request->input('start', 0);
        $length = min((int) $request->input('length', 25), 100);
        $search = trim((string) $request->input('search.value', ''));

        $baseQuery = Invoice::query()->with(['customer:id,name']);
        $recordsTotal = Invoice::count();

        if ($search !== '') {
            $baseQuery->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%")
                    ->orWhereHas('customer', fn ($cq) => $cq->where('name', 'like', "%{$search}%"));
            });
        }

        $recordsFiltered = (clone $baseQuery)->count();

        $columns = ['invoice_number', 'customer_id', 'invoice_date', 'due_date', 'total_amount', 'amount_paid', 'status'];
        $orderColIdx = (int) $request->input('order.0.column', 2);
        $orderDir = $request->input('order.0.dir', 'desc') === 'asc' ? 'asc' : 'desc';
        $orderCol = $columns[$orderColIdx] ?? 'invoice_date';

        $rows = $baseQuery->orderBy($orderCol, $orderDir)
            ->skip($start)
            ->take($length)
            ->get();

        $data = $rows->map(fn (Invoice $inv) => [
            'id' => $inv->id,
            'invoice_number' => $inv->invoice_number,
            'customer_name' => $inv->customer?->name ?? '-',
            'invoice_date' => $inv->invoice_date?->format('Y-m-d') ?? '-',
            'due_date' => $inv->due_date?->format('Y-m-d') ?? '-',
            'total_amount' => $inv->total_amount,
            'amount_paid' => $inv->amount_paid,
            'status' => $inv->status,
        ]);

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ]);
    }

    public function store(StoreInvoiceRequest $request, CreateInvoiceAction $action): JsonResponse
    {
        $invoice = $action->execute($request->validated());

        return response()->json([
            'message' => "Draft invoice [{$invoice->invoice_number}] created successfully.",
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
            'message' => "Invoice [{$issuedInvoice->invoice_number}] issued successfully.",
            'data' => $issuedInvoice,
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $invoice = Invoice::findOrFail($id);
        if ($invoice->status !== Invoice::STATUS_DRAFT) {
            return response()->json([
                'message' => 'Only draft invoices can be deleted.',
            ], 422);
        }

        $num = $invoice->invoice_number;
        $invoice->delete();

        return response()->json([
            'message' => "Invoice [{$num}] deleted successfully.",
        ]);
    }
}
