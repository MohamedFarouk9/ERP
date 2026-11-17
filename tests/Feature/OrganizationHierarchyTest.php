<?php

declare(strict_types=1);

use App\Models\User;
use Modules\Core\Models\Tenant;
use Modules\Core\Services\TenantContext;
use Modules\Users\Models\Department;
use Modules\Users\Models\Team;

test('it creates departments and hierarchical teams with multi-team user memberships', function () {
    $tenant = Tenant::create(['name' => 'Org Tenant', 'slug' => 'org-tenant']);
    $context = app(TenantContext::class);
    $context->set($tenant);

    $department = Department::create([
        'tenant_id' => $tenant->id,
        'name' => 'Technology & Engineering',
        'code' => 'TECH-ENG',
    ]);

    // Root / Parent Team
    $parentTeam = Team::create([
        'tenant_id' => $tenant->id,
        'department_id' => $department->id,
        'name' => 'Engineering Core',
        'slug' => 'eng-core',
    ]);

    // Nested / Child Team
    $childTeam = Team::create([
        'tenant_id' => $tenant->id,
        'department_id' => $department->id,
        'parent_id' => $parentTeam->id,
        'name' => 'Payments Squad',
        'slug' => 'payments-squad',
    ]);

    expect($parentTeam->children->pluck('id')->toArray())->toContain($childTeam->id)
        ->and($childTeam->parent->id)->toBe($parentTeam->id)
        ->and($childTeam->department->code)->toBe('TECH-ENG');

    // User belonging to multiple teams
    $engineer = User::create([
        'name' => 'Lead Architect',
        'email' => 'architect@example.com',
        'password' => 'Password123!',
        'tenant_id' => $tenant->id,
    ]);

    // Member of parent team (not leader), and leader of payments squad
    $parentTeam->members()->attach($engineer->id, ['is_leader' => false]);
    $childTeam->members()->attach($engineer->id, ['is_leader' => true]);

    expect($engineer->teams)->toHaveCount(2);

    $paymentsMembership = $engineer->teams->firstWhere('id', $childTeam->id);
    expect((bool) $paymentsMembership->pivot->is_leader)->toBeTrue();

    $coreMembership = $engineer->teams->firstWhere('id', $parentTeam->id);
    expect((bool) $coreMembership->pivot->is_leader)->toBeFalse();

    $context->reset();
});

test('departments and teams are strictly isolated by tenant', function () {
    $tenant1 = Tenant::create(['name' => 'Tenant One', 'slug' => 'tenant-one']);
    $tenant2 = Tenant::create(['name' => 'Tenant Two', 'slug' => 'tenant-two']);

    $dep1 = Department::create(['tenant_id' => $tenant1->id, 'name' => 'Finance', 'code' => 'FIN']);
    $dep2 = Department::create(['tenant_id' => $tenant2->id, 'name' => 'Finance', 'code' => 'FIN']);

    $context = app(TenantContext::class);

    $context->set($tenant1);
    expect(Department::all()->pluck('id')->toArray())->toContain($dep1->id)
        ->and(Department::all()->pluck('id')->toArray())->not->toContain($dep2->id);

    $context->set($tenant2);
    expect(Department::all()->pluck('id')->toArray())->toContain($dep2->id)
        ->and(Department::all()->pluck('id')->toArray())->not->toContain($dep1->id);

    $context->reset();
});
