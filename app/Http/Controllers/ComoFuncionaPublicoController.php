<?php

namespace App\Http\Controllers;

class ComoFuncionaPublicoController extends Controller
{
   public function index()
{
    // Obtener metadatos de la página como_funciona
    $metadatos = \App\Models\MetadatoPagina::where('pagina', 'como_funciona')->first();
    
    return view('como-funciona', [
        'metadatos' => $metadatos
    ]);
}
}