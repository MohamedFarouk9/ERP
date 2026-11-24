<?php

declare(strict_types=1);

use App\Providers\AppServiceProvider;
use Modules\Accounting\Providers\AccountingServiceProvider;
use Modules\Core\Providers\CoreServiceProvider;
use Modules\Hrm\Providers\HrmServiceProvider;
use Modules\Inventory\Providers\InventoryServiceProvider;
use Modules\Payments\Providers\PaymentsServiceProvider;
use Modules\Procurement\Providers\ProcurementServiceProvider;
use Modules\Sales\Providers\SalesServiceProvider;
use Modules\Users\Providers\UsersServiceProvider;

return [
    AppServiceProvider::class,
    CoreServiceProvider::class,
    UsersServiceProvider::class,
    AccountingServiceProvider::class,
    InventoryServiceProvider::class,
    SalesServiceProvider::class,
    PaymentsServiceProvider::class,
    ProcurementServiceProvider::class,
    HrmServiceProvider::class,
];
