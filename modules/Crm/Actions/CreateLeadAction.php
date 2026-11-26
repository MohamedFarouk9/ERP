<?php

declare(strict_types=1);

namespace Modules\Crm\Actions;

use App\Models\User;
use InvalidArgumentException;
use Modules\Core\Services\TenantContext;
use Modules\Crm\Models\CrmLead;

class CreateLeadAction
{
    public function __construct(
        protected TenantContext $tenantContext
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data, User $user): CrmLead
    {
        $tenantId = $this->tenantContext->requireId();
        $code = (string) ($data['lead_code'] ?? ('LD-'.now()->format('YmdHis').'-'.random_int(10, 99)));

        $exists = CrmLead::withoutGlobalScopes()
            ->where('tenant_id', $tenantId)
            ->where('lead_code', $code)
            ->exists();

        if ($exists) {
            throw new InvalidArgumentException("Lead code [{$code}] already exists.");
        }

        return CrmLead::create([
            'tenant_id' => $tenantId,
            'lead_code' => $code,
            'name' => $data['name'],
            'company' => $data['company'] ?? null,
            'email' => $data['email'] ?? null,
            'phone' => $data['phone'] ?? null,
            'source' => $data['source'] ?? 'website',
            'status' => $data['status'] ?? CrmLead::STATUS_NEW,
            'estimated_value' => (int) ($data['estimated_value'] ?? 0),
            'owner_id' => $data['owner_id'] ?? $user->id,
            'notes' => $data['notes'] ?? null,
        ]);
    }
}
