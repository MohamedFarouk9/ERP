<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('invoice_id')->constrained('invoices')->cascadeOnDelete();
            $table->foreignUlid('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->string('description', 255);
            $table->integer('quantity');
            $table->bigInteger('unit_price'); // minor units
            $table->bigInteger('tax_amount')->default(0); // minor units
            $table->bigInteger('line_total'); // minor units
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoice_items');
    }
};
