<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VistaCalificacionesDetalle extends Model
{
    protected $table = 'vista_calificaciones_detalle';

    public $incrementing = false;
    public $timestamps = false;

    protected $primaryKey = null; // no hay clave primaria
    protected $guarded = [];

    // Evitar escritura si la vista es de solo lectura
    public function save(array $options = [])
    {
        throw new \Exception("Este modelo es de solo lectura.");
    }
}
