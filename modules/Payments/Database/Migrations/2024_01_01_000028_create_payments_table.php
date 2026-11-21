<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignUlid('payment_method_id')->constrained('payment_methods')->cascadeOnDelete();
            $table->foreignUlid('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->foreignUlid('invoice_id')->nullable()->constrained('invoices')->nullOnDelete();
            $table->string('payment_number', 50);
            $table->date('payment_date');
            $table->bigInteger('amount'); // minor units
            $table->string('currency_code', 3)->default('USD');
            $table->enum('status', ['pending', 'completed', 'failed', 'refunded'])->default('pending');
            $table->string('reference', 150)->nullable();
            $table->text('notes')->nullable();
            $table->foreignUlid('processed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['tenant_id', 'payment_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
