<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pagina extends Model
{
    use HasFactory;

    protected $fillable = ['nombre'];

    public function secciones()
    {
        return $this->hasMany(\App\Models\Seccion::class, 'pagina_id');
    }
}
