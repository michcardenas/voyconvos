<?php

namespace App\Http\Controllers\conductor;

use Illuminate\Http\Request;
use App\Models\RegistroConductor;
use App\Models\Reserva;  // ← ESTO FALTA
use App\Models\Viaje; 
use App\Http\Controllers\Controller;

class ConductorController extends Controller
{
public function gestion()
{
    $userId = auth()->id(); // o el ID que necesites
    $registro = RegistroConductor::where('user_id', $userId)->first();

    return view('conductor.gestion', [
        'marca' => $registro?->marca_vehiculo,
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

        // Actualizar información del viaje
        $viaje->update([
            'pasajeros_presentes' => $pasajerosPresentes,
            'pasajeros_ausentes' => $pasajerosAusentes,
            'estado' => 'en_curso' // Cambiar a "en curso"
        ]);

        \DB::commit();

        \Log::info('Asistencia verificada', [
            'viaje_id' => $viaje->id,
            'presentes' => $pasajerosPresentes,
            'ausentes' => $pasajerosAusentes
        ]);

        return redirect()->route('conductor.viaje.en-curso', $viaje->id)
            ->with('success', "Verificación completada. Presentes: {$pasajerosPresentes}, Ausentes: {$pasajerosAusentes}");

    } catch (\Exception $e) {
        \DB::rollBack();
        
        \Log::error('Error al procesar asistencia', [
            'viaje_id' => $viaje->id,
            'error' => $e->getMessage()
        ]);

        return back()->withErrors(['error' => 'Error al procesar la verificación']);
    }
}

}
