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
   public function misReservas(Request $request)
{
    $usuario = Auth::user();

    if (!$usuario) {
        return redirect()->route('login')->with('error', 'Debes iniciar sesión para ver tus reservas.');
    }

    // DEBUG: Ver qué filtro llega
    $estadoFiltro = $request->get('estado', 'activos');
    // dd("Estado filtro recibido: " . $estadoFiltro);

    // Query básico con relaciones
    $query = Reserva::with(['viaje.conductor'])
        ->where('user_id', $usuario->id)
        ->orderBy('created_at', 'desc');

    // Aplicar filtros
    switch ($estadoFiltro) {
        case 'activos':
            $query->whereIn('estado', ['pendiente', 'pendiente_pago', 'confirmada']);
            break;
        case 'pendiente':
            $query->where('estado', 'pendiente');
            break;
        case 'pendiente_pago':
            $query->where('estado', 'pendiente_pago');
            break;
        case 'confirmada':
            $query->where('estado', 'confirmada');
            break;
        case 'cancelados':
            $query->whereIn('estado', ['cancelada', 'fallida']);
            break;
        case 'cancelada':
            $query->where('estado', 'cancelada');
            break;
        case 'fallida':
            $query->where('estado', 'fallida');
            break;
        case 'completados':
            // Alias para confirmada
            $query->where('estado', 'confirmada');
            break;
        case 'todos':
            // No filtrar nada
            break;
        default:
            // Estado específico
            $query->where('estado', $estadoFiltro);
    }

    $reservas = $query->get();

    // DEBUG: Ver cuántas reservas encuentra
    // dd("Reservas encontradas: " . $reservas->count() . " con filtro: " . $estadoFiltro);

    // Estadísticas (mantener tu lógica original)
    $totalViajes = $reservas->count();
    $viajesProximos = $reservas->filter(fn($r) => optional($r->viaje)->fecha_salida >= now())->count();
    $viajesRealizados = $reservas->filter(fn($r) => optional($r->viaje)->fecha_salida < now())->count();

    return view('pasajero.dashboard', compact(
        'reservas', 
        'totalViajes', 
        'viajesProximos', 
        'viajesRealizados',
        'estadoFiltro'  // ← IMPORTANTE: Agregar esta variable
    ));
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

    // 🔥 NUEVA LÓGICA: Verificar si ya existe una reserva
    $reservaExistente = Reserva::where('viaje_id', $viaje->id)
                              ->where('user_id', $userId)
                              ->first();
    
    if ($reservaExistente) {
        \Log::info('=== RESERVA EXISTENTE ENCONTRADA ===', [
            'reserva_id' => $reservaExistente->id,
            'estado' => $reservaExistente->estado
        ]);
        
        // Si está pendiente de pago o cancelada, saltar directo al pago
        if ($reservaExistente->estado === 'pendiente_pago' || $reservaExistente->estado === 'cancelada') {
            \Log::info('=== PROCESANDO PAGO PARA RESERVA EXISTENTE ===', [
                'estado_original' => $reservaExistente->estado
            ]);
            
            // Usar la reserva existente en lugar de crear una nueva
            $reserva = $reservaExistente;
            
            // Saltar directo a la configuración de Mercado Pago
            goto mercadopago_setup;
        }
        
        // Si ya está confirmada o en otro estado, informar
        if ($reservaExistente->estado === 'confirmada') {
            return redirect()->route('pasajero.dashboard')
                ->with('info', 'Ya tienes una reserva confirmada para este viaje');
        }
        
        if ($reservaExistente->estado === 'pendiente') {
            return redirect()->route('pasajero.dashboard')
                ->with('info', 'Tu reserva está pendiente de confirmación por el conductor');
        }
    }

    // Validar datos básicos (solo para reservas nuevas)
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

        // Crear la reserva (solo para nuevas)
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
        
        // 🔥 NUEVA LÓGICA: Verificar si el viaje está completamente ocupado
     // 🔥 NUEVA LÓGICA: Verificar si el viaje está completamente ocupado
            if ($viaje->puestos_disponibles <= 0) {
                $estadoAnterior = $viaje->estado;
                
                // Verificar si el conductor requiere confirmación
                $registroConductor = \App\Models\RegistroConductor::where('user_id', $viaje->conductor_id)->first();
                
                if ($registroConductor && $registroConductor->verificar_pasajeros === 1) {
                    // Si el conductor debe confirmar -> estado pendiente_confirmacion
                    $viaje->estado = 'pendiente_confirmacion';
                    $nuevoEstado = 'pendiente_confirmacion';
                } else {
                    // Si NO requiere confirmación -> estado pendiente (y procederá al pago)
                    $viaje->estado = 'pendiente';
                    $nuevoEstado = 'pendiente';
                }
                
                $viaje->save();
                
                \Log::info('=== VIAJE COMPLETAMENTE OCUPADO ===', [
                    'viaje_id' => $viaje->id,
                    'estado_anterior' => $estadoAnterior,
                    'estado_nuevo' => $nuevoEstado,
                    'puestos_restantes' => $viaje->puestos_disponibles,
                    'reserva_id' => $reserva->id,
                    'conductor_requiere_confirmacion' => ($registroConductor && $registroConductor->verificar_pasajeros === 1)
                ]);
            }
        
        // Si el viaje requiere verificación de pasajeros, no crear preferencia aún
        $registroConductor = \App\Models\RegistroConductor::where('user_id', $viaje->conductor_id)->first();
        if ($registroConductor && $registroConductor->verificar_pasajeros === 1) {
            $reserva->estado = 'pendiente_confirmacion';
            $reserva->save();

            \DB::commit();

            return redirect()->route('pasajero.dashboard')->with('success', '✅ Se ha creado su reserva y está esperando la confirmación del conductor. Una vez confirmada, podrá proceder al pago.');
        }

        // 🏷️ ETIQUETA PARA SALTO DIRECTO A MERCADO PAGO
        mercadopago_setup:

        // Configurar Mercado Pago
        $accessToken = env('MERCADO_PAGO_ACCESS_TOKEN');
        
        if (!$accessToken) {
            throw new \Exception('Token de Mercado Pago no configurado');
        }
        
        MercadoPagoConfig::setAccessToken($accessToken);
        $client = new PreferenceClient();

        // Crear preferencia de pago (funciona para nuevas y existentes)
        $preferenceData = [
            "items" => [
                [
                    "id" => "VIAJE_" . $viaje->id,
                    "title" => substr("Viaje de " . ($viaje->origen_direccion ?? 'origen') . " a " . ($viaje->destino_direccion ?? 'destino'), 0, 255),
                    "description" => "Reserva de {$reserva->cantidad_puestos} puesto(s)",
                    "quantity" => (int) $reserva->cantidad_puestos,
                    "unit_price" => (float) $reserva->precio_por_persona,
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
            ]
        ];

        \Log::info('=== MERCADO PAGO REQUEST ===', [
            'preference_data' => $preferenceData,
            'reserva_id' => $reserva->id,
            'es_existente' => isset($reservaExistente)
        ]);

        $preference = $client->create($preferenceData);

        // Guardar datos de MP
        $reserva->mp_preference_id = $preference->id;
        $reserva->mp_init_point = $preference->init_point;
        $reserva->estado = 'pendiente_pago'; // Asegurar estado correcto
        $reserva->save();
        
        // Confirmar transacción
        \DB::commit();
        
        \Log::info('=== RESERVA PROCESADA EXITOSAMENTE ===', [
            'reserva_id' => $reserva->id,
            'mp_preference_id' => $preference->id,
            'tipo' => isset($reservaExistente) ? 'EXISTENTE' : 'NUEVA'
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
        $reserva->estado = 'pendiente_pago';
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
  public function mostrarViajesDisponibles(Request $request)
{
    $usuarioId = auth()->id();

    $viajesReservados = \DB::table('reservas')
        ->where('user_id', $usuarioId)
        ->pluck('viaje_id')
        ->toArray();

    // Query base
    $query = Viaje::whereDate('fecha_salida', '>=', now())
        ->where('puestos_disponibles', '>', 0)
        ->whereNotIn('id', $viajesReservados)
        ->with('conductor');

    // Filtro por número mínimo de puestos
    if ($request->filled('puestos_minimos')) {
        $query->where('puestos_disponibles', '>=', $request->puestos_minimos);
    }

    // Filtro por ciudad de origen
    if ($request->filled('ciudad_origen')) {
        $query->where('origen_direccion', 'LIKE', '%' . $request->ciudad_origen . '%');
    }

    // Filtro por ciudad de destino
    if ($request->filled('ciudad_destino')) {
        $query->where('destino_direccion', 'LIKE', '%' . $request->ciudad_destino . '%');
    }

    // Filtro por fecha de salida
    if ($request->filled('fecha_salida')) {
        $query->whereDate('fecha_salida', $request->fecha_salida);
    }

    $viajesDisponibles = $query->orderBy('fecha_salida', 'asc')->get();

    // Formatear direcciones
    $viajesDisponibles->each(function ($viaje) {
        $viaje->origen_direccion = $this->formatearDireccion($viaje->origen_direccion);
        $viaje->destino_direccion = $this->formatearDireccion($viaje->destino_direccion);
    });

    // Obtener ciudades únicas para los filtros
    $ciudadesOrigen = Viaje::whereDate('fecha_salida', '>=', now())
        ->where('puestos_disponibles', '>', 0)
        ->distinct()
        ->pluck('origen_direccion')
        ->map(function($direccion) {
            return $this->extraerCiudad($direccion);
        })
        ->filter()
        ->unique()
        ->sort()
        ->values();

    $ciudadesDestino = Viaje::whereDate('fecha_salida', '>=', now())
        ->where('puestos_disponibles', '>', 0)
        ->distinct()
        ->pluck('destino_direccion')
        ->map(function($direccion) {
            return $this->extraerCiudad($direccion);
        })
        ->filter()
        ->unique()
        ->sort()
        ->values();

    return view('pasajero.viajesDisponibles', compact('viajesDisponibles', 'ciudadesOrigen', 'ciudadesDestino'));
}

private function extraerCiudad($direccion)
{
    if (!$direccion) return '';
    
    // Formato: "MQ2X+7P Mosquera, Cundinamarca, Colombia"
    $partes = explode(',', $direccion);
    
    if (count($partes) >= 1) {
        // Tomar la primera parte: "MQ2X+7P Mosquera"
        $primeraParte = trim($partes[0]);
        
        // Dividir por espacios y tomar la última palabra (la ciudad)
        $palabras = explode(' ', $primeraParte);
        return trim(end($palabras));
    }
    
    return '';
}

private function formatearDireccion($direccion)
{
    if (!$direccion) return '';
    
    $partes = explode(', ', $direccion);
    
    if (count($partes) >= 3) {
        return $partes[1] . ', ' . $partes[2];
    }
    
    if (count($partes) >= 2) {
        return implode(', ', array_slice($partes, 0, -1));
    }
    
    return $direccion;
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
