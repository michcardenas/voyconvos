<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfiguracionAdmin extends Model
{
    protected $table = 'configuracion_admin'; // Nombre exacto de la tabla

    protected $primaryKey = 'id_configuracion'; // Clave primaria personalizada

    public $timestamps = true; // Para usar created_at y updated_at

    protected $fillable = [
        'nombre',
        'valor',
    ];
}
