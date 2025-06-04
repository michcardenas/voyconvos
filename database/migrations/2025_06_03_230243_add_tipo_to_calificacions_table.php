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
        Schema::table('calificacions', function (Blueprint $table) {
            $table->string('tipo')->default('pasajero_a_conductor');
        });
    }

    /**
     * Reverse the migrations.
     */
   public function down()
    {
        Schema::table('calificacions', function (Blueprint $table) {
            $table->dropColumn('tipo');
        });
    }

};
