<?php

declare(strict_types=1);

namespace Modules\Accounting\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Modules\Accounting\Models\Account;
use Modules\Accounting\Models\AccountingTransaction;
use Modules\Accounting\Models\AccountingTransactionLine;
use Modules\Payments\Models\Payment;
use Modules\Payments\Models\Refund;

class FinanceDashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'accounts_count' => Account::count(),
            'posted_transactions' => AccountingTransaction::where('status', AccountingTransaction::STATUS_POSTED)->count(),
            'draft_transactions' => AccountingTransaction::where('status', AccountingTransaction::STATUS_DRAFT)->count(),
            'total_ledger_volume' => (int) AccountingTransactionLine::whereHas(
                'transaction',
                fn ($q) => $q->where('status', AccountingTransaction::STATUS_POSTED)
            )->sum('debit'),
            'payments_collected' => (int) Payment::where('status', Payment::STATUS_COMPLETED)->sum('amount'),
            'refunds_total' => (int) Refund::where('status', Refund::STATUS_COMPLETED)->sum('amount'),
        ];

        $accountsByType = Account::query()
            ->selectRaw('type, count(*) as total')
            ->groupBy('type')
            ->pluck('total', 'type');

        $months = [];
        $monthlyInflow = [];
        $monthlyRefunds = [];
        for ($i = 5; $i >= 0; $i--) {
            $start = now()->subMonths($i)->startOfMonth();
            $end = now()->subMonths($i)->endOfMonth();
            $months[] = $start->format('M Y');

            $inflow = (int) Payment::whereBetween('payment_date', [$start->toDateString(), $end->toDateString()])
                ->where('status', Payment::STATUS_COMPLETED)
                ->sum('amount');

            $refunds = (int) Refund::whereBetween('refund_date', [$start->toDateString(), $end->toDateString()])
                ->where('status', Refund::STATUS_COMPLETED)
                ->sum('amount');

            $monthlyInflow[] = round($inflow / 100, 2);
            $monthlyRefunds[] = round($refunds / 100, 2);
        }

        $chartData = [
            'months' => $months,
            'inflow' => $monthlyInflow,
            'refunds' => $monthlyRefunds,
            'account_types' => [
                'asset' => (int) ($accountsByType['asset'] ?? 0),
                'liability' => (int) ($accountsByType['liability'] ?? 0),
                'equity' => (int) ($accountsByType['equity'] ?? 0),
                'revenue' => (int) ($accountsByType['revenue'] ?? 0),
                'expense' => (int) ($accountsByType['expense'] ?? 0),
            ],
        ];

        $recentTransactions = AccountingTransaction::with('lines')
            ->orderByDesc('transaction_date')
            ->take(8)
            ->get();

        $recentPayments = Payment::with('paymentMethod')
            ->orderByDesc('payment_date')
            ->take(8)
            ->get();

        return view('admin.accounting.dashboard', compact('stats', 'accountsByType', 'chartData', 'recentTransactions', 'recentPayments'));
    }
}
