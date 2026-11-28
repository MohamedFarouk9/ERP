<?php

declare(strict_types=1);

namespace Modules\Finance\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\View\View;
use Modules\Finance\Models\Expense;
use Modules\Hrm\Models\PayrollRun;
use Modules\Payments\Models\Payment;
use Modules\Payments\Models\Refund;
use Modules\Pos\Models\PosSale;
use Modules\Procurement\Models\VendorBill;

class CashflowController extends Controller
{
    public function index(): View
    {
        $paymentsIn = (int) Payment::where('status', Payment::STATUS_COMPLETED)->sum('amount');
        $posIn = (int) PosSale::where('status', PosSale::STATUS_COMPLETED)->sum('total_amount');
        $totalInflow = $paymentsIn + $posIn;

        $expensesOut = (int) Expense::where('status', Expense::STATUS_PAID)->sum('total_amount');
        $billsOut = (int) VendorBill::where('status', VendorBill::STATUS_PAID)->sum('total_amount');
        $payrollOut = (int) PayrollRun::where('status', PayrollRun::STATUS_PAID)->sum('total_net');
        $refundsOut = (int) Refund::where('status', Refund::STATUS_COMPLETED)->sum('amount');
        $totalOutflow = $expensesOut + $billsOut + $payrollOut + $refundsOut;

        $stats = [
            'total_inflow' => $totalInflow,
            'total_outflow' => $totalOutflow,
            'net_cashflow' => $totalInflow - $totalOutflow,
            'payments_in' => $paymentsIn,
            'pos_in' => $posIn,
            'expenses_out' => $expensesOut,
            'bills_out' => $billsOut,
            'payroll_out' => $payrollOut,
            'refunds_out' => $refundsOut,
        ];

        $months = [];
        $inflowSeries = [];
        $outflowSeries = [];
        $netSeries = [];

        for ($i = 5; $i >= 0; $i--) {
            $m = Carbon::now()->subMonths($i);
            $ym = $m->format('Y-m');
            $months[] = $m->format('M Y');

            $mIn = (int) Payment::where('status', Payment::STATUS_COMPLETED)->where('payment_date', 'like', $ym.'%')->sum('amount')
                + (int) PosSale::where('status', PosSale::STATUS_COMPLETED)->where('sale_date', 'like', $ym.'%')->sum('total_amount');

            $mOut = (int) Expense::where('status', Expense::STATUS_PAID)->where('expense_date', 'like', $ym.'%')->sum('total_amount')
                + (int) VendorBill::where('status', VendorBill::STATUS_PAID)->where('bill_date', 'like', $ym.'%')->sum('total_amount')
                + (int) PayrollRun::where('status', PayrollRun::STATUS_PAID)->where('period_month', $ym)->sum('total_net')
                + (int) Refund::where('status', Refund::STATUS_COMPLETED)->where('refund_date', 'like', $ym.'%')->sum('amount');

            $inflowSeries[] = round($mIn / 100, 2);
            $outflowSeries[] = round($mOut / 100, 2);
            $netSeries[] = round(($mIn - $mOut) / 100, 2);
        }

        $chartData = [
            'months' => $months,
            'inflows' => $inflowSeries,
            'outflows' => $outflowSeries,
            'net' => $netSeries,
        ];

        $recentExpenses = Expense::with('category:id,name')->where('status', Expense::STATUS_PAID)->latest('expense_date')->limit(8)->get();
        $recentPayments = Payment::where('status', Payment::STATUS_COMPLETED)->latest('payment_date')->limit(8)->get();

        return view('admin.finance.cashflow', compact('stats', 'chartData', 'recentExpenses', 'recentPayments'));
    }
}
