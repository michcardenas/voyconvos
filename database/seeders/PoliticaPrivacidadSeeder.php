<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pagina;
use App\Models\Seccion;
use App\Models\Contenido;

class PoliticaPrivacidadSeeder extends Seeder
{
    public function run(): void
    {
        // Página principal
        
$pagina = Pagina::firstOrCreate([
    'nombre' => 'Política de Privacidad',
]);

        // Sección HEADER

            $seccion = Seccion::firstOrCreate([
            'pagina_id' => $pagina->id,
            'slug' => 'politica-privacidad',
        ], [
            'titulo' => 'Política de Privacidad',
        ]);
  

        // Contenidos del HEADER
        $headerContenidos = [
            'titulo' => 'Tu privacidad es nuestra prioridad',
            'subtitulo' => 'Conocé cómo usamos y protegemos tus datos.',
            'badge_1' => 'Protección de datos',
            'badge_2' => 'Cifrado seguro',
            'badge_3' => 'Privacidad garantizada',
            'badge_4' => 'Sin rastreo no autorizado',
        ];

foreach ($headerContenidos as $clave => $valor) {
    Contenido::updateOrInsert([
        'seccion_id' => $seccion->id,
        'clave' => $clave,
    ], [
        'valor' => $valor,
    ]);
}


        // Sección SIDEBAR
        $sidebar = Seccion::firstOrCreate([
            'pagina_id' => $pagina->id,
            'slug' => 'sidebar-privacidad',
        ], [
            'titulo' => 'Información Complementaria',
        ]);

        // Contenidos del SIDEBAR
        $sidebarContenidos = [
            'info_titulo' => '¿Por qué importa tu privacidad?',
            'info_texto' => 'La protección de tu información personal es esencial para ofrecer una experiencia segura y confiable.',
        ];

        foreach ($sidebarContenidos as $clave => $valor) {
            Contenido::updateOrInsert([
                'seccion_id' => $sidebar->id,
                'clave' => $clave,
            ], [
                'valor' => $valor,
            ]);
        }

        // Sección CONTENIDO
        $contenido = Seccion::firstOrCreate([
            'pagina_id' => $pagina->id,
            'slug' => 'contenido-privacidad',
        ], [
            'titulo' => 'Texto Completo de la Política',
        ]);

        // Contenido general del documento
        $politicaTexto = <<<TEXT
Última actualización: Junio 2025

1. ¿QUÉ INFORMACIÓN RECOPILAMOS?
- Datos personales como nombre, email, teléfono, DNI.
- Ubicación para rutas, trayectos y validaciones.
- Datos técnicos: dispositivo, sistema, IP.
- Perfil: foto, biografía, valoraciones, preferencias.

2. ¿PARA QUÉ USAMOS TU INFORMACIÓN?
- Acceso a la plataforma.
- Conexión conductor/pasajero.
- Envío de notificaciones.
- Mejora continua y seguridad.

3. ¿CÓMO PROTEGEMOS TUS DATOS?
- Encriptación SSL.
- Acceso limitado.
- Revisiones periódicas de seguridad.

4. TUS DERECHOS
- Modificar o eliminar tus datos.
- Retirar consentimiento.
- Presentar quejas ante autoridades.

Para más información, contáctanos en soporte@voyconvos.com.
TEXT;

        Contenido::updateOrInsert([
            'seccion_id' => $contenido->id,
            'clave' => 'contenido',
        ], [
            'valor' => $politicaTexto,
        ]);
    }
}

