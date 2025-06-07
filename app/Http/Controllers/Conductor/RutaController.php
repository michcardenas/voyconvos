<?php

namespace App\Http\Controllers\Conductor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Viaje;
use Illuminate\Support\Facades\Log;

class RutaController extends Controller
{
    public function estimar(Request $request)
    {
        $origen = $request->origen;
        $destino = $request->destino; 
        $apiKey = config('services.google_maps.key');

        $response = Http::get("https://maps.googleapis.com/maps/api/directions/json", [
            'origin' => $origen,
            'destination' => $destino,
            'key' => $apiKey,
            'mode' => 'driving',
            'alternatives' => false
        ]);

        $data = $response->json();

        if (!isset($data['routes'][0])) {
            return response()->json(['error' => 'No se encontró una ruta'], 404);
        }

        $ruta = $data['routes'][0]['legs'][0];

        return response()->json([
            'distancia_texto' => $ruta['distance']['text'],
            'distancia_valor' => $ruta['distance']['value'],
            'duracion_texto' => $ruta['duration']['text'],
            'duracion_valor' => $ruta['duration']['value'],
            'inicio' => $ruta['start_address'],
            'fin' => $ruta['end_address']
        ]);
    }

    public function detalle()
    {
        return view('conductor.detalle-viaje');
    }

    public function store(Request $request)
    {
         Log::info($request);
        $data = $request->validate([
            'origen_direccion' => 'required|string',
            'origen_lat' => 'required|numeric',
            'origen_lng' => 'required|numeric',
            'destino_direccion' => 'required|string',
            'destino_lat' => 'required|numeric',
            'destino_lng' => 'required|numeric',
            'distancia_km' => 'required|numeric',
            'vehiculo' => 'required|string',
            'valor_estimado' => 'required|numeric',
            'valor_cobrado' => 'nullable|numeric',
            'hora_salida' => 'nullable',
            'fecha_salida'         => 'nullable|date',
            'puestos_disponibles' => 'nullable|integer',
            'estado' => 'nullable|string',
            'activo' => 'boolean'
        ]);

        // 👉 Asignar automáticamente el ID del conductor actual
        $data['conductor_id'] = auth()->id();

        // Valores por defecto si no vienen en la solicitud
        $data['estado'] = $data['estado'] ?? 'pendiente';
        $data['activo'] = $data['activo'] ?? true;

       

        // Crear viaje
        $viaje = Viaje::create($data);

        return response()->json(['success' => true, 'viaje_id' => $viaje->id]);
    }
}
