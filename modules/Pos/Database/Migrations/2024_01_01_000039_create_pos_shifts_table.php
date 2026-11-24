<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pos_shifts', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignUlid('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignUlid('warehouse_id')->nullable()->constrained('warehouses')->nullOnDelete();
            $table->string('shift_number', 50);
            $table->timestamp('opened_at');
            $table->timestamp('closed_at')->nullable();
            $table->bigInteger('opening_cash')->default(0);
            $table->bigInteger('expected_cash')->default(0);
            $table->bigInteger('counted_cash')->nullable();
            $table->enum('status', ['open', 'closed'])->default('open');
            $table->string('notes', 255)->nullable();
            $table->timestamps();

            $table->unique(['tenant_id', 'shift_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pos_shifts');
    }
};
