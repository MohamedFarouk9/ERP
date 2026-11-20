<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignUlid('warehouse_id')->constrained('warehouses')->cascadeOnDelete();
            $table->foreignUlid('product_id')->constrained('products')->cascadeOnDelete();
            $table->enum('type', ['in', 'out', 'adjustment']);
            $table->bigInteger('quantity'); // signed delta for adjustment, positive for in/out
            $table->bigInteger('unit_cost')->default(0); // minor units
            $table->string('reference_type', 100)->nullable();
            $table->string('reference_id', 50)->nullable();
            $table->text('notes')->nullable();
            $table->foreignUlid('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};
