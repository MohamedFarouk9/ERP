<?php

declare(strict_types=1);

namespace Modules\Pos\Actions;

use Modules\Pos\Models\PosShift;
use RuntimeException;

class ClosePosShiftAction
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(PosShift $shift, array $data): PosShift
    {
        if ($shift->status !== PosShift::STATUS_OPEN) {
            throw new RuntimeException("Shift [{$shift->shift_number}] is already closed.");
        }

        $shift->update([
            'counted_cash' => (int) $data['counted_cash'],
            'closed_at' => now(),
            'status' => PosShift::STATUS_CLOSED,
            'notes' => $data['notes'] ?? $shift->notes,
        ]);

        return $shift->fresh(['cashier', 'sales']);
    }
}
