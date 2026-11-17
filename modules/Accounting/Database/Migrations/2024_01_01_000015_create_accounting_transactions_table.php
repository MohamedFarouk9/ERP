<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('accounting_transactions', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignUlid('financial_period_id')->nullable()->constrained('financial_periods')->nullOnDelete();
            $table->string('transaction_number', 50); // e.g. "TXN-2026-00001"
            $table->date('transaction_date');
            $table->enum('status', ['draft', 'posted', 'cancelled'])->default('draft');
            $table->string('reference_type', 100)->nullable(); // e.g. "Order", "Manual", "Invoice"
            $table->string('reference_id', 50)->nullable();
            $table->text('notes')->nullable();
            $table->foreignUlid('posted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('posted_at')->nullable();
            $table->timestamps();

            $table->unique(['tenant_id', 'transaction_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('accounting_transactions');
    }
};
