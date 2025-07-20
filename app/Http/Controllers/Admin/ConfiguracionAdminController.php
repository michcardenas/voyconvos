<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ConfiguracionAdmin;
use Illuminate\Http\Request;
class ConfiguracionAdminController extends Controller
{
    public function index()
    {
        $configuraciones = ConfiguracionAdmin::all();
        return view('admin.configuracion.gestion', compact('configuraciones'));
    }

public function create() {
    // Solo los tipos que manejas en tu sistema
    $tiposConfiguracion = [
        'comision' => '💰 Comisión (%)',
        'gasolina' => '⛽ Precio Gasolina ($)'
    ];
    
    return view('admin.create_configuracion', compact('tiposConfiguracion'));
}

public function store(Request $request)
{
    $request->validate([
        'nombre' => 'required|in:gasolina,comision',
        'valor' => 'nullable|string',
    ]);

    ConfiguracionAdmin::create([
        'nombre' => $request->nombre,
        'valor' => $request->valor,
    ]);

    return redirect()->route('admin.gestion')->with('success', 'Configuración creada correctamente.');
}


}
