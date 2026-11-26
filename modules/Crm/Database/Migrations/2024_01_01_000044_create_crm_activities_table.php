<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('crm_activities', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->ulid('tenant_id')->index();
            $table->string('type', 30)->default('call'); // call, email, meeting, task
            $table->string('subject', 180);
            $table->ulid('lead_id')->nullable()->index();
            $table->ulid('deal_id')->nullable()->index();
            $table->ulid('assigned_to')->nullable()->index();
            $table->dateTime('due_at')->nullable();
            $table->string('status', 20)->default('open'); // open, completed
            $table->timestamp('completed_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['tenant_id', 'status', 'due_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('crm_activities');
    }
};
