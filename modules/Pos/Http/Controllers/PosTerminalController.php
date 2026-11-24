<?php

declare(strict_types=1);

namespace Modules\Pos\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Core\Services\TenantContext;
use Modules\Inventory\Models\Category;
use Modules\Inventory\Models\Product;
use Modules\Inventory\Models\Warehouse;
use Modules\Pos\Actions\CompletePosSaleAction;
use Modules\Pos\Http\Requests\CompletePosSaleRequest;
use Modules\Pos\Models\PosSale;
use Modules\Pos\Models\PosShift;
use Modules\Sales\Models\Customer;

class PosTerminalController extends Controller
{
    public function index(Request $request): View
    {
        /** @var User $user */
        $user = $request->user();

        $activeShift = PosShift::where('user_id', $user->id)
            ->where('status', PosShift::STATUS_OPEN)
            ->first();

        $categories = Category::where('is_active', true)->orderBy('name')->get(['id', 'name']);
        $products = Product::where('is_active', true)->orderBy('name')->take(60)->get(['id', 'category_id', 'sku', 'name', 'selling_price', 'unit']);
        $customers = Customer::where('is_active', true)->orderBy('name')->take(100)->get(['id', 'code', 'name']);
        $warehouses = Warehouse::where('is_active', true)->orderBy('name')->get(['id', 'code', 'name']);

        return view('admin.pos.terminal', compact('activeShift', 'categories', 'products', 'customers', 'warehouses'));
    }

    public function checkout(CompletePosSaleRequest $request, CompletePosSaleAction $action): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $sale = $action->execute($request->validated(), $user);

        return response()->json([
            'message' => "Sale [{$sale->receipt_number}] completed!",
            'data' => $sale,
            'receipt_url' => route('admin.pos.receipt', $sale->id),
        ], 201);
    }

    public function receipt(string $id, TenantContext $tenantContext): View
    {
        $sale = PosSale::with(['items', 'cashier', 'shift'])->findOrFail($id);
        $tenant = $tenantContext->require();

        return view('admin.pos.receipt', compact('sale', 'tenant'));
    }
}
