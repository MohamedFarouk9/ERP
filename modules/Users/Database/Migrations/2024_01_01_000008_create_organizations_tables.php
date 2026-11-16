<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Departments (Per-Tenant)
        Schema::create('departments', function (Blueprint $table): void {
            $table->ulid('id')->primary();
            $table->ulid('tenant_id');
            $table->string('name');
            $table->string('code', 50);
            $table->string('description')->nullable();
            $table->timestamps();

            $table->unique(['tenant_id', 'code']);
            $table->foreign('tenant_id')
                ->references('id')
                ->on('tenants')
                ->cascadeOnDelete();
        });

        // 2. Teams (Hierarchical Tree per Tenant, optionally linked to a Department)
        Schema::create('teams', function (Blueprint $table): void {
            $table->ulid('id')->primary();
            $table->ulid('tenant_id');
            $table->ulid('department_id')->nullable();
            $table->ulid('parent_id')->nullable(); // Self-referential parent team for hierarchy
            $table->string('name');
            $table->string('slug');
            $table->string('description')->nullable();
            $table->timestamps();

            $table->unique(['tenant_id', 'slug']);
            $table->foreign('tenant_id')
                ->references('id')
                ->on('tenants')
                ->cascadeOnDelete();
            $table->foreign('department_id')
                ->references('id')
                ->on('departments')
                ->nullOnDelete();
            $table->foreign('parent_id')
                ->references('id')
                ->on('teams')
                ->cascadeOnDelete();
        });

        // 3. Team Members (Users can belong to multiple teams with optional leadership flag)
        Schema::create('team_members', function (Blueprint $table): void {
            $table->ulid('team_id');
            $table->ulid('user_id');
            $table->boolean('is_leader')->default(false);
            $table->timestamps();

            $table->primary(['team_id', 'user_id']);
            $table->foreign('team_id')
                ->references('id')
                ->on('teams')
                ->cascadeOnDelete();
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('team_members');
        Schema::dropIfExists('teams');
        Schema::dropIfExists('departments');
    }
};
