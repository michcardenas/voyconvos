<?php

namespace App\Http\Controllers\Pasajero;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Viaje;
use App\Models\Reserva;
use Illuminate\Support\Facades\Auth;
use App\Services\UalaService;
use App\Models\RegistroConductor; // Asegúrate de importar el modelo correcto


class ReservaPasajeroController extends Controller
{

    // GET: Mostrar todas las reservas del pasajero
public function misReservas(Request $request) 
{
    $usuario = Auth::user();
    
    if (!$usuario) {
        return redirect()->route('login')->with('error', 'Debes iniciar sesión para ver tus reservas.');
    }
    
    // 🔥 CAMBIO: Default a 'todos' en lugar de 'activos'
    $estadoFiltro = $request->get('estado', 'todos');
    
    // Query básico con relaciones
    $query = Reserva::with(['viaje.conductor'])
        ->where('user_id', $usuario->id)
        ->orderBy('created_at', 'desc');
    
    // Aplicar filtros con los nuevos estados
    switch ($estadoFiltro) {
        case 'activos':
            $query->whereIn('estado', [
                'pendiente', 
                'pendiente_pago', 
                'pendiente_confirmacion', 
                'confirmada'
            ]);
            break;
            
        case 'pendiente':
            $query->where('estado', 'pendiente');
            break;
            
        case 'pendiente_pago':
            $query->where('estado', 'pendiente_pago');
            break;
            
        case 'pendiente_confirmacion':
            $query->where('estado', 'pendiente_confirmacion');
            break;
            
        case 'confirmada':
            $query->where('estado', 'confirmada');
            break;
            
        case 'cancelados':
            $query->whereIn('estado', [
                'cancelada', 
                'fallida', 
                'cancelada_por_conductor'
            ]);
            break;
            
        case 'cancelada':
            $query->where('estado', 'cancelada');
            break;
            
        case 'cancelada_por_conductor':
            $query->where('estado', 'cancelada_por_conductor');
            break;
            
        case 'fallida':
            $query->where('estado', 'fallida');
            break;
            
        case 'completada':
            $query->where('estado', 'completada');
            break;
            
        case 'esperando_confirmacion':
            $query->where('estado', 'pendiente_confirmacion');
            break;
            
        case 'todos':
            // No filtrar nada - mostrar todas las reservas
            break;
            
        default:
            $query->where('estado', $estadoFiltro);
    }
    
    // 🔥 NUEVA PAGINACIÓN: 10 elementos por página
    $reservas = $query->paginate(10)->withQueryString();
    
    // Para estadísticas, necesitamos los totales (sin paginación)
    $todasLasReservas = Reserva::where('user_id', $usuario->id)->get();
    
    // Estadísticas actualizadas con nuevos estados
    $totalViajes = $todasLasReservas->count();
    $viajesProximos = $todasLasReservas->filter(fn($r) => optional($r->viaje)->fecha_salida >= now())->count();
    $viajesRealizados = $todasLasReservas->filter(fn($r) => optional($r->viaje)->fecha_salida < now())->count();
    
    // 🔥 ESTADÍSTICAS por estado (para los badges en los filtros)
    $estadisticas = [
        'activos' => $todasLasReservas->whereIn('estado', [
            'pendiente', 
            'pendiente_pago', 
            'pendiente_confirmacion', 
            'confirmada'
        ])->count(),
        
        'pendiente_confirmacion' => $todasLasReservas->where('estado', 'pendiente_confirmacion')->count(),
        'pendiente_pago' => $todasLasReservas->where('estado', 'pendiente_pago')->count(),
        'confirmada' => $todasLasReservas->where('estado', 'confirmada')->count(),
        
        'cancelados' => $todasLasReservas->whereIn('estado', [
            'cancelada', 
            'fallida', 
            'cancelada_por_conductor'
        ])->count(),
    ];
    
    return view('pasajero.dashboard', compact(
        'reservas',           // ← Ahora es paginado
        'totalViajes', 
        'viajesProximos', 
        'viajesRealizados',
        'estadoFiltro',
        'estadisticas'
    ));
}
    // GET: Mostrar página de confirmación
   public function mostrarConfirmacion(Viaje $viaje)
{
    // Buscar información del vehículo manualmente
    $vehiculoInfo = RegistroConductor::where('user_id', $viaje->conductor_id)
        ->select('marca_vehiculo', 'modelo_vehiculo')
        ->first();
    
    // Agregar la información al objeto viaje temporalmente
    $viaje->vehiculo_info = $vehiculoInfo;
    
    
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
            
            // Saltar directo a la configuración de Uala
            goto uala_setup;
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
        
        // Si el viaje requiere verificación de pasajeros, no crear checkout aún
        $registroConductor = \App\Models\RegistroConductor::where('user_id', $viaje->conductor_id)->first();
        if ($registroConductor && $registroConductor->verificar_pasajeros === 1) {
            $reserva->estado = 'pendiente_confirmacion';
            $reserva->save();

            \DB::commit();

            return redirect()->route('pasajero.dashboard')->with('success', '✅ Se ha creado su reserva y está esperando la confirmación del conductor. Una vez confirmada, podrá proceder al pago.');
        }

        // 🏷️ ETIQUETA PARA SALTO DIRECTO A UALA
        uala_setup:

        // Configurar Uala
        $ualaService = new \App\Services\UalaService();
        
        \Log::info('=== INICIANDO PROCESO UALA ===', [
            'reserva_id' => $reserva->id,
            'tipo' => isset($reservaExistente) ? 'EXISTENTE' : 'NUEVA'
        ]);

        // Preparar datos del checkout para Uala
        $checkoutData = $ualaService->prepareCheckoutData($reserva, $viaje);

        \Log::info('=== UALA CHECKOUT REQUEST ===', [
            'checkout_data' => $checkoutData,
            'reserva_id' => $reserva->id,
            'es_existente' => isset($reservaExistente)
        ]);

        // Crear checkout en Uala
        $checkoutResponse = $ualaService->createCheckout($checkoutData);

        // Guardar datos de Uala
        $reserva->uala_checkout_id = $checkoutResponse['id'] ?? null;
        $reserva->uala_payment_url = $checkoutResponse['payment_url'] ?? $checkoutResponse['checkout_url'] ?? null;
        $reserva->uala_external_reference = $checkoutResponse['external_reference'] ?? null;
        $reserva->estado = 'pendiente_pago'; // Asegurar estado correcto
        $reserva->save();
        
        // Confirmar transacción
        \DB::commit();
        
        \Log::info('=== RESERVA PROCESADA EXITOSAMENTE CON UALA ===', [
            'reserva_id' => $reserva->id,
            'uala_checkout_id' => $reserva->uala_checkout_id,
            'tipo' => isset($reservaExistente) ? 'EXISTENTE' : 'NUEVA'
        ]);

        // Validar que tengamos la URL de pago
        $paymentUrl = $reserva->uala_payment_url;
        if (!$paymentUrl) {
            throw new \Exception('No se recibió URL de pago de Uala. Respuesta: ' . json_encode($checkoutResponse));
        }

        // Redirigir a Uala
        return redirect()->away($paymentUrl);

    } catch (\Exception $e) {
        \DB::rollBack();
        
        \Log::error('=== ERROR PROCESANDO PAGO CON UALA ===', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'reserva_id' => $reserva->id ?? 'N/A',
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]);
        
