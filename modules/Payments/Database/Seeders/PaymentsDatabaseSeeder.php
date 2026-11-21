<?php

declare(strict_types=1);

namespace Modules\Payments\Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Modules\Accounting\Models\Account;
use Modules\Core\Services\TenantContext;
use Modules\Payments\Models\Payment;
use Modules\Payments\Models\PaymentMethod;
use Modules\Sales\Models\Customer;
use Modules\Sales\Models\Invoice;

class PaymentsDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $tenantId = app(TenantContext::class)->requireId();
        $admin = User::withoutGlobalScopes()->where('tenant_id', $tenantId)->first();
        $cashAccount = Account::withoutGlobalScopes()->where('tenant_id', $tenantId)->where('code', '1010')->first();
        $customer = Customer::withoutGlobalScopes()->where('tenant_id', $tenantId)->where('code', 'CUST-001')->first();
        $invoice = Invoice::withoutGlobalScopes()->where('tenant_id', $tenantId)->where('invoice_number', 'INV-2026-00001')->first();

        $bankTransfer = PaymentMethod::firstOrCreate(
            ['tenant_id' => $tenantId, 'code' => 'BANK-WIRE'],
            [
                'name' => 'Corporate Bank Transfer',
                'type' => PaymentMethod::TYPE_BANK_TRANSFER,
                'account_id' => $cashAccount?->id,
                'is_active' => true,
            ]
        );

        PaymentMethod::firstOrCreate(
            ['tenant_id' => $tenantId, 'code' => 'CASH-MAIN'],
            [
                'name' => 'Cash Drawer',
                'type' => PaymentMethod::TYPE_CASH,
                'account_id' => $cashAccount?->id,
                'is_active' => true,
            ]
        );

        Payment::firstOrCreate(
            ['tenant_id' => $tenantId, 'payment_number' => 'PAY-2026-00001'],
            [
                'payment_method_id' => $bankTransfer->id,
                'customer_id' => $customer?->id,
                'invoice_id' => $invoice?->id,
                'payment_date' => '2026-11-16',
                'amount' => 150000, // $1,500.00 partial payment
                'currency_code' => 'USD',
                'status' => Payment::STATUS_COMPLETED,
                'reference' => 'WIRE-REF-88412',
                'notes' => 'Initial partial payment for INV-2026-00001',
                'processed_by' => $admin?->id,
                'processed_at' => now(),
            ]
        );
    }
}
