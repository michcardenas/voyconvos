<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    use HasFactory;

    // ✅ ACTUALIZAR: Agregar todos los campos necesarios
    protected $fillable = [
        'viaje_id', 
        'user_id',              // ✅ Tu campo actual
        'estado', 
        'cantidad_puestos', 
        'precio_por_persona',   // ✅ Nuevo campo
        'total',                // ✅ Nuevo campo
        'fecha_reserva',        // ✅ Nuevo campo
        'mp_preference_id',     // ✅ Nuevo campo para Mercado Pago
        'mp_init_point',        // ✅ Nuevo campo para Mercado Pago
        'mp_payment_id',        // ✅ Nuevo campo para Mercado Pago
        'fecha_pago',           // ✅ Nuevo campo para Mercado Pago
        'notificado'            // ✅ Tu campo actual
    ];

    // ✅ AGREGAR: Campos de fecha para manejo automático
    protected $dates = [
        'fecha_reserva',
        'fecha_pago',
        'created_at',
        'updated_at'
    ];

    // ✅ AGREGAR: Casteo automático de tipos
    protected $casts = [
        'precio_por_persona' => 'decimal:2',
        'total' => 'decimal:2',
        'fecha_reserva' => 'datetime',
        'fecha_pago' => 'datetime',
    ];

    // Relaciones existentes - MANTENER IGUAL
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // ✅ AGREGAR: Relación más clara para pasajero
    public function pasajero()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function viaje()
    {
        return $this->belongsTo(Viaje::class);
    }

    public function calificacionPasajero()
    {
        return $this->hasOne(Calificacion::class, 'reserva_id')->where('tipo', 'pasajero_a_conductor');
    }

    public function calificacionConductor()
    {
        return $this->hasOne(Calificacion::class, 'reserva_id')->where('tipo', 'conductor_a_pasajero');
    }

    public function calificacionEnviadaPorPasajero()
    {
        return $this->hasOne(Calificacion::class)->where('tipo', 'pasajero_a_conductor')->exists();
    }
}