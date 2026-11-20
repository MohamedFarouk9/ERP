<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales_orders', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignUlid('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->foreignUlid('warehouse_id')->nullable()->constrained('warehouses')->nullOnDelete();
            $table->string('order_number', 50);
            $table->date('order_date');
            $table->enum('status', ['draft', 'confirmed', 'fulfilled', 'cancelled'])->default('draft');
            $table->bigInteger('subtotal')->default(0);
            $table->bigInteger('tax_amount')->default(0);
            $table->bigInteger('discount_amount')->default(0);
            $table->bigInteger('total_amount')->default(0);
            $table->string('currency_code', 3)->default('USD');
            $table->text('notes')->nullable();
            $table->foreignUlid('confirmed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['tenant_id', 'order_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales_orders');
    }
};
