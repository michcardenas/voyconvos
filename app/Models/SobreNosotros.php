<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SobreNosotros extends Model
{
    protected $table = 'sobre_nosotros';

    protected $fillable = ['titulo', 'contenido'];
}
