<?php

declare(strict_types=1);

namespace Modules\Procurement\Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Modules\Core\Services\TenantContext;
use Modules\Inventory\Models\Product;
use Modules\Inventory\Models\Warehouse;
use Modules\Procurement\Models\PurchaseOrder;
use Modules\Procurement\Models\PurchaseOrderItem;
use Modules\Procurement\Models\Supplier;
use Modules\Procurement\Models\VendorBill;

class ProcurementDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $tenantId = app(TenantContext::class)->requireId();
        $admin = User::withoutGlobalScopes()->where('tenant_id', $tenantId)->first();
        $warehouse = Warehouse::withoutGlobalScopes()->where('tenant_id', $tenantId)->first();
        $laptop = Product::withoutGlobalScopes()->where('tenant_id', $tenantId)->where('sku', 'SKU-LAPTOP-01')->first();

        $supplier1 = Supplier::firstOrCreate(
            ['tenant_id' => $tenantId, 'code' => 'SUP-001'],
            [
                'name' => 'Apex Hardware Distributors',
                'email' => 'orders@apexhardware.test',
                'phone' => '+1-555-0801',
                'tax_number' => 'US-44556677',
                'address' => '900 Supply Chain Way, Austin, TX',
                'payment_terms_days' => 30,
                'is_active' => true,
            ]
        );

        Supplier::firstOrCreate(
            ['tenant_id' => $tenantId, 'code' => 'SUP-002'],
            [
                'name' => 'Metro Office Wholesale',
                'email' => 'sales@metrooffice.test',
                'phone' => '+1-555-0802',
                'address' => '210 Commerce Rd, Chicago, IL',
                'payment_terms_days' => 15,
                'is_active' => true,
            ]
        );

        if ($laptop) {
            $po = PurchaseOrder::firstOrCreate(
                ['tenant_id' => $tenantId, 'po_number' => 'PO-2026-00001'],
                [
                    'supplier_id' => $supplier1->id,
                    'warehouse_id' => $warehouse?->id,
                    'order_date' => '2026-11-10',
                    'expected_date' => '2026-11-20',
                    'status' => PurchaseOrder::STATUS_RECEIVED,
                    'subtotal' => 850000,
                    'tax_amount' => 85000,
                    'total_amount' => 935000,
                    'currency_code' => 'USD',
                    'received_by' => $admin?->id,
                    'received_at' => now(),
                ]
            );

            if ($po->items()->count() === 0) {
                PurchaseOrderItem::create([
                    'purchase_order_id' => $po->id,
                    'product_id' => $laptop->id,
                    'description' => $laptop->name,
                    'quantity' => 10,
                    'unit_cost' => 85000,
                    'tax_rate' => 1000,
                    'tax_amount' => 85000,
                    'line_total' => 935000,
                ]);
            }

            VendorBill::firstOrCreate(
                ['tenant_id' => $tenantId, 'bill_number' => 'BILL-2026-00001'],
                [
                    'supplier_id' => $supplier1->id,
                    'purchase_order_id' => $po->id,
                    'bill_date' => '2026-11-12',
                    'due_date' => '2026-12-12',
                    'status' => VendorBill::STATUS_APPROVED,
                    'subtotal' => 850000,
                    'tax_amount' => 85000,
                    'total_amount' => 935000,
                    'amount_paid' => 0,
                    'currency_code' => 'USD',
                    'notes' => 'Hardware restock invoice from Apex',
                ]
            );
        }
    }
}
