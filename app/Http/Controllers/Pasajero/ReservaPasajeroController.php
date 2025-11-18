<?php

namespace App\Http\Controllers\Pasajero;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Viaje;
use App\Models\Reserva;
use Illuminate\Support\Facades\Auth;
use App\Services\UalaService;
use App\Models\RegistroConductor; // Asegúrate de importar el modelo correcto
use Illuminate\Support\Facades\DB;
use App\Mail\UniversalMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ReservaPasajeroController extends Controller
{

    // GET: Mostrar todas las reservas del pasajero
public function misReservas(Request $request) 
{
    $usuario = Auth::user();
    
    if (!$usuario) {
        return redirect()->route('login')->with('error', 'Debes iniciar sesión para ver tus reservas.');
    }
    
    // 🔥 NUEVO FILTRO: Distinguir entre próximos viajes e historial
    $tipoVista = $request->get('vista', 'proximos'); // 'proximos' o 'historial'
    $estadoFiltro = $request->get('estado', 'todos');

    // Query básico con relaciones
    $query = Reserva::with(['viaje.conductor'])
        ->where('user_id', $usuario->id);

    // Aplicar filtro de fecha según el tipo de vista
    if ($tipoVista === 'proximos') {
        // Solo viajes futuros (posteriores a fecha y hora actual)
        $query->whereHas('viaje', function($q) {
            $q->where(function($query) {
                $query->where('fecha_salida', '>', now()->toDateString())
                      ->orWhere(function($q) {
                          $q->where('fecha_salida', '=', now()->toDateString())
                            ->where('hora_salida', '>', now()->toTimeString());
                      });
            });
        });
    }
    // Si es 'historial', no aplicar filtro de fecha (mostrar todos)

    $query->orderBy('created_at', 'desc');

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

    // ⭐ CALIFICACIONES DEL USUARIO (USANDO MODELOS ELOQUENT)
    try {
        // 📊 Vista de promedios de usuarios - usando modelo Eloquent
        $calificacionesUsuarios = \App\Models\VistaCalificacionesUsuario::select([
            'usuario_id', 
            'tipo', 
            'total_calificaciones', 
            'promedio_calificacion'
        ])->get();

        // 📝 Vista de detalles de calificaciones - usando modelo Eloquent
        $calificacionesDetalle = \App\Models\VistaCalificacionesDetalle::select([
            'calificacion_id', 
            'calificacion', 
            'comentario', 
            'tipo', 
            'fecha_calificacion', 
            'usuario_calificado_id', 
            'nombre_usuario_calificado', 
            'reserva_id', 
            'fecha_reserva', 
            'estado_reserva', 
            'cantidad_puestos', 
            'total_pagado', 
            'viaje_id', 
            'fecha_salida', 
            'hora_salida', 
            'origen_direccion', 
            'destino_direccion', 
            'conductor_id', 
            'nombre_conductor'
        ])
        ->orderBy('fecha_calificacion', 'desc')
        ->limit(20)
        ->get();

        \Log::info('Calificaciones cargadas en misReservas usando modelos Eloquent', [
            'usuarios_count' => $calificacionesUsuarios->count(),
            'detalles_count' => $calificacionesDetalle->count(),
            'usuario_id' => $usuario->id
        ]);

    } catch (\Exception $e) {
        \Log::error('Error al consultar calificaciones en misReservas usando modelos: ' . $e->getMessage());
        $calificacionesUsuarios = collect();
        $calificacionesDetalle = collect();
    }

    // 👤 CALIFICACIONES DEL USUARIO ACTUAL
    $misCalificaciones = null;
    if ($usuario) {
        $misCalificaciones = $calificacionesUsuarios
            ->where('usuario_id', $usuario->id)
            ->keyBy('tipo');
    }

    // 🎯 CALIFICACIONES COMO PASAJERO
    $misCalificacionesComoPasajero = null;
    $comentariosComoPasajero = collect();
    
    if ($usuario) {
        // Obtener resumen de calificaciones como pasajero
        $misCalificacionesComoPasajero = $calificacionesUsuarios
            ->where('usuario_id', $usuario->id)
            ->where('tipo', 'conductor_a_pasajero')
            ->first();

        // Obtener comentarios detallados como pasajero
        $comentariosComoPasajero = $calificacionesDetalle
            ->where('usuario_calificado_id', $usuario->id)
            ->where('tipo', 'conductor_a_pasajero')
            ->sortByDesc('fecha_calificacion');

        // Debug para calificaciones
        \Log::info('Debug calificaciones usuario en misReservas', [
            'user_id' => $usuario->id,
            'mis_calificaciones_count' => $misCalificaciones ? $misCalificaciones->count() : 0,
            'calificaciones_como_pasajero' => $misCalificacionesComoPasajero,
            'comentarios_como_pasajero_count' => $comentariosComoPasajero->count()
        ]);
    }
    
    return view('pasajero.dashboard', compact(
        'reservas',           // ← Ahora es paginado
        'totalViajes',
        'viajesProximos',
        'viajesRealizados',
        'estadoFiltro',
        'tipoVista',          // ← NUEVO: tipo de vista (proximos/historial)
        'estadisticas',
        'calificacionesUsuarios',
        'calificacionesDetalle',
        'misCalificaciones',
        'misCalificacionesComoPasajero',
        'comentariosComoPasajero'
    ));
}
    // GET: Mostrar página de confirmación
public function mostrarConfirmacion(Viaje $viaje) 
{
    $usuarioId = auth()->id();

    // Buscar información del vehículo manualmente
    $vehiculoInfo = RegistroConductor::where('user_id', $viaje->conductor_id)
        ->select('marca_vehiculo', 'modelo_vehiculo')
        ->first();
        
    // Agregar la información al objeto viaje temporalmente
    $viaje->vehiculo_info = $vehiculoInfo;

    // Obtener calificaciones del usuario autenticado
    $calificacionesUsuario = \DB::table('vista_calificaciones_usuarios')
        ->where('usuario_id', $usuarioId)
        ->first();

    // Si no tiene calificaciones, crear objeto vacío con valores por defecto
    if (!$calificacionesUsuario) {
        $calificacionesUsuario = (object) [
            'usuario_id' => $usuarioId,
            'tipo' => null,
            'total_calificaciones' => 0,
            'promedio_calificacion' => 0
        ];
    }
          
    return view('pasajero.confirmar-reserva', compact('viaje', 'calificacionesUsuario'));
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
    $usuario = auth()->user(); // Agregar esta línea para obtener el usuario completo
    
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

        // Si está pendiente de pago, cancelada o pendiente, procesar según el método de pago
        if (in_array($reservaExistente->estado, ['pendiente_pago', 'cancelada', 'pendiente'])) {
            \Log::info('=== PROCESANDO PAGO PARA RESERVA EXISTENTE ===', [
                'estado_original' => $reservaExistente->estado,
                'metodo_pago' => $request->metodo_pago ?? 'no_especificado',
                'tiene_comprobante' => $request->hasFile('comprobante_pago'),
                'subir_ahora' => $request->has('subir_ahora')
            ]);

            // Usar la reserva existente en lugar de crear una nueva
            $reserva = $reservaExistente;

            // 🔥 VERIFICAR SI SE SUBIÓ UN COMPROBANTE (TRANSFERENCIA)
            if ($request->hasFile('comprobante_pago')) {
                \Log::info('=== COMPROBANTE DETECTADO EN RESERVA EXISTENTE ===');

                try {
                    \DB::beginTransaction();

                    $file = $request->file('comprobante_pago');

                    // Crear nombre único para el archivo
                    $fileName = 'comprobante_' . $reserva->id . '_' . time() . '.' . $file->getClientOriginalExtension();

                    // Guardar en storage/app/public/comprobantes
                    $path = $file->storeAs('comprobantes', $fileName, 'public');

                    // Actualizar la reserva con la ruta del comprobante
                    $reserva->comprobante_pago = $path;
                    $reserva->fecha_subida_comprobante = now();
                    $reserva->fecha_limite_comprobante = now()->addHour();
                    $reserva->metodo_pago = 'transferencia';
                    $reserva->estado = 'pendiente'; // Cambiar a pendiente hasta que admin verifique
                    $reserva->save();

                    \DB::commit();

                    \Log::info('=== COMPROBANTE ACTUALIZADO EN RESERVA EXISTENTE ===', [
                        'reserva_id' => $reserva->id,
                        'file_path' => $path,
                        'nuevo_estado' => 'pendiente'
                    ]);

                    // Enviar correo de confirmación al pasajero
                    try {
                        $viaje = $reserva->viaje;
                        $pasajero = $reserva->user;
                        $fechaViaje = \Carbon\Carbon::parse($viaje->fecha_salida)->format('d/m/Y');
                        $horaViaje = \Carbon\Carbon::parse($viaje->hora_salida)->format('H:i');
                        $conductor = \App\Models\User::find($viaje->conductor_id);

                        Mail::to($pasajero->email)->send(new UniversalMail(
                            $pasajero,
                            'Comprobante recibido - En verificación',
                            "¡Gracias por subir tu comprobante de pago!\n\n📍 Detalles del viaje:\n• Fecha: {$fechaViaje}\n• Hora: {$horaViaje}\n• Puestos: {$reserva->cantidad_puestos}\n• Total: $" . number_format($reserva->total, 0, ',', '.') . "\n• Conductor: {$conductor->name}\n\nNuestro equipo verificará tu pago pronto y te notificaremos cuando tu reserva sea confirmada.\n\n¡Gracias por tu paciencia!",
                            'notificacion'
                        ));
                    } catch (\Exception $e) {
                        \Log::error('Error enviando email de comprobante: ' . $e->getMessage());
                    }

                    // Si es AJAX, devolver JSON
                    if ($request->ajax() || $request->wantsJson()) {
                        return response()->json([
                            'success' => true,
                            'message' => 'Comprobante recibido exitosamente',
                            'redirect' => route('pasajero.dashboard')
                        ]);
                    }

                    return redirect()->route('pasajero.dashboard')->with('success', '✅ Tu comprobante ha sido recibido. Nuestro equipo verificará el pago pronto.');

                } catch (\Exception $e) {
                    \DB::rollBack();
                    \Log::error('Error subiendo comprobante en reserva existente: ' . $e->getMessage());
                    return back()->withErrors(['error' => 'Error al subir el comprobante: ' . $e->getMessage()]);
                }
            }

            // Si NO hay comprobante y el método es UalaBis, ir a Uala
            if ($request->has('metodo_pago') && $request->metodo_pago === 'ualabis') {
                \Log::info('=== RESERVA EXISTENTE: Procesando con UalaBis ===');
                goto uala_setup;
            }

            // Si llegó aquí sin comprobante ni método definido, mostrar error
            if (!$request->has('metodo_pago')) {
                return back()->withErrors(['error' => 'Por favor selecciona un método de pago válido.']);
            }
        }
        
        // Si ya está confirmada, informar
        if ($reservaExistente->estado === 'confirmada') {
            return redirect()->route('pasajero.dashboard')
                ->with('info', 'Ya tienes una reserva confirmada para este viaje');
        }

        // Ya no bloqueamos el estado 'pendiente' aquí, ahora se maneja arriba
    }

    // Validar datos básicos (solo para reservas nuevas)
    $validated = $request->validate([
        'cantidad_puestos' => 'required|integer|min:1',
        'valor_cobrado' => 'required|numeric|min:0.01',
        'total' => 'required|numeric|min:0.01',
        'viaje_id' => 'required|integer',
        'comprobante_pago' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120', // 5MB max
        'metodo_pago' => 'nullable|in:mercadopago,uala,ualabis,transferencia'
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

        // Si se seleccionó transferencia como método de pago
        if ($request->has('metodo_pago') && $request->metodo_pago === 'transferencia') {
            $reserva->metodo_pago = 'transferencia';
        }

        // Si se seleccionó UalaBis como método de pago
        if ($request->has('metodo_pago') && $request->metodo_pago === 'ualabis') {
            $reserva->metodo_pago = 'ualabis';
        }

        $reserva->save();

        // 🔥 MANEJAR SUBIDA DE COMPROBANTE SI EXISTE
        if ($request->hasFile('comprobante_pago')) {
            try {
                $file = $request->file('comprobante_pago');

                // Crear nombre único para el archivo
                $fileName = 'comprobante_' . $reserva->id . '_' . time() . '.' . $file->getClientOriginalExtension();

                // Guardar en storage/app/public/comprobantes
                $path = $file->storeAs('comprobantes', $fileName, 'public');

                // Actualizar la reserva con la ruta del comprobante
                $reserva->comprobante_pago = $path;
                $reserva->fecha_subida_comprobante = now();
                $reserva->fecha_limite_comprobante = now()->addHour(); // 1 hora de límite
                $reserva->save();

                \Log::info('=== COMPROBANTE SUBIDO ===', [
                    'reserva_id' => $reserva->id,
                    'file_path' => $path,
                    'file_name' => $fileName,
                    'file_size' => $file->getSize()
                ]);

            } catch (\Exception $e) {
                \Log::error('Error al subir comprobante: ' . $e->getMessage());
                // No falla la reserva si el comprobante falla, solo lo registra
            }
        }

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
        
        // 🔥 SI SE SUBIÓ COMPROBANTE AHORA, NO IR A UALA
        if ($request->has('subir_ahora') && $request->subir_ahora == '1' && $request->hasFile('comprobante_pago')) {
            $reserva->estado = 'pendiente_pago'; // Esperando verificación del comprobante
            $reserva->save();

            \DB::commit();

            // ENVIAR EMAIL AL PASAJERO - COMPROBANTE RECIBIDO
            try {
                $fechaViaje = \Carbon\Carbon::parse($viaje->fecha_salida)->format('d/m/Y');
                $horaViaje = \Carbon\Carbon::parse($viaje->hora_salida)->format('H:i');
                $conductor = \App\Models\User::find($viaje->conductor_id);

                Mail::to($usuario->email)->send(new UniversalMail(
                    $usuario,
                    'Comprobante recibido - Reserva en verificación',
                    "Tu comprobante ha sido recibido exitosamente.\n\n📍 Detalles del viaje:\n• Fecha: {$fechaViaje}\n• Hora: {$horaViaje}\n• Puestos reservados: {$reserva->cantidad_puestos}\n• Total: $" . number_format($reserva->total, 0, ',', '.') . "\n• Conductor: {$conductor->name}\n\nNuestro equipo verificará tu comprobante de pago pronto. Te notificaremos cuando tu reserva sea confirmada.\n\nGracias por tu paciencia.",
                    'notificacion'
                ));

            } catch (\Exception $e) {
                \Log::error('Error enviando email de comprobante recibido: ' . $e->getMessage());
            }

            // Si es AJAX, devolver JSON
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Comprobante recibido exitosamente',
                    'redirect' => route('pasajero.dashboard')
                ]);
            }

            return redirect()->route('pasajero.dashboard')->with('success', '✅ Tu comprobante ha sido recibido. Nuestro equipo verificará el pago pronto.');
        }

        // 🔥 SI SE SELECCIONÓ UALABIS, SALTAR DIRECTO A LA INTEGRACIÓN
        if ($request->has('metodo_pago') && $request->metodo_pago === 'ualabis') {
            \Log::info('Método de pago UalaBis seleccionado, saltando a integración');
            goto uala_setup;
        }

        // Si el viaje requiere verificación de pasajeros, no crear checkout aún (SOLO SI NO ES UALABIS)
        $registroConductor = \App\Models\RegistroConductor::where('user_id', $viaje->conductor_id)->first();
        if ($registroConductor && $registroConductor->verificar_pasajeros === 1) {
            $reserva->estado = 'pendiente_confirmacion';
            $reserva->save();

            \DB::commit();

            // ENVIAR EMAIL AL PASAJERO - RESERVA PENDIENTE DE CONFIRMACIÓN
            try {
                $fechaViaje = \Carbon\Carbon::parse($viaje->fecha_salida)->format('d/m/Y');
                $horaViaje = \Carbon\Carbon::parse($viaje->hora_salida)->format('H:i');
                $conductor = \App\Models\User::find($viaje->conductor_id);

                Mail::to($usuario->email)->send(new UniversalMail(
                    $usuario,
                    'Reserva creada - Esperando confirmación del conductor',
                    "Tu reserva ha sido creada exitosamente y está esperando la confirmación del conductor.\n\n📍 Detalles del viaje:\n• Fecha: {$fechaViaje}\n• Hora: {$horaViaje}\n• Puestos reservados: {$reserva->cantidad_puestos}\n• Total: $" . number_format($reserva->total, 0, ',', '.') . "\n• Conductor: {$conductor->name}\n\nEl conductor revisará tu solicitud y te notificaremos cuando sea confirmada. Una vez confirmada, podrás proceder al pago.\n\nTe mantendremos informado sobre el estado de tu reserva.",
                    'notificacion'
                ));

                // EMAIL AL CONDUCTOR - NUEVA RESERVA PARA CONFIRMAR
                Mail::to($conductor->email)->send(new UniversalMail(
                    $conductor,
                    'Nueva reserva para confirmar - VoyConvos',
                    "Tienes una nueva reserva esperando tu confirmación.\n\n📍 Detalles del viaje:\n• Fecha: {$fechaViaje}\n• Hora: {$horaViaje}\n• Pasajero: {$usuario->name}\n• Puestos solicitados: {$reserva->cantidad_puestos}\n• Valor: $" . number_format($reserva->total, 0, ',', '.') . "\n\nPor favor, ingresa a tu panel de conductor para revisar y confirmar esta reserva.\n\nUna vez que confirmes, el pasajero podrá proceder al pago.",
                    'notificacion'
                ));

            } catch (\Exception $e) {
                \Log::error('Error enviando emails de reserva pendiente: ' . $e->getMessage());
            }

            return redirect()->route('pasajero.dashboard')->with('success', '✅ Se ha creado su reserva y está esperando la confirmación del conductor. Te hemos enviado un correo con los detalles. Una vez confirmada, podrás proceder al pago.');
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
        $reserva->uala_checkout_id = $checkoutResponse['id'] ?? $checkoutResponse['uuid'] ?? null;
        $reserva->uala_payment_url = $checkoutResponse['payment_url'] ?? $checkoutResponse['checkout_url'] ?? null;
        $reserva->uala_external_reference = $checkoutResponse['external_reference'] ?? null;
        $reserva->estado = 'pendiente_pago'; // Asegurar estado correcto
        $reserva->save();
        
        // Confirmar transacción
        \DB::commit();
        
        \Log::info('=== RESERVA PROCESADA EXITOSAMENTE CON UALA ===', [
            'reserva_id' => $reserva->id,
            'uala_checkout_id' => $reserva->uala_checkout_id,
            'uala_payment_url' => $reserva->uala_payment_url,
            'tipo' => isset($reservaExistente) ? 'EXISTENTE' : 'NUEVA'
        ]);

        // Validar que tengamos la URL de pago
        $paymentUrl = $reserva->uala_payment_url;
        if (!$paymentUrl) {
            // Limpiar la reserva creada si falla
            if (isset($reserva) && $reserva->id) {
                $reserva->delete();
                // Restaurar puestos
                $viaje->puestos_disponibles += $validated['cantidad_puestos'];
                $viaje->save();
            }

            \DB::rollBack();

            return back()->withErrors([
                'error' => 'UalaBis no está disponible en este momento. Por favor, intenta con otro método de pago o contacta a soporte. Error: El servicio de pago no respondió correctamente.'
            ])->with('metodo_pago_fallido', 'ualabis');
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

        // Mensaje más amigable para el usuario
        $mensajeUsuario = 'UalaBis no está disponible en este momento. Por favor, intenta con:';
        $opciones = '
• Transferencia bancaria (recomendado)
• Intenta nuevamente en unos minutos

Si el problema persiste, contacta a soporte.';

        return back()->withErrors([
            'error' => $mensajeUsuario . $opciones
        ])->with('metodo_pago_fallido', 'ualabis');
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
    
    // ENVIAR EMAIL DE PAGO EXITOSO
    try {
        $viaje = $reserva->viaje;
        $pasajero = $reserva->user;
        $conductor = \App\Models\User::find($viaje->conductor_id);
        $fechaViaje = \Carbon\Carbon::parse($viaje->fecha_salida)->format('d/m/Y');
        $horaViaje = \Carbon\Carbon::parse($viaje->hora_salida)->format('H:i');
        
        // EMAIL AL PASAJERO
        Mail::to($pasajero->email)->send(new UniversalMail(
            $pasajero,
            '¡Pago confirmado! - Reserva asegurada',
            "¡Excelente! Tu pago ha sido procesado exitosamente.\n\n📍 Detalles de tu viaje confirmado:\n• Origen: {$viaje->origen}\n• Destino: {$viaje->destino}\n• Fecha: {$fechaViaje}\n• Hora: {$horaViaje}\n• Puestos: {$reserva->cantidad_puestos}\n• Total pagado: $" . number_format($reserva->total, 0, ',', '.') . "\n• Conductor: {$conductor->name}\n\nTu reserva está 100% confirmada. Te contactaremos pronto con más detalles del viaje.\n\n¡Buen viaje!",
            'notificacion'
        ));
        
        // EMAIL AL CONDUCTOR
        Mail::to($conductor->email)->send(new UniversalMail(
            $conductor,
            'Pago recibido - Reserva confirmada',
            "¡Buenas noticias! Se ha confirmado el pago de una reserva.\n\n📍 Detalles:\n• Viaje: {$viaje->origen} → {$viaje->destino}\n• Fecha: {$fechaViaje} a las {$horaViaje}\n• Pasajero: {$pasajero->name}\n• Puestos: {$reserva->cantidad_puestos}\n• Monto: $" . number_format($reserva->total, 0, ',', '.') . "\n\nLa reserva está completamente confirmada. Te contactaremos pronto para coordinar detalles del viaje.",
            'notificacion'
        ));
        
    } catch (Exception $e) {
        \Log::error('Error enviando email de pago exitoso: ' . $e->getMessage());
    }

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
    
    // ENVIAR EMAIL DE PAGO FALLIDO
    try {
        $viaje = $reserva->viaje;
        $pasajero = $reserva->user;
        $fechaViaje = \Carbon\Carbon::parse($viaje->fecha_salida)->format('d/m/Y');
        $horaViaje = \Carbon\Carbon::parse($viaje->hora_salida)->format('H:i');
        
        Mail::to($pasajero->email)->send(new UniversalMail(
            $pasajero,
            'Problema con el pago - VoyConvos',
            "Lamentamos informarte que hubo un problema con tu pago.\n\n📍 Detalles de la reserva:\n• Viaje: {$viaje->origen} → {$viaje->destino}\n• Fecha: {$fechaViaje} a las {$horaViaje}\n• Total: $" . number_format($reserva->total, 0, ',', '.') . "\n\nNo te preocupes, puedes intentar realizar el pago nuevamente o buscar otros viajes disponibles.\n\nSi tienes alguna duda, contáctanos.\n\nGracias por usar VoyConvos.",
            'general'
        ));
        
    } catch (Exception $e) {
        \Log::error('Error enviando email de pago fallido: ' . $e->getMessage());
    }
        
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
    
    // ENVIAR EMAIL DE PAGO PENDIENTE
    try {
        $viaje = $reserva->viaje;
        $pasajero = $reserva->user;
        $fechaViaje = \Carbon\Carbon::parse($viaje->fecha_salida)->format('d/m/Y');
        $horaViaje = \Carbon\Carbon::parse($viaje->hora_salida)->format('H:i');
        
        Mail::to($pasajero->email)->send(new UniversalMail(
            $pasajero,
            'Pago en proceso - VoyConvos',
            "Tu pago está siendo procesado.\n\n📍 Detalles de la reserva:\n• Viaje: {$viaje->origen} → {$viaje->destino}\n• Fecha: {$fechaViaje} a las {$horaViaje}\n• Total: $" . number_format($reserva->total, 0, ',', '.') . "\n\nTe notificaremos tan pronto se confirme el pago.\n\nEsto puede tomar unos minutos. Si tienes alguna duda, contáctanos.\n\nGracias por tu paciencia.",
            'general'
        ));
        
    } catch (Exception $e) {
        \Log::error('Error enviando email de pago pendiente: ' . $e->getMessage());
    }
        
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
    
    $reserva->load('viaje', 'viaje.conductor');
    
    // 🌟 VERIFICAR SI YA CALIFICÓ AL CONDUCTOR
    $calificadoPorPasajero = \App\Models\Calificacion::where([
        'reserva_id' => $reserva->id,
        'usuario_id' => Auth::id(),
        'tipo' => 'pasajero_a_conductor'
    ])->exists();
    
    return view('pasajero.reserva-detalles', compact('reserva', 'calificadoPorPasajero'));
}
public function mostrarViajesDisponibles(Request $request) {
    $usuarioId = auth()->id();
    $usuario = auth()->user();

    $viajesReservados = \DB::table('reservas')
        ->where('user_id', $usuarioId)
        ->pluck('viaje_id')
        ->toArray();

    // Query base
    $query = Viaje::whereDate('fecha_salida', '>=', now())
        ->where('puestos_disponibles', '>', 0)
        ->where('estado', '!=', 'cancelado')
        ->whereNotIn('id', $viajesReservados)
        ->with(['conductor' => function($query) {
            $query->leftJoin('vista_calificaciones_usuarios', function($join) {
                $join->on('users.id', '=', 'vista_calificaciones_usuarios.usuario_id')
                     ->where('vista_calificaciones_usuarios.tipo', '=', 'conductor');
            })
            ->select('users.*',
                     'vista_calificaciones_usuarios.total_calificaciones',
                     'vista_calificaciones_usuarios.promedio_calificacion as calificacion_promedio');
        }]);

    // ✅ FILTRO MEJORADO: Aplicar filtros básicos primero
    if ($request->filled('puestos_minimos')) {
        $query->where('puestos_disponibles', '>=', $request->puestos_minimos);
    }

    if ($request->filled('fecha_salida')) {
        $query->whereDate('fecha_salida', $request->fecha_salida);
    }

    // Obtener TODOS los viajes que cumplen los criterios básicos
    $viajesDisponibles = $query->get();

    // ✅ FILTRADO FLEXIBLE POR CIUDADES (después de obtener los viajes)
    if ($request->filled('ciudad_origen')) {
        $ciudadOrigenBuscada = $this->normalizarCiudad($request->ciudad_origen);
        
        $viajesDisponibles = $viajesDisponibles->filter(function($viaje) use ($ciudadOrigenBuscada) {
            $ciudadViaje = $this->normalizarCiudad($this->extraerCiudad($viaje->origen_direccion));
            
            // Coincidencia flexible: contiene o es similar
            return stripos($ciudadViaje, $ciudadOrigenBuscada) !== false 
                   || stripos($ciudadOrigenBuscada, $ciudadViaje) !== false
                   || $this->esSimilar($ciudadViaje, $ciudadOrigenBuscada);
        });
    }

    if ($request->filled('ciudad_destino')) {
        $ciudadDestinoBuscada = $this->normalizarCiudad($request->ciudad_destino);
        
        $viajesDisponibles = $viajesDisponibles->filter(function($viaje) use ($ciudadDestinoBuscada) {
            $ciudadViaje = $this->normalizarCiudad($this->extraerCiudad($viaje->destino_direccion));
            
            // Coincidencia flexible: contiene o es similar
            return stripos($ciudadViaje, $ciudadDestinoBuscada) !== false 
                   || stripos($ciudadDestinoBuscada, $ciudadViaje) !== false
                   || $this->esSimilar($ciudadViaje, $ciudadDestinoBuscada);
        });
    }

    // Determinar ordenamiento
    $ordenamiento = $request->get('ordenar', 'fecha');

    if ($ordenamiento === 'cercania') {
        $viajesDisponibles = $viajesDisponibles->map(function($viaje) {
            $viaje->distancia_km = $this->calcularDistanciaDesdeReferencia($viaje);
            return $viaje;
        })->sortBy('distancia_km');
    } elseif ($ordenamiento === 'precio') {
        $viajesDisponibles = $viajesDisponibles->sortBy('valor_persona');
    } else {
        $viajesDisponibles = $viajesDisponibles->sortBy('fecha_salida');
    }

    // ✅ OBTENER CIUDADES PARA LOS FILTROS (usando extraerCiudad)
    $ciudadesOrigen = Viaje::whereDate('fecha_salida', '>=', now())
        ->where('puestos_disponibles', '>', 0)
        ->where('estado', '!=', 'cancelado')
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
        ->where('estado', '!=', 'cancelado')
        ->distinct()
        ->pluck('destino_direccion')
        ->map(function($direccion) {
            return $this->extraerCiudad($direccion);
        })
        ->filter()
        ->unique()
        ->sort()
        ->values();

    // Calificaciones del usuario
    $calificacionesUsuario = \DB::table('vista_calificaciones_usuarios')
        ->where('usuario_id', $usuarioId)
        ->first();

    if (!$calificacionesUsuario) {
        $calificacionesUsuario = (object) [
            'usuario_id' => $usuarioId,
            'tipo' => null,
            'total_calificaciones' => 0,
            'promedio_calificacion' => 0
        ];
    }

    // Verificar estado del perfil
    $estadoVerificacion = $this->verificarEstadoPerfil($usuario);

    return view('pasajero.viajesDisponibles', compact(
        'viajesDisponibles', 
        'ciudadesOrigen', 
        'ciudadesDestino', 
        'calificacionesUsuario',
        'estadoVerificacion'
    ));
}
/**
 * Normalizar ciudad para comparación
 */
private function normalizarCiudad($ciudad)
{
    if (!$ciudad) return '';
    
    // Convertir a minúsculas
    $ciudad = mb_strtolower($ciudad, 'UTF-8');
    
    // Remover acentos
    $ciudad = $this->removerAcentos($ciudad);
    
    // Remover caracteres especiales y espacios múltiples
    $ciudad = preg_replace('/[^a-z0-9\s]/', '', $ciudad);
    $ciudad = preg_replace('/\s+/', ' ', $ciudad);
    
    return trim($ciudad);
}

/**
 * Remover acentos
 */
private function removerAcentos($str)
{
    $acentos = [
        'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u',
        'Á' => 'A', 'É' => 'E', 'Í' => 'I', 'Ó' => 'O', 'Ú' => 'U',
        'ñ' => 'n', 'Ñ' => 'N',
        'ü' => 'u', 'Ü' => 'U'
    ];
    
    return strtr($str, $acentos);
}

/**
 * Verificar si dos ciudades son similares (Levenshtein distance)
 */
private function esSimilar($ciudad1, $ciudad2, $umbral = 3)
{
    if (empty($ciudad1) || empty($ciudad2)) return false;
    
    // Calcular distancia de Levenshtein
    $distancia = levenshtein(
        substr($ciudad1, 0, 255), 
        substr($ciudad2, 0, 255)
    );
    
    return $distancia <= $umbral;
}

/**
 * Verificar el estado del perfil del usuario para determinar si puede acceder a funcionalidades
 */
private function verificarEstadoPerfil($usuario)
{
    // Campos requeridos para verificación
    $camposRequeridos = ['name', 'email', 'pais', 'ciudad', 'dni', 'celular', 'foto', 'dni_foto', 'dni_foto_atras'];
    
    // Verificar si todos los campos están completos
    $perfilCompleto = true;
    $camposFaltantes = [];
    
    foreach ($camposRequeridos as $campo) {
        if (empty($usuario->$campo)) {
            $perfilCompleto = false;
            $camposFaltantes[] = $campo;
        }
    }
    
    return [
        'verificado' => $usuario->verificado,
        'perfil_completo' => $perfilCompleto,
        'campos_faltantes' => $camposFaltantes,
        'puede_acceder' => $usuario->verificado, // Solo puede acceder si está verificado
        'mensaje' => $this->obtenerMensajeVerificacion($usuario->verificado, $perfilCompleto)
    ];
}

/**
 * Obtener el mensaje apropiado según el estado de verificación
 */
private function obtenerMensajeVerificacion($verificado, $perfilCompleto)
{
    if ($verificado) {
        return null; // Usuario verificado, no necesita mensaje
    }
    
    if ($perfilCompleto) {
        return [
            'tipo' => 'pendiente',
            'titulo' => 'Verificación Pendiente',
            'texto' => 'Tu perfil está siendo revisado. Pronto podrás acceder a todas las funcionalidades.',
            'icono' => 'fas fa-clock'
        ];
    }
    
    return [
        'tipo' => 'incompleto',
        'titulo' => 'Perfil Incompleto',
        'texto' => 'Completa tu perfil para acceder a los detalles y chat de los viajes.',
        'icono' => 'fas fa-user-edit',
        'boton' => [
            'texto' => 'Actualizar Perfil',
            'ruta' => 'pasajero.perfil.edit' // Ajusta esta ruta según tu aplicación
        ]
    ];
}
private function extraerCiudad($direccion)
{
    if (!$direccion) return '';

    // Limpiar códigos postales alfanuméricos (B1650, C1405, C1416IEB, etc)
    $direccion = preg_replace('/\b[A-Z]\d{4}[A-Z]*\b\s*/i', '', $direccion);

    // Formato esperado: "Calle 123, Villa Maipú, Provincia de Buenos Aires, Argentina"
    $partes = array_map('trim', explode(',', $direccion));
    $count = count($partes);

    // Si tiene 4 o más partes: "Calle 123, Ciudad, Provincia, País"
    // Tomar ciudad y provincia (penúltimas 2)
    if ($count >= 4) {
        $ciudad = trim($partes[$count - 3]);
        $provincia = trim($partes[$count - 2]);

        // Limpiar números de calle de la ciudad si existen
        $ciudad = preg_replace('/^[^\s]+\s+\d+\s*,?\s*/', '', $ciudad);

        return $ciudad . ', ' . $provincia;
    }

    // Si tiene 3 partes: "Ciudad, Provincia, País"
    if ($count >= 3) {
        $ciudad = trim($partes[$count - 3]);
        $provincia = trim($partes[$count - 2]);

        // Limpiar números de calle si existen
        $ciudad = preg_replace('/^[^\s]+\s+\d+\s*,?\s*/', '', $ciudad);

        return $ciudad . ', ' . $provincia;
    }

    // Si tiene 2 partes: "Ciudad, País" o "Provincia, País"
    elseif ($count >= 2) {
        $ciudad = trim($partes[$count - 2]);

        // Limpiar números de calle si existen
        $ciudad = preg_replace('/^[^\s]+\s+\d+\s*,?\s*/', '', $ciudad);

        $resultado = $ciudad;
    }

    // Filtrar si el resultado tiene más de 2 números (probablemente es una dirección con calle)
    if ($resultado) {
        // Contar cuántos números tiene
        preg_match_all('/\d/', $resultado, $matches);
        if (count($matches[0]) > 2) {
            return ''; // Descartar si tiene más de 2 números
        }
    }

    return $resultado;
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

    // Cargar calificaciones del conductor si existe
    if ($viaje->conductor) {
        $calificacionesConductor = \DB::table('vista_calificaciones_usuarios')
            ->where('usuario_id', $viaje->conductor_id)
            ->where('tipo', 'conductor')
            ->first();

        // Asignar calificaciones al conductor
        $viaje->conductor->total_calificaciones = $calificacionesConductor->total_calificaciones ?? 0;
        $viaje->conductor->calificacion_promedio = $calificacionesConductor->promedio_calificacion ?? 0;
    }

    // Obtener calificaciones del usuario autenticado
    $usuarioId = auth()->id();
    $calificacionesUsuario = \DB::table('vista_calificaciones_usuarios')
        ->where('usuario_id', $usuarioId)
        ->first();

    // Si no tiene calificaciones, crear objeto vacío con valores por defecto
    if (!$calificacionesUsuario) {
        $calificacionesUsuario = (object) [
            'usuario_id' => $usuarioId,
            'tipo' => null,
            'total_calificaciones' => 0,
            'promedio_calificacion' => 0
        ];
    }

    // ✅ Pasar el precio usado además de las otras variables
    return view('pasajero.resumen-reserva', compact('viaje', 'cantidad', 'total', 'precio', 'calificacionesUsuario'));
}

/**
 * Calcular distancia desde un punto de referencia (centro de Buenos Aires por defecto)
 * @param Viaje $viaje El viaje del cual calcular la distancia
 * @return float Distancia en kilómetros
 */
private function calcularDistanciaDesdeReferencia($viaje)
{
    // Punto de referencia: Centro de Buenos Aires, Argentina
    $latReferencia = -34.6037;
    $lngReferencia = -58.3816;

    // Si el usuario tiene coordenadas guardadas, usarlas como referencia
    $usuario = auth()->user();
    if (isset($usuario->origen_lat) && isset($usuario->origen_lng)) {
        $latReferencia = $usuario->origen_lat;
        $lngReferencia = $usuario->origen_lng;
    }

    // Calcular distancia desde el origen del viaje
    return $this->calcularDistancia(
        $latReferencia,
        $lngReferencia,
        $viaje->origen_lat,
        $viaje->origen_lng
    );
}

/**
 * Calcular distancia entre dos coordenadas GPS usando la fórmula de Haversine
 * @param float $lat1 Latitud del punto 1
 * @param float $lng1 Longitud del punto 1
 * @param float $lat2 Latitud del punto 2
 * @param float $lng2 Longitud del punto 2
 * @return float Distancia en kilómetros
 */
private function calcularDistancia($lat1, $lng1, $lat2, $lng2)
{
    // Validar que todas las coordenadas existan y sean números
    if (!$lat1 || !$lng1 || !$lat2 || !$lng2) {
        return 999999; // Distancia muy grande para poner al final
    }

    $radioTierra = 6371; // Radio de la Tierra en kilómetros

    // Convertir grados a radianes
    $dLat = deg2rad($lat2 - $lat1);
    $dLng = deg2rad($lng2 - $lng1);

    // Fórmula de Haversine
    $a = sin($dLat / 2) * sin($dLat / 2) +
         cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
         sin($dLng / 2) * sin($dLng / 2);

    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    $distancia = $radioTierra * $c;

    return round($distancia, 2); // Redondear a 2 decimales
}

/**
 * Webhook para notificaciones de pago de UalaBis
 * Esta ruta debe estar excluida de CSRF en VerifyCsrfToken middleware
 */
public function handleUalaWebhook(Request $request)
{
    \Log::info('=== WEBHOOK UALABIS RECIBIDO ===', [
        'headers' => $request->headers->all(),
        'body' => $request->all(),
        'raw_body' => $request->getContent()
    ]);

    try {
        // Obtener datos del webhook
        $data = $request->all();

        // Validar que tengamos los datos necesarios
        if (!isset($data['uuid']) && !isset($data['id'])) {
            \Log::error('Webhook UalaBis: No se recibió UUID o ID');
            return response()->json(['error' => 'UUID o ID requerido'], 400);
        }

        // Buscar la reserva por el UUID de UalaBis
        $uuid = $data['uuid'] ?? $data['id'] ?? null;
        $reserva = Reserva::where('uala_checkout_id', $uuid)
                         ->orWhere('uala_bis_uuid', $uuid)
                         ->first();

        if (!$reserva) {
            \Log::error('Webhook UalaBis: Reserva no encontrada', ['uuid' => $uuid]);
            return response()->json(['error' => 'Reserva no encontrada'], 404);
        }

        // Obtener el estado del pago desde el webhook
        $estado = $data['status'] ?? $data['payment_status'] ?? 'unknown';

        \Log::info('Procesando webhook UalaBis', [
            'reserva_id' => $reserva->id,
            'estado_recibido' => $estado,
            'estado_anterior' => $reserva->estado
        ]);

        // Procesar según el estado
        switch (strtolower($estado)) {
            case 'approved':
            case 'paid':
            case 'success':
                $reserva->estado = 'confirmada';
                $reserva->uala_payment_status = 'approved';
                $reserva->uala_payment_date = now();
                $reserva->save();

                // Enviar emails de confirmación
                $this->enviarEmailsPagoExitoso($reserva);

                // Verificar si el viaje está completo
                $this->verificarViajeCompleto($reserva->viaje);

                \Log::info('Pago UalaBis aprobado', ['reserva_id' => $reserva->id]);
                break;

            case 'rejected':
            case 'cancelled':
            case 'failed':
                $reserva->estado = 'cancelada';
                $reserva->uala_payment_status = 'rejected';
                $reserva->save();

                \Log::info('Pago UalaBis rechazado', ['reserva_id' => $reserva->id]);
                break;

            case 'pending':
            case 'in_process':
                $reserva->estado = 'pendiente_pago';
                $reserva->uala_payment_status = 'pending';
                $reserva->save();

                \Log::info('Pago UalaBis pendiente', ['reserva_id' => $reserva->id]);
                break;

            default:
                \Log::warning('Estado de pago UalaBis desconocido', [
                    'estado' => $estado,
                    'reserva_id' => $reserva->id
                ]);
        }

        return response()->json(['success' => true], 200);

    } catch (\Exception $e) {
        \Log::error('Error procesando webhook UalaBis', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return response()->json(['error' => 'Error interno'], 500);
    }
}

/**
 * Enviar emails de pago exitoso (extraído para reutilización)
 */
private function enviarEmailsPagoExitoso($reserva)
{
    try {
        $viaje = $reserva->viaje;
        $pasajero = $reserva->user;
        $conductor = \App\Models\User::find($viaje->conductor_id);
        $fechaViaje = \Carbon\Carbon::parse($viaje->fecha_salida)->format('d/m/Y');
        $horaViaje = \Carbon\Carbon::parse($viaje->hora_salida)->format('H:i');

        // EMAIL AL PASAJERO
        Mail::to($pasajero->email)->send(new UniversalMail(
            $pasajero,
            '¡Pago confirmado! - Reserva asegurada',
            "¡Excelente! Tu pago ha sido procesado exitosamente.\n\n📍 Detalles de tu viaje confirmado:\n• Origen: {$viaje->origen}\n• Destino: {$viaje->destino}\n• Fecha: {$fechaViaje}\n• Hora: {$horaViaje}\n• Puestos: {$reserva->cantidad_puestos}\n• Total pagado: $" . number_format($reserva->total, 0, ',', '.') . "\n• Conductor: {$conductor->name}\n\nTu reserva está 100% confirmada. Te contactaremos pronto con más detalles del viaje.\n\n¡Buen viaje!",
            'notificacion'
        ));

        // EMAIL AL CONDUCTOR
        Mail::to($conductor->email)->send(new UniversalMail(
            $conductor,
            'Pago recibido - Reserva confirmada',
            "¡Buenas noticias! Se ha confirmado el pago de una reserva.\n\n📍 Detalles:\n• Viaje: {$viaje->origen} → {$viaje->destino}\n• Fecha: {$fechaViaje} a las {$horaViaje}\n• Pasajero: {$pasajero->name}\n• Puestos: {$reserva->cantidad_puestos}\n• Monto: $" . number_format($reserva->total, 0, ',', '.') . "\n\nLa reserva está completamente confirmada. Te contactaremos pronto para coordinar detalles del viaje.",
            'notificacion'
        ));

    } catch (\Exception $e) {
        \Log::error('Error enviando emails de pago exitoso: ' . $e->getMessage());
    }
}

}
