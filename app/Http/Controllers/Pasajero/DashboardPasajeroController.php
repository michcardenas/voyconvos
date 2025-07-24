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

    // ⭐ OBTENER CALIFICACIONES DIRECTAMENTE (SIN VISTAS)
    $calificacionesUsuarios = $this->obtenerCalificacionesUsuarios();
    $calificacionesDetalle = $this->obtenerCalificacionesDetalle();

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

    \Log::info('Dashboard cargado exitosamente - PRODUCCIÓN', [
        'viajes_count' => $viajesDisponibles->count(),
        'calificaciones_usuarios_count' => $calificacionesUsuarios->count(),
        'calificaciones_detalle_count' => $calificacionesDetalle->count(),
        'user_id' => auth()->id(),
        'environment' => config('app.env'),
        'database' => config('database.connections.mysql.database')
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

/**
 * 📊 Obtener calificaciones usuarios DIRECTAMENTE
 */
private function obtenerCalificacionesUsuarios()
{
    try {
        $result = collect(DB::select("
            SELECT 
                c.usuario_id,
                c.tipo,
                COUNT(*) as total_calificaciones,
                ROUND(AVG(c.calificacion), 2) as promedio_calificacion
            FROM calificacions c
            GROUP BY c.usuario_id, c.tipo
            HAVING COUNT(*) > 0
        "));

        \Log::info('✅ Calificaciones usuarios obtenidas exitosamente', [
            'method' => 'consulta_directa',
            'count' => $result->count(),
            'sample' => $result->first()
        ]);

        return $result;

    } catch (\Exception $e) {
        \Log::error('❌ Error al obtener calificaciones usuarios', [
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]);
        
        // Retornar colección vacía como fallback
        return collect();
    }
}

/**
 * 📝 Obtener calificaciones detalle DIRECTAMENTE
 */
private function obtenerCalificacionesDetalle()
{
    try {
        $result = collect(DB::select("
            SELECT 
                c.id as calificacion_id,
                c.calificacion,
                c.comentario,
                c.tipo,
                c.created_at as fecha_calificacion,
                c.usuario_id as usuario_calificado_id,
                u.name as nombre_usuario_calificado,
                r.id as reserva_id,
                r.fecha_reserva,
                r.estado as estado_reserva,
                r.cantidad_puestos,
                r.total_pagado,
                v.id as viaje_id,
                v.fecha_salida,
                v.hora_salida,
                v.origen as origen_direccion,
                v.destino as destino_direccion,
                v.conductor_id,
                uc.name as nombre_conductor
            FROM calificacions c
            INNER JOIN users u ON c.usuario_id = u.id
            INNER JOIN reservas r ON c.reserva_id = r.id
            INNER JOIN viajes v ON r.viaje_id = v.id
            INNER JOIN users uc ON v.conductor_id = uc.id
            ORDER BY c.created_at DESC
            LIMIT 20
        "));

        \Log::info('✅ Calificaciones detalle obtenidas exitosamente', [
            'method' => 'consulta_directa',
            'count' => $result->count(),
            'sample' => $result->first()
        ]);

        return $result;

    } catch (\Exception $e) {
        \Log::error('❌ Error al obtener calificaciones detalle', [
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]);
        
        // Retornar colección vacía como fallback
        return collect();
    }
}

    public function verViajesDisponibles()
    {
        $viajes = Viaje::where('fecha', '>=', now()) // solo viajes futuros
            ->with('conductor')
            ->get();

        return view('pasajero.viajes.lista', compact('viajes'));
    }

}
