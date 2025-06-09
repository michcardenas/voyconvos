<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pagina;
use App\Models\Seccion;
use App\Models\Contenido;
use Illuminate\Support\Facades\DB;

class PaginaInicioSeeder extends Seeder
{
    public function run(): void
    {
        // IMPORTANTE: Eliminar en el orden correcto para evitar errores de foreign key
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Contenido::truncate();
        Seccion::truncate();
        Pagina::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Crear la página
        $pagina = Pagina::create(['nombre' => 'Inicio']);

        $secciones = [
            'hero' => [
                'titulo' => 'Hero',
                'contenidos' => [
                    ['clave' => 'h1', 'valor' => 'Comparte tu viaje en auto'],
                    ['clave' => 'h2', 'valor' => 'Ahorra dinero y conecta con otras personas'],
                    ['clave' => 'btn_buscar', 'valor' => 'Buscar viaje'],
                    ['clave' => 'btn_publicar', 'valor' => 'Publicar viaje'],
                    ['clave' => 'btn_publicar_main', 'valor' => 'Publicar mi viaje'],
                    ['clave' => 'ahorro_texto', 'valor' => 'Ahorra hasta'],
                    ['clave' => 'ahorro_valor', 'valor' => '70%'],
                    ['clave' => 'ahorro_sufijo', 'valor' => 'en tus viajes'],
                    ['clave' => 'como_funciona', 'valor' => '¿Cómo funciona?'],
                    ['clave' => 'background', 'valor' => asset('img/fondo.jpg')],
                ]
            ],
            'busqueda' => [
                'titulo' => 'Formulario de Búsqueda',
                'contenidos' => [
                    // Labels del formulario
                    ['clave' => 'label_origen', 'valor' => 'Origen'],
                    ['clave' => 'placeholder_origen', 'valor' => 'Selecciona origen'],
                    ['clave' => 'label_destino', 'valor' => 'Destino'],
                    ['clave' => 'placeholder_destino', 'valor' => 'Selecciona destino'],
                    ['clave' => 'label_fecha', 'valor' => 'Fecha de viaje'],
                    ['clave' => 'label_pasajeros', 'valor' => 'Pasajeros'],
                    ['clave' => 'btn_buscar_viajes', 'valor' => 'Buscar viajes'],
                    ['clave' => 'btn_intercambiar_tooltip', 'valor' => 'Intercambiar origen y destino'],
                    
                    // Opciones de origen (ciudades colombianas)
                    ['clave' => 'origenes', 'valor' => 'Bogotá,Medellín,Cali,Barranquilla,Cartagena,Bucaramanga,Pereira,Santa Marta,Manizales,Pasto'],
                    
                    // Opciones de destino 
                    ['clave' => 'destinos', 'valor' => 'Bogotá,Medellín,Cali,Barranquilla,Cartagena,Bucaramanga,Pereira,Santa Marta,Manizales,Pasto,Armenia,Villavicencio,Ibagué,Neiva,Valledupar'],
                    
                    // Opciones de pasajeros
                    ['clave' => 'max_pasajeros', 'valor' => '6'],
                ]
            ],
            'viajes' => [
                'titulo' => 'Viajes disponibles',
                'contenidos' => [
                    // Títulos principales
                    ['clave' => 'titulo', 'valor' => 'Viajes Disponibles'],
                    ['clave' => 'descripcion', 'valor' => 'Encuentra tu viaje ideal entre las principales ciudades'],
                    
                    // Viaje 1
                    ['clave' => 'origen_1', 'valor' => 'Bogotá'],
                    ['clave' => 'destino_1', 'valor' => 'Medellín'],
                    ['clave' => 'tiempo_1', 'valor' => '6h 30min'],
                    ['clave' => 'img_1', 'valor' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=80&h=80&fit=crop&crop=face'],
                    ['clave' => 'conductor_1', 'valor' => 'Carlos M.'],
                    ['clave' => 'rating_1', 'valor' => '4.8'],
                    ['clave' => 'fecha_1', 'valor' => 'Hoy 15:30'],
                    ['clave' => 'lugares_1', 'valor' => '2 lugares'],
                    ['clave' => 'precio_1', 'valor' => '$45.000'],
                    
                    // Viaje 2
                    ['clave' => 'origen_2', 'valor' => 'Cali'],
                    ['clave' => 'destino_2', 'valor' => 'Bogotá'],
                    ['clave' => 'tiempo_2', 'valor' => '4h 45min'],
                    ['clave' => 'img_2', 'valor' => 'https://images.unsplash.com/photo-1494790108755-2616b612b8fb?w=80&h=80&fit=crop&crop=face'],
                    ['clave' => 'conductor_2', 'valor' => 'María G.'],
                    ['clave' => 'rating_2', 'valor' => '4.9'],
                    ['clave' => 'fecha_2', 'valor' => 'Hoy 18:00'],
                    ['clave' => 'lugares_2', 'valor' => '3 lugares'],
                    ['clave' => 'precio_2', 'valor' => '$55.000'],
                    
                    // Viaje 3
                    ['clave' => 'origen_3', 'valor' => 'Medellín'],
                    ['clave' => 'destino_3', 'valor' => 'Cartagena'],
                    ['clave' => 'tiempo_3', 'valor' => '8h 15min'],
                    ['clave' => 'img_3', 'valor' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=80&h=80&fit=crop&crop=face'],
                    ['clave' => 'conductor_3', 'valor' => 'Diego F.'],
                    ['clave' => 'rating_3', 'valor' => '4.7'],
                    ['clave' => 'fecha_3', 'valor' => 'Mañana 08:00'],
                    ['clave' => 'lugares_3', 'valor' => '1 lugar'],
                    ['clave' => 'precio_3', 'valor' => '$75.000'],
                    
                    // Viaje 4
                    ['clave' => 'origen_4', 'valor' => 'Barranquilla'],
                    ['clave' => 'destino_4', 'valor' => 'Santa Marta'],
                    ['clave' => 'tiempo_4', 'valor' => '1h 30min'],
                    ['clave' => 'img_4', 'valor' => 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=80&h=80&fit=crop&crop=face'],
                    ['clave' => 'conductor_4', 'valor' => 'Alejandro R.'],
                    ['clave' => 'rating_4', 'valor' => '5.0'],
                    ['clave' => 'fecha_4', 'valor' => 'Hoy 20:30'],
                    ['clave' => 'lugares_4', 'valor' => '4 lugares'],
                    ['clave' => 'precio_4', 'valor' => '$25.000'],
                    
                    // Botón reservar
                    ['clave' => 'btn_reservar', 'valor' => 'Reservar']
                ]
            ],
            'cta' => [
                'titulo' => 'Call to Action',
                'contenidos' => [
                    ['clave' => 'titulo', 'valor' => '¿Buscas más opciones?'],
                    ['clave' => 'boton', 'valor' => 'Ver Todos los Viajes'],
                ]
            ],
            'features' => [
                'titulo' => 'Features',
                'contenidos' => [
                    ['clave' => 'titulo', 'valor' => '¿Por qué elegir VoyConVos?'],
                    ['clave' => 'feature_1_icon', 'valor' => 'fa-dollar-sign'],
                    ['clave' => 'feature_1_titulo', 'valor' => 'Ahorra en cada viaje'],
                    ['clave' => 'feature_1_texto', 'valor' => 'Comparte los gastos de gasolina y peajes con otros viajeros'],
                    ['clave' => 'feature_2_icon', 'valor' => 'fa-users'],
                    ['clave' => 'feature_2_titulo', 'valor' => 'Conoce nuevas personas'],
                    ['clave' => 'feature_2_texto', 'valor' => 'Conecta con gente que comparte tu ruta e intereses'],
                    ['clave' => 'feature_3_icon', 'valor' => 'fa-leaf'],
                    ['clave' => 'feature_3_titulo', 'valor' => 'Cuida el medio ambiente'],
                    ['clave' => 'feature_3_texto', 'valor' => 'Reduce la contaminación compartiendo vehículo'],
                ]
            ],
            'slogan' => [
                'titulo' => 'Slogan',
                'contenidos' => [
                    ['clave' => 'titulo', 'valor' => 'Conduce. Comparte. Ahorra.'],
                    ['clave' => 'boton', 'valor' => 'Publica un viaje'],
                ]
            ],
            'contacto' => [
                'titulo' => 'Contacto',
                'contenidos' => [
                    ['clave' => 'titulo', 'valor' => '¿Tienes alguna pregunta?'],
                    ['clave' => 'descripcion', 'valor' => 'Estamos aquí para ayudarte. Contáctanos y te responderemos lo antes posible.'],
                    ['clave' => 'email', 'valor' => 'contacto@voyconvos.com'],
                    ['clave' => 'telefono', 'valor' => '+57 1 234 5678'],
                    ['clave' => 'horario', 'valor' => 'Lun - Vie: 8:00 AM - 6:00 PM'],
                    ['clave' => 'social_facebook', 'valor' => 'https://facebook.com'],
                    ['clave' => 'social_twitter', 'valor' => 'https://twitter.com'],
                    ['clave' => 'social_instagram', 'valor' => 'https://instagram.com'],
                    ['clave' => 'social_whatsapp', 'valor' => 'https://wa.me/573123456789'],
                ]
            ],
            'contacto_form' => [
                'titulo' => 'Formulario de Contacto',
                'contenidos' => [
                    // Títulos del formulario
                    ['clave' => 'titulo', 'valor' => 'Envíanos un mensaje'],
                    ['clave' => 'subtitulo', 'valor' => 'Estamos aquí para ayudarte con cualquier duda o comentario'],
                    
                    // Labels de los campos
                    ['clave' => 'label_nombre', 'valor' => 'Nombre completo'],
                    ['clave' => 'label_email', 'valor' => 'Correo electrónico'],
                    ['clave' => 'label_telefono', 'valor' => 'Teléfono (opcional)'],
                    ['clave' => 'label_asunto', 'valor' => 'Asunto'],
                    ['clave' => 'placeholder_asunto', 'valor' => 'Selecciona un tema'],
                    ['clave' => 'label_mensaje', 'valor' => 'Tu mensaje'],
                    
                    // Opciones de asunto
                    ['clave' => 'asuntos', 'valor' => 'Consulta General,Problema con un viaje,Sugerencia,Reportar un usuario,Problema técnico,Colaboraciones,Prensa'],
                    
                    // Términos y botones
                    ['clave' => 'acepto_terminos', 'valor' => 'Acepto los <a href="/terminos" target="_blank">términos y condiciones</a> y la <a href="/privacidad" target="_blank">política de privacidad</a>'],
                    ['clave' => 'btn_enviar', 'valor' => 'Enviar mensaje'],
                    
                    // Mensajes de estado
                    ['clave' => 'msg_exito', 'valor' => 'Gracias por contactarnos. Te responderemos en las próximas 24 horas.'],
                    ['clave' => 'msg_error', 'valor' => 'Por favor, completa todos los campos obligatorios y acepta los términos.'],
                    ['clave' => 'msg_enviando', 'valor' => 'Enviando mensaje...'],
                    
                    // Placeholders adicionales
                    ['clave' => 'placeholder_nombre', 'valor' => 'Ingresa tu nombre completo'],
                    ['clave' => 'placeholder_email', 'valor' => 'ejemplo@correo.com'],
                    ['clave' => 'placeholder_telefono', 'valor' => '+57 300 123 4567'],
                    ['clave' => 'placeholder_mensaje', 'valor' => 'Describe tu consulta o comentario...'],
                ]
            ],
        ];

        foreach ($secciones as $slug => $datos) {
            echo "Creando sección: $slug\n"; // Para debug
            
            $seccion = Seccion::create([
                'pagina_id' => $pagina->id,
                'slug' => $slug,
                'titulo' => $datos['titulo'],
            ]);

            foreach ($datos['contenidos'] as $contenido) {
                Contenido::create([
                    'seccion_id' => $seccion->id,
                    'clave' => $contenido['clave'],
                    'valor' => $contenido['valor'],
                ]);
            }
            
            echo "Sección $slug creada con " . count($datos['contenidos']) . " contenidos\n"; // Para debug
        }
        
        echo "Seeder completado exitosamente!\n";
    }
}