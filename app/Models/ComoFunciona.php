<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComoFunciona extends Model
{
    protected $table = 'como_funciona';

    protected $fillable = ['titulo', 'contenido'];
}
