<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    use HasFactory;

    protected $fillable = ['viaje_id', 'user_id', 'estado', 'cantidad_puestos', 'notificado'];

    // Relaciones
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function usuario()
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
