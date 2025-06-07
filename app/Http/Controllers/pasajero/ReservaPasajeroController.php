<?php

namespace App\Http\Controllers\Pasajero;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Viaje;
use App\Models\Reserva;
use Illuminate\Support\Facades\Auth;

use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\Exceptions\MPApiException;


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

    // POST: Procesar la reserva
public function reservar(Request $request, Viaje $viaje)
{
       die('ESTOY AQUdÍ - SI VES ESTO, ESTOY EDITANDO EL ARCHIVO CORRECTO');

}


 public function procesarPago(Reserva $reserva)
{
     die('ESTOY AQUdÍ - SI VES ESTO, ESTOY EDITANDO EL ARCHIVO CORRECTO procesar pago');

}
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
    $viajesDisponibles = Viaje::whereRaw('DATE(fecha_salida) >= DATE(NOW())')
        ->where('puestos_disponibles', '>', 0)
        ->with('conductor')
        ->orderBy('fecha_salida', 'asc')
        ->get();

    return view('pasajero.viajesDisponibles', compact('viajesDisponibles'));
}
   public function mostrarResumen(Request $request, Viaje $viaje)
{
    // Validación de entrada
    $request->validate([
        'cantidad_puestos' => 'required|integer|min:1|max:' . $viaje->puestos_disponibles,
    ]);

    // ✅ Obtener cantidad del request (funciona tanto para GET como POST)
    $cantidad = $request->input('cantidad_puestos');
    
    // 🔍 Verificar que el viaje tenga precio configurado
    if (!$viaje->valor_cobrado || $viaje->valor_cobrado <= 0) {
        return back()->withErrors([
            'error' => 'Este viaje no tiene un precio configurado correctamente.'
        ]);
    }
    
    // ✅ Calcular el total
    $total = $viaje->valor_cobrado * $cantidad;

    // 📊 Log para seguimiento (opcional - puedes quitarlo si no lo necesitas)
    \Log::info('Resumen de Reserva', [
        'viaje_id' => $viaje->id,
        'cantidad' => $cantidad,
        'precio_unitario' => $viaje->valor_cobrado,
        'total' => $total,
        'usuario_id' => auth()->id()
    ]);

    return view('pasajero.resumen-reserva', compact('viaje', 'cantidad', 'total'));
}
}