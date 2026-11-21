<?php

declare(strict_types=1);

namespace Modules\Accounting\Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Modules\Accounting\Models\Account;
use Modules\Accounting\Models\AccountingTransaction;
use Modules\Accounting\Models\AccountingTransactionLine;
use Modules\Accounting\Models\Currency;
use Modules\Accounting\Models\FinancialPeriod;
use Modules\Accounting\Models\FinancialYear;
use Modules\Core\Services\TenantContext;

class AccountingDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $tenantId = app(TenantContext::class)->requireId();

        $currencies = [
            ['code' => 'USD', 'name' => 'US Dollar', 'symbol' => '$', 'decimal_places' => 2],
            ['code' => 'EUR', 'name' => 'Euro', 'symbol' => '€', 'decimal_places' => 2],
            ['code' => 'EGP', 'name' => 'Egyptian Pound', 'symbol' => 'E£', 'decimal_places' => 2],
            ['code' => 'SAR', 'name' => 'Saudi Riyal', 'symbol' => 'SAR', 'decimal_places' => 2],
        ];

        foreach ($currencies as $currency) {
            Currency::firstOrCreate(['code' => $currency['code']], $currency);
        }

        $fy = FinancialYear::firstOrCreate(
            ['tenant_id' => $tenantId, 'name' => 'FY 2026'],
            ['start_date' => '2026-01-01', 'end_date' => '2026-12-31', 'is_closed' => false]
        );

        $period = FinancialPeriod::firstOrCreate(
            ['tenant_id' => $tenantId, 'financial_year_id' => $fy->id, 'name' => 'Q4 2026'],
            ['start_date' => '2026-10-01', 'end_date' => '2026-12-31', 'is_closed' => false]
        );

        $cashAccount = Account::firstOrCreate(
            ['tenant_id' => $tenantId, 'code' => '1010'],
            [
                'name' => 'Cash at Bank',
                'type' => Account::TYPE_ASSET,
                'normal_balance' => Account::BALANCE_DEBIT,
                'currency_code' => 'USD',
            ]
        );

        Account::firstOrCreate(
            ['tenant_id' => $tenantId, 'code' => '1200'],
            [
                'name' => 'Accounts Receivable',
                'type' => Account::TYPE_ASSET,
                'normal_balance' => Account::BALANCE_DEBIT,
                'currency_code' => 'USD',
            ]
        );

        $equityAccount = Account::firstOrCreate(
            ['tenant_id' => $tenantId, 'code' => '3000'],
            [
                'name' => 'Owner Equity',
                'type' => Account::TYPE_EQUITY,
                'normal_balance' => Account::BALANCE_CREDIT,
                'currency_code' => 'USD',
            ]
        );

        Account::firstOrCreate(
            ['tenant_id' => $tenantId, 'code' => '4000'],
            [
                'name' => 'Sales Revenue',
                'type' => Account::TYPE_REVENUE,
                'normal_balance' => Account::BALANCE_CREDIT,
                'currency_code' => 'USD',
            ]
        );

        Account::firstOrCreate(
            ['tenant_id' => $tenantId, 'code' => '5000'],
            [
                'name' => 'Cost of Goods Sold',
                'type' => Account::TYPE_EXPENSE,
                'normal_balance' => Account::BALANCE_DEBIT,
                'currency_code' => 'USD',
            ]
        );

        $admin = User::withoutGlobalScopes()->where('tenant_id', $tenantId)->first();

        $txn = AccountingTransaction::firstOrCreate(
            ['tenant_id' => $tenantId, 'transaction_number' => 'TXN-2026-00001'],
            [
                'financial_period_id' => $period->id,
                'transaction_date' => '2026-10-01',
                'status' => AccountingTransaction::STATUS_POSTED,
                'reference_type' => 'OpeningBalance',
                'notes' => 'Initial capital deposit ($100,000.00)',
                'posted_by' => $admin?->id,
                'posted_at' => now(),
            ]
        );

        if ($txn->lines()->count() === 0) {
            AccountingTransactionLine::create([
                'transaction_id' => $txn->id,
                'account_id' => $cashAccount->id,
                'debit' => 10000000, // $100,000.00
                'credit' => 0,
                'description' => 'Opening bank deposit',
            ]);

            AccountingTransactionLine::create([
                'transaction_id' => $txn->id,
                'account_id' => $equityAccount->id,
                'debit' => 0,
                'credit' => 10000000,
                'description' => 'Owner capital contribution',
            ]);
        }
    }
}
