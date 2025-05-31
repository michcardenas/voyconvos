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
        Schema::create('registro_conductores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->string('marca_vehiculo');
            $table->string('modelo_vehiculo');
            $table->integer('anio_vehiculo');
            $table->string('patente');

            // Documentos
            $table->string('licencia')->nullable();
            $table->string('cedula')->nullable();
            $table->string('cedula_verde')->nullable();
            $table->string('seguro')->nullable();
            $table->string('rto')->nullable();
            $table->string('antecedentes')->nullable();

            // Estados
            $table->enum('estado_verificacion', ['pendiente', 'aprobado', 'rechazado'])->default('pendiente');
            $table->enum('estado_registro', ['incompleto', 'completo'])->default('incompleto');

            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registro_conductores');
    }
};
