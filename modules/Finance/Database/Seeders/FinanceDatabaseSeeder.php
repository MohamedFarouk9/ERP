<?php

declare(strict_types=1);

namespace Modules\Finance\Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Modules\Accounting\Models\Account;
use Modules\Core\Models\Tenant;
use Modules\Core\Services\TenantContext;
use Modules\Finance\Models\Budget;
use Modules\Finance\Models\Expense;
use Modules\Finance\Models\ExpenseCategory;
use Modules\Finance\Models\TaxRate;

class FinanceDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = Tenant::where('slug', 'acme-corp')->first();
        if (! $tenant) {
            return;
        }

        app(TenantContext::class)->set($tenant);
        $tenantId = $tenant->id;
        $admin = User::withoutGlobalScopes()->where('tenant_id', $tenantId)->first();
        $expenseAcc = Account::where('tenant_id', $tenantId)->where('code', '5000')->first();

        $catRent = ExpenseCategory::firstOrCreate(
            ['tenant_id' => $tenantId, 'code' => 'EXP-RENT'],
            ['name' => 'Rent & Facilities', 'account_id' => $expenseAcc?->id, 'description' => 'Office & warehouse lease']
        );
        $catUtil = ExpenseCategory::firstOrCreate(
            ['tenant_id' => $tenantId, 'code' => 'EXP-UTIL'],
            ['name' => 'Utilities & Internet', 'account_id' => $expenseAcc?->id, 'description' => 'Power, water, fiber internet']
        );
        $catMkt = ExpenseCategory::firstOrCreate(
            ['tenant_id' => $tenantId, 'code' => 'EXP-MKT'],
            ['name' => 'Marketing & Ads', 'account_id' => $expenseAcc?->id, 'description' => 'Digital campaigns & promos']
        );
        $catSaas = ExpenseCategory::firstOrCreate(
            ['tenant_id' => $tenantId, 'code' => 'EXP-SAAS'],
            ['name' => 'Software & Cloud', 'account_id' => $expenseAcc?->id, 'description' => 'Hosting, ERP & SaaS licenses']
        );

        $vat15 = TaxRate::firstOrCreate(
            ['tenant_id' => $tenantId, 'code' => 'VAT-15'],
            ['name' => 'Standard VAT (15%)', 'rate_bps' => 1500, 'type' => 'both', 'is_default' => true]
        );
        $tax10 = TaxRate::firstOrCreate(
            ['tenant_id' => $tenantId, 'code' => 'TAX-10'],
            ['name' => 'Sales Tax (10%)', 'rate_bps' => 1000, 'type' => 'sales', 'is_default' => false]
        );
        TaxRate::firstOrCreate(
            ['tenant_id' => $tenantId, 'code' => 'ZERO-0'],
            ['name' => 'Zero-Rated (0%)', 'rate_bps' => 0, 'type' => 'both', 'is_default' => false]
        );

        Expense::firstOrCreate(
            ['tenant_id' => $tenantId, 'expense_number' => 'EXP-2026-0001'],
            [
                'expense_category_id' => $catRent->id,
                'tax_rate_id' => $vat15->id,
                'payee' => 'Metro Commercial Properties',
                'expense_date' => now()->subDays(12)->toDateString(),
                'amount' => 350000,
                'tax_amount' => 52500,
                'total_amount' => 402500,
                'payment_method' => 'bank_transfer',
                'status' => Expense::STATUS_PAID,
                'reference' => 'LEASE-NOV',
                'created_by' => $admin?->id,
                'paid_at' => now()->subDays(11),
            ]
        );

        Expense::firstOrCreate(
            ['tenant_id' => $tenantId, 'expense_number' => 'EXP-2026-0002'],
            [
                'expense_category_id' => $catMkt->id,
                'tax_rate_id' => $tax10->id,
                'payee' => 'Google Ads & Meta',
                'expense_date' => now()->subDays(5)->toDateString(),
                'amount' => 120000,
                'tax_amount' => 12000,
                'total_amount' => 132000,
                'payment_method' => 'card',
                'status' => Expense::STATUS_PAID,
                'reference' => 'ADS-Q4',
                'created_by' => $admin?->id,
                'paid_at' => now()->subDays(4),
            ]
        );

        Expense::firstOrCreate(
            ['tenant_id' => $tenantId, 'expense_number' => 'EXP-2026-0003'],
            [
                'expense_category_id' => $catUtil->id,
                'tax_rate_id' => $vat15->id,
                'payee' => 'City Power & Fiber Co.',
                'expense_date' => now()->subDays(2)->toDateString(),
                'amount' => 45000,
                'tax_amount' => 6750,
                'total_amount' => 51750,
                'payment_method' => 'bank_transfer',
                'status' => Expense::STATUS_DRAFT,
                'reference' => 'UTIL-NOV',
                'created_by' => $admin?->id,
            ]
        );

        $ym = now()->format('Y-m');
        Budget::firstOrCreate(
            ['tenant_id' => $tenantId, 'expense_category_id' => $catRent->id, 'period_month' => $ym],
            ['name' => 'Monthly Rent Budget', 'budget_amount' => 420000]
        );
        Budget::firstOrCreate(
            ['tenant_id' => $tenantId, 'expense_category_id' => $catMkt->id, 'period_month' => $ym],
            ['name' => 'Monthly Marketing Budget', 'budget_amount' => 200000]
        );
        Budget::firstOrCreate(
            ['tenant_id' => $tenantId, 'expense_category_id' => $catSaas->id, 'period_month' => $ym],
            ['name' => 'Cloud & SaaS Budget', 'budget_amount' => 80000]
        );
    }
}
