<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pagina;
use App\Models\Seccion;
use App\Models\Contenido;

class TerminosCondicionesSeeder extends Seeder
{
    public function run(): void
    {
        // Página
        $pagina = Pagina::where('slug', 'terminos-condiciones')
            ->orWhere('nombre', 'Términos y Condiciones')
            ->first();

        if (!$pagina) {
            $pagina = Pagina::create([
                'slug' => 'terminos-condiciones',
                'nombre' => 'Términos y Condiciones',
            ]);
        }

        // Sección: header-terminos
        $seccionHeader = Seccion::firstOrCreate([
            'pagina_id' => $pagina->id,
            'slug' => 'header-terminos',
        ], [
            'titulo' => 'Términos y Condiciones',
        ]);

        $headerDatos = [
            'titulo' => 'Términos y Condiciones de Uso',
            'subtitulo' => 'Leé cuidadosamente este documento antes de usar VoyConVos',
        ];

        foreach ($headerDatos as $clave => $valor) {
            Contenido::firstOrCreate([
                'seccion_id' => $seccionHeader->id,
                'clave' => $clave,
            ], [
                'valor' => $valor,
            ]);
        }

        // Sección: contenido-terminos
        $seccionContenido = Seccion::firstOrCreate([
            'pagina_id' => $pagina->id,
            'slug' => 'contenido-terminos',
        ], [
            'titulo' => 'Contenido legal',
        ]);

        Contenido::firstOrCreate([
            'seccion_id' => $seccionContenido->id,
            'clave' => 'contenido',
        ], [
            'valor' => <<<TEXT
                    1. Aceptación de términos  
                    Al acceder y utilizar VoyConVos, aceptás estar sujeto a estos términos.

                    2. Servicios ofrecidos  
                    VoyConVos permite conectar pasajeros con conductores de forma colaborativa.

                    3. Responsabilidad  
                    No nos hacemos responsables por acuerdos o conductas fuera de la plataforma.

                    4. Privacidad  
                    Tus datos están protegidos según nuestra política de privacidad vigente.

                    5. Cambios  
                    Nos reservamos el derecho de modificar estos términos en cualquier momento.
                    TEXT
        ]);
    }
}
