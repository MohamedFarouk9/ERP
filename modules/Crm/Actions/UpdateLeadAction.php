<?php

declare(strict_types=1);

namespace Modules\Crm\Actions;

use Modules\Crm\Models\CrmLead;

class UpdateLeadAction
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(CrmLead $lead, array $data): CrmLead
    {
        $lead->update([
            'name' => $data['name'] ?? $lead->name,
            'company' => $data['company'] ?? $lead->company,
            'email' => $data['email'] ?? $lead->email,
            'phone' => $data['phone'] ?? $lead->phone,
            'source' => $data['source'] ?? $lead->source,
            'status' => $data['status'] ?? $lead->status,
            'estimated_value' => isset($data['estimated_value']) ? (int) $data['estimated_value'] : $lead->estimated_value,
            'owner_id' => $data['owner_id'] ?? $lead->owner_id,
            'notes' => $data['notes'] ?? $lead->notes,
        ]);

        return $lead->fresh(['owner', 'convertedCustomer']);
    }
}
