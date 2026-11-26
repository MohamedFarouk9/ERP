<?php

declare(strict_types=1);

namespace Modules\Crm\Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Modules\Core\Models\Tenant;
use Modules\Core\Services\TenantContext;
use Modules\Crm\Models\CrmActivity;
use Modules\Crm\Models\CrmDeal;
use Modules\Crm\Models\CrmLead;
use Modules\Sales\Models\Customer;

class CrmDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = Tenant::where('slug', 'acme-corp')->first();
        if (! $tenant) {
            return;
        }

        app(TenantContext::class)->set($tenant);
        $admin = User::where('tenant_id', $tenant->id)->first();
        $customer = Customer::where('tenant_id', $tenant->id)->first();

        $lead1 = CrmLead::firstOrCreate(
            ['tenant_id' => $tenant->id, 'lead_code' => 'LD-2026-001'],
            [
                'name' => 'Elena Rostova',
                'company' => 'Nordic Logistics Group',
                'email' => 'elena@nordiclogistics.example',
                'phone' => '+1-555-0142',
                'source' => 'website',
                'status' => CrmLead::STATUS_QUALIFIED,
                'estimated_value' => 1850000,
                'owner_id' => $admin?->id,
                'notes' => 'Requested multi-warehouse ERP rollout quote.',
            ]
        );

        $lead2 = CrmLead::firstOrCreate(
            ['tenant_id' => $tenant->id, 'lead_code' => 'LD-2026-002'],
            [
                'name' => 'Marcus Vance',
                'company' => 'Apex Retail Outlets',
                'email' => 'mvance@apexretail.example',
                'phone' => '+1-555-0188',
                'source' => 'referral',
                'status' => CrmLead::STATUS_CONTACTED,
                'estimated_value' => 920000,
                'owner_id' => $admin?->id,
                'notes' => 'Interested in 12-store POS & Inventory sync.',
            ]
        );

        CrmLead::firstOrCreate(
            ['tenant_id' => $tenant->id, 'lead_code' => 'LD-2026-003'],
            [
                'name' => 'Sophia Chen',
                'company' => 'Pacific Wholesale Supply',
                'email' => 'schen@pacificwholesale.example',
                'phone' => '+1-555-0195',
                'source' => 'event',
                'status' => CrmLead::STATUS_CONVERTED,
                'estimated_value' => 2400000,
                'owner_id' => $admin?->id,
                'converted_customer_id' => $customer?->id,
                'converted_at' => now()->subDays(6),
                'notes' => 'Met at ERP Summit; onboarded as B2B client.',
            ]
        );

        $deal1 = CrmDeal::firstOrCreate(
            ['tenant_id' => $tenant->id, 'deal_code' => 'DL-2026-001'],
            [
                'title' => 'Nordic Logistics — Annual Supply Contract',
                'customer_id' => $customer?->id,
                'lead_id' => $lead1->id,
                'owner_id' => $admin?->id,
                'stage' => CrmDeal::STAGE_NEGOTIATION,
                'value_amount' => 1850000,
                'probability' => 75,
                'expected_close_date' => now()->addDays(14)->toDateString(),
                'notes' => 'Final pricing review with procurement lead.',
            ]
        );

        CrmDeal::firstOrCreate(
            ['tenant_id' => $tenant->id, 'deal_code' => 'DL-2026-002'],
            [
                'title' => 'Apex Retail — POS Hardware & Software Bundle',
                'customer_id' => $customer?->id,
                'lead_id' => $lead2->id,
                'owner_id' => $admin?->id,
                'stage' => CrmDeal::STAGE_PROPOSAL,
                'value_amount' => 920000,
                'probability' => 50,
                'expected_close_date' => now()->addDays(25)->toDateString(),
            ]
        );

        CrmDeal::firstOrCreate(
            ['tenant_id' => $tenant->id, 'deal_code' => 'DL-2026-003'],
            [
                'title' => 'Pacific Wholesale — Q4 Bulk Fulfillment',
                'customer_id' => $customer?->id,
                'owner_id' => $admin?->id,
                'stage' => CrmDeal::STAGE_WON,
                'value_amount' => 2400000,
                'probability' => 100,
                'expected_close_date' => now()->subDays(3)->toDateString(),
                'closed_at' => now()->subDays(3),
            ]
        );

        CrmActivity::firstOrCreate(
            ['tenant_id' => $tenant->id, 'subject' => 'Follow-up contract review call with Elena'],
            [
                'type' => CrmActivity::TYPE_CALL,
                'lead_id' => $lead1->id,
                'deal_id' => $deal1->id,
                'assigned_to' => $admin?->id,
                'due_at' => now()->addDays(2),
                'status' => CrmActivity::STATUS_OPEN,
                'notes' => 'Confirm delivery SLA and volume discount.',
            ]
        );

        CrmActivity::firstOrCreate(
            ['tenant_id' => $tenant->id, 'subject' => 'Send POS demo deck to Apex Retail'],
            [
                'type' => CrmActivity::TYPE_EMAIL,
                'lead_id' => $lead2->id,
                'assigned_to' => $admin?->id,
                'due_at' => now()->addDays(1),
                'status' => CrmActivity::STATUS_OPEN,
            ]
        );
    }
}
