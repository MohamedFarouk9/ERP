<?php

declare(strict_types=1);

namespace Modules\Pos\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Pos\Models\PosSale;

class PosSaleCompleted
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public readonly PosSale $posSale
    ) {}
}
