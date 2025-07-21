<?php

namespace App\Http\Controllers\Conductor;

use App\Http\Controllers\Controller;
use App\Models\Reserva;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FinalizarPasajeroController extends Controller
{
public function finalizar(Request $request, $reserva)
{
    // 📝 Log básico
    Log::info('Iniciando finalización de pasajero', [
        'reserva_id' => $reserva, // Ahora es $reserva, no $reservaId
        'user_id' => auth()->id() ?? 'no_auth'
    ]);

    try {
        // 🔍 Buscar reserva
        $reservaModel = Reserva::find($reserva);
        
        if (!$reservaModel) {
            return response()->json([
                'success' => false,
                'message' => 'Reserva no encontrada'
            ]);
        }

        // 🔄 Actualizar estado
        $reservaModel->estado = 'finalizado';
        $reservaModel->save();

        Log::info('Pasajero finalizado', [
            'reserva_id' => $reservaModel->id,
            'estado' => $reservaModel->estado
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pasajero finalizado exitosamente'
        ]);

    } catch (\Exception $e) {
        Log::error('Error al finalizar pasajero', [
            'error' => $e->getMessage(),
            'reserva_id' => $reserva
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ]);
    }
}
}