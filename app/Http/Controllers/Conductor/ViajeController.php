<?php

namespace App\Http\Controllers\Conductor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Viaje;

class ViajeController extends Controller
{
  public function eliminar(Request $request, Viaje $viaje)
{
    // Validar si el viaje pertenece al conductor autenticado
    if ($viaje->conductor_id !== auth()->id()) {
        abort(403, 'No autorizado para eliminar este viaje.');
    }

    // Validar el motivo de cancelación
    $request->validate([
        'motivo_cancelacion' => 'required|string|min:10|max:1000'
    ]);

    // Guardar el motivo en observaciones y cancelar el viaje
    $viaje->update([
        'activo' => false,
        'estado' => 'cancelado',
        'observaciones' => 'CANCELADO: ' . $request->motivo_cancelacion . ' (Cancelado el ' . now()->format('d/m/Y H:i') . ')'
    ]);

    return redirect()->back()->with('success', '🚫 Viaje cancelado correctamente.');
}

public function detalle(Viaje $viaje) 
{
    try {
        // 🔍 Cargar relaciones básicas
        $viaje->load([
            'reservas.user',
            'conductor.registroConductor'
        ]);

        // 🎯 Para cada reserva, agregar las calificaciones como propiedades
        foreach ($viaje->reservas as $reserva) {
            // Calificación del pasajero al conductor
            $reserva->calificacionPasajero = \App\Models\Calificacion::where([
                'reserva_id' => $reserva->id,
                'tipo' => 'pasajero_a_conductor',
                'usuario_id' => $reserva->user_id
            ])->first();
            
            // Calificación del conductor al pasajero  
            $reserva->calificacionConductor = \App\Models\Calificacion::where([
                'reserva_id' => $reserva->id,
                'tipo' => 'conductor_a_pasajero',
                'usuario_id' => $viaje->conductor_id
            ])->first();
        }

        // Verificar si se requiere verificación de pasajeros
        $requiereVerificacion = $viaje->conductor->registroConductor->verificar_pasajeros == 1;

        // 📝 Log para debug
        \Log::info('Detalle de viaje cargado con calificaciones', [
            'viaje_id' => $viaje->id,
            'reservas_count' => $viaje->reservas->count(),
        ]);

        return view('conductor.viaje-detalles', compact('viaje', 'requiereVerificacion'));
        
    } catch (\Exception $e) {
        \Log::error('Error al cargar detalle de viaje', [
            'viaje_id' => $viaje->id,
            'error' => $e->getMessage(),
            'linea' => $e->getLine()
        ]);

        return redirect()->route('dashboard')
                        ->with('error', 'Error al cargar los detalles del viaje.');
    }
}
}
