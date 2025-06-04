<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use App\Models\Calificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CalificacionController extends Controller

{
    public function formularioPasajero(Reserva $reserva)
    {
        if ($reserva->user_id !== Auth::id()) {
            abort(403);
        }

        // Verificar si ya fue calificado
        if (Calificacion::where('reserva_id', $reserva->id)
            ->where('usuario_id', Auth::id())
            ->where('tipo', 'pasajero_a_conductor')
            ->exists()) {
            return redirect()->route('pasajero.dashboard')->with('info', 'Ya has calificado este viaje.');
        }

        return view('pasajero.calificar', compact('reserva'));
    }

    public function guardarCalificacionPasajero(Request $request, Reserva $reserva)
    {
        $request->validate([
            'calificacion' => 'required|integer|min:1|max:5',
            'comentario' => 'required|string|max:255',
        ]);

        if ($reserva->user_id !== Auth::id()) {
            abort(403);
        }

        Calificacion::create([
            'reserva_id' => $reserva->id,
            'usuario_id' => Auth::id(),
            'calificacion' => $request->calificacion,
            'comentario' => $request->comentario,
            'tipo' => 'pasajero_a_conductor',
        ]);

        return redirect()->route('pasajero.dashboard')->with('success', 'Gracias por calificar al conductor.');
    }
}
