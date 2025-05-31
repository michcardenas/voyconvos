<?php

namespace App\Http\Controllers\Pasajero;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Viaje;
use App\Models\Reserva;
use Illuminate\Support\Facades\Auth;

class ReservaPasajeroController extends Controller
{
    // GET: Mostrar todas las reservas del pasajero
    public function misReservas()
    {
        $usuario = Auth::user();

        if (!$usuario) {
        return redirect()->route('login')->with('error', 'Debes iniciar sesión para ver tus reservas.');
    }

        $reservas = Reserva::with('viaje')
            ->where('user_id', $usuario->id)
            ->latest()
            ->get();

        $totalViajes = $reservas->count();
        $viajesProximos = $reservas->filter(fn($r) => optional($r->viaje)->fecha_salida >= now())->count();
        $viajesRealizados = $reservas->filter(fn($r) => optional($r->viaje)->fecha_salida < now())->count();

        return view('pasajero.dashboard', compact('reservas', 'totalViajes', 'viajesProximos', 'viajesRealizados'));
    }

    // GET: Mostrar página de confirmación
    public function mostrarConfirmacion(Viaje $viaje)
    {
        return view('pasajero.confirmar-reserva', compact('viaje'));
    }

    // POST: Procesar la reserva
    public function reservar(Request $request, Viaje $viaje)
    {
        $request->validate([
            'cantidad_puestos' => 'required|integer|min:1|max:' . $viaje->puestos_disponibles,
        ]);

        $userId = auth()->id();
        $cantidad = $request->cantidad_puestos;

        // Verifica si ya tiene reserva
        if (Reserva::where('viaje_id', $viaje->id)->where('user_id', $userId)->exists()) {
            return back()->with('error', 'Ya tienes una reserva en este viaje.');
        }

        // Crear reserva
        $reserva = Reserva::create([
            'viaje_id' => $viaje->id,
            'user_id' => $userId,
            'estado' => 'pendiente',
            'cantidad_puestos' => $cantidad,
            'notificado' => false,
        ]);

        // Resta puestos
        $viaje->puestos_disponibles -= $cantidad;

        // Cambia estado a activo
        if ($viaje->estado === 'pendiente') {
            $viaje->estado = 'activo';
        }

        $viaje->save();

        return redirect()->route('pasajero.reserva.confirmada', $viaje->id)
            ->with('success', 'Reserva realizada correctamente');
    }



    // // GET: Confirmación final de reserva
    // public function confirmacion(Viaje $viaje)
    // {
    //     $reserva = Reserva::where('viaje_id', $viaje->id)
    //                      ->where('user_id', Auth::id())
    //                      ->first();

    //     if (!$reserva) {
    //         return redirect()->route('pasajero.dashboard')->with('error', 'No se encontró la reserva.');
    //     }

    //     return view('pasajero.reserva-confirmada', compact('viaje', 'reserva'));
    // }

    public function verDetalles(Reserva $reserva)
    {
        // Asegúrate de que la reserva pertenece al usuario logueado
        if ($reserva->user_id !== Auth::id()) {
            abort(403, 'No autorizado.');
        }

        $reserva->load('viaje', 'viaje.conductor'); // Asegúrate de tener la relación `conductor` en Viaje

        return view('pasajero.reserva-detalles', compact('reserva'));
    }

    public function mostrarViajesDisponibles()
    {
        $viajesDisponibles = Viaje::where('fecha_salida', '>=', now())
            ->where('puestos_disponibles', '>', 0)
            ->with('conductor')
            ->orderBy('fecha_salida', 'asc')
            ->get();

        return view('pasajero.viajesDisponibles', compact('viajesDisponibles'));
    }

    public function mostrarResumen(Request $request, Viaje $viaje)
    {
        $request->validate([
            'cantidad_puestos' => 'required|integer|min:1|max:' . $viaje->puestos_disponibles,
        ]);

        $cantidad = $request->query('cantidad_puestos');
        $total = $viaje->valor_cobrado * $cantidad;

        return view('pasajero.resumen-reserva', compact('viaje', 'cantidad', 'total'));
    }
}