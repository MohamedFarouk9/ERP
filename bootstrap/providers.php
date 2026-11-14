<?php

declare(strict_types=1);

use App\Providers\AppServiceProvider;
use Modules\Core\Providers\CoreServiceProvider;
use Modules\Users\Providers\UsersServiceProvider;

return [
    AppServiceProvider::class,
    CoreServiceProvider::class,
    UsersServiceProvider::class,
];
