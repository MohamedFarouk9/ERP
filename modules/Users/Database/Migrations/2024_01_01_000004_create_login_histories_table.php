<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('login_histories', function (Blueprint $table): void {
            $table->ulid('id')->primary();
            $table->ulid('user_id');
            $table->string('ip_address', 45);
            $table->text('user_agent')->nullable();
            $table->string('status');
            $table->string('failure_reason')->nullable();
            $table->ulid('tenant_id')->nullable();
            $table->timestamp('created_at')->nullable();

            $table->index('user_id');
            $table->index('status');
            $table->index('tenant_id');
            $table->index('created_at');
            $table->index(['user_id', 'created_at']);

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('login_histories');
    }
};
