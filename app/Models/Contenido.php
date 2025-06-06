<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Seccion;

class Contenido extends Model
{
    use HasFactory;

    protected $fillable = ['seccion_id', 'clave', 'valor'];

    public function seccion()
    {
        return $this->belongsTo(Seccion::class);
    }

    public static function getValor(string $slug, string $clave, string $default = '')
    {
        $seccion = Seccion::where('slug', $slug)->first();

        if (!$seccion) return $default;

        $contenido = $seccion->contenidos->firstWhere('clave', $clave);

        return $contenido?->valor ?? $default;
    }

    public static function getTitulo(string $slug, string $default = '')
    {
        return Seccion::where('slug', $slug)->value('titulo') ?? $default;
    }
}
