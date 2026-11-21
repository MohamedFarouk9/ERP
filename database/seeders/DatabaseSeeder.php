<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Accounting\Database\Seeders\AccountingDatabaseSeeder;
use Modules\Core\Database\Seeders\CoreDatabaseSeeder;
use Modules\Inventory\Database\Seeders\InventoryDatabaseSeeder;
use Modules\Payments\Database\Seeders\PaymentsDatabaseSeeder;
use Modules\Sales\Database\Seeders\SalesDatabaseSeeder;
use Modules\Users\Database\Seeders\UsersDatabaseSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            CoreDatabaseSeeder::class,
            UsersDatabaseSeeder::class,
            AccountingDatabaseSeeder::class,
            InventoryDatabaseSeeder::class,
            SalesDatabaseSeeder::class,
            PaymentsDatabaseSeeder::class,
        ]);
    }
}
