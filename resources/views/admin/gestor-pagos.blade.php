@extends('layouts.app_admin')

@section('title', 'Gestor de Pagos')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">💳 Gestor de Pagos</h1>
    </div>

    <!-- Mensajes de éxito/error -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>{{ session('success') }}</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>{{ session('error') }}</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <!-- Tarjetas de Estadísticas -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Pagos
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($estadisticas['total_pagos']) }}
                            </div>
                            <div class="text-xs text-muted mt-1">
                                <i class="fas fa-wallet"></i> Uala: {{ $estadisticas['total_uala'] ?? 0 }} |
                                <i class="fas fa-money-check"></i> Transfer: {{ $estadisticas['total_transferencias'] ?? 0 }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-credit-card fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Pagos Exitosos
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($estadisticas['pagos_exitosos']) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Pendientes de Verificar
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($estadisticas['comprobantes_pendientes'] ?? 0) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Total Recaudado
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                ${{ number_format($estadisticas['total_recaudado'], 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filtros de Búsqueda</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.gestor-pagos') }}">
                <div class="row">
                    <div class="col-md-2 mb-3">
                        <label for="metodo_pago">Método de Pago</label>
                        <select name="metodo_pago" id="metodo_pago" class="form-control">
                            <option value="">Todos</option>
                            <option value="uala" {{ request('metodo_pago') == 'uala' ? 'selected' : '' }}>Uala</option>
                            <option value="transferencia" {{ request('metodo_pago') == 'transferencia' ? 'selected' : '' }}>Transferencia</option>
                        </select>
                    </div>

                    <div class="col-md-2 mb-3">
                        <label for="estado_comprobante">Estado Comprobante</label>
                        <select name="estado_comprobante" id="estado_comprobante" class="form-control">
                            <option value="">Todos</option>
                            <option value="pendiente" {{ request('estado_comprobante') == 'pendiente' ? 'selected' : '' }}>⏳ Pendiente</option>
                            <option value="verificado" {{ request('estado_comprobante') == 'verificado' ? 'selected' : '' }}>✅ Verificado</option>
                            <option value="rechazado" {{ request('estado_comprobante') == 'rechazado' ? 'selected' : '' }}>❌ Rechazado</option>
                        </select>
                    </div>

                    <div class="col-md-2 mb-3">
                        <label for="estado_pago">Estado Pago Uala</label>
                        <select name="estado_pago" id="estado_pago" class="form-control">
                            <option value="">Todos</option>
                            <option value="approved" {{ request('estado_pago') == 'approved' ? 'selected' : '' }}>Aprobado</option>
                            <option value="rejected" {{ request('estado_pago') == 'rejected' ? 'selected' : '' }}>Rechazado</option>
                            <option value="pending" {{ request('estado_pago') == 'pending' ? 'selected' : '' }}>Pendiente</option>
                        </select>
                    </div>

                    <div class="col-md-2 mb-3">
                        <label for="estado_reserva">Estado Reserva</label>
                        <select name="estado_reserva" id="estado_reserva" class="form-control">
                            <option value="">Todos</option>
                            <option value="confirmada" {{ request('estado_reserva') == 'confirmada' ? 'selected' : '' }}>Confirmada</option>
                            <option value="pendiente_pago" {{ request('estado_reserva') == 'pendiente_pago' ? 'selected' : '' }}>Pendiente Pago</option>
                            <option value="cancelada" {{ request('estado_reserva') == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                        </select>
                    </div>

                    <div class="col-md-2 mb-3">
                        <label for="fecha_desde">Fecha Desde</label>
                        <input type="date" name="fecha_desde" id="fecha_desde" class="form-control" value="{{ request('fecha_desde') }}">
                    </div>

                    <div class="col-md-2 mb-3">
                        <label for="fecha_hasta">Fecha Hasta</label>
                        <input type="date" name="fecha_hasta" id="fecha_hasta" class="form-control" value="{{ request('fecha_hasta') }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Filtrar
                        </button>
                        <a href="{{ route('admin.gestor-pagos') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Limpiar
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla de Pagos -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Historial de Pagos</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th>ID</th>
                            <th>Pasajero</th>
                            <th>Conductor</th>
                            <th>Viaje</th>
                            <th>Monto</th>
                            <th>Método Pago</th>
                            <th>Estado</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pagos as $pago)
                        <tr>
                            <td><strong>#{{ $pago->id }}</strong></td>
                            <td>
                                <strong>{{ $pago->user->name ?? 'N/A' }}</strong><br>
                                <small class="text-muted">{{ $pago->user->email ?? 'N/A' }}</small>
                            </td>
                            <td>
                                <strong>{{ $pago->viaje->conductor->name ?? 'N/A' }}</strong><br>
                                <small class="text-muted">{{ $pago->viaje->conductor->email ?? 'N/A' }}</small>
                            </td>
                            <td>
                                <div style="max-width: 200px;">
                                    <strong>{{ Str::limit($pago->viaje->origen_direccion ?? 'N/A', 25) }}</strong><br>
                                    <i class="fas fa-arrow-down text-primary"></i><br>
                                    <strong>{{ Str::limit($pago->viaje->destino_direccion ?? 'N/A', 25) }}</strong><br>
                                    <small class="text-muted">
                                        <i class="far fa-calendar"></i> {{ optional($pago->viaje->fecha_salida)->format('d/m/Y') ?? 'N/A' }}
                                    </small>
                                </div>
                            </td>
                            <td>
                                <strong>${{ number_format($pago->total, 0, ',', '.') }}</strong><br>
                                <small class="text-muted">{{ $pago->cantidad_puestos }} puesto(s)</small>
                            </td>
                            <td>
                                @if($pago->metodo_pago === 'transferencia' || $pago->comprobante_pago)
                                    <span class="badge badge-info badge-lg">
                                        <i class="fas fa-money-check"></i> Transferencia
                                    </span>
                                @elseif($pago->uala_checkout_id)
                                    <span class="badge badge-primary badge-lg">
                                        <i class="fas fa-wallet"></i> Uala
                                    </span>
                                @else
                                    <span class="badge badge-secondary">N/A</span>
                                @endif
                            </td>
                            <td>
                                <!-- Estado para Transferencia -->
                                @if($pago->metodo_pago === 'transferencia' || $pago->comprobante_pago)
                                    @if($pago->comprobante_verificado)
                                        <span class="badge badge-success">✅ Verificado</span>
                                    @elseif($pago->comprobante_rechazado)
                                        <span class="badge badge-danger">❌ Rechazado</span>
                                    @else
                                        <span class="badge badge-warning">⏳ Pendiente</span>
                                    @endif
                                @endif

                                <!-- Estado para Uala -->
                                @if($pago->uala_checkout_id)
                                    @switch($pago->uala_payment_status)
                                        @case('approved')
                                            <span class="badge badge-success">✅ Aprobado</span>
                                            @break
                                        @case('rejected')
                                            <span class="badge badge-danger">❌ Rechazado</span>
                                            @break
                                        @case('pending')
                                            <span class="badge badge-warning">⏳ Pendiente</span>
                                            @break
                                        @default
                                            <span class="badge badge-secondary">{{ $pago->uala_payment_status ?? 'N/A' }}</span>
                                    @endswitch
                                @endif

                                <br>
                                <!-- Estado de Reserva -->
                                <small class="mt-1">
                                    @switch($pago->estado)
                                        @case('confirmada')
                                            <span class="badge badge-success badge-sm">Confirmada</span>
                                            @break
                                        @case('pendiente_pago')
                                            <span class="badge badge-warning badge-sm">Pendiente Pago</span>
                                            @break
                                        @case('cancelada')
                                            <span class="badge badge-danger badge-sm">Cancelada</span>
                                            @break
                                        @default
                                            <span class="badge badge-secondary badge-sm">{{ ucfirst($pago->estado) }}</span>
                                    @endswitch
                                </small>
                            </td>
                            <td>
                                @if($pago->metodo_pago === 'transferencia' && $pago->fecha_subida_comprobante)
                                    <small>
                                        <i class="far fa-clock"></i> {{ $pago->fecha_subida_comprobante->format('d/m/Y H:i') }}
                                    </small>
                                @elseif($pago->uala_payment_date)
                                    <small>
                                        <i class="far fa-clock"></i> {{ $pago->uala_payment_date->format('d/m/Y H:i') }}
                                    </small>
                                @else
                                    <small class="text-muted">N/A</small>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group-vertical" role="group" style="min-width: 140px;">
                                    <!-- Ver Detalles -->
                                    <button type="button" class="btn btn-sm btn-info mb-1" data-toggle="modal" data-target="#detalleModal{{ $pago->id }}">
                                        <i class="fas fa-eye"></i> Ver Detalles
                                    </button>

                                    <!-- Ver/Descargar Comprobante -->
                                    @if($pago->comprobante_pago)
                                        <a href="{{ asset('storage/' . $pago->comprobante_pago) }}" target="_blank" class="btn btn-sm btn-primary mb-1">
                                            <i class="fas fa-file-image"></i> Ver Comprobante
                                        </a>
                                    @endif

                                    <!-- Aprobar Comprobante -->
                                    @if($pago->comprobante_pago && !$pago->comprobante_verificado && !$pago->comprobante_rechazado)
                                        <form action="{{ route('admin.comprobante.aprobar', $pago->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success mb-1 w-100" onclick="return confirm('¿Aprobar este comprobante?')">
                                                <i class="fas fa-check"></i> Aprobar
                                            </button>
                                        </form>

                                        <button type="button" class="btn btn-sm btn-danger mb-1" data-toggle="modal" data-target="#rechazarModal{{ $pago->id }}">
                                            <i class="fas fa-times"></i> Rechazar
                                        </button>
                                    @endif

                                    <!-- Enlace Uala -->
                                    @if($pago->uala_payment_url)
                                        <a href="{{ $pago->uala_payment_url }}" target="_blank" class="btn btn-sm btn-primary mb-1">
                                            <i class="fas fa-external-link-alt"></i> Ver en Uala
                                        </a>
                                    @endif
                                </div>

                                <!-- Modal de Detalles Mejorado -->
                                <div class="modal fade" id="detalleModal{{ $pago->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-xl">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary text-white">
                                                <h5 class="modal-title">
                                                    <i class="fas fa-file-invoice-dollar"></i> Detalles del Pago - Reserva #{{ $pago->id }}
                                                </h5>
                                                <button type="button" class="close text-white" data-dismiss="modal">
                                                    <span>&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body" style="background-color: #f8f9fc;">
                                                <!-- Alert de Estado -->
                                                @if($pago->comprobante_pago)
                                                    @if($pago->comprobante_verificado)
                                                        <div class="alert alert-success border-left-success">
                                                            <i class="fas fa-check-circle"></i> <strong>Comprobante Verificado</strong>
                                                            <small class="d-block mt-1">Verificado el {{ $pago->fecha_verificacion_comprobante?->format('d/m/Y H:i') ?? 'N/A' }}</small>
                                                        </div>
                                                    @elseif($pago->comprobante_rechazado)
                                                        <div class="alert alert-danger border-left-danger">
                                                            <i class="fas fa-times-circle"></i> <strong>Comprobante Rechazado</strong>
                                                            <small class="d-block mt-1"><strong>Motivo:</strong> {{ $pago->motivo_rechazo_comprobante }}</small>
                                                        </div>
                                                    @else
                                                        <div class="alert alert-warning border-left-warning">
                                                            <i class="fas fa-exclamation-triangle"></i> <strong>Comprobante Pendiente de Verificación</strong>
                                                            <small class="d-block mt-1">Revisa el comprobante y decide si aprobarlo o rechazarlo.</small>
                                                        </div>
                                                    @endif
                                                @endif

                                                <div class="row">
                                                    <!-- Columna Izquierda: Información del Pago y Pasajero -->
                                                    <div class="col-lg-5">
                                                        <!-- Información del Pasajero -->
                                                        <div class="card shadow-sm mb-3">
                                                            <div class="card-header bg-gradient-primary text-white">
                                                                <h6 class="m-0"><i class="fas fa-user"></i> Información del Pasajero</h6>
                                                            </div>
                                                            <div class="card-body">
                                                                <div class="info-row">
                                                                    <small class="text-muted">Nombre</small>
                                                                    <div><strong>{{ $pago->user->name ?? 'N/A' }}</strong></div>
                                                                </div>
                                                                <div class="info-row">
                                                                    <small class="text-muted">Email</small>
                                                                    <div>{{ $pago->user->email ?? 'N/A' }}</div>
                                                                </div>
                                                                <div class="info-row">
                                                                    <small class="text-muted">Teléfono</small>
                                                                    <div>{{ $pago->user->celular ?? 'N/A' }}</div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Información del Pago -->
                                                        <div class="card shadow-sm mb-3">
                                                            <div class="card-header" style="background: linear-gradient(135deg, #36b9cc 0%, #258391 100%); color: white;">
                                                                <h6 class="m-0"><i class="fas fa-credit-card"></i> Información del Pago</h6>
                                                            </div>
                                                            <div class="card-body">
                                                                <div class="info-row">
                                                                    <small class="text-muted">Método de Pago</small>
                                                                    <div>
                                                                        @if($pago->metodo_pago === 'transferencia' || $pago->comprobante_pago)
                                                                            <span class="badge badge-info badge-lg">
                                                                                <i class="fas fa-money-check"></i> Transferencia Bancaria
                                                                            </span>
                                                                        @elseif($pago->uala_checkout_id)
                                                                            <span class="badge badge-primary badge-lg">
                                                                                <i class="fas fa-wallet"></i> Uala
                                                                            </span>
                                                                        @else
                                                                            <span class="badge badge-secondary">N/A</span>
                                                                        @endif
                                                                    </div>
                                                                </div>

                                                                @if($pago->comprobante_pago)
                                                                    <div class="info-row">
                                                                        <small class="text-muted">Archivo Comprobante</small>
                                                                        <div>
                                                                            <a href="{{ asset('storage/' . $pago->comprobante_pago) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                                                <i class="fas fa-download"></i> Descargar Comprobante
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                    <div class="info-row">
                                                                        <small class="text-muted">Fecha de Subida</small>
                                                                        <div>{{ $pago->fecha_subida_comprobante?->format('d/m/Y H:i:s') ?? 'N/A' }}</div>
                                                                    </div>
                                                                    <div class="info-row">
                                                                        <small class="text-muted">Estado del Comprobante</small>
                                                                        <div>
                                                                            @if($pago->comprobante_verificado)
                                                                                <span class="badge badge-success">✅ Verificado</span>
                                                                            @elseif($pago->comprobante_rechazado)
                                                                                <span class="badge badge-danger">❌ Rechazado</span>
                                                                            @else
                                                                                <span class="badge badge-warning">⏳ Pendiente</span>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                @endif

                                                                @if($pago->uala_checkout_id)
                                                                    <div class="info-row">
                                                                        <small class="text-muted">Uala Checkout ID</small>
                                                                        <div><code>{{ $pago->uala_checkout_id }}</code></div>
                                                                    </div>
                                                                    <div class="info-row">
                                                                        <small class="text-muted">Estado Pago Uala</small>
                                                                        <div>
                                                                            @switch($pago->uala_payment_status)
                                                                                @case('approved')
                                                                                    <span class="badge badge-success">✅ Aprobado</span>
                                                                                    @break
                                                                                @case('rejected')
                                                                                    <span class="badge badge-danger">❌ Rechazado</span>
                                                                                    @break
                                                                                @case('pending')
                                                                                    <span class="badge badge-warning">⏳ Pendiente</span>
                                                                                    @break
                                                                                @default
                                                                                    <span class="badge badge-secondary">{{ $pago->uala_payment_status ?? 'N/A' }}</span>
                                                                            @endswitch
                                                                        </div>
                                                                    </div>
                                                                @endif

                                                                <div class="info-row">
                                                                    <small class="text-muted">Cantidad de Puestos</small>
                                                                    <div><strong>{{ $pago->cantidad_puestos }}</strong> asiento(s)</div>
                                                                </div>
                                                                <div class="info-row">
                                                                    <small class="text-muted">Precio por Persona</small>
                                                                    <div>${{ number_format($pago->precio_por_persona, 0, ',', '.') }}</div>
                                                                </div>
                                                                <div class="info-row">
                                                                    <small class="text-muted">Total Pagado</small>
                                                                    <div><h4 class="text-success mb-0">${{ number_format($pago->total, 0, ',', '.') }}</h4></div>
                                                                </div>
                                                                <div class="info-row">
                                                                    <small class="text-muted">Estado de la Reserva</small>
                                                                    <div>
                                                                        @switch($pago->estado)
                                                                            @case('confirmada')
                                                                                <span class="badge badge-success">Confirmada</span>
                                                                                @break
                                                                            @case('pendiente_pago')
                                                                                <span class="badge badge-warning">Pendiente Pago</span>
                                                                                @break
                                                                            @case('cancelada')
                                                                                <span class="badge badge-danger">Cancelada</span>
                                                                                @break
                                                                            @default
                                                                                <span class="badge badge-secondary">{{ ucfirst($pago->estado) }}</span>
                                                                        @endswitch
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Información del Viaje -->
                                                        <div class="card shadow-sm mb-3">
                                                            <div class="card-header" style="background: linear-gradient(135deg, #1cc88a 0%, #169b6b 100%); color: white;">
                                                                <h6 class="m-0"><i class="fas fa-route"></i> Información del Viaje</h6>
                                                            </div>
                                                            <div class="card-body">
                                                                <div class="info-row">
                                                                    <small class="text-muted">Conductor</small>
                                                                    <div><strong>{{ $pago->viaje->conductor->name ?? 'N/A' }}</strong></div>
                                                                </div>
                                                                <div class="info-row">
                                                                    <small class="text-muted">Origen</small>
                                                                    <div>{{ $pago->viaje->origen_direccion ?? 'N/A' }}</div>
                                                                </div>
                                                                <div class="info-row">
                                                                    <small class="text-muted">Destino</small>
                                                                    <div>{{ $pago->viaje->destino_direccion ?? 'N/A' }}</div>
                                                                </div>
                                                                <div class="info-row">
                                                                    <small class="text-muted">Fecha del Viaje</small>
                                                                    <div>
                                                                        <i class="far fa-calendar"></i> {{ optional($pago->viaje->fecha_salida)->format('d/m/Y') ?? 'N/A' }}
                                                                        <i class="far fa-clock ml-2"></i> {{ $pago->viaje->hora_salida ?? 'N/A' }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Columna Derecha: Vista Previa del Comprobante -->
                                                    <div class="col-lg-7">
                                                        @if($pago->comprobante_pago)
                                                            <div class="card shadow-sm">
                                                                <div class="card-header bg-white">
                                                                    <h6 class="m-0 text-primary"><i class="fas fa-file-image"></i> Vista Previa del Comprobante</h6>
                                                                </div>
                                                                <div class="card-body text-center" style="background-color: #f0f0f0;">
                                                                    @php
                                                                        $extension = pathinfo($pago->comprobante_pago, PATHINFO_EXTENSION);
                                                                    @endphp
                                                                    @if(in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif']))
                                                                        <img src="{{ asset('storage/' . $pago->comprobante_pago) }}"
                                                                             class="img-fluid comprobante-preview"
                                                                             style="max-height: 600px; cursor: zoom-in;"
                                                                             onclick="window.open('{{ asset('storage/' . $pago->comprobante_pago) }}', '_blank')">
                                                                        <div class="mt-2">
                                                                            <small class="text-muted"><i class="fas fa-info-circle"></i> Click en la imagen para ampliar</small>
                                                                        </div>
                                                                    @elseif(strtolower($extension) === 'pdf')
                                                                        <iframe src="{{ asset('storage/' . $pago->comprobante_pago) }}"
                                                                                class="comprobante-preview"
                                                                                style="width: 100%; height: 600px;"></iframe>
                                                                    @else
                                                                        <div class="alert alert-info">
                                                                            <i class="fas fa-file"></i>
                                                                            <p class="mb-0">Tipo de archivo no previsualizable.</p>
                                                                            <a href="{{ asset('storage/' . $pago->comprobante_pago) }}" target="_blank" class="btn btn-primary btn-sm mt-2">
                                                                                <i class="fas fa-download"></i> Descargar Archivo
                                                                            </a>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        @else
                                                            <div class="card shadow-sm">
                                                                <div class="card-body text-center py-5">
                                                                    <i class="fas fa-file-invoice fa-4x text-muted mb-3"></i>
                                                                    <h5 class="text-muted">No hay comprobante disponible</h5>
                                                                    @if($pago->uala_checkout_id)
                                                                        <p class="text-muted">Este pago se realizó a través de Uala.</p>
                                                                        @if($pago->uala_payment_url)
                                                                            <a href="{{ $pago->uala_payment_url }}" target="_blank" class="btn btn-primary">
                                                                                <i class="fas fa-external-link-alt"></i> Ver en Uala
                                                                            </a>
                                                                        @endif
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="modal-footer" style="background-color: #f8f9fc;">
                                                @if($pago->comprobante_pago && !$pago->comprobante_verificado && !$pago->comprobante_rechazado)
                                                    <!-- Botones de Aprobación/Rechazo -->
                                                    <form action="{{ route('admin.comprobante.aprobar', $pago->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-success" onclick="return confirm('¿Estás seguro de APROBAR este comprobante?\n\nLa reserva será confirmada y el pasajero recibirá un email de confirmación.')">
                                                            <i class="fas fa-check-circle"></i> Aprobar Comprobante
                                                        </button>
                                                    </form>

                                                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#rechazarModal{{ $pago->id }}" data-dismiss="modal">
                                                        <i class="fas fa-times-circle"></i> Rechazar Comprobante
                                                    </button>
                                                @endif

                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                                    <i class="fas fa-times"></i> Cerrar
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal de Rechazo -->
                                @if($pago->comprobante_pago && !$pago->comprobante_verificado && !$pago->comprobante_rechazado)
                                <div class="modal fade" id="rechazarModal{{ $pago->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger text-white">
                                                <h5 class="modal-title">Rechazar Comprobante - Reserva #{{ $pago->id }}</h5>
                                                <button type="button" class="close text-white" data-dismiss="modal">
                                                    <span>&times;</span>
                                                </button>
                                            </div>
                                            <form action="{{ route('admin.comprobante.rechazar', $pago->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="alert alert-warning">
                                                        <i class="fas fa-exclamation-triangle"></i>
                                                        <strong>Atención:</strong> Al rechazar este comprobante, la reserva será cancelada y los puestos volverán a estar disponibles.
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="motivo"><strong>Motivo del Rechazo:</strong> <span class="text-danger">*</span></label>
                                                        <textarea name="motivo" id="motivo" class="form-control" rows="4" required minlength="10" maxlength="500" placeholder="Describe el motivo del rechazo (mínimo 10 caracteres)"></textarea>
                                                        <small class="form-text text-muted">Este mensaje será enviado al pasajero por email.</small>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                                    <button type="submit" class="btn btn-danger">
                                                        <i class="fas fa-times"></i> Rechazar Comprobante
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">
                                <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                                No hay pagos registrados con los filtros seleccionados.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div class="d-flex justify-content-center">
                {{ $pagos->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>

<style>
.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}
.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}
.border-left-danger {
    border-left: 0.25rem solid #e74a3b !important;
}
.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}
.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}

/* Forzar texto oscuro en badges */
.badge-lg {
    padding: 0.5em 0.75em;
    font-size: 0.9em;
    color: #fff !important;
}

.badge-sm {
    padding: 0.25em 0.5em;
    font-size: 0.75em;
    color: #fff !important;
}

/* Asegurar contraste en badges específicos */
.badge-info {
    background-color: #36b9cc !important;
    color: #fff !important;
}

.badge-primary {
    background-color: #4e73df !important;
    color: #fff !important;
}

.badge-success {
    background-color: #1cc88a !important;
    color: #fff !important;
}

.badge-danger {
    background-color: #e74a3b !important;
    color: #fff !important;
}

.badge-warning {
    background-color: #f6c23e !important;
    color: #212529 !important; /* Texto oscuro para amarillo */
}

.badge-secondary {
    background-color: #858796 !important;
    color: #fff !important;
}

.table td {
    vertical-align: middle;
}

.btn-group-vertical .btn {
    white-space: nowrap;
}

/* Mejorar modal */
.modal-header.bg-primary {
    background: linear-gradient(135deg, #4e73df 0%, #224abe 100%) !important;
}

.modal-header.bg-danger {
    background: linear-gradient(135deg, #e74a3b 0%, #c93123 100%) !important;
}

.comprobante-preview {
    border: 2px solid #ddd;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.info-row {
    border-bottom: 1px solid #e3e6f0;
    padding: 0.5rem 0;
}

.info-row:last-child {
    border-bottom: none;
}

.section-header {
    border-bottom: 2px solid #4e73df;
    padding-bottom: 0.5rem;
    margin-bottom: 1rem;
    color: #4e73df;
    font-weight: 600;
}

/* Headers de cards con gradientes */
.bg-gradient-primary {
    background: linear-gradient(135deg, #4e73df 0%, #224abe 100%) !important;
    color: white !important;
}

.card-header h6 {
    color: inherit !important;
}

/* Alertas con bordes */
.alert.border-left-success {
    border-left: 4px solid #1cc88a;
}

.alert.border-left-danger {
    border-left: 4px solid #e74a3b;
}

.alert.border-left-warning {
    border-left: 4px solid #f6c23e;
}
</style>
@endsection
