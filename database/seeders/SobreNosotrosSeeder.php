<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pagina;
use App\Models\Seccion;
use App\Models\Contenido;

class SobreNosotrosSeeder extends Seeder
{
    public function run(): void
    {
        $pagina = Pagina::firstOrCreate([
            'nombre' => 'Sobre Nosotros',
        ]);

        $seccion = Seccion::firstOrCreate([
            'pagina_id' => $pagina->id,
            'slug' => 'sobre-nosotros',
        ], [
            'titulo' => 'Quiénes somos',
        ]);

        $contenidos = [
            ['clave' => 'subtitulo', 'valor' => 'Nuestra historia y propósito'],
            ['clave' => 'parrafo_1', 'valor' => 'Somos una empresa comprometida con el bienestar de nuestros usuarios...'],
            ['clave' => 'parrafo_2', 'valor' => 'Nuestro equipo está formado por profesionales apasionados...'],
        ];

        foreach ($contenidos as $contenido) {
            Contenido::firstOrCreate([
                'seccion_id' => $seccion->id,
                'clave' => $contenido['clave'],
            ], [
                'valor' => $contenido['valor'],
            ]);
        }
    }
}
