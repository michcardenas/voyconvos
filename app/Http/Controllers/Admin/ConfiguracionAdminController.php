<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ConfiguracionAdmin;
use Illuminate\Http\Request;
use App\Models\Reserva;


class ConfiguracionAdminController extends Controller

{
public function index()
{
    // Agrupar por nombre y ordenar cada grupo por fecha más reciente
    $configuraciones = ConfiguracionAdmin::whereNotNull('created_at')
        ->orderBy('nombre')
        ->latest()
        ->get()
        ->groupBy('nombre');
    
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
 public function gestorPagos(Request $request)
    {
        // Query base para obtener reservas con información de pagos
        $query = Reserva::with(['viaje.conductor', 'user'])
            ->whereNotNull('uala_checkout_id') // Solo reservas que tienen checkout de Uala
            ->orderBy('updated_at', 'desc');

        // Filtros opcionales
        if ($request->filled('estado_pago')) {
            $query->where('uala_payment_status', $request->estado_pago);
        }

        if ($request->filled('estado_reserva')) {
            $query->where('estado', $request->estado_reserva);
        }

        if ($request->filled('fecha_desde')) {
            $query->whereDate('uala_payment_date', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->whereDate('uala_payment_date', '<=', $request->fecha_hasta);
        }

        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where(function($q) use ($buscar) {
                $q->whereHas('user', function($subq) use ($buscar) {
                    $subq->where('name', 'LIKE', "%{$buscar}%")
                         ->orWhere('email', 'LIKE', "%{$buscar}%");
                })
                ->orWhere('uala_checkout_id', 'LIKE', "%{$buscar}%")
                ->orWhere('uala_external_reference', 'LIKE', "%{$buscar}%");
            });
        }

        $pagos = $query->paginate(20);

        // Estadísticas de pagos
        $estadisticas = [
            'total_pagos' => Reserva::whereNotNull('uala_checkout_id')->count(),
            'pagos_exitosos' => Reserva::where('uala_payment_status', 'approved')->count(),
            'pagos_fallidos' => Reserva::where('uala_payment_status', 'rejected')->count(),
            'pagos_pendientes' => Reserva::where('uala_payment_status', 'pending')->count(),
            'total_recaudado' => Reserva::where('uala_payment_status', 'approved')->sum('total'),
        ];

        return view('admin.gestor-pagos', compact('pagos', 'estadisticas'));
    }

}
