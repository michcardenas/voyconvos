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

    // GET: Mostrar página de confirmación
    public function mostrarConfirmacion(Viaje $viaje)
    {
        return view('pasajero.confirmar-reserva', compact('viaje'));
    }

    // POST: Procesar la reserva
public function reservar(Request $request, Viaje $viaje)
{
    // DEBUG AL INICIO
    \Log::info('=== INICIO RESERVAR ===', [
        'request_all' => $request->all(),
        'viaje_id' => $viaje->id,
        'viaje_total' => $viaje->valor_cobrado
    ]);

    // Validar datos básicos
    $validated = $request->validate([
        'cantidad_puestos' => 'required|integer|min:1',
        'valor_cobrado' => 'required|numeric|min:0.01',
        'total' => 'required|numeric|min:0.01',
        'viaje_id' => 'required|integer'
    ]);

    // DEBUG DESPUÉS DE VALIDAR
    // dd([
    //     '1_validated' => $validated,
    //     '2_total_string' => $validated['total'],
    //     '3_total_float' => floatval($validated['total']),
    //     '4_valor_cobrado' => $validated['valor_cobrado'],
    //     '5_request_total' => $request->input('total'),
    //     '6_request_all' => $request->all()
    // ]);

    try {
        // Crear la reserva
        $reserva = new Reserva();
        $reserva->viaje_id = $viaje->id;
        $reserva->user_id = auth()->id();
        $reserva->cantidad_puestos = $validated['cantidad_puestos'];
        $reserva->precio_por_persona = $validated['valor_cobrado'];
        $reserva->total = $validated['total'];
        $reserva->estado = 'pendiente_pago';
        $reserva->fecha_reserva = now();
        $reserva->save();

        // Obtener el token
        $accessToken = env('MERCADO_PAGO_ACCESS_TOKEN');

        // Configurar Mercado Pago
        MercadoPagoConfig::setAccessToken($accessToken);
        $client = new PreferenceClient();

        // PROBAR CON VALOR FIJO
        $preference = $client->create([
            "items" => [
                [
                    "title" => "Test viaje",
                    "quantity" => 1,
                    "unit_price" => 1000.0, // Valor fijo en pesos argentinos
                    "currency_id" => "ARS"
                ]
            ],
            "external_reference" => "RESERVA_" . $reserva->id
        ]);

        // Guardar datos de MP
        $reserva->mp_preference_id = $preference->id;
        $reserva->mp_init_point = $preference->init_point;
        $reserva->save();

        return redirect()->away($preference->init_point);

    } catch (\Exception $e) {
        \Log::error('=== ERROR MERCADO PAGO ===', [
            'message' => $e->getMessage(),
            'class' => get_class($e),
            'trace' => $e->getTraceAsString()
        ]);
        
        if (isset($reserva) && $reserva->exists) {
            $reserva->delete();
        }
        
        // Mostrar el error completo para debug
        dd([
            'error' => $e->getMessage(),
            'response' => method_exists($e, 'getApiResponse') ? $e->getApiResponse() : null
        ]);
    }
}
    // Callbacks de Mercado Pago
    public function pagoSuccess(Reserva $reserva)
    {
        $reserva->estado = 'pagada';
        $reserva->save();
        
        return view('pasajero.pago-exitoso', compact('reserva'));
    }

    public function pagoFailure(Reserva $reserva)
    {
        $reserva->estado = 'fallida';
        $reserva->save();
        
        return view('pasajero.pago-fallido', compact('reserva'));
    }

    public function pagoPending(Reserva $reserva)
    {
        $reserva->estado = 'pendiente';
        $reserva->save();
        
        return view('pasajero.pago-pendiente', compact('reserva'));
    }
public function confirmacionReserva(Reserva $reserva)
{
    // Verificar que la reserva pertenece al usuario autenticado
    if ($reserva->user_id !== auth()->id()) {
        abort(403, 'No tienes permisos para ver esta reserva.');
    }

    return view('pasajero.confirmacion-reserva', compact('reserva'));
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
 public function procesarPago(Reserva $reserva)
{
     die('ESTOY AQUÍ - SI VES ESTO, ESTOY EDITANDO EL ARCHIVO CORRECTO procesar pago');

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
