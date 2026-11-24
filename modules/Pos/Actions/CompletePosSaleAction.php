<?php

declare(strict_types=1);

namespace Modules\Pos\Actions;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use Modules\Core\Services\TenantContext;
use Modules\Inventory\Models\Product;
use Modules\Pos\Events\PosSaleCompleted;
use Modules\Pos\Models\PosSale;
use Modules\Pos\Models\PosSaleItem;
use Modules\Pos\Models\PosShift;
use RuntimeException;

class CompletePosSaleAction
{
    public function __construct(
        protected TenantContext $tenantContext
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data, User $cashier): PosSale
    {
        $tenantId = $this->tenantContext->requireId();
        $shift = PosShift::findOrFail($data['pos_shift_id']);

        if ($shift->status !== PosShift::STATUS_OPEN) {
            throw new RuntimeException("Register shift [{$shift->shift_number}] is closed.");
        }

        $discount = (int) ($data['discount_amount'] ?? 0);
        $taxRateBps = (int) ($data['tax_rate_bps'] ?? 1000); // default 10%
        $tendered = (int) $data['amount_tendered'];
        $paymentType = (string) $data['payment_type'];

        return DB::transaction(function () use ($data, $tenantId, $shift, $cashier, $discount, $taxRateBps, $tendered, $paymentType) {
            $subtotal = 0;
            $taxTotal = 0;
            $linePayloads = [];

            foreach ($data['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);
                $qty = (int) $item['quantity'];
                $unitPrice = $product->selling_price;
                $lineSub = $qty * $unitPrice;
                $lineTax = (int) round($lineSub * ($taxRateBps / 10000));
                $lineTotal = $lineSub + $lineTax;

                $subtotal += $lineSub;
                $taxTotal += $lineTax;

                $linePayloads[] = [
                    'product_id' => $product->id,
                    'sku' => $product->sku,
                    'product_name' => $product->name,
                    'quantity' => $qty,
                    'unit_price' => $unitPrice,
                    'tax_amount' => $lineTax,
                    'line_total' => $lineTotal,
                ];
            }

            $total = max(0, $subtotal + $taxTotal - $discount);

            if ($paymentType === 'cash' && $tendered < $total) {
                throw new InvalidArgumentException('Amount tendered is less than the total due.');
            }

            $changeDue = $paymentType === 'cash' ? max(0, $tendered - $total) : 0;

            $sale = PosSale::create([
                'tenant_id' => $tenantId,
                'pos_shift_id' => $shift->id,
                'customer_id' => $data['customer_id'] ?: null,
                'user_id' => $cashier->id,
                'receipt_number' => $data['receipt_number'] ?? ('RCP-'.now()->format('YmdHis').'-'.random_int(10, 99)),
                'sale_date' => now()->toDateString(),
                'subtotal' => $subtotal,
                'tax_amount' => $taxTotal,
                'discount_amount' => $discount,
                'total_amount' => $total,
                'amount_tendered' => $tendered,
                'change_due' => $changeDue,
                'payment_type' => $paymentType,
                'status' => PosSale::STATUS_COMPLETED,
            ]);

            foreach ($linePayloads as $lp) {
                PosSaleItem::create(array_merge($lp, ['pos_sale_id' => $sale->id]));
            }

            if (in_array($paymentType, ['cash', 'split'], true)) {
                $shift->increment('expected_cash', $total);
            }

            event(new PosSaleCompleted($sale));

            return $sale->load(['items', 'cashier', 'shift']);
        });
    }
}
