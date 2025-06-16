<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('registro_conductores', function (Blueprint $table) {
            $table->boolean('verificar_pasajeros')->nullable()->default(null)
                ->comment('Indica si el conductor desea verificar a los pasajeros antes de aceptar la reserva');
        });
    }

    public function down(): void
    {
        Schema::table('registro_conductores', function (Blueprint $table) {
            $table->dropColumn('verificar_pasajeros');
        });
    }
};
