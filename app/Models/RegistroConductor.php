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
        'verificacion_conductor',
        'estado_registro',
        'numero_puestos',
        'verificar_pasajeros',
        'consumo_por_galon',

    ];

    protected $casts = [
        'verificacion_conductor' => 'boolean',
        'verificar_pasajeros' => 'boolean',
    ];
}
