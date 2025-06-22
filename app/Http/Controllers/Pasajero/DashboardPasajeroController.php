<?php

namespace App\Http\Controllers\pasajero;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Viaje;

class DashboardPasajeroController extends Controller
{
   public function index(Request $request)
{
    $userId = auth()->id();
    
    // Filtro de estado (por defecto: activos)
    $estadoFiltro = $request->get('estado', 'activos');
    
    // Construir query base con relaciones
    $reservasQuery = Reserva::with(['viaje.conductor', 'viaje.vehiculo'])
        ->where('user_id', $userId)
        ->orderBy('created_at', 'desc');
    
    // Aplicar filtros
    switch ($estadoFiltro) {
        case 'activos':
            $reservasQuery->whereIn('estado', ['pendiente', 'pendiente_pago', 'confirmada']);
            break;
        case 'pendiente_pago':
            $reservasQuery->where('estado', 'pendiente_pago');
            break;
        case 'cancelados':
            $reservasQuery->whereIn('estado', ['cancelada', 'fallida']);
            break;
        case 'completados':
            $reservasQuery->where('estado', 'confirmada');
            break;
        case 'todos':
            // Mostrar todos, no filtrar
            break;
        default:
            $reservasQuery->where('estado', $estadoFiltro);
    }
    
    // Obtener reservas
    $reservas = $reservasQuery->paginate(10);
    
    // Estadísticas básicas (mantener lo que ya tienes)
    $totalViajes = 0; // O mantén tu lógica actual
    $viajesProximos = 0; // O mantén tu lógica actual  
    $viajesRealizados = 0; // O mantén tu lógica actual
    
    // Contar por estado para los badges
    $conteoEstados = [
        'activos' => Reserva::where('user_id', $userId)
            ->whereIn('estado', ['pendiente', 'pendiente_pago', 'confirmada'])
            ->count(),
        'pendiente_pago' => Reserva::where('user_id', $userId)
            ->where('estado', 'pendiente_pago')
            ->count(),
        'cancelados' => Reserva::where('user_id', $userId)
            ->whereIn('estado', ['cancelada', 'fallida'])
            ->count(),
        'completados' => Reserva::where('user_id', $userId)
            ->where('estado', 'confirmada')
            ->count(),
        'todos' => Reserva::where('user_id', $userId)->count()
    ];
    
    return view('pasajero.dashboard', compact(
        'reservas',
        'estadoFiltro', 
        'conteoEstados',
        'totalViajes',
        'viajesProximos', 
        'viajesRealizados'
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
