<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('registro_conductores', function (Blueprint $table) {
            $table->decimal('consumo_por_galon', 8, 2)->nullable()->after('verificar_pasajeros');
        });
    }

    public function down(): void
    {
        Schema::table('registro_conductores', function (Blueprint $table) {
            $table->dropColumn('consumo_por_galon');
        });
    }
};
