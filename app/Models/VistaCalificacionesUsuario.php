<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VistaCalificacionesUsuario extends Model
{
    protected $table = 'vista_calificaciones_usuarios'; // solo el nombre, sin el esquema

    public $incrementing = false;
    public $timestamps = false;

    // Si no tiene una clave primaria definida, Laravel no usará ninguna
    protected $primaryKey = null;

    protected $guarded = [];

    // Si quieres proteger la vista contra escritura
    public function save(array $options = [])
    {
        throw new \Exception("Este modelo es de solo lectura.");
    }
}
