<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pos_sales', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignUlid('pos_shift_id')->constrained('pos_shifts')->cascadeOnDelete();
            $table->foreignUlid('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->foreignUlid('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('receipt_number', 50);
            $table->date('sale_date');
            $table->bigInteger('subtotal')->default(0);
            $table->bigInteger('tax_amount')->default(0);
            $table->bigInteger('discount_amount')->default(0);
            $table->bigInteger('total_amount')->default(0);
            $table->bigInteger('amount_tendered')->default(0);
            $table->bigInteger('change_due')->default(0);
            $table->enum('payment_type', ['cash', 'card', 'split'])->default('cash');
            $table->enum('status', ['completed', 'voided'])->default('completed');
            $table->timestamps();

            $table->unique(['tenant_id', 'receipt_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pos_sales');
    }
};
