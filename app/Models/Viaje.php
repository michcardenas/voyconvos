<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Viaje extends Model
{
    protected $fillable = [
        'conductor_id',
        'origen_direccion',
        'origen_lat',
        'origen_lng',
        'destino_direccion',
        'destino_lat',
        'destino_lng',
        'distancia_km',
        'tiempo_estimado',
        'vehiculo',
        'valor_estimado',
        'valor_cobrado',
        'valor_persona',
        'puestos_totales',
        'hora_salida',
        'fecha_salida',
        'puestos_disponibles',
        'estado',
        'activo',
        'observaciones',
        'pasajeros_presentes',
        'pasajeros_ausentes',
        'ida_vuelta',
        'hora_regreso',
        'fecha_inicio_proceso',
        'fecha_inicio_real',
        'fecha_finalizacion',
    ];

    public function reservas()
    {
        return $this->hasMany(\App\Models\Reserva::class);
    }

    public function conductor()
    {
        return $this->belongsTo(User::class, 'conductor_id');
    }
       public function registroConductor()
{
    return $this->hasOne(\App\Models\RegistroConductor::class, 'user_id', 'conductor_id');
}

    /**
     * Relación con las paradas intermedias del viaje
     */
    public function paradas()
    {
        return $this->hasMany(Parada::class)->orderBy('orden');
    }
}
