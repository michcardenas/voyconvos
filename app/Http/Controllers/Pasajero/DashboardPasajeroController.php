<?php

namespace App\Http\Controllers\pasajero;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Viaje;
use Illuminate\Support\Facades\DB;
use App\Models\RegistroConductor;
use App\Models\Reserva;
use Illuminate\Support\Facades\Auth;

class DashboardPasajeroController extends Controller
{
 public function index() 
{
    // 🚗 LÓGICA ORIGINAL
    $viajesDisponibles = Viaje::with('conductor')
        ->where('estado', 'pendiente')
        ->where('fecha_salida', '>=', now())
        ->where('puestos_disponibles', '>', 0)
        ->orderBy('fecha_salida', 'asc')
        ->get();

    // ⭐ USANDO TUS VISTAS SQL EXISTENTES
    try {
        // 📊 Vista de promedios de usuarios (la que SÍ funciona)
        $calificacionesUsuarios = collect(DB::select("
            SELECT usuario_id, tipo, total_calificaciones, promedio_calificacion
            FROM voyconvos.vista_calificaciones_usuarios
        "));

        // 📝 Vista de detalles de calificaciones (la que SÍ funciona)
        $calificacionesDetalle = collect(DB::select("
            SELECT 
                calificacion_id, calificacion, comentario, tipo, fecha_calificacion, 
                usuario_calificado_id, nombre_usuario_calificado, reserva_id, 
                fecha_reserva, estado_reserva, cantidad_puestos, total_pagado, 
                viaje_id, fecha_salida, hora_salida, origen_direccion, destino_direccion, 
                conductor_id, nombre_conductor
            FROM voyconvos.vista_calificaciones_detalle
            ORDER BY fecha_calificacion DESC
            LIMIT 20
        "));

        \Log::info('Vistas SQL consultadas exitosamente', [
            'usuarios_count' => $calificacionesUsuarios->count(),
            'detalles_count' => $calificacionesDetalle->count(),
            'sample_usuario' => $calificacionesUsuarios->first(),
            'sample_detalle' => $calificacionesDetalle->first()
        ]);

    } catch (\Exception $e) {
        \Log::error('Error al consultar vistas SQL: ' . $e->getMessage(), [
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'sql_error' => $e->getMessage()
        ]);
        
        // Si las vistas fallan, usar consultas directas como fallback
        $calificacionesUsuarios = collect(DB::select("
            SELECT 
                c.usuario_id,
                c.tipo,
                COUNT(*) as total_calificaciones,
                ROUND(AVG(c.calificacion), 2) as promedio_calificacion
            FROM calificacions c
            GROUP BY c.usuario_id, c.tipo
            HAVING COUNT(*) > 0
        "));

        $calificacionesDetalle = collect();
    }

    // 🔄 ENRIQUECER VIAJES CON CALIFICACIONES DE CONDUCTORES
    $viajesDisponibles->each(function($viaje) use ($calificacionesUsuarios) {
        $calificacionConductor = $calificacionesUsuarios
            ->where('usuario_id', $viaje->conductor_id)
            ->where('tipo', 'pasajero_a_conductor')
            ->first();
        
        $viaje->conductor_calificacion = $calificacionConductor 
            ? $calificacionConductor->promedio_calificacion 
            : 3.0;
            
        $viaje->conductor_total_calificaciones = $calificacionConductor 
            ? $calificacionConductor->total_calificaciones 
            : 0;
    });

    // 📊 ESTADÍSTICAS
    $estadisticasCalificaciones = [
        'total_conductores_calificados' => $calificacionesUsuarios
            ->where('tipo', 'pasajero_a_conductor')
            ->where('total_calificaciones', '>', 0)
            ->count(),
            
        'promedio_general_conductores' => round($calificacionesUsuarios
            ->where('tipo', 'pasajero_a_conductor')
            ->where('total_calificaciones', '>', 0)
            ->avg('promedio_calificacion'), 2) ?: 0,
            
        'total_calificaciones_recientes' => $calificacionesDetalle->count(),
        
        'calificaciones_positivas' => $calificacionesDetalle
            ->where('calificacion', '>=', 4)
            ->count()
    ];

    // 🏆 TOP 5 CONDUCTORES
    $topConductores = $calificacionesUsuarios
        ->where('tipo', 'pasajero_a_conductor')
        ->where('total_calificaciones', '>=', 2)
        ->sortByDesc('promedio_calificacion')
        ->take(5);

    // 💬 COMENTARIOS DESTACADOS
    $comentariosDestacados = $calificacionesDetalle
        ->where('calificacion', 5)
        ->whereNotNull('comentario')
        ->where('comentario', '!=', '')
        ->take(3);

    // 👤 CALIFICACIONES DEL USUARIO ACTUAL
    $misCalificaciones = null;
    if (auth()->check()) {
        $misCalificaciones = $calificacionesUsuarios
            ->where('usuario_id', auth()->id())
            ->keyBy('tipo');
    }

    // 🔍 DEBUG ESPECÍFICO PARA TU CASO
        \Log::info('Debug específico usuario 14', [
            'todas_sus_calificaciones' => $calificacionesUsuarios->where('usuario_id', 14)->values(),
            'detalles_usuario_14' => $calificacionesDetalle->where('usuario_calificado_id', 14)->values(),
            'total_registros_vista_usuarios' => $calificacionesUsuarios->count(),
            'total_registros_vista_detalle' => $calificacionesDetalle->count()
        ]);
    

    return view('pasajero.dashboard', compact(
        'viajesDisponibles',
        'calificacionesUsuarios',
        'calificacionesDetalle',
        'estadisticasCalificaciones',
        'topConductores',
        'comentariosDestacados',
        'misCalificaciones'
    ));
}

    public function verViajesDisponibles()
    {
        $viajes = Viaje::where('fecha', '>=', now()) // solo viajes futuros
            ->with('conductor')
            ->get();

        return view('pasajero.viajes.lista', compact('viajes'));
    }

}
