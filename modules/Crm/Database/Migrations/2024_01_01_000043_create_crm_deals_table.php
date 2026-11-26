<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('crm_deals', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->ulid('tenant_id')->index();
            $table->string('deal_code', 40);
            $table->string('title', 180);
            $table->ulid('customer_id')->nullable()->index();
            $table->ulid('lead_id')->nullable()->index();
            $table->ulid('owner_id')->nullable()->index();
            $table->string('stage', 30)->default('qualified'); // qualified, proposal, negotiation, won, lost
            $table->bigInteger('value_amount')->default(0); // cents
            $table->unsignedTinyInteger('probability')->default(25); // 0..100
            $table->date('expected_close_date')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['tenant_id', 'deal_code']);
            $table->index(['tenant_id', 'stage']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('crm_deals');
    }
};
