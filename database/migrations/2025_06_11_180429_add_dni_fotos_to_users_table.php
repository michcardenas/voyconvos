<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDniFotosToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('dni_foto')->nullable()->after('foto');
            $table->string('dni_foto_atras')->nullable()->after('dni_foto');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['dni_foto', 'dni_foto_atras']);
        });
    }
}

