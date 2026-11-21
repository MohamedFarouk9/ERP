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

        $recentTransactions = AccountingTransaction::with('lines')
            ->orderByDesc('transaction_date')
            ->take(8)
            ->get();

        $recentPayments = Payment::with('paymentMethod')
            ->orderByDesc('payment_date')
            ->take(8)
            ->get();

        return view('admin.accounting.dashboard', compact('stats', 'accountsByType', 'recentTransactions', 'recentPayments'));
    }
}
