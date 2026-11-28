<?php

declare(strict_types=1);

namespace Modules\Finance\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;
use Modules\Finance\Models\Expense;
use Modules\Finance\Models\ExpenseCategory;
use Modules\Finance\Models\TaxRate;
use Modules\Hrm\Models\PayrollRun;
use Modules\Pos\Models\PosSale;
use Modules\Procurement\Models\VendorBill;
use Modules\Sales\Models\Invoice;

class FinanceReportController extends Controller
{
    public function expenseSummary(Request $request): View
    {
        [$start, $end] = $this->dateRange($request);

        $byCategory = ExpenseCategory::orderBy('name')->get()->map(function (ExpenseCategory $cat) use ($start, $end) {
            $q = Expense::where('expense_category_id', $cat->id)->whereBetween('expense_date', [$start, $end]);

            return (object) [
                'code' => $cat->code,
                'name' => $cat->name,
                'count' => (clone $q)->count(),
                'subtotal' => (int) (clone $q)->sum('amount'),
                'tax' => (int) (clone $q)->sum('tax_amount'),
                'total' => (int) (clone $q)->sum('total_amount'),
            ];
        })->filter(fn ($row) => $row->count > 0)->values();

        $vendorBillsTotal = (int) VendorBill::whereBetween('bill_date', [$start, $end])->sum('total_amount');
        $payrollTotal = (int) PayrollRun::whereBetween('pay_date', [$start, $end])->where('status', PayrollRun::STATUS_PAID)->sum('total_net');
        $operatingTotal = (int) $byCategory->sum('total');

        return view('admin.finance.reports.expense-summary', compact('start', 'end', 'byCategory', 'operatingTotal', 'vendorBillsTotal', 'payrollTotal'));
    }

    public function incomeSummary(Request $request): View
    {
        [$start, $end] = $this->dateRange($request);

        $invoicesQ = Invoice::whereIn('status', [Invoice::STATUS_ISSUED, Invoice::STATUS_PAID, Invoice::STATUS_OVERDUE])
            ->whereBetween('invoice_date', [$start, $end]);
        $posQ = PosSale::where('status', PosSale::STATUS_COMPLETED)
            ->whereBetween('sale_date', [$start, $end]);

        $summary = [
            'invoice_count' => (clone $invoicesQ)->count(),
            'invoice_revenue' => (int) (clone $invoicesQ)->sum('total_amount'),
            'invoice_collected' => (int) (clone $invoicesQ)->sum('amount_paid'),
            'pos_count' => (clone $posQ)->count(),
            'pos_revenue' => (int) (clone $posQ)->sum('total_amount'),
        ];
        $summary['total_income'] = $summary['invoice_revenue'] + $summary['pos_revenue'];

        $recentInvoices = (clone $invoicesQ)->with('customer:id,name')->latest('invoice_date')->limit(15)->get();
        $recentPos = (clone $posQ)->with('cashier:id,name')->latest('sale_date')->limit(15)->get();

        return view('admin.finance.reports.income-summary', compact('start', 'end', 'summary', 'recentInvoices', 'recentPos'));
    }

    public function profitAndLoss(Request $request): View
    {
        [$start, $end] = $this->dateRange($request);

        $b2bRevenue = (int) Invoice::whereIn('status', [Invoice::STATUS_ISSUED, Invoice::STATUS_PAID, Invoice::STATUS_OVERDUE])
            ->whereBetween('invoice_date', [$start, $end])
            ->sum('subtotal');
        $posRevenue = (int) PosSale::where('status', PosSale::STATUS_COMPLETED)
            ->whereBetween('sale_date', [$start, $end])
            ->sum('subtotal');
        $totalRevenue = $b2bRevenue + $posRevenue;

        $cogsBills = (int) VendorBill::whereBetween('bill_date', [$start, $end])->sum('total_amount');
        $grossProfit = $totalRevenue - $cogsBills;

        $operatingExpenses = (int) Expense::whereBetween('expense_date', [$start, $end])->sum('amount');
        $payrollExpenses = (int) PayrollRun::whereBetween('pay_date', [$start, $end])->where('status', PayrollRun::STATUS_PAID)->sum('total_net');
        $totalOpEx = $operatingExpenses + $payrollExpenses;

        $netProfit = $grossProfit - $totalOpEx;
        $netMargin = $totalRevenue > 0 ? round(($netProfit / $totalRevenue) * 100, 1) : 0.0;

        $expenseBreakdown = ExpenseCategory::orderBy('name')->get()->map(function (ExpenseCategory $cat) use ($start, $end) {
            return (object) [
                'name' => $cat->name,
                'amount' => (int) Expense::where('expense_category_id', $cat->id)->whereBetween('expense_date', [$start, $end])->sum('amount'),
            ];
        })->filter(fn ($r) => $r->amount > 0)->values();

        return view('admin.finance.reports.profit-loss', compact(
            'start',
            'end',
            'b2bRevenue',
            'posRevenue',
            'totalRevenue',
            'cogsBills',
            'grossProfit',
            'operatingExpenses',
            'payrollExpenses',
            'totalOpEx',
            'netProfit',
            'netMargin',
            'expenseBreakdown'
        ));
    }

