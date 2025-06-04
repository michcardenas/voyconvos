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
        Schema::table('users', function (Blueprint $table) {
            // Campo booleano con valor por defecto
            $table->boolean('verificado')->default(false)->after('foto');

            // Ruta a imagen del documento (opcional)
            $table->string('dni_foto')->nullable()->after('verificado');

            // Índice para optimizar búsquedas
            $table->index('verificado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['verificado']);
            $table->dropColumn(['verificado', 'dni_foto']);
        });
    }
};
