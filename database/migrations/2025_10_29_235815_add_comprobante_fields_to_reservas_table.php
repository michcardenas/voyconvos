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
        Schema::table('reservas', function (Blueprint $table) {
            // Método de pago seleccionado
            $table->string('metodo_pago')->nullable()->after('total'); // 'mercadopago', 'transferencia', 'uala'

            // Campos para transferencia manual
            $table->string('comprobante_pago')->nullable()->after('metodo_pago'); // Ruta del archivo
            $table->timestamp('fecha_subida_comprobante')->nullable()->after('comprobante_pago');
            $table->timestamp('fecha_limite_comprobante')->nullable()->after('fecha_subida_comprobante'); // 1 hora después de reservar

            // Verificación del comprobante por el conductor
            $table->boolean('comprobante_verificado')->default(false)->after('fecha_limite_comprobante');
            $table->timestamp('fecha_verificacion_comprobante')->nullable()->after('comprobante_verificado');

            // Rechazo del comprobante
            $table->boolean('comprobante_rechazado')->default(false)->after('fecha_verificacion_comprobante');
            $table->text('motivo_rechazo_comprobante')->nullable()->after('comprobante_rechazado');

            // Índices para búsquedas rápidas
            $table->index('metodo_pago');
            $table->index('comprobante_verificado');
            $table->index('fecha_limite_comprobante');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservas', function (Blueprint $table) {
            // Eliminar índices primero
            $table->dropIndex(['metodo_pago']);
            $table->dropIndex(['comprobante_verificado']);
            $table->dropIndex(['fecha_limite_comprobante']);

            // Eliminar columnas
            $table->dropColumn([
                'metodo_pago',
                'comprobante_pago',
                'fecha_subida_comprobante',
                'fecha_limite_comprobante',
                'comprobante_verificado',
                'fecha_verificacion_comprobante',
                'comprobante_rechazado',
                'motivo_rechazo_comprobante'
            ]);
        });
    }
};
