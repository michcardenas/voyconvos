<?php

namespace App\Http\Controllers\pasajero;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Viaje;

class DashboardPasajeroController extends Controller
{
    public function index()
    {
        $viajesDisponibles = Viaje::with('conductor')
            ->where('estado', 'pendiente')
            ->where('fecha_salida', '>=', now())
            ->where('puestos_disponibles', '>', 0)
            ->orderBy('fecha_salida', 'asc')
            ->get();

        return view('pasajero.dashboard', compact('viajesDisponibles'));
    }

    public function verViajesDisponibles()
    {
        $viajes = Viaje::where('fecha', '>=', now()) // solo viajes futuros
            ->with('conductor')
            ->get();

        return view('pasajero.viajes.lista', compact('viajes'));
    }

}
