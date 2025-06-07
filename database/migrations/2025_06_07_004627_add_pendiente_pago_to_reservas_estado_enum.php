<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Agregar 'pendiente_pago' al ENUM
        DB::statement("ALTER TABLE reservas MODIFY COLUMN estado ENUM('pendiente', 'pendiente_pago', 'confirmada', 'cancelada') DEFAULT 'pendiente'");
    }

    public function down()
    {
        // Primero actualizar registros que tengan 'pendiente_pago' a 'pendiente'
        DB::table('reservas')
            ->where('estado', 'pendiente_pago')
            ->update(['estado' => 'pendiente']);
            
        // Luego restaurar el ENUM original
        DB::statement("ALTER TABLE reservas MODIFY COLUMN estado ENUM('pendiente', 'confirmada', 'cancelada') DEFAULT 'pendiente'");
    }
};
