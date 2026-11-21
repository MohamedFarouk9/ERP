<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Modules\Accounting\Models\Account;
use Modules\Accounting\Models\AccountingTransaction;

class AccountingWebController extends Controller
{
    public function accounts(): View
    {
        $accounts = Account::with('parent')->orderBy('code')->paginate(25);

        return view('admin.accounting.accounts', compact('accounts'));
    }

    public function transactions(): View
    {
        $transactions = AccountingTransaction::with(['lines.account', 'poster'])
            ->orderByDesc('transaction_date')
            ->paginate(20);

        return view('admin.accounting.transactions', compact('transactions'));
    }
}
