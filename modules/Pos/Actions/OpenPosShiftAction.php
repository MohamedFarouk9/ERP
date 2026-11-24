<?php

declare(strict_types=1);

namespace Modules\Pos\Actions;

use App\Models\User;
use InvalidArgumentException;
use Modules\Core\Services\TenantContext;
use Modules\Pos\Models\PosShift;
use RuntimeException;

class OpenPosShiftAction
{
    public function __construct(
        protected TenantContext $tenantContext
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data, User $cashier): PosShift
    {
        $tenantId = $this->tenantContext->requireId();

        $alreadyOpen = PosShift::where('user_id', $cashier->id)
            ->where('status', PosShift::STATUS_OPEN)
            ->first();

        if ($alreadyOpen) {
            throw new RuntimeException("You already have an open register shift [{$alreadyOpen->shift_number}].");
        }

        $duplicate = PosShift::withoutGlobalScopes()
            ->where('tenant_id', $tenantId)
            ->where('shift_number', $data['shift_number'])
            ->first();

        if ($duplicate) {
            throw new InvalidArgumentException("Shift number [{$data['shift_number']}] already exists.");
        }

        $openingCash = (int) ($data['opening_cash'] ?? 0);

        return PosShift::create([
            'tenant_id' => $tenantId,
            'user_id' => $cashier->id,
            'warehouse_id' => $data['warehouse_id'] ?: null,
            'shift_number' => $data['shift_number'],
            'opened_at' => now(),
            'opening_cash' => $openingCash,
            'expected_cash' => $openingCash,
            'status' => PosShift::STATUS_OPEN,
            'notes' => $data['notes'] ?? null,
        ]);
    }
}
