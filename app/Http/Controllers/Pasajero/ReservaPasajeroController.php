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
    // VERIFICAR AUTENTICACIÓN
    if (!auth()->check()) {
        return redirect()->route('login')
            ->with('error', 'Debes iniciar sesión para realizar una reserva');
    }
    
    $userId = auth()->id();
    
    // DEBUG AL INICIO
    \Log::info('=== INICIO RESERVAR ===', [
        'user_id' => $userId,
        'user_authenticated' => auth()->check(),
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

    // Verificar disponibilidad de puestos
    if ($viaje->puestos_disponibles < $validated['cantidad_puestos']) {
        return back()->withErrors([
            'error' => 'No hay suficientes puestos disponibles'
        ]);
    }

    try {
        // Usar transacción para asegurar consistencia
        \DB::beginTransaction();
        
        // Verificar nuevamente la autenticación
        if (!$userId) {
            throw new \Exception('Usuario no autenticado');
        }

        // Crear la reserva
        $reserva = new Reserva();
        $reserva->viaje_id = $viaje->id;
        $reserva->user_id = $userId;
        $reserva->cantidad_puestos = $validated['cantidad_puestos'];
        $reserva->precio_por_persona = $validated['valor_cobrado'];
        $reserva->total = $validated['total'];
        $reserva->estado = 'pendiente';
        $reserva->fecha_reserva = now();
        $reserva->save();
        
        // Actualizar puestos disponibles
        $viaje->puestos_disponibles -= $validated['cantidad_puestos'];
        $viaje->save();
        // Si el viaje requiere verificación de pasajeros, no crear preferencia aún
    
        $registroConductor = \App\Models\RegistroConductor::where('user_id', $viaje->conductor_id)->first();
        if ($registroConductor && $registroConductor->verificar_pasajeros === 1) {
            $reserva->estado = 'pendiente';
            $reserva->save();

            \DB::commit();

            return redirect()->route('pasajero.dashboard')->with('success', '✅ Se ha creado su reserva y está esperando la confirmación del conductor. Una vez confirmada, podrá proceder al pago.');
        }


        // Configurar Mercado Pago
        $accessToken = env('MERCADO_PAGO_ACCESS_TOKEN');
        
        if (!$accessToken) {
            throw new \Exception('Token de Mercado Pago no configurado');
        }
        
        MercadoPagoConfig::setAccessToken($accessToken);
        $client = new PreferenceClient();

        // Crear preferencia de pago
      $preferenceData = [
    "items" => [
        [
            "id" => "VIAJE_" . $viaje->id,
            "title" => substr("Viaje de " . ($viaje->origen_direccion ?? 'origen') . " a " . ($viaje->destino_direccion ?? 'destino'), 0, 255),
            "description" => "Reserva de {$validated['cantidad_puestos']} puesto(s)",
            "quantity" => (int) $validated['cantidad_puestos'],
            "unit_price" => (float) $validated['valor_cobrado'],
            "currency_id" => "ARS"
        ]
    ],
    "back_urls" => [
        "success" => route('pasajero.pago.success', $reserva->id),
        "failure" => route('pasajero.pago.failure', $reserva->id),
        "pending" => route('pasajero.pago.pending', $reserva->id)
    ],
    "auto_return" => "approved",
    "external_reference" => "RESERVA_" . $reserva->id,
    "payer" => [
        "email" => auth()->user()->email,
        "name" => auth()->user()->name
    ],
    // Opcional, solo si tienes endpoint público configurado:
    //"notification_url" => route('webhook.mercadopago') 
];

        
        \Log::info('=== MERCADO PAGO REQUEST ===', [
            'preference_data' => $preferenceData
        ]);

        $preference = $client->create($preferenceData);

        // Guardar datos de MP
        $reserva->mp_preference_id = $preference->id;
        $reserva->mp_init_point = $preference->init_point;
        $reserva->save();
        
        // Confirmar transacción
        \DB::commit();
        
        \Log::info('=== RESERVA CREADA EXITOSAMENTE ===', [
            'reserva_id' => $reserva->id,
            'mp_preference_id' => $preference->id
        ]);

        // Redirigir a Mercado Pago
        return redirect()->away($preference->init_point);

    } catch (MPApiException $e) {
        \DB::rollBack();
        
        \Log::error('=== ERROR MERCADO PAGO API ===', [
            'message' => $e->getMessage(),
            'status_code' => $e->getCode(),
            'api_response' => $e->getApiResponse()
        ]);
        
        return back()->withErrors([
            'error' => 'Error al procesar el pago: ' . $e->getMessage()
        ]);
        
    } catch (\Exception $e) {
        \DB::rollBack();
        
        \Log::error('=== ERROR GENERAL ===', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return back()->withErrors([
            'error' => 'Error al procesar la reserva: ' . $e->getMessage()
        ]);
    }
}


    // Callbacks de Mercado Pago
    public function pagoSuccess(Reserva $reserva)
    {
        $reserva->estado = 'confirmada';
        $reserva->save();
        
        return view('pasajero.pago-exitoso', compact('reserva'));
    }

    public function pagoFailure(Reserva $reserva)
    {
        $reserva->estado = 'cancelada';
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
        $usuarioId = auth()->id(); // ID del usuario logueado

        // Traer IDs de viajes que ya reservó el usuario
        $viajesReservados = \DB::table('reservas')
            ->where('user_id', $usuarioId)
            ->pluck('viaje_id')
            ->toArray();

        // Consultar solo los viajes que aún puede reservar
        $viajesDisponibles = Viaje::whereDate('fecha_salida', '>=', now())
            ->where('puestos_disponibles', '>', 0)
            ->whereNotIn('id', $viajesReservados)
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

        // ✅ Obtener cantidad del request
        $cantidad = $request->input('cantidad_puestos');
        
        // 🔍 Determinar el precio a usar (en orden de prioridad)
        $precio = $viaje->valor_persona ?? $viaje->valor_cobrado ?? $viaje->valor_estimado ?? 0;
        
        // Verificar que el viaje tenga precio configurado
        if (!$precio || $precio <= 0) {
            return back()->withErrors([
                'error' => 'Este viaje no tiene un precio configurado correctamente.'
            ])->withInput();
        }
        
        // ✅ Calcular el total
        $total = $precio * $cantidad;

        // 📊 Log para seguimiento
        \Log::info('Resumen de Reserva', [
            'viaje_id' => $viaje->id,
            'cantidad' => $cantidad,
            'precio_unitario' => $precio,
            'precio_campo_usado' => $viaje->valor_persona ? 'valor_persona' : 
                                ($viaje->valor_cobrado ? 'valor_cobrado' : 'valor_estimado'),
            'total' => $total,
            'usuario_id' => auth()->id()
        ]);

        // ✅ Pasar el precio usado además de las otras variables
        return view('pasajero.resumen-reserva', compact('viaje', 'cantidad', 'total', 'precio'));
    }


}
