<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('viajes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('conductor_id')->nullable();

            $table->string('origen_direccion');
            $table->decimal('origen_lat', 10, 6);
            $table->decimal('origen_lng', 10, 6);

            $table->string('destino_direccion');
            $table->decimal('destino_lat', 10, 6);
            $table->decimal('destino_lng', 10, 6);

            $table->decimal('distancia_km', 8, 2);
            $table->string('vehiculo');

            $table->decimal('valor_estimado', 12, 2);
            $table->decimal('valor_cobrado', 12, 2)->nullable();

            $table->time('hora_salida')->nullable();
            $table->tinyInteger('puestos_disponibles')->nullable();

            $table->string('estado')->default('pendiente');
            $table->boolean('activo')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('viajes');
    }
};
