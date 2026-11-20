<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('code', 50);
            $table->string('name', 150);
            $table->string('email', 150)->nullable();
            $table->string('phone', 50)->nullable();
            $table->string('tax_number', 50)->nullable();
            $table->string('billing_address', 255)->nullable();
            $table->string('shipping_address', 255)->nullable();
            $table->bigInteger('credit_limit')->default(0); // minor units
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['tenant_id', 'code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
