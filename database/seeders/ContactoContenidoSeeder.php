<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pagina;
use App\Models\Seccion;
use App\Models\Contenido;

class ContactoContenidoSeeder extends Seeder
{
    public function run(): void
    {
        $pagina = Pagina::firstOrCreate([
            'nombre' => 'Contacto',
            'slug' => 'contacto',
        ]);

        // HERO CONTACTO
        $seccionHero = Seccion::firstOrCreate([
            'pagina_id' => $pagina->id,
            'slug' => 'hero-contacto',
        ], [
            'titulo' => '¿En qué podemos ayudarte?',
        ]);

        Contenido::updateOrCreate([
            'seccion_id' => $seccionHero->id,
            'clave' => 'titulo',
        ], [
            'valor' => 'Contáctanos',
        ]);

        Contenido::updateOrCreate([
            'seccion_id' => $seccionHero->id,
            'clave' => 'texto',
        ], [
            'valor' => '¿Tenés dudas, sugerencias o querés saludarnos? Completá el formulario y nos pondremos en contacto.',
        ]);

        // INFO CONTACTO
        $seccionInfo = Seccion::firstOrCreate([
            'pagina_id' => $pagina->id,
            'slug' => 'info-contacto',
        ], [
            'titulo' => 'Datos de contacto',
        ]);

        $info = [
            'ubicacion' => 'Calle Falsa 123, Buenos Aires, Argentina',
            'email' => 'contacto@voyconvos.com',
            'telefono' => '+54 9 11 1234-5678',
            'horario' => 'Lunes a Viernes de 9:00 a 18:00',
        ];

        foreach ($info as $clave => $valor) {
            Contenido::updateOrCreate([
                'seccion_id' => $seccionInfo->id,
                'clave' => $clave,
            ], [
                'valor' => $valor,
            ]);
        }

        // FORM CONTACTO
        $seccionForm = Seccion::firstOrCreate([
            'pagina_id' => $pagina->id,
            'slug' => 'form-contacto',
        ], [
            'titulo' => 'Formulario de contacto',
        ]);

        $form = [
            'titulo' => 'Envíanos un mensaje',
            'label_nombre' => 'Nombre completo',
            'label_email' => 'Correo electrónico',
            'label_asunto' => 'Asunto',
            'label_mensaje' => 'Mensaje',
            'placeholder' => 'Escribí tu mensaje aquí...',
            'boton' => 'Enviar mensaje',
        ];

        foreach ($form as $clave => $valor) {
            Contenido::updateOrCreate([
                'seccion_id' => $seccionForm->id,
                'clave' => $clave,
            ], [
                'valor' => $valor,
            ]);
        }
    }
}
