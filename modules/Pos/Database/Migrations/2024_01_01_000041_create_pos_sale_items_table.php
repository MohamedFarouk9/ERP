<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pos_sale_items', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('pos_sale_id')->constrained('pos_sales')->cascadeOnDelete();
            $table->foreignUlid('product_id')->constrained('products')->cascadeOnDelete();
            $table->string('sku', 100);
            $table->string('product_name', 200);
            $table->integer('quantity');
            $table->bigInteger('unit_price');
            $table->bigInteger('tax_amount')->default(0);
            $table->bigInteger('line_total');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pos_sale_items');
    }
};
