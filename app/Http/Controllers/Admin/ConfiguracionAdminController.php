<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ConfiguracionAdmin;
use Illuminate\Http\Request;
use App\Models\Reserva;
use App\Models\Viaje;
use App\Models\User;


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
    // Tipos de configuración disponibles (usar minúsculas para consistencia)
    $tiposConfiguracion = [
        'comision' => '💰 Comisión de la plataforma (%)',
        'maximo' => '💵 Monto máximo permitido (%)',
        'costo_km' => '📏 Costo por kilómetro recorrido',
        'costo_combustible' => '⛽ Costo del combustible por litro/galón',
        'numero_galones' => '🛢️ Número de galones (máx. 100)',
    ];

    return view('admin.create_configuracion', compact('tiposConfiguracion'));
}

public function store(Request $request)
{
    // Validación base
    $rules = [
        'nombre' => 'required|in:comision,maximo,costo_km,costo_combustible,numero_galones',
        'valor' => 'required|numeric|min:0',
    ];

    $messages = [
        'nombre.required' => 'Debes seleccionar un tipo de configuración',
        'nombre.in' => 'El tipo de configuración seleccionado no es válido',
        'valor.required' => 'El valor es obligatorio',
        'valor.numeric' => 'El valor debe ser un número',
        'valor.min' => 'El valor debe ser mayor o igual a 0',
    ];

    // Validaciones específicas según el tipo de configuración
    if ($request->nombre === 'comision' || $request->nombre === 'maximo') {
        $rules['valor'] = 'required|numeric|min:0|max:100';
        $messages['valor.max'] = 'El porcentaje no puede ser mayor a 100';
    }

    if ($request->nombre === 'numero_galones') {
        $rules['valor'] = 'required|numeric|min:0|max:100';
        $messages['valor.max'] = 'El número de galones no puede ser mayor a 100';
    }

    $request->validate($rules, $messages);

    ConfiguracionAdmin::create([
        'nombre' => $request->nombre,
        'valor' => $request->valor,
    ]);

    return redirect()->route('admin.gestion')->with('success', 'Configuración creada correctamente.');
}
 public function gestorPagos(Request $request)
    {
        // Query base para obtener TODAS las reservas con pagos (Uala o Transferencia)
        $query = Reserva::with(['viaje.conductor', 'user'])
            ->where(function($q) {
                $q->whereNotNull('uala_checkout_id') // Pagos Uala
                  ->orWhereNotNull('comprobante_pago'); // Transferencias
            })
            ->orderBy('updated_at', 'desc');

        // Filtro por método de pago
        if ($request->filled('metodo_pago')) {
            if ($request->metodo_pago === 'transferencia') {
                $query->where('metodo_pago', 'transferencia');
            } elseif ($request->metodo_pago === 'uala') {
                $query->where(function($q) {
                    $q->where('metodo_pago', 'uala')
                      ->orWhereNotNull('uala_checkout_id');
                });
            }
        }

        // Filtro por estado de pago (Uala)
        if ($request->filled('estado_pago')) {
            $query->where('uala_payment_status', $request->estado_pago);
        }

        // Filtro por estado de comprobante (Transferencia)
        if ($request->filled('estado_comprobante')) {
            if ($request->estado_comprobante === 'verificado') {
                $query->where('comprobante_verificado', true);
            } elseif ($request->estado_comprobante === 'rechazado') {
                $query->where('comprobante_rechazado', true);
            } elseif ($request->estado_comprobante === 'pendiente') {
                $query->whereNotNull('comprobante_pago')
                      ->where('comprobante_verificado', false)
                      ->where('comprobante_rechazado', false);
            }
        }

        // Filtro por estado de reserva
        if ($request->filled('estado_reserva')) {
            $query->where('estado', $request->estado_reserva);
        }

        // Filtro por rango de fechas
        if ($request->filled('fecha_desde')) {
            $query->where(function($q) use ($request) {
                $q->whereDate('uala_payment_date', '>=', $request->fecha_desde)
                  ->orWhereDate('fecha_subida_comprobante', '>=', $request->fecha_desde);
            });
        }

        if ($request->filled('fecha_hasta')) {
            $query->where(function($q) use ($request) {
                $q->whereDate('uala_payment_date', '<=', $request->fecha_hasta)
                  ->orWhereDate('fecha_subida_comprobante', '<=', $request->fecha_hasta);
            });
        }

        // Búsqueda general
        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where(function($q) use ($buscar) {
                $q->whereHas('user', function($subq) use ($buscar) {
                    $subq->where('name', 'LIKE', "%{$buscar}%")
                         ->orWhere('email', 'LIKE', "%{$buscar}%");
                })
                ->orWhere('id', 'LIKE', "%{$buscar}%")
                ->orWhere('uala_checkout_id', 'LIKE', "%{$buscar}%")
                ->orWhere('uala_external_reference', 'LIKE', "%{$buscar}%");
            });
        }

        $pagos = $query->paginate(20);

        // Estadísticas actualizadas
        $estadisticas = [
            'total_pagos' => Reserva::where(function($q) {
                $q->whereNotNull('uala_checkout_id')
                  ->orWhereNotNull('comprobante_pago');
            })->count(),

            'pagos_exitosos' => Reserva::where(function($q) {
                $q->where('uala_payment_status', 'approved')
                  ->orWhere('comprobante_verificado', true);
            })->count(),

            'pagos_fallidos' => Reserva::where(function($q) {
                $q->where('uala_payment_status', 'rejected')
                  ->orWhere('comprobante_rechazado', true);
            })->count(),

            'pagos_pendientes' => Reserva::where(function($q) {
                $q->where('uala_payment_status', 'pending')
                  ->orWhere(function($subq) {
                      $subq->whereNotNull('comprobante_pago')
                           ->where('comprobante_verificado', false)
                           ->where('comprobante_rechazado', false);
                  });
            })->count(),

            'total_recaudado' => Reserva::where(function($q) {
                $q->where('uala_payment_status', 'approved')
                  ->orWhere('comprobante_verificado', true);
            })->sum('total'),

            // Estadísticas adicionales por método
            'total_uala' => Reserva::whereNotNull('uala_checkout_id')->count(),
            'total_transferencias' => Reserva::where('metodo_pago', 'transferencia')->count(),
            'comprobantes_pendientes' => Reserva::whereNotNull('comprobante_pago')
                ->where('comprobante_verificado', false)
                ->where('comprobante_rechazado', false)
                ->count(),
        ];

        return view('admin.gestor-pagos', compact('pagos', 'estadisticas'));
    }

    // Aprobar comprobante de transferencia
    public function aprobarComprobante(Request $request, $reservaId)
    {
        $reserva = Reserva::findOrFail($reservaId);

        if (!$reserva->comprobante_pago) {
            return back()->with('error', 'Esta reserva no tiene comprobante de pago');
        }

        $reserva->comprobante_verificado = true;
        $reserva->comprobante_rechazado = false;
        $reserva->fecha_verificacion_comprobante = now();
        $reserva->estado = 'confirmada'; // Cambiar a confirmada
        $reserva->fecha_pago = now();
        $reserva->save();

        // Enviar email de confirmación al pasajero
        try {
            $usuario = $reserva->user;
            $viaje = $reserva->viaje;
            $fechaViaje = \Carbon\Carbon::parse($viaje->fecha_salida)->format('d/m/Y');
            $horaViaje = \Carbon\Carbon::parse($viaje->hora_salida)->format('H:i');

            \Mail::to($usuario->email)->send(new \App\Mail\UniversalMail(
                $usuario,
                '✅ Pago Aprobado - Reserva Confirmada',
                "¡Buenas noticias! Tu comprobante de pago ha sido verificado y aprobado.\n\n📍 Detalles del viaje:\n• Fecha: {$fechaViaje}\n• Hora: {$horaViaje}\n• Puestos: {$reserva->cantidad_puestos}\n• Total: $" . number_format($reserva->total, 0, ',', '.') . "\n\n✅ Tu reserva está CONFIRMADA.\n\nTe esperamos en el viaje. ¡Buen viaje!",
                'notificacion'
            ));
        } catch (\Exception $e) {
            \Log::error('Error enviando email de aprobación: ' . $e->getMessage());
        }

        return back()->with('success', '✅ Comprobante aprobado. Reserva confirmada.');
    }

    // Rechazar comprobante de transferencia
    public function rechazarComprobante(Request $request, $reservaId)
    {
        $request->validate([
            'motivo' => 'required|string|min:10|max:500'
        ]);

        $reserva = Reserva::findOrFail($reservaId);

        if (!$reserva->comprobante_pago) {
            return back()->with('error', 'Esta reserva no tiene comprobante de pago');
        }

        $reserva->comprobante_verificado = false;
        $reserva->comprobante_rechazado = true;
        $reserva->fecha_verificacion_comprobante = now();
        $reserva->motivo_rechazo_comprobante = $request->motivo;
        $reserva->estado = 'cancelada'; // Cancelar la reserva
        $reserva->save();

        // Devolver puestos al viaje
        $viaje = $reserva->viaje;
        $viaje->puestos_disponibles += $reserva->cantidad_puestos;
        $viaje->save();

        // Enviar email de rechazo al pasajero
        try {
            $usuario = $reserva->user;

            \Mail::to($usuario->email)->send(new \App\Mail\UniversalMail(
                $usuario,
                '❌ Comprobante Rechazado',
                "Tu comprobante de pago ha sido revisado y no pudo ser aprobado.\n\n❌ Motivo del rechazo:\n{$request->motivo}\n\nPor favor, verifica los datos de tu transferencia y sube un nuevo comprobante válido, o contacta a soporte para más información.\n\nTu reserva ha sido cancelada y los puestos están nuevamente disponibles.",
                'notificacion'
            ));
        } catch (\Exception $e) {
            \Log::error('Error enviando email de rechazo: ' . $e->getMessage());
        }

        return back()->with('success', '❌ Comprobante rechazado. Reserva cancelada.');
    }
public function detalleViaje(Viaje $viaje)
{
    $viaje->load(['conductor', 'reservas.user', 'registroConductor']);
    
    return view('admin.viaje-detalle', compact('viaje'));
}

public function editarViaje(Viaje $viaje)
{
    return view('admin.viaje-editar', compact('viaje'));
}

public function todosLosViajes(Request $request)
{
    $query = Viaje::with(['conductor', 'reservas']);
    
    // Filtros opcionales
    if ($request->filled('estado')) {
        $query->where('estado', $request->estado);
    }
    
    if ($request->filled('fecha_desde')) {
        $query->whereDate('fecha_salida', '>=', $request->fecha_desde);
    }
    
    $viajes = $query->orderBy('fecha_salida', 'desc')->paginate(20);
    
    return view('admin.todos-viajes', compact('viajes'));
}
}
