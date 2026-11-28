<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pos_print_settings', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->ulid('tenant_id')->unique();
            $table->string('printer_type', 30)->default('thermal_80mm'); // thermal_80mm, thermal_58mm, a4
            $table->string('barcode_type', 20)->default('CODE128'); // CODE128, CODE39, EAN13
            $table->string('label_paper_size', 30)->default('A4'); // A4, thermal_80mm, thermal_58mm, roll_50x30
            $table->string('store_name', 150)->nullable();
            $table->string('store_address', 255)->nullable();
            $table->string('store_phone', 50)->nullable();
            $table->string('tax_number', 60)->nullable();
            $table->string('footer_message', 255)->default('Thank you for your business!');
            $table->boolean('auto_print_receipt')->default(false);
            $table->boolean('show_logo')->default(true);
            $table->boolean('show_barcode_on_receipt')->default(true);
            $table->boolean('show_cashier')->default(true);
            $table->boolean('show_customer')->default(true);
            $table->boolean('show_tax_breakdown')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pos_print_settings');
    }
};
