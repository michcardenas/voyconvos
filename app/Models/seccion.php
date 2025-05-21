<?php

namespace App\Models;
//Seccion
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Contenido;
use App\Models\Pagina;

class Seccion extends Model
{
    use HasFactory;

    protected $table = 'secciones'; 
    protected $fillable = ['pagina_id', 'slug', 'titulo'];

    public function pagina()
    {
        return $this->belongsTo(Pagina::class);
    }

    public function contenidos()
    {
        return $this->hasMany(Contenido::class, 'seccion_id');
    }
}
