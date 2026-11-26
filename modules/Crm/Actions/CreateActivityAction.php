<?php

declare(strict_types=1);

namespace Modules\Crm\Actions;

use App\Models\User;
use Modules\Core\Services\TenantContext;
use Modules\Crm\Models\CrmActivity;

class CreateActivityAction
{
    public function __construct(
        protected TenantContext $tenantContext
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data, User $user): CrmActivity
    {
        $tenantId = $this->tenantContext->requireId();

        return CrmActivity::create([
            'tenant_id' => $tenantId,
            'type' => $data['type'] ?? CrmActivity::TYPE_CALL,
            'subject' => $data['subject'],
            'lead_id' => $data['lead_id'] ?: null,
            'deal_id' => $data['deal_id'] ?: null,
            'assigned_to' => $data['assigned_to'] ?? $user->id,
            'due_at' => $data['due_at'] ?? now()->addDay(),
            'status' => CrmActivity::STATUS_OPEN,
            'notes' => $data['notes'] ?? null,
        ]);
    }
}
