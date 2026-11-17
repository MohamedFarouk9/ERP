<?php

declare(strict_types=1);

use App\Providers\AppServiceProvider;
use Modules\Accounting\Providers\AccountingServiceProvider;
use Modules\Core\Providers\CoreServiceProvider;
use Modules\Users\Providers\UsersServiceProvider;

return [
    AppServiceProvider::class,
    CoreServiceProvider::class,
    UsersServiceProvider::class,
    AccountingServiceProvider::class,
];
