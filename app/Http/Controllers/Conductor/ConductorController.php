<?php

namespace App\Http\Controllers\conductor;

use Illuminate\Http\Request;
use App\Models\RegistroConductor;
use App\Models\Reserva;  // ← ESTO FALTA
use App\Models\Viaje; 
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ConductorController extends Controller
{
public function gestion() 
{
    $userId = auth()->id();
    $registro = RegistroConductor::where('user_id', $userId)->first();
    
    // Obtener configuraciones más recientes de gasolina y comisión
    $configuracionGasolina = DB::table('configuracion_admin')
        ->select('id_configuracion', 'nombre', 'valor', 'created_at', 'updated_at')
        ->where('nombre', 'gasolina')
        ->orderBy('created_at', 'desc')
        ->first();
        
    $configuracionComision = DB::table('configuracion_admin')
        ->select('id_configuracion', 'nombre', 'valor', 'created_at', 'updated_at')
        ->where('nombre', 'comision')
        ->orderBy('created_at', 'desc')
        ->first();
    
    return view('conductor.gestion', [
        'marca' => $registro ? $registro->marca_vehiculo . ' ' . $registro->modelo_vehiculo : null,
        'consumo_por_galon' => $registro ? $registro->consumo_por_galon : null,
        'anio_vehiculo' => $registro ? $registro->anio_vehiculo : null,
        'numero_puestos' => $registro ? $registro->numero_puestos : null,
        'patente' => $registro ? $registro->patente : null,
        'registro_completo' => $registro ? true : false,
        
        // Configuraciones de administrador
        'precio_gasolina' => $configuracionGasolina ? $configuracionGasolina->valor : null,
        'comision_plataforma' => $configuracionComision ? $configuracionComision->valor : null,
        'config_gasolina' => $configuracionGasolina,
        'config_comision' => $configuracionComision,
    ]);
}
public function verificarPasajero(Request $request, Reserva $reserva)
{
    $request->validate([
        'accion' => 'required|in:verificar,rechazar'
    ]);
    
    // Verificar que el usuario actual sea el conductor del viaje
    if ($reserva->viaje->conductor_id !== auth()->id()) {
        abort(403, 'No tienes permiso para realizar esta acción.');
    }
    
    // Verificar que la reserva esté en un estado válido para ser modificada
    if (!in_array($reserva->estado, ['pendiente_confirmacion', 'pendiente_pago'])) {
        return redirect()->back()->with('error', 'No se puede modificar esta reserva en su estado actual.');
    }
    
    $viaje = $reserva->viaje;
    
    if ($request->accion === 'verificar') {
        $reserva->update([
            'estado' => 'pendiente_pago',
            'updated_at' => now()
        ]);
        
        $mensaje = 'Pasajero aprobado. Estado cambiado a pendiente de pago.';
        $tipo = 'success';
        
    } elseif ($request->accion === 'rechazar') {
        // Actualizar el estado a cancelado por conductor
        $reserva->update([
            'estado' => 'cancelar_por_conductor',
            'updated_at' => now()
        ]);
        
        // Devolver los puestos al viaje (incrementar puestos disponibles)
        $viaje->increment('puestos_disponibles', $reserva->cantidad_puestos);
        
        $mensaje = 'Pasajero rechazado exitosamente. Los puestos han sido liberados.';
        $tipo = 'success';
        
        // Opcional: Enviar notificación al pasajero
        // $this->notificarRechazoAlPasajero($reserva);
    }
    
    // Verificar y actualizar el estado del viaje
    $this->actualizarEstadoViaje($viaje);
    
    return redirect()->back()->with($tipo, $mensaje);
}

/**
 * Actualiza el estado del viaje basado en las reservas pendientes de confirmación
 */
private function actualizarEstadoViaje($viaje)
{
    // Contar reservas que aún están pendientes de confirmación
    $reservasPendientesConfirmacion = $viaje->reservas()
        ->where('estado', 'pendiente_confirmacion')
        ->count();
    
    if ($reservasPendientesConfirmacion > 0) {
        // Hay reservas esperando confirmación - cambiar a pendiente_confirmacion
        if ($viaje->estado !== 'pendiente_confirmacion') {
            $viaje->update([
                'estado' => 'pendiente_confirmacion',
                'updated_at' => now()
            ]);
            
            \Log::info("Viaje {$viaje->id} cambió a 'pendiente_confirmacion' - {$reservasPendientesConfirmacion} reservas esperando confirmación");
        }
    } else {
        // No hay reservas pendientes de confirmación - cambiar a pendiente
        if ($viaje->estado !== 'pendiente') {
            $viaje->update([
                'estado' => 'pendiente',
                'updated_at' => now()
            ]);
            
            \Log::info("Viaje {$viaje->id} cambió a 'pendiente' - todas las reservas han sido procesadas");
        }
    }
}

public function iniciarViaje(Viaje $viaje)
{
    try {
        // 🔒 Verificar permisos
        if ((int)$viaje->conductor_id !== (int)auth()->id()) {
            \Log::warning('Acceso denegado al viaje', [
                'viaje_id' => $viaje->id,
                'conductor_id_viaje' => $viaje->conductor_id,
                'usuario_id' => auth()->id()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'No tienes permisos para iniciar este viaje'
            ], 403);
        }

        // 🔍 Debug del estado actual
        \Log::info('Debug estado del viaje antes de verificar', [
            'viaje_id' => $viaje->id,
            'estado_actual' => $viaje->estado,
            'conductor_id' => auth()->id()
        ]);

        // 🔍 Verificar estado actual del viaje (solo pendiente puede iniciarse)
        if (!in_array($viaje->estado, ['pendiente', 'listo_para_iniciar'])) {
            $mensajes = [
                'iniciado' => 'El viaje ya está iniciado',
                'en_curso' => 'El viaje ya está en curso',
                'finalizado' => 'El viaje ya está finalizado',
                'cancelado' => 'El viaje está cancelado'
            ];

            $mensaje = $mensajes[$viaje->estado] ?? 'El viaje no puede ser iniciado desde el estado actual: ' . $viaje->estado;
            
            \Log::warning('Intento de iniciar viaje con estado inválido', [
                'viaje_id' => $viaje->id,
                'estado_actual' => $viaje->estado,
                'mensaje' => $mensaje
            ]);

            return response()->json([
                'success' => false,
                'message' => $mensaje
            ], 400);
        }

        // 🕐 Verificar si hay reservas confirmadas
        $reservasConfirmadas = $viaje->reservas()->whereIn('estado', ['confirmado', 'pendiente'])->count();
        
        \Log::info('Reservas encontradas', [
            'viaje_id' => $viaje->id,
            'reservas_confirmadas' => $reservasConfirmadas
        ]);

        // 🚀 Cambiar estado a "iniciado"
        $viaje->update([
            'estado' => 'iniciado',
            'fecha_inicio_proceso' => now()
        ]);

        // ✅ Verificar que el estado se guardó correctamente
        $viaje->refresh(); // Recargar desde BD
        
        \Log::info('Viaje iniciado exitosamente', [
            'viaje_id' => $viaje->id,
            'conductor_id' => auth()->id(),
            'reservas_confirmadas' => $reservasConfirmadas,
            'estado_anterior' => 'pendiente',
            'estado_nuevo' => $viaje->estado
        ]);

        // 🔄 Generar URL de verificación
        try {
            $redirectUrl = route('conductor.viaje.verificar-pasajeros', $viaje->id);
            
            \Log::info('Ruta de verificación generada', [
                'viaje_id' => $viaje->id,
                'redirect_url' => $redirectUrl
            ]);
            
        } catch (\Exception $routeException) {
            \Log::error('Ruta verificar-pasajeros no encontrada', [
                'viaje_id' => $viaje->id,
                'error' => $routeException->getMessage()
            ]);

            $redirectUrl = route('conductor.viaje.detalle', $viaje->id) . '?accion=verificar';
            
            \Log::info('Usando ruta fallback', [
                'viaje_id' => $viaje->id,
                'fallback_url' => $redirectUrl
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Viaje iniciado. Redirigiendo a verificación de pasajeros...',
            'redirect_url' => $redirectUrl,
            'viaje_id' => $viaje->id,
            'estado' => $viaje->estado
        ]);

    } catch (\Exception $e) {
        \Log::error('Error crítico al iniciar viaje', [
            'viaje_id' => $viaje->id ?? 'desconocido',
            'conductor_id' => auth()->id(),
            'error' => $e->getMessage(),
            'archivo' => $e->getFile(),
            'linea' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Error interno del servidor. Inténtalo nuevamente.'
        ], 500);
    }
}

// 🔍 MÉTODO ADICIONAL PARA DEBUG (temporal)
public function debugViaje(Viaje $viaje)
{
    return response()->json([
        'viaje' => [
            'id' => $viaje->id,
            'conductor_id' => $viaje->conductor_id,
            'conductor_id_type' => gettype($viaje->conductor_id),
            'estado' => $viaje->estado,
        ],
        'usuario' => [
            'id' => auth()->id(),
            'id_type' => gettype(auth()->id()),
            'user' => auth()->user(),
        ],
        'comparaciones' => [
            'estricta' => $viaje->conductor_id === auth()->id(),
            'suave' => $viaje->conductor_id == auth()->id(),
            'int_cast' => (int)$viaje->conductor_id === (int)auth()->id(),
        ]
    ]);
}

// 2. MÉTODO PARA MOSTRAR VISTA DE VERIFICACIÓN
public function verificarPasajeros(Viaje $viaje)
{
    // Verificar permisos
    if ($viaje->conductor_id !== auth()->id()) {
        abort(403, 'No tienes permisos para acceder a este viaje');
    }

    // Verificar que el viaje esté iniciado
    if ($viaje->estado !== 'iniciado') {
        return redirect()->route('conductor.viaje.detalle', $viaje->id)
            ->with('error', 'El viaje debe estar iniciado para continuar');
    }

    try {
        // 🎯 CAMBIO DIRECTO: Pasar automáticamente a "en_curso"
        $viaje->update([
            'estado' => 'en_curso',
            'fecha_inicio_real' => $viaje->fecha_inicio_real ?? now(), // Solo si no existe
        ]);

        // 📝 Log para seguimiento
        \Log::info('Viaje pasado automáticamente a en_curso', [
            'viaje_id' => $viaje->id,
            'conductor_id' => auth()->id(),
            'timestamp' => now()
        ]);

        // 🚀 REDIRECCIÓN DIRECTA a viaje en curso
        return redirect()->route('conductor.viaje.en-curso', $viaje->id)
            ->with('success', 'Viaje iniciado correctamente. ¡Buen viaje!');

    } catch (\Exception $e) {
        \Log::error('Error al iniciar viaje automáticamente', [
            'viaje_id' => $viaje->id,
            'error' => $e->getMessage(),
            'linea' => $e->getLine()
        ]);

        return redirect()->route('conductor.viaje.detalle', $viaje->id)
            ->with('error', 'Error al iniciar el viaje. Intenta nuevamente.');
    }
}
// 3. MÉTODO PARA PROCESAR VERIFICACIÓN DE ASISTENCIA
public function procesarAsistencia(Request $request, Viaje $viaje) 
{
    $request->validate([
        'asistencias' => 'required|array',
        'asistencias.*' => 'required|in:presente,ausente'
    ]);

    try {
        \DB::beginTransaction();

        $pasajerosPresentes = 0;
        $pasajerosAusentes = 0;

        foreach ($request->asistencias as $reservaId => $estado) {
            $reserva = $viaje->reservas()->findOrFail($reservaId);
            
            // 🔥 ACTUALIZAR ESTADO SEGÚN LA LÓGICA CORRECTA
            if ($estado === 'presente') {
                $estadoReserva = 'en_curso';  // Pasajero presente y viajando
                $pasajerosPresentes += $reserva->cantidad_puestos;
            } else {
                $estadoReserva = 'ausente';   // Pasajero no se presentó
                $pasajerosAusentes += $reserva->cantidad_puestos;
                
                // Liberar puestos de pasajeros ausentes
                $viaje->puestos_disponibles += $reserva->cantidad_puestos;
            }

            // Actualizar reserva con estado correcto
            $reserva->update([
                'estado' => $estadoReserva,                    // ← CAMPO PRINCIPAL
                'asistencia' => $estado,                       // ← MANTENER SI LO USAS
                'verificado_por_conductor' => true,
                'fecha_verificacion' => now()
            ]);

            \Log::info("Reserva {$reservaId} actualizada", [
                'estado_anterior' => $reserva->getOriginal('estado'),
                'estado_nuevo' => $estadoReserva,
                'asistencia' => $estado,
                'cantidad_puestos' => $reserva->cantidad_puestos
            ]);
        }

        // Actualizar información del viaje - CAMBIAR A 'en_curso'
        $viaje->update([
            'estado' => 'en_curso',                          // ← CAMBIO PRINCIPAL
            'pasajeros_presentes' => $pasajerosPresentes,
            'pasajeros_ausentes' => $pasajerosAusentes,
        ]);

        \DB::commit();

        \Log::info('Asistencia verificada y viaje en curso', [
            'viaje_id' => $viaje->id,
            'estado_anterior' => $viaje->getOriginal('estado'),
            'estado_nuevo' => 'en_curso',
            'presentes' => $pasajerosPresentes,
            'ausentes' => $pasajerosAusentes,
            'puestos_liberados' => $pasajerosAusentes,
            'puestos_disponibles_final' => $viaje->puestos_disponibles
        ]);

        // Verificar que la ruta existe
        try {
            $rutaDestino = route('conductor.viaje.en-curso', $viaje->id);
        } catch (\Exception $routeException) {
            // Si la ruta no existe, redirigir al dashboard
            \Log::warning('Ruta viaje.en-curso no existe, redirigiendo a dashboard', [
                'viaje_id' => $viaje->id,
                'error' => $routeException->getMessage()
            ]);
            $rutaDestino = route('conductor.dashboard');
        }

        \Log::info('Redirigiendo después de verificación', [
            'viaje_id' => $viaje->id,
            'ruta_destino' => $rutaDestino
        ]);

        return redirect()->to($rutaDestino)
            ->with('success', "✅ Viaje iniciado exitosamente. Presentes: {$pasajerosPresentes}, Ausentes: {$pasajerosAusentes}");

    } catch (\Exception $e) {
        \DB::rollBack();

        \Log::error('Error al procesar asistencia', [
            'viaje_id' => $viaje->id,
            'usuario_id' => auth()->id(),
            'error' => $e->getMessage(),
            'linea' => $e->getLine(),
            'archivo' => $e->getFile(),
            'datos_request' => $request->all()
        ]);

        return back()
            ->withErrors(['error' => 'Error al procesar la verificación: ' . $e->getMessage()])
            ->withInput();
    }
}
/**
 * Mostrar página de viaje en curso con todas las estadísticas
 */
public function viajeEnCurso(Viaje $viaje)
{
    try {
        // Verificar permisos
        if ((int)$viaje->conductor_id !== (int)auth()->id()) {
            return redirect()
                ->route('conductor.dashboard')
                ->with('error', 'No tienes permisos para ver este viaje.');
        }

        // Verificar que el viaje esté en curso
        if ($viaje->estado !== 'en_curso') {
            \Log::info('Acceso a viaje que no está en curso', [
                'viaje_id' => $viaje->id,
                'estado_actual' => $viaje->estado
            ]);
            
            return redirect()
                ->route('conductor.viaje.detalle', $viaje->id)
                ->with('error', 'Este viaje no está en curso.');
        }

        // Cargar datos relacionados
        $viaje->load(['reservas.user']);
        
        // 🔥 CALCULAR TODAS LAS ESTADÍSTICAS QUE NECESITA LA VISTA
        
        // Separar reservas por estado de asistencia
        $reservasPresentas = $viaje->reservas->where('asistencia', 'presente');
        $reservasAusentes = $viaje->reservas->where('asistencia', 'ausente');
        
        // Contar pasajeros y puestos
        $pasajerosPresentes = $reservasPresentas->sum('cantidad_puestos');
        $pasajerosAusentes = $reservasAusentes->sum('cantidad_puestos');
        $puestosOcupados = $pasajerosPresentes; // Los puestos que realmente están ocupados
        
        // Calcular ingresos reales (solo de los presentes)
        $ingresosReales = $reservasPresentas->sum(function($reserva) {
            return $reserva->cantidad_puestos * $reserva->precio_por_persona;
        });
        
        // Determinar hora de inicio (pueden ser varios campos)
        $horaInicio = $viaje->fecha_inicio_real ?? 
                     $viaje->fecha_inicio_proceso ?? 
                     $viaje->updated_at; // Como fallback
        
        // Crear array de estadísticas que espera la vista
        $estadisticas = [
            'presentes' => $pasajerosPresentes,
            'ausentes' => $pasajerosAusentes,
            'puestos_ocupados' => $puestosOcupados,
            'ingresos_reales' => $ingresosReales,
            'hora_inicio' => $horaInicio->toISOString(), // Para JavaScript
            'total_reservas' => $viaje->reservas->count(),
            'total_puestos_originales' => $viaje->reservas->sum('cantidad_puestos'),
        ];

        // 🔍 DEBUG: Log de estadísticas para verificar
        \Log::info('Estadísticas de viaje en curso', [
            'viaje_id' => $viaje->id,
            'estadisticas' => $estadisticas,
            'reservas_total' => $viaje->reservas->count(),
            'reservas_presentes' => $reservasPresentas->count(),
            'reservas_ausentes' => $reservasAusentes->count()
        ]);

        return view('conductor.viaje-en-curso', compact('viaje', 'estadisticas'));

    } catch (\Exception $e) {
        \Log::error('Error al mostrar viaje en curso', [
            'viaje_id' => $viaje->id,
            'error' => $e->getMessage(),
            'linea' => $e->getLine()
        ]);

        return redirect()
            ->route('conductor.dashboard')
            ->with('error', 'Error al cargar el viaje en curso.');
    }
}
// 🔍 CONTROLADOR TEMPORAL PARA DEBUG

public function verViajeFinalizados($viajeId) 
{
    try {
        // 🔍 PASO 1: Cargar viaje SIN calificaciones primero
        $viaje = \App\Models\Viaje::with([
            'reservas.user', 
            'conductor.registroConductor'
        ])
        ->where('id', $viajeId)
        ->where('conductor_id', auth()->id())
        ->firstOrFail();

        \Log::info('Viaje cargado exitosamente', [
            'viaje_id' => $viajeId,
            'reservas_count' => $viaje->reservas->count()
        ]);

        // 🔍 PASO 2: Intentar cargar calificaciones manualmente
        foreach ($viaje->reservas as $reserva) {
            try {
                // Verificar si la relación existe
                if (method_exists($reserva, 'calificaciones')) {
                    $calificaciones = $reserva->calificaciones;
                    \Log::info('Calificaciones encontradas', [
                        'reserva_id' => $reserva->id,
                        'calificaciones_count' => $calificaciones->count()
                    ]);
                } else {
                    \Log::error('Método calificaciones() no existe en modelo Reserva');
                }
            } catch (\Exception $e) {
                \Log::error('Error al cargar calificaciones para reserva', [
                    'reserva_id' => $reserva->id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        // 📊 Calcular estadísticas básicas (sin calificaciones por ahora)
        $estadisticas = [
            'total_pasajeros' => $viaje->reservas->count(),
            'pasajeros_presentes' => $viaje->reservas->where('asistencia', 'presente')->count(),
            'pasajeros_ausentes' => $viaje->reservas->where('asistencia', 'ausente')->count(),
            'pasajeros_finalizados' => $viaje->reservas->where('estado', 'finalizado')->count(),
            'ingresos_totales' => $viaje->reservas->sum(function($reserva) {
                return $reserva->valor_pagado ?? ($reserva->precio_por_persona * $reserva->cantidad_puestos);
            }),
            'puestos_vendidos' => $viaje->reservas->sum('cantidad_puestos'),
            'duracion_viaje' => $viaje->fecha_inicio_real ? 
                \Carbon\Carbon::parse($viaje->fecha_inicio_real)->diffForHumans(now()) : 'No calculada',
            'valor_por_persona' => $viaje->valor_persona ?? 0,
            'total_esperado' => $viaje->reservas->sum('cantidad_puestos') * ($viaje->valor_persona ?? 0),
            // 🚫 Temporalmente sin calificaciones
            'total_calificaciones' => 0,
            'promedio_calificaciones' => 0
        ];

        \Log::info('Estadísticas calculadas exitosamente', [
            'viaje_id' => $viajeId,
            'estadisticas' => $estadisticas
        ]);

        return view('conductor.viaje-finalizado', compact('viaje', 'estadisticas'));
        
    } catch (\Exception $e) {
        \Log::error('Error completo al mostrar viaje finalizado', [
            'viaje_id' => $viajeId,
            'error' => $e->getMessage(),
            'linea' => $e->getLine(),
            'archivo' => $e->getFile(),
            'trace' => $e->getTraceAsString()
        ]);

        return redirect()
            ->route('dashboard')
            ->with('error', 'Error al cargar el viaje finalizado: ' . $e->getMessage());
    }
}
public function calificar(Request $request, $reservaId) 
{
    try {
        // Validar datos
        $request->validate([
            'calificacion' => 'required|integer|min:1|max:5',
            'comentario' => 'nullable|string|max:500'
        ]);

        // Verificar que la reserva existe y pertenece a un viaje del conductor
        $reserva = \App\Models\Reserva::whereHas('viaje', function($query) {
            $query->where('conductor_id', auth()->id());
        })->findOrFail($reservaId);

        // 🔒 VERIFICAR SI YA EXISTE UNA CALIFICACIÓN
        $calificacionExistente = \App\Models\Calificacion::where([
            'reserva_id' => $reservaId,
            'usuario_id' => auth()->id(),
            'tipo' => 'conductor_a_pasajero'
        ])->first();

        if ($calificacionExistente) {
            return response()->json([
                'success' => false,
                'message' => 'Ya has calificado a este pasajero anteriormente',
                'codigo' => 'YA_CALIFICADO'
            ], 400);
        }

        // ✅ Verificar que el pasajero esté finalizado (opcional)
        if ($reserva->estado !== 'finalizado') {
            return response()->json([
                'success' => false,
                'message' => 'Solo puedes calificar pasajeros que hayan finalizado el viaje'
            ], 400);
        }

        // 🆕 Crear nueva calificación
        $calificacion = \App\Models\Calificacion::create([
            'reserva_id' => $reservaId,
            'usuario_id' => auth()->id(), // El conductor que califica
            'calificacion' => $request->calificacion,
            'comentario' => $request->comentario,
            'tipo' => 'conductor_a_pasajero'
        ]);

        // 📝 Log para seguimiento
        \Log::info('Calificación creada exitosamente', [
            'calificacion_id' => $calificacion->id,
            'reserva_id' => $reservaId,
            'conductor_id' => auth()->id(),
            'calificacion' => $request->calificacion,
            'pasajero_id' => $reserva->user_id
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Calificación enviada exitosamente',
            'data' => [
                'calificacion_id' => $calificacion->id,
                'calificacion' => $calificacion->calificacion,
                'comentario' => $calificacion->comentario,
                'fecha' => $calificacion->created_at->format('d/m/Y H:i')
            ]
        ]);

    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Datos inválidos: ' . implode(', ', $e->validator->errors()->all())
        ], 422);
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Reserva no encontrada o no tienes permisos'
        ], 404);
    } catch (\Exception $e) {
        \Log::error('Error al calificar pasajero', [
            'reserva_id' => $reservaId,
            'conductor_id' => auth()->id(),
            'error' => $e->getMessage(),
            'linea' => $e->getLine()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Error interno del servidor'
        ], 500);
    }
}


// 🔥 MÉTODO ADICIONAL: Finalizar viaje
public function finalizarViaje(Request $request, $viajeId)
{
    try {
        // 🔍 Buscar el viaje
        $viaje = \App\Models\Viaje::findOrFail($viajeId);
        
        // 🔐 Verificar que el conductor es el dueño del viaje
        if ($viaje->conductor_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permisos para finalizar este viaje'
            ], 403);
        }

        // 🔄 Cambiar estado a finalizado
        $viaje->update([
            'estado' => 'finalizado',
            'fecha_finalizacion' => now() // Opcional: agregar timestamp de finalización
        ]);

        // 📝 Log para auditoría
        \Illuminate\Support\Facades\Log::info('Viaje finalizado', [
            'viaje_id' => $viaje->id,
            'conductor_id' => auth()->id(),
            'fecha_finalizacion' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Viaje finalizado exitosamente',
            'redirect_url' => route('conductor.viaje.detalles.finalizados', $viaje->id)
        ]);

    } catch (\Exception $e) {
        \Illuminate\Support\Facades\Log::error('Error al finalizar viaje', [
            'error' => $e->getMessage(),
            'viaje_id' => $viajeId,
            'conductor_id' => auth()->id()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'No se pudo finalizar el viaje. Intenta nuevamente.'
        ], 500);
    }
}



}
