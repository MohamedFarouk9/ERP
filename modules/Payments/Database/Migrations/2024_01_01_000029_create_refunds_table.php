<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('refunds', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignUlid('payment_id')->constrained('payments')->cascadeOnDelete();
            $table->string('refund_number', 50);
            $table->date('refund_date');
            $table->bigInteger('amount'); // minor units
            $table->text('reason')->nullable();
            $table->enum('status', ['pending', 'completed', 'rejected'])->default('completed');
            $table->foreignUlid('processed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();

            $table->unique(['tenant_id', 'refund_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('refunds');
    }
};
