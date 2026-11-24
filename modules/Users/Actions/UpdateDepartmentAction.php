<?php

declare(strict_types=1);

namespace Modules\Users\Actions;

use InvalidArgumentException;
use Modules\Core\Services\TenantContext;
use Modules\Users\Models\Department;

class UpdateDepartmentAction
{
    public function __construct(
        protected TenantContext $tenantContext
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(Department $department, array $data): Department
    {
        $tenantId = $this->tenantContext->requireId();

        $duplicate = Department::withoutGlobalScopes()
            ->where('tenant_id', $tenantId)
            ->where('code', $data['code'])
            ->where('id', '!=', $department->id)
            ->first();

        if ($duplicate) {
            throw new InvalidArgumentException("Department with code [{$data['code']}] already exists.");
        }

        $department->update([
            'code' => $data['code'],
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
        ]);

        return $department->fresh();
    }
}
