<?php

declare(strict_types=1);

namespace Modules\Crm\Actions;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Modules\Core\Services\TenantContext;
use Modules\Crm\Events\LeadConverted;
use Modules\Crm\Models\CrmDeal;
use Modules\Crm\Models\CrmLead;
use Modules\Sales\Models\Customer;
use RuntimeException;

class ConvertLeadToCustomerAction
{
    public function __construct(
        protected TenantContext $tenantContext
    ) {}

    /**
     * @return array{lead: CrmLead, customer: Customer, deal: CrmDeal|null}
     */
    public function execute(CrmLead $lead, User $user, bool $createDeal = true): array
    {
        if ($lead->status === CrmLead::STATUS_CONVERTED) {
            throw new RuntimeException("Lead [{$lead->lead_code}] has already been converted.");
        }

        $tenantId = $this->tenantContext->requireId();

        return DB::transaction(function () use ($lead, $user, $tenantId, $createDeal) {
            $customerCode = 'CUST-'.strtoupper(substr(str_replace('-', '', $lead->lead_code), 0, 8)).'-'.random_int(100, 999);

            $customer = Customer::create([
                'tenant_id' => $tenantId,
                'code' => $customerCode,
                'name' => $lead->company ?: $lead->name,
                'email' => $lead->email,
                'phone' => $lead->phone,
                'address' => null,
                'is_active' => true,
            ]);

            $lead->update([
                'status' => CrmLead::STATUS_CONVERTED,
                'converted_customer_id' => $customer->id,
                'converted_at' => now(),
            ]);

            $deal = null;
            if ($createDeal) {
                $deal = CrmDeal::create([
                    'tenant_id' => $tenantId,
                    'deal_code' => 'DL-'.now()->format('YmdHis').'-'.random_int(10, 99),
                    'title' => ($lead->company ?: $lead->name).' — Initial Opportunity',
                    'customer_id' => $customer->id,
                    'lead_id' => $lead->id,
                    'owner_id' => $lead->owner_id ?? $user->id,
                    'stage' => CrmDeal::STAGE_QUALIFIED,
                    'value_amount' => $lead->estimated_value,
                    'probability' => 30,
                    'expected_close_date' => now()->addDays(30)->toDateString(),
                    'notes' => "Converted from lead {$lead->lead_code}",
                ]);
            }

            event(new LeadConverted($lead, $customer));

            return [
                'lead' => $lead->fresh(['owner', 'convertedCustomer']),
                'customer' => $customer,
                'deal' => $deal,
            ];
        });
    }
}