        return back()->withErrors([
            'error' => 'Error al procesar el pago con Uala: ' . $e->getMessage()
        ]);
    }
}

// Agregar esta nueva función
private function verificarViajeCompleto($viaje)
{
    // Refrescar datos del viaje
    $viaje->refresh();
    
    // Contar reservas pendientes (no confirmadas)
    $reservasPendientes = $viaje->reservas()
        ->whereIn('estado', ['pendiente_confirmacion', 'pendiente_pago'])
        ->count();
    
    // Contar reservas confirmadas
    $reservasConfirmadas = $viaje->reservas()
        ->where('estado', 'confirmada')
        ->count();
    
    // Si no hay puestos disponibles, no hay pendientes, y hay confirmadas
    if ($viaje->puestos_disponibles <= 0 && $reservasPendientes === 0 && $reservasConfirmadas > 0) {
        
        $viaje->update([
            'estado' => 'listo_para_iniciar',
            'updated_at' => now()
        ]);
        
        \Log::info("Viaje {$viaje->id} completado - todas las reservas confirmadas");
    }
}
    // Callbacks de Mercado Pago
   // Actualizar tu método pagoSuccess existente
public function pagoSuccess(Request $request, Reserva $reserva)
{
    // Logging para Uala
    \Log::info('=== UALA PAGO SUCCESS ===', [
        'reserva_id' => $reserva->id,
        'request_params' => $request->all(),
        'estado_anterior' => $reserva->estado
    ]);

    // Actualizar información de Uala en la reserva
    $reserva->estado = 'confirmada';
    $reserva->uala_payment_status = 'approved';
    $reserva->uala_payment_date = now();
    $reserva->save();
    
    // Verificar si el viaje está completo (tu lógica existente)
    $this->verificarViajeCompleto($reserva->viaje);
    
    return view('pasajero.pago-exitoso', compact('reserva'));
}

public function pagoFailure(Request $request, Reserva $reserva)
{
    // Logging para Uala
    \Log::info('=== UALA PAGO FAILURE ===', [
        'reserva_id' => $reserva->id,
        'request_params' => $request->all(),
        'estado_anterior' => $reserva->estado
    ]);

    // Actualizar información de Uala en la reserva
    $reserva->estado = 'cancelada';
    $reserva->uala_payment_status = 'rejected';
    $reserva->save();
    
    return view('pasajero.pago-fallido', compact('reserva'));
}

public function pagoPending(Request $request, Reserva $reserva)
{
    // Logging para Uala
    \Log::info('=== UALA PAGO PENDING ===', [
        'reserva_id' => $reserva->id,
        'request_params' => $request->all(),
        'estado_anterior' => $reserva->estado
    ]);

    // Actualizar información de Uala en la reserva
    $reserva->estado = 'pendiente_pago';
    $reserva->uala_payment_status = 'pending';
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
