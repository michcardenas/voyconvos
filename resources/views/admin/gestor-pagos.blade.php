@extends('layouts.app_admin')

@section('title', 'Gestor de Pagos')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">💳 Gestor de Pagos</h1>
    </div>

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
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Pagos Fallidos
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($estadisticas['pagos_fallidos']) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-times-circle fa-2x text-gray-300"></i>
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
                    <div class="col-md-3 mb-3">
                        <label for="estado_pago">Estado del Pago</label>
                        <select name="estado_pago" id="estado_pago" class="form-control">
                            <option value="">Todos</option>
                            <option value="approved" {{ request('estado_pago') == 'approved' ? 'selected' : '' }}>Aprobado</option>
                            <option value="rejected" {{ request('estado_pago') == 'rejected' ? 'selected' : '' }}>Rechazado</option>
                            <option value="pending" {{ request('estado_pago') == 'pending' ? 'selected' : '' }}>Pendiente</option>
                        </select>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="estado_reserva">Estado de Reserva</label>
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

                    <div class="col-md-2 mb-3">
                        <label for="buscar">Buscar</label>
                        <input type="text" name="buscar" id="buscar" class="form-control" placeholder="Usuario, email, ID..." value="{{ request('buscar') }}">
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
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID Reserva</th>
                            <th>Pasajero</th>
                            <th>Conductor</th>
                            <th>Viaje</th>
                            <th>Monto</th>
                            <th>Estado Pago</th>
                            <th>Estado Reserva</th>
                            <th>Fecha Pago</th>
                            <th>Uala ID</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pagos as $pago)
                        <tr>
                            <td>{{ $pago->id }}</td>
                            <td>
                                <strong>{{ $pago->user->name ?? 'N/A' }}</strong><br>
                                <small class="text-muted">{{ $pago->user->email ?? 'N/A' }}</small>
                            </td>
                            <td>
                                <strong>{{ $pago->viaje->conductor->name ?? 'N/A' }}</strong><br>
                                <small class="text-muted">{{ $pago->viaje->conductor->email ?? 'N/A' }}</small>
                            </td>
                            <td>
                                <strong>{{ Str::limit($pago->viaje->origen_direccion ?? 'N/A', 20) }}</strong><br>
                                <i class="fas fa-arrow-down"></i><br>
                                <strong>{{ Str::limit($pago->viaje->destino_direccion ?? 'N/A', 20) }}</strong><br>
                                <small class="text-muted">
                                    {{ optional($pago->viaje->fecha_salida)->format('d/m/Y') ?? 'N/A' }}
                                </small>
                            </td>
                            <td>
                                <strong>${{ number_format($pago->total, 0, ',', '.') }}</strong><br>
                                <small class="text-muted">{{ $pago->cantidad_puestos }} puesto(s)</small>
                            </td>
                            <td>
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
                            </td>
                            <td>
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
                            </td>
                            <td>
                                {{ optional($pago->uala_payment_date)->format('d/m/Y H:i') ?? 'N/A' }}
                            </td>
                            <td>
                                <code>{{ Str::limit($pago->uala_checkout_id ?? 'N/A', 15) }}</code>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#detalleModal{{ $pago->id }}">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    @if($pago->uala_payment_url)
                                    <a href="{{ $pago->uala_payment_url }}" target="_blank" class="btn btn-sm btn-primary">
                                        <i class="fas fa-external-link-alt"></i>
                                    </a>
                                    @endif
                                </div>

                                <!-- Modal de Detalles -->
                                <div class="modal fade" id="detalleModal{{ $pago->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Detalles del Pago - Reserva #{{ $pago->id }}</h5>
                                                <button type="button" class="close" data-dismiss="modal">
                                                    <span>&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <h6>Información del Pago</h6>
                                                        <table class="table table-sm">
                                                            <tr><td><strong>Uala Checkout ID:</strong></td><td>{{ $pago->uala_checkout_id }}</td></tr>
                                                            <tr><td><strong>Referencia Externa:</strong></td><td>{{ $pago->uala_external_reference ?? 'N/A' }}</td></tr>
                                                            <tr><td><strong>Estado del Pago:</strong></td><td>{{ $pago->uala_payment_status ?? 'N/A' }}</td></tr>
                                                            <tr><td><strong>Fecha de Pago:</strong></td><td>{{ optional($pago->uala_payment_date)->format('d/m/Y H:i:s') ?? 'N/A' }}</td></tr>
                                                            <tr><td><strong>URL de Pago:</strong></td><td><a href="{{ $pago->uala_payment_url }}" target="_blank">Ver</a></td></tr>
                                                        </table>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <h6>Información de la Reserva</h6>
                                                        <table class="table table-sm">
                                                            <tr><td><strong>Cantidad Puestos:</strong></td><td>{{ $pago->cantidad_puestos }}</td></tr>
                                                            <tr><td><strong>Precio por Persona:</strong></td><td>${{ number_format($pago->precio_por_persona, 0, ',', '.') }}</td></tr>
                                                            <tr><td><strong>Total:</strong></td><td>${{ number_format($pago->total, 0, ',', '.') }}</td></tr>
                                                            <tr><td><strong>Fecha Reserva:</strong></td><td>{{ optional($pago->fecha_reserva)->format('d/m/Y H:i') ?? 'N/A' }}</td></tr>
                                                            <tr><td><strong>Estado Reserva:</strong></td><td>{{ ucfirst($pago->estado) }}</td></tr>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center text-muted">No hay pagos registrados.</td>
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
</style>
@endsection