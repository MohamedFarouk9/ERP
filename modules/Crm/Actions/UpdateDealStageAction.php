<?php

declare(strict_types=1);

namespace Modules\Crm\Actions;

use InvalidArgumentException;
use Modules\Crm\Events\DealWon;
use Modules\Crm\Models\CrmDeal;

class UpdateDealStageAction
{
    public function execute(CrmDeal $deal, string $stage): CrmDeal
    {
        $allowed = [
            CrmDeal::STAGE_QUALIFIED => 25,
            CrmDeal::STAGE_PROPOSAL => 50,
            CrmDeal::STAGE_NEGOTIATION => 75,
            CrmDeal::STAGE_WON => 100,
            CrmDeal::STAGE_LOST => 0,
        ];

        if (! array_key_exists($stage, $allowed)) {
            throw new InvalidArgumentException("Invalid CRM deal stage [{$stage}].");
        }

        $wasWon = $deal->stage === CrmDeal::STAGE_WON;

        $deal->update([
            'stage' => $stage,
            'probability' => $allowed[$stage],
            'closed_at' => in_array($stage, [CrmDeal::STAGE_WON, CrmDeal::STAGE_LOST], true) ? now() : null,
        ]);

        if (! $wasWon && $stage === CrmDeal::STAGE_WON) {
            event(new DealWon($deal));
        }

        return $deal->fresh(['customer', 'lead', 'owner']);
    }
}
