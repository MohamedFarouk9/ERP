<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Modules\Sales\Models\Customer;
use Modules\Sales\Models\Invoice;
use Modules\Sales\Models\SalesOrder;

class SalesWebController extends Controller
{
    public function customers(): View
    {
        $customers = Customer::withCount(['salesOrders', 'invoices'])->orderBy('name')->paginate(25);

        return view('admin.sales.customers', compact('customers'));
    }

    public function orders(): View
    {
        $orders = SalesOrder::with(['customer', 'items'])->orderByDesc('order_date')->paginate(25);

        return view('admin.orders.index', compact('orders'));
    }

    public function invoices(): View
    {
        $invoices = Invoice::with(['customer', 'salesOrder'])->orderByDesc('invoice_date')->paginate(25);

        return view('admin.sales.invoices', compact('invoices'));
    }
}
