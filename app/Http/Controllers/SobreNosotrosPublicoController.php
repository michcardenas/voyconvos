<?php

namespace App\Http\Controllers;

class SobreNosotrosPublicoController extends Controller
{
  public function index()
{
    // Obtener metadatos de la página nosotros
    $metadatos = \App\Models\MetadatoPagina::where('pagina', 'nosotros')->first();
    
    return view('sobre-nosotros', [
        'metadatos' => $metadatos
    ]);
}
}
