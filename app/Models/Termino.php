<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Termino extends Model
{
    protected $table = 'terminos';

    // Permitir asignación masiva del campo 'contenido'
    protected $fillable = ['contenido'];
}
