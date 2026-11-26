<?php

declare(strict_types=1);

namespace Modules\Crm\Actions;

use App\Models\User;
use InvalidArgumentException;
use Modules\Core\Services\TenantContext;
use Modules\Crm\Models\CrmDeal;

class CreateDealAction
{
    public function __construct(
        protected TenantContext $tenantContext
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data, User $user): CrmDeal
    {
        $tenantId = $this->tenantContext->requireId();
        $code = (string) ($data['deal_code'] ?? ('DL-'.now()->format('YmdHis').'-'.random_int(10, 99)));

        $exists = CrmDeal::withoutGlobalScopes()
            ->where('tenant_id', $tenantId)
            ->where('deal_code', $code)
            ->exists();

        if ($exists) {
            throw new InvalidArgumentException("Deal code [{$code}] already exists.");
        }

        $stage = (string) ($data['stage'] ?? CrmDeal::STAGE_QUALIFIED);

        return CrmDeal::create([
            'tenant_id' => $tenantId,
            'deal_code' => $code,
            'title' => $data['title'],
            'customer_id' => $data['customer_id'] ?: null,
            'lead_id' => $data['lead_id'] ?: null,
            'owner_id' => $data['owner_id'] ?? $user->id,
            'stage' => $stage,
            'value_amount' => (int) ($data['value_amount'] ?? 0),
            'probability' => (int) ($data['probability'] ?? 25),
            'expected_close_date' => $data['expected_close_date'] ?? null,
            'closed_at' => in_array($stage, [CrmDeal::STAGE_WON, CrmDeal::STAGE_LOST], true) ? now() : null,
            'notes' => $data['notes'] ?? null,
        ]);
    }
}
