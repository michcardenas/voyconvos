<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request; // ✅ NECESARIO
use App\Models\Pagina;
use App\Models\User; // Asegúrate de importar el modelo User
use App\Models\Viaje; // Si necesitas datos de viajes
use App\Models\Pago; // Si necesitas datos de pagos
use App\Models\Reserva; // Si necesitas datos de reservas

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

 public function dashboard()
{
    // Datos generales (mantener igual)
    $totalUsuarios = User::count();
    $conductores = User::role('conductor')->count();
    $pasajeros = User::role('pasajero')->count();
    
    // Usuarios sin verificar (mantener igual)
    $conductoresSinVerificar = User::role('conductor')->where('verificado', false)->count();
    $pasajerosSinVerificar = User::role('pasajero')->where('verificado', false)->count();
    $totalSinVerificar = $conductoresSinVerificar + $pasajerosSinVerificar;
    
    // Usuarios recientes (mantener igual)
    $usuariosRecientes = User::latest()->take(5)->get();
    
    // Usuarios verificados (mantener igual)
    $conductoresVerificados = User::role('conductor')->where('verificado', true)->count();
    $pasajerosVerificados = User::role('pasajero')->where('verificado', true)->count();
    
    // Viajes (mantener lo existente)
    $viajesTotales = Viaje::count();
    $viajesActivos = Viaje::where('activo', true)->count();
    $viajesInactivos = Viaje::where('activo', false)->count();
    $viajesRecientes = Viaje::with('conductor')->latest()->take(5)->get();
    
    // ✨ AGREGAR: Estadísticas adicionales por estado para la nueva tabla
    $estadisticasViajes = [
        'pendiente' => Viaje::where('estado', 'pendiente')->count(),
        'confirmado' => Viaje::where('estado', 'confirmado')->count(),
        'activo' => Viaje::where('estado', 'activo')->count(),
        'en_curso' => Viaje::where('estado', 'en_curso')->count(),
        'completado' => Viaje::where('estado', 'completado')->count(),
        'cancelado' => Viaje::where('estado', 'cancelado')->count(),
        'listo_para_iniciar' => Viaje::where('estado', 'listo_para_iniciar')->count(),
    ];

    // ✨ AGREGAR: Lista de viajes con paginación para la tabla
    $viajes = Viaje::with(['conductor', 'reservas'])
        ->orderBy('fecha_salida', 'desc')
        ->paginate(10);
    
    // Reservas (mantener igual)
    $reservasTotales = Reserva::count();
    $reservasConfirmadas = Reserva::where('estado', 'confirmada')->count();
    $reservasPendientes = Reserva::whereIn('estado', ['pendiente', 'pendiente_pago'])->count();
    $reservasCanceladas = Reserva::whereIn('estado', ['cancelada', 'fallida'])->count();
    $reservasRecientes = Reserva::with(['pasajero', 'viaje.conductor'])->latest()->take(5)->get();
    
    return view('admin.dashboard', compact(
        // Variables existentes (mantener todas)
        'totalUsuarios',
        'conductores', 
        'pasajeros',
        'conductoresSinVerificar',
        'pasajerosSinVerificar',
        'totalSinVerificar',
        'conductoresVerificados',
        'pasajerosVerificados',
        'usuariosRecientes',
        'viajesTotales',
        'viajesActivos',
        'viajesInactivos',
        'viajesRecientes',
        'reservasTotales',
        'reservasConfirmadas',
        'reservasPendientes',
        'reservasCanceladas',
        'reservasRecientes',
        // ✨ NUEVAS variables agregadas
        'estadisticasViajes',
        'viajes'
    ));
}
}
