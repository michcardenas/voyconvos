<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pagina;
use App\Models\Seccion;
use App\Models\Contenido;

class PreguntasFrecuentesSeeder extends Seeder
{
    public function run(): void
    {
        // Página
        $pagina = Pagina::firstOrCreate(
            ['slug' => 'faq'],
            ['nombre' => 'Preguntas Frecuentes']
        );

        // Sección header
        $seccion = Seccion::firstOrCreate(
            ['pagina_id' => $pagina->id, 'slug' => 'faq-header'],
            ['titulo' => 'Preguntas Frecuentes']
        );

        $contenidosHeader = [
            'titulo' => '¿Tenés preguntas?',
            'subtitulo' => 'Acá te resolvemos las dudas más comunes de nuestros usuarios',
            'cantidad' => '3',
            'respuesta_inmediata' => 'Respuestas automáticas disponibles 24/7',
            'soporte' => 'Soporte humano en menos de 24 horas',
        ];

        foreach ($contenidosHeader as $clave => $valor) {
            Contenido::firstOrCreate([
                'seccion_id' => $seccion->id,
                'clave' => $clave,
            ], [
                'valor' => $valor,
            ]);
        }

        // Preguntas (cada una como sección tipo faq-1, faq-2, etc.)
        $preguntas = [
            ['slug' => 'faq-1', 'pregunta' => '¿Cómo me registro en VoyConVos?', 'respuesta' => 'Podés registrarte gratis desde el botón "Crear cuenta" en la parte superior.'],
            ['slug' => 'faq-2', 'pregunta' => '¿Puedo cancelar un viaje reservado?', 'respuesta' => 'Sí, desde tu panel de usuario podés cancelar con anticipación.'],
            ['slug' => 'faq-3', 'pregunta' => '¿Cómo contacto al conductor?', 'respuesta' => 'Una vez reservado el viaje, se habilita el chat interno para comunicarte.'],
        ];

        foreach ($preguntas as $faq) {
            $s = Seccion::firstOrCreate([
                'pagina_id' => $pagina->id,
                'slug' => $faq['slug'],
            ]);

            Contenido::firstOrCreate([
                'seccion_id' => $s->id,
                'clave' => 'pregunta',
            ], [
                'valor' => $faq['pregunta'],
            ]);

            Contenido::firstOrCreate([
                'seccion_id' => $s->id,
                'clave' => 'respuesta',
            ], [
                'valor' => $faq['respuesta'],
            ]);
        }
    }
}
