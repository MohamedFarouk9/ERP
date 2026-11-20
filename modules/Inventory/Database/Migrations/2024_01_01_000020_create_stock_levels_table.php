<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_levels', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignUlid('warehouse_id')->constrained('warehouses')->cascadeOnDelete();
            $table->foreignUlid('product_id')->constrained('products')->cascadeOnDelete();
            $table->bigInteger('quantity_on_hand')->default(0);
            $table->bigInteger('quantity_reserved')->default(0);
            $table->timestamps();

            $table->unique(['tenant_id', 'warehouse_id', 'product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_levels');
    }
};
