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
        
        if ($request->accion === 'verificar') {
            $reserva->update([
                'estado' => 'pendiente_pago',
                'updated_at' => now()
            ]);
            
            $mensaje = 'Pasajero aprobado. Estado cambiado a pendiente de pago.';
        }
        
        return redirect()->back()->with('success', $mensaje);
    }
}
