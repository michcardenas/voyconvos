<?php

namespace App\Http\Controllers\Conductor;
use Illuminate\Support\Facades\Http;

public function estimar(Request $request)
{
    $origen = $request->origen; // ej: "4.648625,-74.247896"
    $destino = $request->destino; // ej: "4.689234,-74.048217"
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
        'distancia_valor' => $ruta['distance']['value'], // en metros
        'duracion_texto' => $ruta['duration']['text'],
        'duracion_valor' => $ruta['duration']['value'], // en segundos
        'inicio' => $ruta['start_address'],
        'fin' => $ruta['end_address']
    ]);
}
