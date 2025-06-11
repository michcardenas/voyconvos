<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('registro_conductores', function (Blueprint $table) {
            $table->integer('numero_puestos')->after('anio_vehiculo')->comment('Número de puestos del vehículo incluyendo el conductor');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registro_conductores', function (Blueprint $table) {
            $table->dropColumn('numero_puestos');
        });
    }
};