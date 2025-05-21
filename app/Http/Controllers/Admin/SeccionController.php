<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pagina;
use App\Models\Seccion;
use Illuminate\Http\Request;

class SeccionController extends Controller
{
    public function index(Pagina $pagina)
    {
        $secciones = $pagina->secciones()->withCount('contenidos')->get();
        return view('admin.secciones.index', compact('pagina', 'secciones'));
    }

    public function editarContenidos($slug)
    {
        $seccion = Seccion::where('slug', $slug)->with('contenidos')->firstOrFail();
        return view('admin.secciones.editar-contenidos', compact('seccion'));
    }

    public function actualizarContenidos(Request $request, $slug)
    {
        $seccion = Seccion::where('slug', $slug)->with('contenidos')->firstOrFail();

        foreach ($seccion->contenidos as $contenido) {
            $inputName = "valor_{$contenido->id}";

            // 📷 Si es una imagen
            if ($request->hasFile($inputName)) {
                $archivo = $request->file($inputName);

                if ($archivo->isValid()) {
                    $nombreArchivo = time() . '_' . $archivo->getClientOriginalName();
                    $ruta = $archivo->storeAs('contenidos', $nombreArchivo, 'public'); // guarda en storage/app/public/contenidos
                    $contenido->valor = 'storage/' . $ruta; // guarda ruta pública
                    $contenido->save();
                }
            }

            // 📝 Si es texto
            elseif ($request->has($inputName)) {
                $contenido->valor = $request->input($inputName);
                $contenido->save();
            }
        }

        return redirect()->back()->with('success', 'Contenidos actualizados correctamente.');
    }

}
