<?php

namespace App\Http\Controllers\conductor;

use Illuminate\Http\Request;
use App\Models\RegistroConductor;

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
}
