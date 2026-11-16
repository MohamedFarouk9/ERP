<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. System & Module Permissions
        Schema::create('permissions', function (Blueprint $table): void {
            $table->ulid('id')->primary();
            $table->string('module', 50)->index();
            $table->string('resource', 50)->index();
            $table->string('action', 50);
            $table->string('slug')->unique(); // e.g. "users.roles.create"
            $table->string('name');
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // 2. Per-Tenant Roles
        Schema::create('roles', function (Blueprint $table): void {
            $table->ulid('id')->primary();
            $table->ulid('tenant_id');
            $table->string('name');
            $table->string('slug');
            $table->string('description')->nullable();
            $table->boolean('is_system')->default(false);
            $table->timestamps();

            $table->unique(['tenant_id', 'slug']);
            $table->foreign('tenant_id')
                ->references('id')
                ->on('tenants')
                ->cascadeOnDelete();
        });

        // 3. Role <-> Permissions Pivot
        Schema::create('role_permissions', function (Blueprint $table): void {
            $table->ulid('role_id');
            $table->ulid('permission_id');

            $table->primary(['role_id', 'permission_id']);
            $table->foreign('role_id')
                ->references('id')
                ->on('roles')
                ->cascadeOnDelete();
            $table->foreign('permission_id')
                ->references('id')
                ->on('permissions')
                ->cascadeOnDelete();
        });

        // 4. User <-> Roles Pivot
        Schema::create('user_roles', function (Blueprint $table): void {
            $table->ulid('user_id');
            $table->ulid('role_id');

            $table->primary(['user_id', 'role_id']);
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();
            $table->foreign('role_id')
                ->references('id')
                ->on('roles')
                ->cascadeOnDelete();
        });

        // 5. User <-> Direct Custom Permissions Pivot
        Schema::create('user_permissions', function (Blueprint $table): void {
            $table->ulid('user_id');
            $table->ulid('permission_id');
            $table->boolean('is_granted')->default(true); // true = allow, false = explicit deny

            $table->primary(['user_id', 'permission_id']);
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();
            $table->foreign('permission_id')
                ->references('id')
                ->on('permissions')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_permissions');
        Schema::dropIfExists('user_roles');
        Schema::dropIfExists('role_permissions');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('permissions');
    }
};

