<?php

namespace App\Http\Controllers\Conductor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Viaje;

class ViajeController extends Controller
{
    public function eliminar(\App\Models\Viaje $viaje)
    {
        // Validar si el viaje pertenece al conductor autenticado
        if ($viaje->conductor_id !== auth()->id()) {
            abort(403, 'No autorizado para eliminar este viaje.');
        }

        // Eliminar o marcar como inactivo
        $viaje->activo = false;
        $viaje->estado = 'cancelado';
        $viaje->save();

        return redirect()->back()->with('success', '🚫 Viaje cancelado correctamente.');
    }

    public function detalle(Viaje $viaje)
    {
        // Ya tienes el modelo `$viaje`, solo necesitas cargar las relaciones
        $viaje->load('reservas.user');

        return view('conductor.viaje-detalles', compact('viaje'));
    }

}
