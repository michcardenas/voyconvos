<?php

namespace App\Http\Controllers\pasajero;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Viaje;

class DashboardPasajeroController extends Controller
{
    public function index()
    {
        // Precarga la relación con el conductor
        $viajesDisponibles = Viaje::with('conductor')->where('estado', 'pendiente')->get();

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
