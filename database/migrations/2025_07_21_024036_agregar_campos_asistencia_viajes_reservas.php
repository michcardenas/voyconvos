<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Agregar campos a tabla reservas
        Schema::table('reservas', function (Blueprint $table) {
            // Campo para marcar si el conductor verificó la asistencia
            $table->boolean('verificado_por_conductor')->default(false)->after('estado');
            
            // Campo para la fecha/hora de verificación
            $table->timestamp('fecha_verificacion')->nullable()->after('verificado_por_conductor');
            
            // Campo adicional para el estado de asistencia (si no lo tienes)
            if (!Schema::hasColumn('reservas', 'asistencia')) {
                $table->enum('asistencia', ['presente', 'ausente'])->nullable()->after('fecha_verificacion');
            }
        });

        // Agregar campos a tabla viajes
        Schema::table('viajes', function (Blueprint $table) {
            // Contadores de pasajeros
            $table->integer('pasajeros_presentes')->default(0)->after('puestos_disponibles');
            $table->integer('pasajeros_ausentes')->default(0)->after('pasajeros_presentes');
            
            // Campo para fecha de inicio real del viaje (opcional)
            $table->timestamp('fecha_inicio_real')->nullable()->after('pasajeros_ausentes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Eliminar campos de tabla reservas
        Schema::table('reservas', function (Blueprint $table) {
            $table->dropColumn([
                'verificado_por_conductor',
                'fecha_verificacion'
            ]);
            
            // Solo eliminar asistencia si fue creada en esta migración
            if (Schema::hasColumn('reservas', 'asistencia')) {
                $table->dropColumn('asistencia');
            }
        });

        // Eliminar campos de tabla viajes
        Schema::table('viajes', function (Blueprint $table) {
            $table->dropColumn([
                'pasajeros_presentes',
                'pasajeros_ausentes',
                'fecha_inicio_real'
            ]);
        });
    }
};