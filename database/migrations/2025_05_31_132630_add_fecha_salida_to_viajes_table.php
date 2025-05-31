<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('viajes', function (Blueprint $table) {
            $table->datetime('fecha_salida')->nullable()->after('id');
            // O si quieres que sea obligatoria:
            // $table->datetime('fecha_salida')->after('id');
            
            // Si también necesitas fecha de llegada:
            // $table->datetime('fecha_llegada')->nullable()->after('fecha_salida');
        });
    }

    public function down()
    {
        Schema::table('viajes', function (Blueprint $table) {
            $table->dropColumn('fecha_salida');
            // $table->dropColumn('fecha_llegada');
        });
    }
};