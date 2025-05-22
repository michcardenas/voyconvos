<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('destino_conductors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('conductor_id')->nullable(); // Opcional, si usas auth
            $table->decimal('latitud', 10, 6);
            $table->decimal('longitud', 10, 6);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('destino_conductors');
    }
};
