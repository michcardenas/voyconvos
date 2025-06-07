<?php

namespace App\Http\Controllers\Conductor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DestinoConductor;

class DestinoController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'latitud' => 'required|numeric',
            'longitud' => 'required|numeric',
        ]);

        DestinoConductor::create([
            'conductor_id' => auth()->id(), // si usas auth
            'latitud' => $request->latitud,
            'longitud' => $request->longitud,
        ]);

        return response()->json(['message' => 'Destino guardado correctamente.']);
    }
}

