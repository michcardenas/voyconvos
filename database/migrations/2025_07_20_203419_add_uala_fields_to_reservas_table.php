<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reservas', function (Blueprint $table) {
            // Campos para Uala
            $table->string('uala_checkout_id')->nullable();
            $table->text('uala_payment_url')->nullable();
            $table->string('uala_external_reference')->nullable();
            $table->string('uala_payment_status')->nullable();
            $table->timestamp('uala_payment_date')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('reservas', function (Blueprint $table) {
            $table->dropColumn([
                'uala_checkout_id',
                'uala_payment_url', 
                'uala_external_reference',
                'uala_payment_status',
                'uala_payment_date'
            ]);
        });
    }
};