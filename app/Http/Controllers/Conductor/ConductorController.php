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
    $configuracionGasolina = DB::table('voyconvos.configuracion_admin')
        ->select('id_configuracion', 'nombre', 'valor', 'created_at', 'updated_at')
        ->where('nombre', 'gasolina')
        ->orderBy('created_at', 'desc')
        ->first();
        
    $configuracionComision = DB::table('voyconvos.configuracion_admin')
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
        // 🔍 DEBUG: Verificar datos
        $usuarioAuth = auth()->id();
        $conductorViaje = $viaje->conductor_id;
        $usuarioCompleto = auth()->user();
        
        \Log::info('=== DEBUG INICIAR VIAJE ===', [
            'viaje_id' => $viaje->id,
            'conductor_id_viaje' => $conductorViaje,
            'usuario_autenticado_id' => $usuarioAuth,
            'usuario_autenticado' => $usuarioCompleto ? $usuarioCompleto->toArray() : null,
            'tipos' => [
                'conductor_id_type' => gettype($conductorViaje),
                'usuario_auth_type' => gettype($usuarioAuth),
            ],
            'comparacion_estricta' => $conductorViaje === $usuarioAuth,
            'comparacion_suave' => $conductorViaje == $usuarioAuth,
        ]);

        // 🔧 COMPARACIÓN MÁS FLEXIBLE (temporal para debug)
        if ((int)$viaje->conductor_id !== (int)auth()->id()) {
            \Log::warning('Acceso denegado al viaje', [
                'viaje_id' => $viaje->id,
                'conductor_id_viaje' => $viaje->conductor_id,
                'usuario_id' => auth()->id(),
                'convertidos' => [
                    'conductor_int' => (int)$viaje->conductor_id,
                    'usuario_int' => (int)auth()->id(),
                ]
            ]);
            
            return response()->json([
                'success' => false, 
                'message' => 'No tienes permisos para iniciar este viaje',
                'debug' => env('APP_DEBUG') ? [
                    'conductor_id' => $viaje->conductor_id,
                    'user_id' => auth()->id(),
                    'tipos' => [
                        'conductor_type' => gettype($viaje->conductor_id),
                        'user_type' => gettype(auth()->id()),
                    ]
                ] : null
            ], 403);
        }

        // Verificar que el viaje esté en estado válido para iniciar
        if ($viaje->estado === 'iniciado') {
            return response()->json([
                'success' => false, 
                'message' => 'El viaje ya está iniciado'
            ], 400);
        }

        // Cambiar estado a iniciado
        $viaje->update([
            'estado' => 'iniciado',
            // Solo agregar si existe la columna
            // 'hora_inicio_real' => now()
        ]);

        \Log::info('Viaje iniciado exitosamente', [
            'viaje_id' => $viaje->id,
            'conductor_id' => auth()->id(),
            'hora_inicio' => now()
        ]);

        return response()->json([
            'success' => true, 
            'message' => 'Viaje iniciado exitosamente',
            'redirect_url' => route('conductor.viaje.verificar-pasajeros', $viaje->id)
        ]);

    } catch (\Exception $e) {
        \Log::error('Error al iniciar viaje', [
            'viaje_id' => $viaje->id,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return response()->json([
            'success' => false, 
            'message' => 'Error interno del servidor: ' . $e->getMessage()
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
            ->with('error', 'El viaje debe estar iniciado para verificar pasajeros');
    }

    // Cargar reservas confirmadas con información del usuario
    $viaje->load(['reservas' => function($query) {
        $query->where('estado', 'confirmada')
              ->with('user')
              ->orderBy('created_at', 'asc');
    }]);

    return view('conductor.verificar-pasajeros', compact('viaje'));
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
            
            // Actualizar estado de asistencia
            $reserva->update([
                'asistencia' => $estado,
                'verificado_por_conductor' => true,
                'fecha_verificacion' => now()
            ]);

            if ($estado === 'presente') {
                $pasajerosPresentes += $reserva->cantidad_puestos;
            } else {
                $pasajerosAusentes += $reserva->cantidad_puestos;
                
                // Opcional: Liberar puestos de pasajeros ausentes
                $viaje->puestos_disponibles += $reserva->cantidad_puestos;
            }
        }

        // Actualizar información del viaje - CAMBIAR A 'en_curso'
        $viaje->update([
            'estado' => 'en_curso',  // ← CAMBIO IMPORTANTE
            'pasajeros_presentes' => $pasajerosPresentes,
            'pasajeros_ausentes' => $pasajerosAusentes,
        ]);

        \DB::commit();

        \Log::info('Asistencia verificada y viaje en curso', [
            'viaje_id' => $viaje->id,
            'presentes' => $pasajerosPresentes,
            'ausentes' => $pasajerosAusentes,
            'nuevo_estado' => 'en_curso'
        ]);

        // 🔥 REDIRECCIÓN CORRECTA - Verificar que la ruta existe
        $rutaDestino = route('conductor.viaje.en-curso', $viaje->id);
        
        \Log::info('Redirigiendo a viaje en curso', [
            'viaje_id' => $viaje->id,
            'ruta_destino' => $rutaDestino
        ]);

        return redirect()->to($rutaDestino)
            ->with('success', "✅ Verificación completada. Presentes: {$pasajerosPresentes}, Ausentes: {$pasajerosAusentes}");

    } catch (\Exception $e) {
        \DB::rollBack();
        
        \Log::error('Error al procesar asistencia', [
            'viaje_id' => $viaje->id,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return back()->withErrors(['error' => 'Error al procesar la verificación: ' . $e->getMessage()]);
    }
}
public function viajeEnCurso(Viaje $viaje)
{
    try {
        // Verificar permisos
        if ((int)$viaje->conductor_id !== (int)auth()->id()) {
            abort(403, 'No tienes permisos para acceder a este viaje');
        }

        // Verificar que el viaje esté en estado válido
        if (!in_array($viaje->estado, ['iniciado', 'en_curso'])) {
            return redirect()->route('conductor.viaje.detalle', $viaje->id)
                ->with('error', 'El viaje debe estar iniciado para ver esta pantalla');
        }

        // 🔥 CAMBIAR ESTADO A "EN_CURSO" si está iniciado
        if ($viaje->estado === 'iniciado') {
            $viaje->update([
                'estado' => 'en_curso',
                'hora_inicio_real' => now() // Si tienes esta columna
            ]);

            \Log::info('Viaje cambiado a en_curso', [
                'viaje_id' => $viaje->id,
                'conductor_id' => auth()->id(),
                'hora_cambio' => now()
            ]);
        }

        // Cargar datos necesarios
        $viaje->load([
            'reservas' => function($query) {
                $query->where('estado', 'confirmada')
                      ->whereNotNull('asistencia') // Solo los que fueron verificados
                      ->with('user')
                      ->orderBy('asistencia', 'desc') // Presentes primero
                      ->orderBy('created_at', 'asc');
            },
            'registroConductor'
        ]);

        // Calcular estadísticas del viaje
        $estadisticas = [
            'total_reservas' => $viaje->reservas->count(),
            'presentes' => $viaje->reservas->where('asistencia', 'presente')->count(),
            'ausentes' => $viaje->reservas->where('asistencia', 'ausente')->count(),
            'puestos_ocupados' => $viaje->reservas->where('asistencia', 'presente')->sum('cantidad_puestos'),
            'ingresos_reales' => $viaje->reservas->where('asistencia', 'presente')->sum('total'),
            'hora_inicio' => $viaje->hora_inicio_real ?? $viaje->created_at,
        ];

        return view('conductor.viaje-en-curso', compact('viaje', 'estadisticas'));

    } catch (\Exception $e) {
        \Log::error('Error en viajeEnCurso', [
            'viaje_id' => $viaje->id,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return redirect()->route('dashboard')
            ->with('error', 'Error al acceder al viaje en curso');
    }
}

// 🔥 MÉTODO ADICIONAL: Finalizar viaje
public function finalizarViaje(Viaje $viaje)
{
    try {
        // Verificar permisos
        if ((int)$viaje->conductor_id !== (int)auth()->id()) {
            return response()->json([
                'success' => false, 
                'message' => 'No tienes permisos'
            ], 403);
        }

        // Verificar estado
        if ($viaje->estado !== 'en_curso') {
            return response()->json([
                'success' => false, 
                'message' => 'El viaje no está en curso'
            ], 400);
        }

        // Finalizar viaje
        $viaje->update([
            'estado' => 'finalizado',
            'hora_finalizacion' => now()
        ]);

        // Actualizar reservas presentes a "completada"
        $viaje->reservas()
            ->where('asistencia', 'presente')
            ->update(['estado' => 'completada']);

        \Log::info('Viaje finalizado', [
            'viaje_id' => $viaje->id,
            'conductor_id' => auth()->id(),
            'hora_finalizacion' => now()
        ]);

        return response()->json([
            'success' => true, 
            'message' => 'Viaje finalizado exitosamente',
            'redirect_url' => route('dashboard')
        ]);

    } catch (\Exception $e) {
        \Log::error('Error al finalizar viaje', [
            'viaje_id' => $viaje->id,
            'error' => $e->getMessage()
        ]);

        return response()->json([
            'success' => false, 
            'message' => 'Error al finalizar el viaje'
        ], 500);
    }
}
}
