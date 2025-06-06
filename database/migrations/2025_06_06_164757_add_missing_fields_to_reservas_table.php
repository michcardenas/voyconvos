<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMissingFieldsToReservasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reservas', function (Blueprint $table) {
            // Verificar si los campos no existen antes de agregarlos
            
            if (!Schema::hasColumn('reservas', 'precio_por_persona')) {
                $table->decimal('precio_por_persona', 10, 2)->nullable()->after('cantidad_puestos');
            }
            
            if (!Schema::hasColumn('reservas', 'total')) {
                $table->decimal('total', 10, 2)->nullable()->after('precio_por_persona');
            }
            
            if (!Schema::hasColumn('reservas', 'fecha_reserva')) {
                $table->timestamp('fecha_reserva')->nullable()->after('total');
            }

            // Campos para Mercado Pago (opcionales por ahora)
            if (!Schema::hasColumn('reservas', 'mp_preference_id')) {
                $table->string('mp_preference_id')->nullable()->after('fecha_reserva');
            }
            
            if (!Schema::hasColumn('reservas', 'mp_init_point')) {
                $table->text('mp_init_point')->nullable()->after('mp_preference_id');
            }
            
            if (!Schema::hasColumn('reservas', 'mp_payment_id')) {
                $table->string('mp_payment_id')->nullable()->after('mp_init_point');
            }
            
            if (!Schema::hasColumn('reservas', 'fecha_pago')) {
                $table->timestamp('fecha_pago')->nullable()->after('mp_payment_id');
            }

            // Índices para mejorar rendimiento
            $table->index('user_id');
            $table->index('viaje_id');
            $table->index('estado');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reservas', function (Blueprint $table) {
            $table->dropColumn([
                'precio_por_persona',
                'total',
                'fecha_reserva',
                'mp_preference_id',
                'mp_init_point',
                'mp_payment_id',
                'fecha_pago'
            ]);
        });
    }
}