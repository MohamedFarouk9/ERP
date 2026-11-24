<?php

declare(strict_types=1);

namespace Modules\Users\Actions;

use Illuminate\Support\Str;
use InvalidArgumentException;
use Modules\Core\Services\TenantContext;
use Modules\Users\Models\Team;

class UpdateTeamAction
{
    public function __construct(
        protected TenantContext $tenantContext
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(Team $team, array $data): Team
    {
        $tenantId = $this->tenantContext->requireId();
        $slug = ! empty($data['slug']) ? (string) $data['slug'] : Str::slug((string) $data['name']);

        $duplicate = Team::withoutGlobalScopes()
            ->where('tenant_id', $tenantId)
            ->where('slug', $slug)
            ->where('id', '!=', $team->id)
            ->first();

        if ($duplicate) {
            throw new InvalidArgumentException("Team with slug [{$slug}] already exists.");
        }

        $team->update([
            'name' => $data['name'],
            'slug' => $slug,
            'department_id' => $data['department_id'] ?: null,
            'parent_id' => $data['parent_id'] ?: null,
            'description' => $data['description'] ?? null,
        ]);

        return $team->fresh(['department']);
    }
}
