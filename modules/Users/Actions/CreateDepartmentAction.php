<?php

declare(strict_types=1);

namespace Modules\Users\Actions;

use Modules\Core\Services\TenantContext;
use Modules\Users\Models\Department;

final class CreateDepartmentAction
{
    public function __construct(
        private readonly TenantContext $tenantContext
    ) {}

    /**
     * @param  array{
     *     name: string,
     *     code: string,
     *     description?: string|null
     * }  $data
     */
    public function execute(array $data): Department
    {
        $tenantId = $this->tenantContext->require()->id;

        return Department::create([
            'tenant_id' => $tenantId,
            'name' => trim($data['name']),
            'code' => strtoupper(trim($data['code'])),
            'description' => $data['description'] ?? null,
        ]);
    }
}
