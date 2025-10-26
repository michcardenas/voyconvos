<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Parada extends Model
{
    protected $fillable = [
        'viaje_id',
        'conductor_id',
        'nombre',
        'latitud',
        'longitud',
        'orden',
    ];

    /**
     * Relación con el viaje
     */
    public function viaje()
    {
        return $this->belongsTo(Viaje::class);
    }

    /**
     * Relación con el conductor
     */
    public function conductor()
    {
        return $this->belongsTo(User::class, 'conductor_id');
    }
}
