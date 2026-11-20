<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignUlid('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->foreignUlid('sales_order_id')->nullable()->constrained('sales_orders')->nullOnDelete();
            $table->string('invoice_number', 50);
            $table->date('invoice_date');
            $table->date('due_date');
            $table->enum('status', ['draft', 'issued', 'paid', 'overdue', 'cancelled'])->default('draft');
            $table->bigInteger('subtotal')->default(0);
            $table->bigInteger('tax_amount')->default(0);
            $table->bigInteger('total_amount')->default(0);
            $table->bigInteger('amount_paid')->default(0);
            $table->string('currency_code', 3)->default('USD');
            $table->text('notes')->nullable();
            $table->foreignUlid('issued_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('issued_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['tenant_id', 'invoice_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
