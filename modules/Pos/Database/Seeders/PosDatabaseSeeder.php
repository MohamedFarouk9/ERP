<?php

declare(strict_types=1);

namespace Modules\Pos\Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Modules\Core\Services\TenantContext;
use Modules\Inventory\Models\Product;
use Modules\Inventory\Models\Warehouse;
use Modules\Pos\Models\PosSale;
use Modules\Pos\Models\PosSaleItem;
use Modules\Pos\Models\PosShift;

class PosDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $tenantId = app(TenantContext::class)->requireId();
        $admin = User::withoutGlobalScopes()->where('tenant_id', $tenantId)->first();
        $warehouse = Warehouse::withoutGlobalScopes()->where('tenant_id', $tenantId)->first();
        $products = Product::withoutGlobalScopes()->where('tenant_id', $tenantId)->take(4)->get();

        if (! $admin || $products->isEmpty()) {
            return;
        }

        $shift = PosShift::firstOrCreate(
            ['tenant_id' => $tenantId, 'shift_number' => 'SHIFT-2026-001'],
            [
                'user_id' => $admin->id,
                'warehouse_id' => $warehouse?->id,
                'opened_at' => now()->startOfDay(),
                'opening_cash' => 50000, // $500.00 float
                'expected_cash' => 50000,
                'status' => PosShift::STATUS_OPEN,
            ]
        );

        if (PosSale::withoutGlobalScopes()->where('tenant_id', $tenantId)->count() === 0) {
            $types = ['cash', 'card', 'cash', 'split', 'card'];
            foreach ($types as $idx => $payType) {
                $p = $products[$idx % $products->count()];
                $qty = ($idx % 3) + 1;
                $sub = $p->selling_price * $qty;
                $tax = (int) round($sub * 0.10);
                $tot = $sub + $tax;

                $sale = PosSale::create([
                    'tenant_id' => $tenantId,
                    'pos_shift_id' => $shift->id,
                    'user_id' => $admin->id,
                    'receipt_number' => 'RCP-2026-000'.($idx + 1),
                    'sale_date' => now()->subDays($idx % 4)->toDateString(),
                    'subtotal' => $sub,
                    'tax_amount' => $tax,
                    'discount_amount' => 0,
                    'total_amount' => $tot,
                    'amount_tendered' => $tot,
                    'change_due' => 0,
                    'payment_type' => $payType,
                    'status' => PosSale::STATUS_COMPLETED,
                ]);

                PosSaleItem::create([
                    'pos_sale_id' => $sale->id,
                    'product_id' => $p->id,
                    'sku' => $p->sku,
                    'product_name' => $p->name,
                    'quantity' => $qty,
                    'unit_price' => $p->selling_price,
                    'tax_amount' => $tax,
                    'line_total' => $tot,
                ]);
            }
        }
    }
}
