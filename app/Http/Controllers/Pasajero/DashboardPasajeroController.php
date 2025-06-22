<?php

namespace App\Http\Controllers\pasajero;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Viaje;

class DashboardPasajeroController extends Controller
{
public function index(Request $request) {
    $userId = auth()->id();
    $estadoFiltro = $request->get('estado', 'activos');
    
    // DEBUG: Ver qué filtro llega
    // dd("Estado filtro recibido: " . $estadoFiltro);
    
    // Query básico
    $query = Reserva::with(['viaje.conductor'])
        ->where('user_id', $userId)
        ->orderBy('created_at', 'desc');
    
    // Filtros ACTUALIZADOS - Incluye los casos que faltan
    switch ($estadoFiltro) {
        case 'activos':
            $query->whereIn('estado', ['pendiente', 'pendiente_pago', 'confirmada']);
            break;
        case 'pendiente':
            $query->where('estado', 'pendiente');
            break;
        case 'pendiente_pago':
            $query->where('estado', 'pendiente_pago');
            break;
        case 'confirmada':
            $query->where('estado', 'confirmada');
            break;
        case 'cancelados':
            $query->whereIn('estado', ['cancelada', 'fallida']);
            break;
        case 'cancelada':
            $query->where('estado', 'cancelada');
            break;
        case 'fallida':
            $query->where('estado', 'fallida');
            break;
        case 'completados':
            // Alias para confirmada (por compatibilidad)
            $query->where('estado', 'confirmada');
            break;
        case 'todos':
            // No filtrar nada
            break;
        default:
            // Si viene un estado específico, usarlo directamente
            $query->where('estado', $estadoFiltro);
    }
    
    $reservas = $query->get();
    
    // DEBUG: Ver cuántas reservas encuentra
    // dd("Reservas encontradas: " . $reservas->count() . " con filtro: " . $estadoFiltro);
    
    return view('pasajero.dashboard', compact('reservas', 'estadoFiltro'));
}

    public function verViajesDisponibles()
    {
        $viajes = Viaje::where('fecha', '>=', now()) // solo viajes futuros
            ->with('conductor')
            ->get();

        return view('pasajero.viajes.lista', compact('viajes'));
    }

}
