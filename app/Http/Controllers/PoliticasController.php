<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Politica;
use App\Models\Contenido;


class PoliticasController extends Controller
{
  
public function index()
{
    $politicas = \App\Models\Contenido::whereIn('seccion_id', [14, 15, 16])
        ->get()
        ->groupBy('seccion_id');

    $header = $politicas[14]->pluck('valor', 'clave');
    $sidebar = $politicas[15]->pluck('valor', 'clave');
    $contenido = $politicas[16]->where('clave', 'contenido')->first()?->valor;

    return view('politicas.index', compact('header', 'sidebar', 'contenido'));
}

}
