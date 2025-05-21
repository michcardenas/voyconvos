<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request; // ✅ NECESARIO
use App\Models\Pagina;

class PaginaController extends Controller
{
    public function index()
    {
        $paginas = Pagina::all();
        return view('admin.paginas.index', compact('paginas'));
    }

    public function editar($id)
    {
        $pagina = Pagina::with('secciones.contenidos')->findOrFail($id);

        return view('admin.configuracion.paginas.edit', compact('pagina'));
    }

    public function update(Request $request, $id)
    {
        $pagina = Pagina::findOrFail($id);

        $pagina->update([
            'nombre' => $request->input('nombre'),
            'ruta' => $request->input('ruta'),
        ]);

        return redirect()->route('configuracion.paginas')->with('success', 'Página actualizada correctamente');
    }
}
