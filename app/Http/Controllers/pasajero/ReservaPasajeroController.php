<?php

namespace App\Http\Controllers\Pasajero;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Viaje;
use App\Models\Reserva;
use Illuminate\Support\Facades\Auth;
use MercadoPago\Resources\Preference\Item;

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
    // Validar campos
    $request->validate([
        'cantidad_puestos' => 'required|integer|min:1|max:' . $viaje->puestos_disponibles,
    ]);

    $userId = auth()->id();
    $cantidad = $request->cantidad_puestos;

    // Calcular precio total por la cantidad de puestos
    $precioUnitario = floatval(str_replace(',', '.', $viaje->precio_por_persona));
    $precioTotal = round($precioUnitario * $cantidad, 2);

    // Validar precio antes de continuar
    if ($precioTotal <= 0) {
        return back()->with('error', 'El precio calculado no es válido para realizar el pago.');
    }

    // Configurar credencial Mercado Pago
    MercadoPagoConfig::setAccessToken(config('services.mercadopago.access_token'));

    // Crear ítem
    $item = new Item();
    $item->title = 'Reserva de viaje #' . $viaje->id;
    $item->quantity = 1;
    $item->unit_price = $precioTotal;
    $item->currency_id = 'ARS'; // Pesos Argentinos

    // ✅ Opcional: Debug
    // dd([
    //     'title' => $item->title,
    //     'quantity' => $item->quantity,
    //     'unit_price' => $item->unit_price,
    //     'currency_id' => $item->currency_id,
    // ]);

    // Crear reserva en BD
    $reserva = Reserva::create([
        'viaje_id' => $viaje->id,
        'user_id' => $userId,
        'estado' => 'pendiente',
        'cantidad_puestos' => $cantidad,
        'notificado' => false,
    ]);

    $viaje->puestos_disponibles -= $cantidad;
    $viaje->save();

    // Crear preferencia
    $client = new PreferenceClient();

    try {
        $preference = $client->create([
            'items' => [$item],
            'back_urls' => [
                'success' => route('pasajero.reserva.confirmada', $viaje->id),
                'failure' => route('pasajero.reserva.fallida', $viaje->id),
                'pending' => route('pasajero.reserva.pendiente', $viaje->id),
            ],
            'auto_return' => 'approved',
            'external_reference' => (string) $reserva->id,
        ]);

        return redirect()->away($preference->init_point);
    } catch (\MercadoPago\Exceptions\MPApiException $e) {
        return back()->with('error', 'Error al generar el enlace de pago: ' . $e->getMessage());
    }
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
    $viajesDisponibles = Viaje::whereRaw('DATE(fecha_salida) >= DATE(NOW())')
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