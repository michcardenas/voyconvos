<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegistroConductor extends Model
{
    protected $table = 'registro_conductores';

    protected $fillable = [
        'user_id',
        'marca_vehiculo',
        'modelo_vehiculo',
        'anio_vehiculo',
        'patente',
        'licencia',
        'cedula',
        'cedula_verde',
        'seguro',
        'rto',
        'antecedentes',
        'estado_verificacion',
        'estado_registro',
    ];
}
