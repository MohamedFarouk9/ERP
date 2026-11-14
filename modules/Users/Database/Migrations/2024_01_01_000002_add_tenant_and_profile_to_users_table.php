<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->ulid('tenant_id')->nullable()->after('id');
            $table->string('phone')->nullable()->after('email');
            $table->string('timezone')->default('UTC')->after('phone');
            $table->string('locale')->default('en')->after('timezone');
            $table->string('avatar_path')->nullable()->after('locale');
            $table->boolean('is_active')->default(true)->after('avatar_path');

            $table->index('tenant_id');
            $table->index('is_active');
        });

        // SQLite does not support dropping indexes or adding unique constraints
        // on existing columns easily. We use a pragmatic approach: rebuild via
        // raw statements only when NOT on SQLite (where the default unique on
        // email from the stock migration is acceptable during local dev).
        if (DB::getDriverName() !== 'sqlite') {
            Schema::table('users', function (Blueprint $table): void {
                $table->dropUnique(['email']);
                $table->unique(['tenant_id', 'email'], 'users_tenant_id_email_unique');
            });
        } else {
            // On SQLite we can still add a composite unique index; the original
            // unique('email') constraint lives inside the CREATE TABLE statement
            // and cannot be dropped, but the composite index adds the tenant-scoped
            // guarantee we need for application-level checks.
            Schema::table('users', function (Blueprint $table): void {
                $table->unique(['tenant_id', 'email'], 'users_tenant_id_email_unique');
            });
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            Schema::table('users', function (Blueprint $table): void {
                $table->dropUnique('users_tenant_id_email_unique');
                $table->unique('email');
            });
        } else {
            Schema::table('users', function (Blueprint $table): void {
                $table->dropUnique('users_tenant_id_email_unique');
            });
        }

        Schema::table('users', function (Blueprint $table): void {
            $table->dropIndex(['tenant_id']);
            $table->dropIndex(['is_active']);
            $table->dropColumn([
                'tenant_id',
                'phone',
                'timezone',
                'locale',
                'avatar_path',
                'is_active',
            ]);
        });
    }
};
