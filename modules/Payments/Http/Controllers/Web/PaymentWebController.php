<?php

declare(strict_types=1);

namespace Modules\Payments\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Modules\Payments\Models\Payment;
use Modules\Payments\Models\PaymentMethod;
use Modules\Payments\Models\Refund;

class PaymentWebController extends Controller
{
    public function index(): View
    {
        $payments = Payment::with(['paymentMethod', 'processor'])->orderByDesc('payment_date')->paginate(25);
        $methods = PaymentMethod::orderBy('name')->get();

        return view('admin.payments.index', compact('payments', 'methods'));
    }

    public function refunds(): View
    {
        $refunds = Refund::with(['payment', 'processor'])->orderByDesc('refund_date')->paginate(25);

        return view('admin.payments.refunds', compact('refunds'));
    }
}
