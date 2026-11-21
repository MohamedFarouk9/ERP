<?php

declare(strict_types=1);

namespace Modules\Inventory\Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Modules\Core\Services\TenantContext;
use Modules\Inventory\Models\Category;
use Modules\Inventory\Models\Product;
use Modules\Inventory\Models\StockLevel;
use Modules\Inventory\Models\StockMovement;
use Modules\Inventory\Models\Warehouse;

class InventoryDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $tenantId = app(TenantContext::class)->requireId();
        $admin = User::withoutGlobalScopes()->where('tenant_id', $tenantId)->first();

        $electronics = Category::firstOrCreate(
            ['tenant_id' => $tenantId, 'slug' => 'electronics'],
            ['name' => 'Electronics', 'description' => 'Hardware and IT equipment']
        );

        $furniture = Category::firstOrCreate(
            ['tenant_id' => $tenantId, 'slug' => 'office-furniture'],
            ['name' => 'Office Furniture', 'description' => 'Desks, chairs, and workstations']
        );

        $warehouse = Warehouse::firstOrCreate(
            ['tenant_id' => $tenantId, 'code' => 'WH-MAIN'],
            ['name' => 'Main Warehouse', 'address' => '100 Industrial Blvd', 'is_default' => true]
        );

        $laptop = Product::firstOrCreate(
            ['tenant_id' => $tenantId, 'sku' => 'SKU-LAPTOP-01'],
            [
                'category_id' => $electronics->id,
                'name' => 'Enterprise Laptop 15"',
                'unit' => 'pcs',
                'cost_price' => 85000, // $850.00
                'selling_price' => 129900, // $1,299.00
                'reorder_level' => 10,
            ]
        );

        $desk = Product::firstOrCreate(
            ['tenant_id' => $tenantId, 'sku' => 'SKU-DESK-01'],
            [
                'category_id' => $furniture->id,
                'name' => 'Standing Desk Pro',
                'unit' => 'pcs',
                'cost_price' => 30000, // $300.00
                'selling_price' => 54900, // $549.00
                'reorder_level' => 5,
            ]
        );

        foreach ([[$laptop, 50], [$desk, 25]] as [$product, $qty]) {
            $level = StockLevel::firstOrCreate(
                ['tenant_id' => $tenantId, 'warehouse_id' => $warehouse->id, 'product_id' => $product->id],
                ['quantity_on_hand' => $qty, 'quantity_reserved' => 0]
            );

            if ($level->wasRecentlyCreated) {
                StockMovement::create([
                    'tenant_id' => $tenantId,
                    'warehouse_id' => $warehouse->id,
                    'product_id' => $product->id,
                    'type' => StockMovement::TYPE_IN,
                    'quantity' => $qty,
                    'unit_cost' => $product->cost_price,
                    'reference_type' => 'OpeningStock',
                    'notes' => 'Initial inventory seed',
                    'created_by' => $admin?->id,
                ]);
            }
        }
    }
}
