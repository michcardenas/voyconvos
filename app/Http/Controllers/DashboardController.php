<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Viaje;
use App\Models\RegistroConductor;
use App\Models\Reserva;

class DashboardController extends Controller
{
public function index(Request $request)
{
    $user = Auth::user();

    // 🚧 Si es conductor pero NO tiene vehículo registrado, redirigir
    if ($user->hasRole('conductor') && !RegistroConductor::where('user_id', $user->id)->exists()) {
        return redirect()->route('conductor.registro.form')->with('info', 'Debes completar el registro de tu vehículo antes de continuar.');
    }

    // 🚗 Viajes como conductor
    $viajesConductorQuery = Viaje::where('conductor_id', $user->id)
        ->where('activo', 1);

    // Aplicar filtros simples
    if ($request->filled('estado') && $request->estado !== 'todos') {
        $viajesConductorQuery->where('estado', $request->estado);
    }

    if ($request->filled('fecha_desde')) {
        $viajesConductorQuery->where('fecha_salida', '>=', $request->fecha_desde);
    }

    if ($request->filled('fecha_hasta')) {
        $viajesConductorQuery->where('fecha_salida', '<=', $request->fecha_hasta);
    }

    if ($request->filled('buscar')) {
        $buscar = $request->buscar;
        $viajesConductorQuery->where(function ($query) use ($buscar) {
            $query->where('origen_direccion', 'LIKE', '%' . $buscar . '%')
                  ->orWhere('destino_direccion', 'LIKE', '%' . $buscar . '%');
        });
    }

    $viajesConductor = $viajesConductorQuery->orderBy('created_at', 'desc')->get();

    // 🧍 Viajes como pasajero (futuro)
    $viajesPasajero = collect(); // por ahora vacío

    // 🧮 Combinar resultados
    $viajesProximosList = $viajesConductor->merge($viajesPasajero)->unique('id');

    $viajeIds = $viajesConductor->pluck('id');

    // 🔔 Reservas nuevas para viajes de este conductor
    $reservasNoVistas = Reserva::whereIn('viaje_id', $viajeIds)
        ->where('notificado', false)
        ->count();

    $reservasDetalles = Reserva::with(['viaje', 'user'])
        ->whereIn('viaje_id', $viajeIds)
        ->where('notificado', false)
        ->latest()
        ->take(5)
        ->get();

    $notificaciones = Reserva::whereHas('viaje', function ($query) use ($user) {
        $query->where('conductor_id', $user->id);
    })->where('notificado', false)->count();

    // 💬 Obtener calificaciones/comentarios detallados del usuario (solo como conductor)
    $calificacionesDetalle = \DB::table('vista_calificaciones_detalle')
        ->where('conductor_id', $user->id)
        ->where('tipo', 'pasajero_a_conductor')
        ->orderBy('fecha_calificacion', 'desc')
        ->get();

    // 📊 Obtener resumen de calificaciones del usuario desde vista_calificaciones_usuarios
    $resumenCalificacionesUsuario = \DB::table('vista_calificaciones_usuarios')
        ->where('usuario_id', $user->id)
        ->first();

    // Si no tiene calificaciones en el resumen, crear objeto vacío con valores por defecto
    if (!$resumenCalificacionesUsuario) {
        $resumenCalificacionesUsuario = (object) [
            'usuario_id' => $user->id,
            'tipo' => null,
            'total_calificaciones' => 0,
            'promedio_calificacion' => 0
        ];
    }

    // 🐛 Debug para verificar los datos
    \Log::info('DEBUG Calificaciones Dashboard', [
        'user_id' => $user->id,
        'total_calificaciones_encontradas' => $calificacionesDetalle->count(),
        'primera_calificacion' => $calificacionesDetalle->first(),
        'consulta_prueba_sin_filtros' => \DB::table('vista_calificaciones_detalle')->count(),
        'consulta_prueba_solo_conductor' => \DB::table('vista_calificaciones_detalle')->where('conductor_id', $user->id)->count(),
        'consulta_prueba_solo_tipo' => \DB::table('vista_calificaciones_detalle')->where('tipo', 'pasajero_a_conductor')->count(),
        'resumen_calificaciones_usuario' => $resumenCalificacionesUsuario
    ]);

    // 📊 Resumen de calificaciones del usuario
    $resumenCalificaciones = [
        'total_calificaciones' => $calificacionesDetalle->count(),
        'promedio_calificacion' => $calificacionesDetalle->avg('calificacion') ?? 0,
        'calificaciones_recientes' => $calificacionesDetalle->take(5),
        'calificaciones_5_estrellas' => $calificacionesDetalle->where('calificacion', 5)->count(),
        'calificaciones_4_estrellas' => $calificacionesDetalle->where('calificacion', 4)->count(),
        'calificaciones_3_estrellas' => $calificacionesDetalle->where('calificacion', 3)->count(),
        'calificaciones_2_estrellas' => $calificacionesDetalle->where('calificacion', 2)->count(),
        'calificaciones_1_estrella' => $calificacionesDetalle->where('calificacion', 1)->count(),
    ];

    return view('dashboard', [
        'viajesProximosList' => $viajesProximosList,
        'totalViajes' => $viajesProximosList->count(),
        'viajesProximos' => $viajesProximosList->count(),
        'viajesRealizados' => 0,
        'reservasNoVistas' => $reservasNoVistas,
        'notificaciones' => $notificaciones,
        'reservasDetalles' => $reservasDetalles,
        'calificacionesDetalle' => $calificacionesDetalle,
        'resumenCalificaciones' => $resumenCalificaciones,
        'resumenCalificacionesUsuario' => $resumenCalificacionesUsuario,
        // Pasar los filtros actuales a la vista
        'filtros' => [
            'estado' => $request->get('estado', 'todos'),
            'fecha_desde' => $request->get('fecha_desde'),
            'fecha_hasta' => $request->get('fecha_hasta'),
            'buscar' => $request->get('buscar')
        ]
    ]);
}

    
    protected function authenticated($request, $user)
    {
        if ($user->hasRole('conductor')) {
            return redirect()->route('dashboard'); // conductor dashboard
        }

        return redirect()->route('pasajero.dashboard'); // pasajero
    }

}
