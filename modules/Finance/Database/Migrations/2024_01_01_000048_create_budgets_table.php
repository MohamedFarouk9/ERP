<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('budgets', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->ulid('tenant_id')->index();
            $table->string('name', 150);
            $table->ulid('expense_category_id')->index();
            $table->string('period_month', 7); // YYYY-MM
            $table->bigInteger('budget_amount')->default(0); // cents
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['tenant_id', 'expense_category_id', 'period_month']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('budgets');
    }
};
