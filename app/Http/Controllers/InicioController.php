<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contenido;
use App\Models\Viaje;

class InicioController extends Controller
{
public function index() 
{
    // Obtener metadatos de la página inicio
    $metadatos = \App\Models\MetadatoPagina::where('pagina', 'inicio')->first();
        
    // Obtener viajes para mostrar en la home (simplificado para que muestre todos)
    $viajesDestacados = Viaje::with(['conductor'])
        ->where('estado', '!=', 'cancelado') // Excluir viajes cancelados
        ->orderBy('created_at', 'desc') // Los más recientes primero
        ->limit(6)
        ->get();

    // Formatear direcciones solo si existen los métodos
    if (method_exists($this, 'formatearDireccion')) {
        $viajesDestacados->each(function ($viaje) {
            $viaje->origen_direccion = $this->formatearDireccion($viaje->origen_direccion);
            $viaje->destino_direccion = $this->formatearDireccion($viaje->destino_direccion);
        });
    }
            
    // Obtener datos de formularios desde el seeder
    $origenes = $this->getOrigenes();
    $destinos = $this->getDestinos();
    $asuntos = $this->getAsuntos();

    return view('welcome', [
        'metadatos' => $metadatos,
        'viajesDestacados' => $viajesDestacados,
        'origenes' => $origenes,
        'destinos' => $destinos,
        'asuntos' => $asuntos,
                        
        // Variables del formulario de contacto
        'titulo' => $this->getContenido('contacto_form', 'titulo', 'Envíanos un mensaje'),
        'subtitulo' => $this->getContenido('contacto_form', 'subtitulo', 'Estamos aquí para ayudarte'),
        'acepto' => $this->getContenido('contacto_form', 'acepto_terminos', 'Acepto los términos y condiciones'),
        'boton' => $this->getContenido('contacto_form', 'btn_enviar', 'Enviar mensaje'),
        'msg_ok' => $this->getContenido('contacto_form', 'msg_exito', 'Mensaje enviado correctamente'),
    ]);
}
    /**
     * Obtener contenido con valor por defecto
     */
    private function getContenido($seccion, $clave, $default = '')
    {
        try {
            return Contenido::getValor($seccion, $clave) ?: $default;
        } catch (\Exception $e) {
            return $default;
        }
    }

    /**
     * Obtener lista de ciudades de origen
     */
    private function getOrigenes()
    {
        try {
            $origenes = Contenido::getValor('busqueda', 'origenes');
            if ($origenes) {
                return array_map('trim', explode(',', $origenes));
            }
        } catch (\Exception $e) {
            // Log del error si es necesario
        }

        // Valores por defecto si no hay datos en BD
        return [
            'Bogotá',
            'Medellín',
            'Cali',
            'Barranquilla',
            'Cartagena',
            'Bucaramanga',
            'Pereira',
            'Santa Marta',
            'Manizales',
            'Pasto'
        ];
    }

    /**
     * Obtener lista de ciudades de destino
     */
    private function getDestinos()
    {
        try {
            $destinos = Contenido::getValor('busqueda', 'destinos');
            if ($destinos) {
                return array_map('trim', explode(',', $destinos));
            }
        } catch (\Exception $e) {
            // Log del error si es necesario
        }

        // Valores por defecto si no hay datos en BD
        return [
            'Bogotá',
            'Medellín',
            'Cali',
            'Barranquilla',
            'Cartagena',
            'Bucaramanga',
            'Pereira',
            'Santa Marta',
            'Manizales',
            'Pasto',
            'Armenia',
            'Villavicencio',
            'Ibagué',
            'Neiva',
            'Valledupar'
        ];
    }

    /**
     * Obtener lista de asuntos del formulario de contacto
     */
    private function getAsuntos()
    {
        try {
            $asuntos = Contenido::getValor('contacto_form', 'asuntos');
            if ($asuntos) {
                return array_map('trim', explode(',', $asuntos));
            }
        } catch (\Exception $e) {
            // Log del error si es necesario
        }

        // Valores por defecto si no hay datos en BD
        return [
            'Consulta General',
            'Problema con un viaje',
            'Sugerencia',
            'Reportar un usuario',
            'Problema técnico',
            'Colaboraciones',
            'Prensa'
        ];
    }
}