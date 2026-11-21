<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Modules\Accounting\Models\Account;
use Modules\Core\Models\Tenant;
use Modules\Inventory\Models\Category;
use Modules\Inventory\Models\Product;
use Modules\Inventory\Models\Warehouse;
use Modules\Payments\Models\PaymentMethod;
use Modules\Users\Models\Role;

class BigDataSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = Tenant::where('slug', 'acme-corp')->first();

        if (! $tenant) {
            $this->call(DatabaseSeeder::class);
            $tenant = Tenant::where('slug', 'acme-corp')->firstOrFail();
        }

        $tenantId = $tenant->id;
        $now = now()->toDateTimeString();
        $passwordHash = Hash::make('Password123!');

        // 1. Bulk Users (100)
        $roleIds = Role::withoutGlobalScopes()->where('tenant_id', $tenantId)->pluck('id')->all();
        $users = [];
        $userRoles = [];
        for ($i = 1; $i <= 100; $i++) {
            $userId = (string) Str::ulid();
            $users[] = [
                'id' => $userId,
                'tenant_id' => $tenantId,
                'name' => "ERP Staff Member {$i}",
                'email' => "staff{$i}@acme.test",
                'password' => $passwordHash,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ];
            if (! empty($roleIds)) {
                $userRoles[] = [
                    'user_id' => $userId,
                    'role_id' => $roleIds[array_rand($roleIds)],
                ];
            }
        }
        DB::table('users')->insertOrIgnore($users);
        if (! empty($userRoles)) {
            DB::table('user_roles')->insertOrIgnore($userRoles);
        }

        // 2. Categories (20) & Warehouses (5)
        $categories = [];
        for ($i = 1; $i <= 20; $i++) {
            $categories[] = [
                'id' => (string) Str::ulid(),
                'tenant_id' => $tenantId,
                'name' => "Bulk Category {$i}",
                'slug' => "bulk-category-{$i}",
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }
        DB::table('categories')->insertOrIgnore($categories);
        $categoryIds = Category::withoutGlobalScopes()->where('tenant_id', $tenantId)->pluck('id')->all();

        $warehouses = [];
        for ($i = 1; $i <= 5; $i++) {
            $warehouses[] = [
                'id' => (string) Str::ulid(),
                'tenant_id' => $tenantId,
                'code' => "WH-BULK-{$i}",
                'name' => "Regional Warehouse {$i}",
                'address' => "Zone {$i}, Logistics Park",
                'is_default' => false,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }
        DB::table('warehouses')->insertOrIgnore($warehouses);
        $warehouseIds = Warehouse::withoutGlobalScopes()->where('tenant_id', $tenantId)->pluck('id')->all();

        // 3. Products (500) + Stock Levels (500 * 5 = 2500) + Stock Movements (2500)
        $products = [];
        $productIds = [];
        for ($i = 1; $i <= 500; $i++) {
            $pid = (string) Str::ulid();
            $productIds[] = $pid;
            $cost = random_int(1000, 50000);
            $products[] = [
                'id' => $pid,
                'tenant_id' => $tenantId,
                'category_id' => $categoryIds[array_rand($categoryIds)],
                'sku' => sprintf('BULK-SKU-%05d', $i),
                'name' => "Enterprise Item #{$i}",
                'unit' => 'pcs',
                'cost_price' => $cost,
                'selling_price' => (int) round($cost * 1.45),
                'reorder_level' => 15,
                'track_inventory' => true,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }
        foreach (array_chunk($products, 250) as $chunk) {
            DB::table('products')->insertOrIgnore($chunk);
        }

        $allProducts = Product::withoutGlobalScopes()
            ->where('tenant_id', $tenantId)
            ->whereIn('sku', array_column($products, 'sku'))
            ->get(['id', 'cost_price']);

        $stockLevels = [];
        $stockMovements = [];
        foreach ($allProducts as $product) {
            foreach ($warehouseIds as $wid) {
                $qty = random_int(5, 200);
                $stockLevels[] = [
                    'id' => (string) Str::ulid(),
                    'tenant_id' => $tenantId,
                    'warehouse_id' => $wid,
                    'product_id' => $product->id,
                    'quantity_on_hand' => $qty,
                    'quantity_reserved' => random_int(0, 4),
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
                $stockMovements[] = [
                    'id' => (string) Str::ulid(),
                    'tenant_id' => $tenantId,
                    'warehouse_id' => $wid,
                    'product_id' => $product->id,
                    'type' => 'in',
                    'quantity' => $qty,
                    'unit_cost' => $product->cost_price,
                    'reference_type' => 'BulkSeed',
                    'notes' => 'BigDataSeeder initial stock',
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }
        foreach (array_chunk($stockLevels, 500) as $chunk) {
            DB::table('stock_levels')->insertOrIgnore($chunk);
        }
        foreach (array_chunk($stockMovements, 500) as $chunk) {
            DB::table('stock_movements')->insert($chunk);
        }

        // 4. Customers (200)
        $customers = [];
        for ($i = 1; $i <= 200; $i++) {
            $customers[] = [
                'id' => (string) Str::ulid(),
                'tenant_id' => $tenantId,
                'code' => sprintf('BULK-CUST-%04d', $i),
                'name' => "Corporate Client {$i} LLC",
                'email' => "billing{$i}@client.test",
                'phone' => '+1-555-'.sprintf('%04d', $i),
                'credit_limit' => random_int(100000, 10000000),
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }
        DB::table('customers')->insertOrIgnore($customers);
        $customerIds = DB::table('customers')->where('tenant_id', $tenantId)->pluck('id')->all();
        $seededProductIds = $allProducts->pluck('id')->all();

        // 5. Sales Orders (1,000) + Order Items (3,000)
        $orders = [];
        $orderItems = [];
        $orderIds = [];
        $statuses = ['draft', 'confirmed', 'fulfilled', 'cancelled'];
        for ($i = 1; $i <= 1000; $i++) {
            $oid = (string) Str::ulid();
            $orderIds[] = $oid;
            $subtotal = 0;
            $taxTotal = 0;
            for ($j = 0; $j < 3; $j++) {
                $qty = random_int(1, 10);
                $price = random_int(2500, 90000);
                $lineSub = $qty * $price;
                $lineTax = (int) round($lineSub * 0.1);
                $subtotal += $lineSub;
                $taxTotal += $lineTax;
                $orderItems[] = [
                    'id' => (string) Str::ulid(),
                    'sales_order_id' => $oid,
                    'product_id' => $seededProductIds[array_rand($seededProductIds)],
                    'description' => "Order Item #{$j}",
                    'quantity' => $qty,
                    'unit_price' => $price,
                    'tax_rate' => 1000,
                    'tax_amount' => $lineTax,
                    'line_total' => $lineSub + $lineTax,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
            $orders[] = [
                'id' => $oid,
                'tenant_id' => $tenantId,
                'customer_id' => $customerIds[array_rand($customerIds)],
                'warehouse_id' => $warehouseIds[array_rand($warehouseIds)],
                'order_number' => sprintf('BULK-SO-%06d', $i),
                'order_date' => now()->subDays(random_int(0, 180))->toDateString(),
                'status' => $statuses[array_rand($statuses)],
                'subtotal' => $subtotal,
                'tax_amount' => $taxTotal,
                'discount_amount' => 0,
                'total_amount' => $subtotal + $taxTotal,
                'currency_code' => 'USD',
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }
        foreach (array_chunk($orders, 250) as $chunk) {
            DB::table('sales_orders')->insertOrIgnore($chunk);
        }
        foreach (array_chunk($orderItems, 500) as $chunk) {
            DB::table('sales_order_items')->insert($chunk);
        }

        // 6. Invoices (800) + Invoice Items (2,400)
        $invoices = [];
        $invoiceItems = [];
        $invoiceData = [];
        $invStatuses = ['draft', 'issued', 'paid', 'overdue'];
        for ($i = 1; $i <= 800; $i++) {
            $iid = (string) Str::ulid();
            $cid = $customerIds[array_rand($customerIds)];
            $subtotal = 0;
            $taxTotal = 0;
            for ($j = 0; $j < 3; $j++) {
                $qty = random_int(1, 5);
                $price = random_int(5000, 80000);
                $lineSub = $qty * $price;
                $lineTax = (int) round($lineSub * 0.1);
                $subtotal += $lineSub;
                $taxTotal += $lineTax;
                $invoiceItems[] = [
                    'id' => (string) Str::ulid(),
                    'invoice_id' => $iid,
                    'product_id' => $seededProductIds[array_rand($seededProductIds)],
                    'description' => "Invoice Line #{$j}",
                    'quantity' => $qty,
                    'unit_price' => $price,
                    'tax_amount' => $lineTax,
                    'line_total' => $lineSub + $lineTax,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
            $total = $subtotal + $taxTotal;
            $invDate = now()->subDays(random_int(0, 120));
            $invoices[] = [
                'id' => $iid,
                'tenant_id' => $tenantId,
                'customer_id' => $cid,
                'sales_order_id' => $orderIds[array_rand($orderIds)],
                'invoice_number' => sprintf('BULK-INV-%06d', $i),
                'invoice_date' => $invDate->toDateString(),
                'due_date' => $invDate->copy()->addDays(30)->toDateString(),
                'status' => $invStatuses[array_rand($invStatuses)],
                'subtotal' => $subtotal,
                'tax_amount' => $taxTotal,
                'total_amount' => $total,
                'amount_paid' => 0,
                'currency_code' => 'USD',
                'created_at' => $now,
                'updated_at' => $now,
            ];
            $invoiceData[] = ['id' => $iid, 'customer_id' => $cid, 'total' => $total];
        }
        foreach (array_chunk($invoices, 250) as $chunk) {
            DB::table('invoices')->insertOrIgnore($chunk);
        }
        foreach (array_chunk($invoiceItems, 500) as $chunk) {
            DB::table('invoice_items')->insert($chunk);
        }

        // 7. Payments (700) + Refunds (50)
        $methodIds = PaymentMethod::withoutGlobalScopes()->where('tenant_id', $tenantId)->pluck('id')->all();
        $payments = [];
        $refunds = [];
        if (! empty($methodIds)) {
            for ($i = 1; $i <= 700; $i++) {
                $payId = (string) Str::ulid();
                $inv = $invoiceData[array_rand($invoiceData)];
                $amount = min($inv['total'], random_int(5000, 150000));
                $payments[] = [
                    'id' => $payId,
                    'tenant_id' => $tenantId,
                    'payment_method_id' => $methodIds[array_rand($methodIds)],
                    'customer_id' => $inv['customer_id'],
                    'invoice_id' => $inv['id'],
                    'payment_number' => sprintf('BULK-PAY-%06d', $i),
                    'payment_date' => now()->subDays(random_int(0, 90))->toDateString(),
                    'amount' => $amount,
                    'currency_code' => 'USD',
                    'status' => 'completed',
                    'reference' => 'REF-'.strtoupper(Str::random(8)),
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
                if ($i <= 50) {
                    $refunds[] = [
                        'id' => (string) Str::ulid(),
                        'tenant_id' => $tenantId,
                        'payment_id' => $payId,
                        'refund_number' => sprintf('BULK-REF-%05d', $i),
                        'refund_date' => now()->subDays(random_int(0, 30))->toDateString(),
                        'amount' => (int) round($amount * 0.5),
                        'reason' => 'Partial customer return',
                        'status' => 'completed',
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
            }
            foreach (array_chunk($payments, 250) as $chunk) {
                DB::table('payments')->insertOrIgnore($chunk);
            }
            DB::table('refunds')->insertOrIgnore($refunds);
        }

        // 8. Accounting Transactions (500) + Balanced Lines (1,000)
        $accountIds = Account::withoutGlobalScopes()->where('tenant_id', $tenantId)->pluck('id')->all();
        if (count($accountIds) >= 2) {
            $txns = [];
            $txnLines = [];
            for ($i = 1; $i <= 500; $i++) {
                $tid = (string) Str::ulid();
                $amt = random_int(10000, 500000);
                $txns[] = [
                    'id' => $tid,
                    'tenant_id' => $tenantId,
                    'transaction_number' => sprintf('BULK-TXN-%06d', $i),
                    'transaction_date' => now()->subDays(random_int(0, 120))->toDateString(),
                    'status' => 'posted',
                    'reference_type' => 'BulkEntry',
                    'notes' => "Automated bulk ledger entry #{$i}",
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
                $txnLines[] = [
                    'id' => (string) Str::ulid(),
                    'transaction_id' => $tid,
                    'account_id' => $accountIds[0],
                    'debit' => $amt,
                    'credit' => 0,
                    'description' => 'Debit entry',
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
                $txnLines[] = [
                    'id' => (string) Str::ulid(),
                    'transaction_id' => $tid,
                    'account_id' => $accountIds[1],
                    'debit' => 0,
                    'credit' => $amt,
                    'description' => 'Credit entry',
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
            foreach (array_chunk($txns, 250) as $chunk) {
                DB::table('accounting_transactions')->insertOrIgnore($chunk);
            }
            foreach (array_chunk($txnLines, 500) as $chunk) {
                DB::table('accounting_transaction_lines')->insert($chunk);
            }
        }
    }
}
