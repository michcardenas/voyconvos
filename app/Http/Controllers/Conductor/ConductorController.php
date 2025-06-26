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

// Método opcional para notificar al pasajero del rechazo
private function notificarRechazoAlPasajero(Reserva $reserva)
{
    // Aquí puedes implementar el envío de notificación por email, SMS, etc.
    // Por ejemplo, usando el sistema de notificaciones de Laravel:
    
    // $reserva->user->notify(new PasajeroRechazadoNotification($reserva));
}
}
