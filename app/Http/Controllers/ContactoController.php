<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContactoController extends Controller
{
     public function mostrarFormulario()
    {
        return view('contacto'); 
    }

    public function enviarFormulario(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email',
            'telefono' => 'nullable|string|max:20',
            'asunto' => 'required|string|max:255',
            'mensaje' => 'required|string',
        ]);

        // Aquí puedes enviar un correo, guardar en la base de datos, etc.
        return back()->with('success', 'Tu mensaje ha sido enviado correctamente.');
    }
}
