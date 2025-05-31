<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Viaje;
use App\Models\RegistroConductor;
use App\Models\Reserva;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // 🚧 Si es conductor pero NO tiene vehículo registrado, redirigir
        if ($user->hasRole('conductor') && !RegistroConductor::where('user_id', $user->id)->exists()) {
            return redirect()->route('conductor.registro.form')->with('info', 'Debes completar el registro de tu vehículo antes de continuar.');
        }

        // 🚗 Viajes como conductor
        $viajesConductor = Viaje::where('conductor_id', $user->id)
            ->where('estado', 'pendiente')
            ->where('activo', 1)
            ->orderBy('created_at', 'desc')
            ->get();

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
            'reservasDetalles' => $reservasDetalles
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
