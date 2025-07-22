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
        'user_id',
        'estado',
        'verificado_por_conductor',
        'fecha_verificacion',
        'asistencia',
        'notificado',
        'cantidad_puestos',
        'precio_por_persona',
        'total',
        'fecha_reserva',
        'mp_preference_id',
        'mp_init_point',
        'uala_bis_uuid',
        'mp_payment_id',
        'fecha_pago',
        'uala_bis_checkout_link',
        'uala_bis_external_reference',
        'uala_bis_webhook_response',
        'uala_checkout_id',
        'uala_payment_url',
        'uala_external_reference',
        'uala_payment_status',
        'uala_payment_date'
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

    // public function calificacionPasajero()
    // {
    //     return $this->hasOne(Calificacion::class, 'reserva_id')->where('tipo', 'pasajero_a_conductor');
    // }

   public function calificacionConductor()
    {
        return $this->hasOne(Calificacion::class, 'reserva_id', 'id')
                    ->where('tipo', 'conductor_a_pasajero');
    }
        public function calificaciones()
    {
        return $this->hasMany(Calificacion::class, 'reserva_id', 'id');
    }
     public function calificacionPasajero()
    {
        return $this->hasOne(Calificacion::class, 'reserva_id', 'id')
                    ->where('tipo', 'pasajero_a_conductor')
                    ->where('usuario_id', $this->user_id);
    }

       public function yaCalificadoPorConductor($conductorId)
    {
        return $this->calificaciones()
                    ->where('usuario_id', $conductorId)
                    ->where('tipo', 'conductor_a_pasajero')
                    ->exists();
    }
}