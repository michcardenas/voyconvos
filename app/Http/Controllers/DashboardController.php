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

        return view('dashboard', [
            'viajesProximosList' => $viajesProximosList,
            'totalViajes' => $viajesProximosList->count(),
            'viajesProximos' => $viajesProximosList->count(),
            'viajesRealizados' => 0,
            'reservasNoVistas' => $reservasNoVistas,
            'notificaciones' => $notificaciones,
            'reservasDetalles' => $reservasDetalles,
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
