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
    Schema::table('viajes', function (Blueprint $table) {
        $table->string('tiempo_estimado', 50)->nullable()->after('distancia_km');
        $table->boolean('ida_vuelta')->default(0)->after('valor_persona');
        $table->time('hora_regreso')->nullable()->after('ida_vuelta');
    });
}

public function down()
{
    Schema::table('viajes', function (Blueprint $table) {
        $table->dropColumn(['tiempo_estimado', 'ida_vuelta', 'hora_regreso']);
    });
}
};
