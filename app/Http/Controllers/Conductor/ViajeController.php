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
    $viaje->load([
        'reservas.user',
        'reservas.calificacionPasajero',
        'reservas.calificacionConductor',
        'registroConductor',
    ]);
    
    // Verificar si se requiere verificación de pasajeros
    $requiereVerificacion = $viaje->registroConductor->verificar_pasajeros == 1;
    
    return view('conductor.viaje-detalles', compact('viaje', 'requiereVerificacion'));
}

}
