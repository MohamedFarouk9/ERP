<?php

declare(strict_types=1);

namespace Modules\Users\Actions;

use Illuminate\Support\Str;
use Modules\Core\Services\TenantContext;
use Modules\Users\Models\Team;

final class CreateTeamAction
{
    public function __construct(
        private readonly TenantContext $tenantContext
    ) {}

    /**
     * @param  array{
     *     name: string,
     *     slug?: string|null,
     *     department_id?: string|null,
     *     parent_id?: string|null,
     *     description?: string|null
     * }  $data
     */
    public function execute(array $data): Team
    {
        $tenantId = $this->tenantContext->require()->id;
        $slug = ! empty($data['slug']) ? Str::slug($data['slug']) : Str::slug($data['name']);

        return Team::create([
            'tenant_id' => $tenantId,
            'department_id' => $data['department_id'] ?? null,
            'parent_id' => $data['parent_id'] ?? null,
            'name' => trim($data['name']),
            'slug' => $slug,
            'description' => $data['description'] ?? null,
        ]);
    }
}
