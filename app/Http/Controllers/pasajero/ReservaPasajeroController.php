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
    // ✅ Validar todos los campos que llegan
    $validated = $request->validate([
        'cantidad_puestos' => 'required|integer|min:1|max:' . $viaje->puestos_disponibles,
        'valor_cobrado' => 'required|numeric|min:0',
        'total' => 'required|numeric|min:0',
        'viaje_id' => 'required|integer'
    ]);

    $userId = auth()->id();
    $cantidad = $validated['cantidad_puestos'];
    $precioUnitario = floatval($validated['valor_cobrado']);
    $total = floatval($validated['total']);

    // 🔍 Verificaciones de seguridad
    if (abs($precioUnitario - floatval($viaje->valor_cobrado)) > 0.01) {
        return back()->withErrors(['error' => 'El precio del viaje ha cambiado. Por favor, actualiza la página.']);
    }

    $totalCalculado = $precioUnitario * $cantidad;
    if (abs($total - $totalCalculado) > 0.01) {
        return back()->withErrors(['error' => 'Error en el cálculo del total. Por favor, intenta nuevamente.']);
    }

    // Verificar disponibilidad de puestos
    $puestosReservados = $viaje->reservas()->where('estado', '!=', 'cancelada')->sum('cantidad_puestos');
    if (($puestosReservados + $cantidad) > $viaje->puestos_disponibles) {
        return back()->withErrors(['error' => 'No hay suficientes puestos disponibles.']);
    }

    try {
        // 💾 Crear la reserva - ✅ CORREGIDO: usar user_id en lugar de pasajero_id
        $reserva = new Reserva();
        $reserva->viaje_id = $viaje->id;
        $reserva->user_id = $userId;                    // ✅ CAMBIO: user_id en lugar de pasajero_id
        $reserva->cantidad_puestos = $cantidad;
        $reserva->precio_por_persona = $precioUnitario;
        $reserva->total = $total;
        $reserva->estado = 'pendiente_pago';
        $reserva->fecha_reserva = now();
        $reserva->save();

        \Log::info('Reserva creada exitosamente', [
            'reserva_id' => $reserva->id,
            'viaje_id' => $viaje->id,
            'usuario_id' => $userId,
            'cantidad' => $cantidad,
            'total' => $total
        ]);

        // 💳 TODO: Integración con Mercado Pago (opcional por ahora)
        // Por ahora, simplemente redirigir a una página de confirmación
        return redirect()->route('pasajero.reserva.confirmacion', $reserva->id)
            ->with('success', '¡Reserva creada exitosamente!');

        /* CODIGO MERCADO PAGO - Comentado por ahora
        $mp = new \MercadoPago\SDK(env('MERCADO_PAGO_ACCESS_TOKEN'));

        $preference = new \MercadoPago\Preference();

        $item = new \MercadoPago\Item();
        $item->title = "Viaje: " . $viaje->origen_direccion . " → " . $viaje->destino_direccion;
        $item->description = "Reserva de {$cantidad} " . ($cantidad == 1 ? 'puesto' : 'puestos') . " para el " . \Carbon\Carbon::parse($viaje->fecha_salida)->format('d/m/Y');
        $item->quantity = 1;
        $item->unit_price = $total;
        $item->currency_id = "COP";

        $preference->items = array($item);

        $preference->back_urls = array(
            "success" => route('pasajero.pago.success', $reserva->id),
            "failure" => route('pasajero.pago.failure', $reserva->id),
            "pending" => route('pasajero.pago.pending', $reserva->id)
        );

        $preference->auto_return = "approved";

        $payer = new \MercadoPago\Payer();
        $payer->name = auth()->user()->name;
        $payer->email = auth()->user()->email;
        $preference->payer = $payer;

        $preference->external_reference = "RESERVA_" . $reserva->id;
        $preference->statement_descriptor = "VIAJE_APP";
        $preference->expires = true;
        $preference->expiration_date_from = date('c');
        $preference->expiration_date_to = date('c', strtotime('+1 hour'));

        $preference->save();

        $reserva->mp_preference_id = $preference->id;
        $reserva->mp_init_point = $preference->init_point;
        $reserva->save();

        return redirect($preference->init_point);
        */

    } catch (\Exception $e) {
        \Log::error('Error al crear reserva', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'usuario_id' => $userId,
            'viaje_id' => $viaje->id
        ]);

        return back()->withErrors(['error' => 'Error al procesar la reserva. Por favor, intenta nuevamente.']);
    }
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
    \Log::info('Iniciando procesarPago', ['reserva_id' => $reserva->id]);
    
    // Verificar que la reserva pertenece al usuario autenticado
    if ($reserva->user_id !== auth()->id()) {
        abort(403, 'No tienes permisos para acceder a esta reserva.');
    }

    // Verificar que la reserva está pendiente de pago
    if ($reserva->estado !== 'pendiente_pago') {
        return redirect()->route('pasajero.reserva.confirmacion', $reserva->id)
            ->with('error', 'Esta reserva ya ha sido procesada.');
    }

    try {
        // Obtener el access token
        $accessToken = config('mercadopago.access_token') ?? env('MERCADO_PAGO_ACCESS_TOKEN');
        
        \Log::info('Access token obtenido', ['token_length' => strlen($accessToken)]);
        
        // Validar que existe el token
        if (!$accessToken) {
            throw new \Exception('Access token de Mercado Pago no configurado');
        }

        // Configurar SDK con la nueva versión
        MercadoPagoConfig::setAccessToken($accessToken);

        // Crear cliente de preferencias
        $client = new PreferenceClient();

        // Crear array de preferencia
        $preferenceData = [
            "items" => [
                [
                    "id" => "RESERVA_" . $reserva->id,
                    "title" => "Viaje: " . $reserva->viaje->origen_direccion . " → " . $reserva->viaje->destino_direccion,
                    "description" => "Reserva de {$reserva->cantidad_puestos} " . ($reserva->cantidad_puestos == 1 ? 'puesto' : 'puestos'),
                    "quantity" => 1,
                    "unit_price" => floatval($reserva->total),
                    "currency_id" => "COP"
                ]
            ],
            "payer" => [
                "name" => auth()->user()->name,
                "email" => auth()->user()->email
            ],
            "back_urls" => [
                "success" => route('pasajero.pago.success', $reserva->id),
                "failure" => route('pasajero.pago.failure', $reserva->id),
                "pending" => route('pasajero.pago.pending', $reserva->id)
            ],
            "auto_return" => "approved",
            "external_reference" => "RESERVA_" . $reserva->id
        ];

        \Log::info('Datos de preferencia preparados', $preferenceData);

        // Crear preferencia
        $preference = $client->create($preferenceData);

        \Log::info('Preferencia creada', [
            'preference_id' => $preference->id,
            'init_point' => $preference->init_point
        ]);

        // Actualizar reserva con datos de MP
        $reserva->mp_preference_id = $preference->id;
        $reserva->mp_init_point = $preference->init_point;
        $reserva->save();

        \Log::info('Redirigiendo a MP', ['url' => $preference->init_point]);

        // Redirigir a Mercado Pago
        return redirect()->away($preference->init_point);

    } catch (MPApiException $e) {
        \Log::error('Error API Mercado Pago', [
            'status' => $e->getApiResponse()->getStatusCode(),
            'error' => $e->getApiResponse()->getContent(),
            'reserva_id' => $reserva->id
        ]);

        return redirect()->route('pasajero.reserva.confirmacion', $reserva->id)
            ->with('error', 'Error al procesar el pago con Mercado Pago: ' . $e->getMessage());

    } catch (\Exception $e) {
        \Log::error('Error general al crear preferencia MP', [
            'error' => $e->getMessage(),
            'reserva_id' => $reserva->id,
            'trace' => $e->getTraceAsString()
        ]);

        return redirect()->route('pasajero.reserva.confirmacion', $reserva->id)
            ->with('error', 'Error al procesar el pago: ' . $e->getMessage());
    }
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