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
        $table->decimal('valor_persona', 10, 2)->nullable();
        $table->integer('puestos_totales')->nullable();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('viajes', function (Blueprint $table) {
            //
        });
    }
};
