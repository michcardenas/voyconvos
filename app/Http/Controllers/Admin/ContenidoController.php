<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contenido;


class ContenidoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $contenido = Contenido::findOrFail($id); 

        return view('admin.contenidos.editar-contenido', compact('contenido'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $contenido = Contenido::findOrFail($id);

        // Si es una imagen (clave background o imagen)
        if ($request->hasFile('valor')) {
            $file = $request->file('valor');

            // Validar imagen
            $request->validate([
                'valor' => 'image|max:2048', // max 2MB
            ]);

            $filename = 'img_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('img', $filename, 'public'); // guarda en storage/app/public/img

            $contenido->update([
                'valor' => 'storage/' . $path, // se guarda como ruta pública
            ]);
        } else {
            // Validar texto normalmente
            $request->validate([
                'valor' => 'required|string|max:2000',
            ]);

            $contenido->update([
                'valor' => $request->input('valor'),
            ]);
        }

        return redirect()
            ->route('admin.contenidos.edit', $contenido->id)
            ->with('success', '✅ Contenido actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
