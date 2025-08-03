<?php

namespace App\Http\Controllers\Conductor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Models\RegistroConductor;

class RegistroVehiculoController extends Controller
{
   public function form()
    {
        $user = auth()->user();

        if (!$user->hasRole('conductor')) {
            abort(403, 'No tienes permiso para acceder a esta página.');
        }

        if (RegistroConductor::where('user_id', $user->id)->exists()) {
            return redirect()->route('dashboard')->with('success', 'Ya completaste tu registro como conductor.');
        }

        return view('conductor.completar-registro');
    }

   public function store(Request $request)
{
    $request->validate([
        'marca_vehiculo' => 'required|string|max:255',
        'modelo_vehiculo' => 'required|string|max:255',
        'anio_vehiculo' => 'required|integer|min:2012|max:' . date('Y'),
        'numero_puestos' => 'required|integer|min:2|max:50', // Nuevo campo agregado
        'patente' => 'required|string|max:20',
        'licencia' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        'cedula' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        'cedula_verde' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        'consumo_por_galon' => 'nullable|numeric|min:0|max:100', 
    ]);

    $user = Auth::user();

    try {
        $registro = RegistroConductor::create([
            'user_id' => $user->id,
            'marca_vehiculo' => $request->marca_vehiculo,
            'modelo_vehiculo' => $request->modelo_vehiculo,
            'anio_vehiculo' => $request->anio_vehiculo,
            'numero_puestos' => $request->numero_puestos, // Nuevo campo agregado
            'patente' => $request->patente,
            'consumo_por_galon' => $request->consumo_por_galon,
                'licencia' => $request->file('licencia')->store('documentos', 'public'),
                'cedula' => $request->file('cedula')->store('documentos', 'public'),
                'cedula_verde' => $request->file('cedula_verde')->store('documentos', 'public'),
            // 'seguro' => $request->file('seguro')->store('documentos'),
            // 'rto' => $request->hasFile('rto') ? $request->file('rto')->store('documentos') : null,
            // 'antecedentes' => $request->hasFile('antecedentes') ? $request->file('antecedentes')->store('documentos') : null,
            
            'estado_verificacion' => 'pendiente',
            'estado_registro' => 'completo',
        ]);

        Log::info('✅ Registro guardado correctamente.', ['user_id' => $user->id]);

        return redirect()->route('dashboard')
            ->with('success', '✅ Tu registro fue enviado para revisión.');
    } catch (\Exception $e) {
        Log::error('❌ Error al guardar el registro del conductor: ' . $e->getMessage());
        return back()->withErrors(['general' => 'Ocurrió un error al guardar el registro. Intenta nuevamente.']);
    }
}
}
