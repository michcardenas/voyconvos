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
        Schema::create('paradas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('viaje_id');
            $table->unsignedBigInteger('conductor_id');
            $table->string('nombre'); // Nombre/dirección de la parada
            $table->decimal('latitud', 10, 6);
            $table->decimal('longitud', 10, 6);
            $table->integer('orden')->default(0); // Orden de la parada en la ruta
            $table->timestamps();

            // Foreign keys
            $table->foreign('viaje_id')->references('id')->on('viajes')->onDelete('cascade');
            $table->foreign('conductor_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paradas');
    }
};