    public function incomeVsExpense(): View
    {
        $months = [];
        $incomeSeries = [];
        $expenseSeries = [];
        $rows = [];

        for ($i = 5; $i >= 0; $i--) {
            $m = Carbon::now()->subMonths($i);
            $ym = $m->format('Y-m');
            $label = $m->format('M Y');
            $months[] = $label;

            $inc = (int) Invoice::whereIn('status', [Invoice::STATUS_ISSUED, Invoice::STATUS_PAID, Invoice::STATUS_OVERDUE])
                ->where('invoice_date', 'like', $ym.'%')->sum('total_amount')
                + (int) PosSale::where('status', PosSale::STATUS_COMPLETED)->where('sale_date', 'like', $ym.'%')->sum('total_amount');

            $exp = (int) Expense::where('expense_date', 'like', $ym.'%')->sum('total_amount')
                + (int) VendorBill::where('bill_date', 'like', $ym.'%')->sum('total_amount')
                + (int) PayrollRun::where('status', PayrollRun::STATUS_PAID)->where('period_month', $ym)->sum('total_net');

            $incomeSeries[] = round($inc / 100, 2);
            $expenseSeries[] = round($exp / 100, 2);
            $rows[] = (object) [
                'month' => $label,
                'income' => $inc,
                'expense' => $exp,
                'net' => $inc - $exp,
            ];
        }

        $chartData = [
            'months' => $months,
            'income' => $incomeSeries,
            'expense' => $expenseSeries,
        ];

        return view('admin.finance.reports.income-vs-expense', compact('chartData', 'rows'));
    }

    public function taxSummary(Request $request): View
    {
        [$start, $end] = $this->dateRange($request);

        $invoiceTax = (int) Invoice::whereIn('status', [Invoice::STATUS_ISSUED, Invoice::STATUS_PAID, Invoice::STATUS_OVERDUE])
            ->whereBetween('invoice_date', [$start, $end])
            ->sum('tax_amount');
        $posTax = (int) PosSale::where('status', PosSale::STATUS_COMPLETED)
            ->whereBetween('sale_date', [$start, $end])
            ->sum('tax_amount');
        $outputTax = $invoiceTax + $posTax;

        $expenseTax = (int) Expense::whereBetween('expense_date', [$start, $end])->sum('tax_amount');
        $billTax = (int) VendorBill::whereBetween('bill_date', [$start, $end])->sum('tax_amount');
        $inputTax = $expenseTax + $billTax;

        $netTaxPayable = $outputTax - $inputTax;
        $taxRates = TaxRate::orderBy('code')->get();

        return view('admin.finance.reports.tax-summary', compact(
            'start',
            'end',
            'invoiceTax',
            'posTax',
            'outputTax',
            'expenseTax',
            'billTax',
            'inputTax',
            'netTaxPayable',
            'taxRates'
        ));
    }

    /**
     * @return array{0: string, 1: string}
     */
    protected function dateRange(Request $request): array
    {
        $start = (string) $request->input('start_date', Carbon::now()->startOfYear()->toDateString());
        $end = (string) $request->input('end_date', Carbon::now()->endOfYear()->toDateString());

        return [$start, $end];
    }
}
