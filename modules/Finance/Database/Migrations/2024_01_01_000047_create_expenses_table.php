<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->ulid('tenant_id')->index();
            $table->string('expense_number', 40);
            $table->ulid('expense_category_id')->index();
            $table->ulid('tax_rate_id')->nullable()->index();
            $table->string('payee', 150);
            $table->date('expense_date');
            $table->bigInteger('amount')->default(0); // subtotal cents
            $table->bigInteger('tax_amount')->default(0); // cents
            $table->bigInteger('total_amount')->default(0); // cents
            $table->string('payment_method', 40)->default('bank_transfer');
            $table->string('status', 20)->default('draft'); // draft, approved, paid
            $table->string('reference', 100)->nullable();
            $table->text('notes')->nullable();
            $table->ulid('created_by')->nullable()->index();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->unique(['tenant_id', 'expense_number']);
            $table->index(['tenant_id', 'expense_date', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
