<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('crm_leads', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->ulid('tenant_id')->index();
            $table->string('lead_code', 40);
            $table->string('name', 150);
            $table->string('company', 150)->nullable();
            $table->string('email', 150)->nullable();
            $table->string('phone', 50)->nullable();
            $table->string('source', 40)->default('website'); // website, referral, cold_call, social, event
            $table->string('status', 30)->default('new'); // new, contacted, qualified, converted, lost
            $table->bigInteger('estimated_value')->default(0); // cents
            $table->ulid('owner_id')->nullable()->index();
            $table->ulid('converted_customer_id')->nullable()->index();
            $table->timestamp('converted_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['tenant_id', 'lead_code']);
            $table->index(['tenant_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('crm_leads');
    }
};
