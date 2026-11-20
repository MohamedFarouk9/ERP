<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales_order_items', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('sales_order_id')->constrained('sales_orders')->cascadeOnDelete();
            $table->foreignUlid('product_id')->constrained('products')->cascadeOnDelete();
            $table->string('description', 255)->nullable();
            $table->integer('quantity');
            $table->bigInteger('unit_price'); // minor units
            $table->integer('tax_rate')->default(0); // basis points: 1400 = 14.00%
            $table->bigInteger('tax_amount')->default(0); // minor units
            $table->bigInteger('line_total'); // minor units
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales_order_items');
    }
};
