<?php

declare(strict_types=1);

namespace Modules\Core\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Core\Models\Tenant;
use Modules\Core\Services\FeatureManager;
use Modules\Core\Services\TenantContext;

class CoreDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = Tenant::firstOrCreate(
            ['slug' => 'acme-corp'],
            [
                'name' => 'Acme Corporation',
                'domain' => 'acme.erp.local',
                'status' => Tenant::ACTIVE,
                'settings' => [
                    'currency' => 'USD',
                    'timezone' => 'UTC',
                ],
            ]
        );

        app(TenantContext::class)->set($tenant);

        $featureManager = app(FeatureManager::class);
        foreach (['accounting', 'inventory', 'sales', 'payments', 'procurement'] as $feature) {
            $featureManager->enable($feature, $tenant->id);
        }
    }
}
