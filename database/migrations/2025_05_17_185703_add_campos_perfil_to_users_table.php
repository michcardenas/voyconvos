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
    Schema::table('users', function (Blueprint $table) {
        $table->string('pais')->default('Argentina')->after('email');
        $table->string('ciudad')->nullable()->after('pais');
        $table->string('dni')->nullable()->after('ciudad');
        $table->string('celular')->nullable()->after('dni');
        $table->string('foto')->nullable()->after('celular');
    });
}

public function down()
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn(['pais', 'ciudad', 'dni', 'celular', 'foto']);
    });
}

};
