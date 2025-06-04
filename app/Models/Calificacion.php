<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Calificacion extends Model
{
    use HasFactory;

    protected $fillable = [
        'reserva_id',
        'usuario_id',
        'tipo', // 'pasajero_a_conductor' o 'conductor_a_pasajero'
        'comentario',
        'calificacion'
    ];

    public function reserva()
    {
        return $this->belongsTo(Reserva::class);
    }
}
