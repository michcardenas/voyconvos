<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Viaje;
use App\Models\Reserva;
use Illuminate\Support\Facades\DB;

class DashboardHibridoController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // Datos comunes
        $data = [
            'user' => $user,
            'esConductor' => $user->hasRole('conductor'),
            'esPasajero' => $user->hasRole('pasajero'),
        ];
        
        // DATOS COMO PASAJERO (todos los usuarios)
        $estadoFiltro = $request->get('estado', 'todos');
        
        $reservasQuery = Reserva::with(['viaje.user'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc');
        
        // Aplicar filtros de estado para pasajero
        if ($estadoFiltro !== 'todos') {
            if ($estadoFiltro === 'activos') {
                $reservasQuery->whereIn('estado', ['pendiente', 'pendiente_pago', 'pendiente_confirmacion', 'confirmada']);
            } elseif ($estadoFiltro === 'cancelados') {
                $reservasQuery->whereIn('estado', ['cancelada', 'cancelada_por_conductor']);
            } else {
                $reservasQuery->where('estado', $estadoFiltro);
            }
        }
        
        $data['reservas'] = $reservasQuery->paginate(10);
        $data['estadoFiltro'] = $estadoFiltro;
        
        // Estadísticas pasajero
        $data['estadisticas'] = [
            'activos' => Reserva::where('user_id', $user->id)
                ->whereIn('estado', ['pendiente', 'pendiente_pago', 'pendiente_confirmacion', 'confirmada'])
                ->count(),
            'pendiente_confirmacion' => Reserva::where('user_id', $user->id)
                ->where('estado', 'pendiente_confirmacion')
                ->count(),
            'pendiente_pago' => Reserva::where('user_id', $user->id)
                ->where('estado', 'pendiente_pago')
                ->count(),
            'confirmada' => Reserva::where('user_id', $user->id)
                ->where('estado', 'confirmada')
                ->count(),
            'cancelados' => Reserva::where('user_id', $user->id)
                ->whereIn('estado', ['cancelada', 'cancelada_por_conductor'])
                ->count(),
        ];
        
        $data['totalViajes'] = $data['reservas']->total();
        $data['viajesProximos'] = $data['estadisticas']['activos'];
        $data['viajesRealizados'] = Reserva::where('user_id', $user->id)
            ->where('estado', 'completada')
            ->count();
        
        // Calificaciones recibidas como pasajero
        $data['calificacionesDetalle'] = collect();
        $data['misCalificaciones'] = null;
        
        try {
            $calificaciones = DB::select("
                SELECT * FROM vista_calificaciones_usuarios 
                WHERE usuario_calificado_id = ? AND tipo = 'conductor_a_pasajero'
            ", [$user->id]);
            
            if (!empty($calificaciones)) {
                $data['misCalificaciones'] = [
                    'conductor_a_pasajero' => (object) [
                        'total_calificaciones' => $calificaciones[0]->total_calificaciones ?? 0,
                        'promedio_calificacion' => $calificaciones[0]->promedio_calificacion ?? 0
                    ]
                ];
            }
            
            $data['calificacionesDetalle'] = collect(DB::select("
                SELECT * FROM vista_detalle_calificaciones 
                WHERE usuario_calificado_id = ? AND tipo = 'conductor_a_pasajero'
                ORDER BY fecha_calificacion DESC
            ", [$user->id]));
        } catch (\Exception $e) {
            \Log::error('Error al obtener calificaciones pasajero: ' . $e->getMessage());
        }
        
        // DATOS COMO CONDUCTOR (solo si tiene el rol)
        if ($data['esConductor']) {
            $data['viajesProximosList'] = Viaje::with(['reservas.user'])
                ->where('conductor_id', $user->id)
                ->where('fecha_salida', '>=', now())
                ->orderBy('fecha_salida', 'asc')
                ->get();
            
            $data['totalViajesConductor'] = Viaje::where('conductor_id', $user->id)->count();
            $data['viajesProximosConductor'] = $data['viajesProximosList']->count();
            $data['viajesRealizadosConductor'] = Viaje::where('conductor_id', $user->id)
                ->where('estado', 'completado')
                ->count();
            
            // Calificaciones como conductor
            try {
                $calificacionesConductor = DB::select("
                    SELECT * FROM vista_calificaciones_usuarios 
                    WHERE usuario_calificado_id = ? AND tipo = 'pasajero_a_conductor'
                ", [$user->id]);
                
                $data['resumenCalificacionesUsuario'] = (object) [
                    'total_calificaciones' => $calificacionesConductor[0]->total_calificaciones ?? 0,
                    'promedio_calificacion' => $calificacionesConductor[0]->promedio_calificacion ?? 0
                ];
                
                $data['calificacionesDetalleConductor'] = collect(DB::select("
                    SELECT * FROM vista_detalle_calificaciones 
                    WHERE usuario_calificado_id = ? AND tipo = 'pasajero_a_conductor'
                    ORDER BY fecha_calificacion DESC
                ", [$user->id]));
            } catch (\Exception $e) {
                \Log::error('Error al obtener calificaciones conductor: ' . $e->getMessage());
                $data['resumenCalificacionesUsuario'] = (object) [
                    'total_calificaciones' => 0,
                    'promedio_calificacion' => 0
                ];
                $data['calificacionesDetalleConductor'] = collect();
            }
        } else {
            // Datos vacíos para no conductores
            $data['viajesProximosList'] = collect();
            $data['totalViajesConductor'] = 0;
            $data['viajesProximosConductor'] = 0;
            $data['viajesRealizadosConductor'] = 0;
            $data['resumenCalificacionesUsuario'] = (object) [
                'total_calificaciones' => 0,
                'promedio_calificacion' => 0
            ];
            $data['calificacionesDetalleConductor'] = collect();
        }
        
        return view('hibrido.dashboard', $data);
    }
}