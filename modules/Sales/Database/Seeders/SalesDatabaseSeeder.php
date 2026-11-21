<?php

declare(strict_types=1);

namespace Modules\Sales\Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Modules\Core\Services\TenantContext;
use Modules\Inventory\Models\Product;
use Modules\Inventory\Models\Warehouse;
use Modules\Sales\Models\Customer;
use Modules\Sales\Models\Invoice;
use Modules\Sales\Models\InvoiceItem;
use Modules\Sales\Models\SalesOrder;
use Modules\Sales\Models\SalesOrderItem;

class SalesDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $tenantId = app(TenantContext::class)->requireId();
        $admin = User::withoutGlobalScopes()->where('tenant_id', $tenantId)->first();
        $warehouse = Warehouse::withoutGlobalScopes()->where('tenant_id', $tenantId)->first();
        $laptop = Product::withoutGlobalScopes()->where('tenant_id', $tenantId)->where('sku', 'SKU-LAPTOP-01')->first();

        $customer1 = Customer::firstOrCreate(
            ['tenant_id' => $tenantId, 'code' => 'CUST-001'],
            [
                'name' => 'Global Tech Ltd',
                'email' => 'procurement@globaltech.test',
                'phone' => '+1-555-0101',
                'tax_number' => 'US-99887766',
                'billing_address' => '450 Market St, San Francisco, CA',
                'credit_limit' => 5000000, // $50,000.00
            ]
        );

        Customer::firstOrCreate(
            ['tenant_id' => $tenantId, 'code' => 'CUST-002'],
            [
                'name' => 'Summit Retail Co',
                'email' => 'ap@summitretail.test',
                'phone' => '+1-555-0202',
                'billing_address' => '780 5th Ave, New York, NY',
                'credit_limit' => 2500000,
            ]
        );

        if ($laptop) {
            $order = SalesOrder::firstOrCreate(
                ['tenant_id' => $tenantId, 'order_number' => 'SO-2026-00001'],
                [
                    'customer_id' => $customer1->id,
                    'warehouse_id' => $warehouse?->id,
                    'order_date' => '2026-11-15',
                    'status' => SalesOrder::STATUS_CONFIRMED,
                    'subtotal' => 259800,
                    'tax_amount' => 25980,
                    'discount_amount' => 0,
                    'total_amount' => 285780,
                    'currency_code' => 'USD',
                    'confirmed_by' => $admin?->id,
                    'confirmed_at' => now(),
                ]
            );

            if ($order->items()->count() === 0) {
                SalesOrderItem::create([
                    'sales_order_id' => $order->id,
                    'product_id' => $laptop->id,
                    'description' => $laptop->name,
                    'quantity' => 2,
                    'unit_price' => 129900,
                    'tax_rate' => 1000, // 10%
                    'tax_amount' => 25980,
                    'line_total' => 285780,
                ]);
            }

            $invoice = Invoice::firstOrCreate(
                ['tenant_id' => $tenantId, 'invoice_number' => 'INV-2026-00001'],
                [
                    'customer_id' => $customer1->id,
                    'sales_order_id' => $order->id,
                    'invoice_date' => '2026-11-15',
                    'due_date' => '2026-12-15',
                    'status' => Invoice::STATUS_ISSUED,
                    'subtotal' => 259800,
                    'tax_amount' => 25980,
                    'total_amount' => 285780,
                    'amount_paid' => 0,
                    'currency_code' => 'USD',
                    'issued_by' => $admin?->id,
                    'issued_at' => now(),
                ]
            );

            if ($invoice->items()->count() === 0) {
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => $laptop->id,
                    'description' => $laptop->name,
                    'quantity' => 2,
                    'unit_price' => 129900,
                    'tax_amount' => 25980,
                    'line_total' => 285780,
                ]);
            }
        }
    }
}
