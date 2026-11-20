<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignUlid('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->string('sku', 100);
            $table->string('name', 200);
            $table->text('description')->nullable();
            $table->string('unit', 30)->default('pcs');
            $table->bigInteger('cost_price')->default(0);
            $table->bigInteger('selling_price')->default(0);
            $table->integer('reorder_level')->default(0);
            $table->boolean('track_inventory')->default(true);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['tenant_id', 'sku']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
