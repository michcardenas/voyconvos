@extends('layouts.app_admin')

@section('title', 'Detalle del Viaje #' . $viaje->id)

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-route"></i> Detalle del Viaje 
        </h1>
        <div>
            <a href="{{ url()->previous() }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
      
        </div>
    </div>

    <div class="row">
        <!-- Información Principal del Viaje -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle"></i> Información del Viaje
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Ruta -->
                        <div class="col-md-12 mb-4">
                            <div class="ruta-container">
                                <div class="origen-destino">
                                    <div class="punto-ruta origen">
                                        <i class="fas fa-map-marker-alt text-success"></i>
                                        <div>
                                            <strong>Origen:</strong><br>
                                            <span class="direccion">{{ $viaje->origen_direccion ?? 'No especificado' }}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="linea-ruta">
                                        <i class="fas fa-route text-primary"></i>
                                        <span class="distancia">{{ $viaje->distancia_km ?? 'N/A' }} km</span>
                                    </div>
                                    
                                    <div class="punto-ruta destino">
                                        <i class="fas fa-flag-checkered text-danger"></i>
                                        <div>
                                            <strong>Destino:</strong><br>
                                            <span class="direccion">{{ $viaje->destino_direccion ?? 'No especificado' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Detalles del Viaje -->
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong><i class="fas fa-calendar text-primary"></i> Fecha:</strong></td>
                                    <td>{{ $viaje->fecha_salida ? \Carbon\Carbon::parse($viaje->fecha_salida)->format('d/m/Y') : 'No especificada' }}</td>
                                </tr>
                                <tr>
                                    <td><strong><i class="fas fa-clock text-primary"></i> Hora:</strong></td>
                                    <td>{{ $viaje->hora_salida ? \Carbon\Carbon::parse($viaje->hora_salida)->format('H:i') : 'No especificada' }}</td>
                                </tr>
                                <tr>
                                    <td><strong><i class="fas fa-car text-primary"></i> Vehículo:</strong></td>
                                    <td>{{ $viaje->vehiculo ?? 'No especificado' }}</td>
                                </tr>
                                <tr>
                                    <td><strong><i class="fas fa-users text-primary"></i> Puestos:</strong></td>
                                    <td>
                                        <span class="badge badge-success">
                                           
                                        </span>
                                       {{ $viaje->puestos_disponibles ?? 0 }}/{{ $viaje->puestos_totales ?? 0 }}   disponibles
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong><i class="fas fa-dollar-sign text-success"></i> Valor por persona:</strong></td>
                                    <td class="money-text">${{ number_format($viaje->valor_cobrado ?? 0, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td><strong><i class="fas fa-calculator text-info"></i> Valor estimado:</strong></td>
                                    <td>${{ number_format($viaje->valor_estimado ?? 0, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td><strong><i class="fas fa-toggle-on text-{{ $viaje->activo ? 'success' : 'danger' }}"></i> Estado:</strong></td>
                                    <td>
                                        @switch($viaje->estado)
                                            @case('pendiente')
                                                <span class="badge badge-warning">⏳ Pendiente</span>
                                                @break
                                            @case('confirmado')
                                            @case('activo')
                                                <span class="badge badge-success">✅ Confirmado</span>
                                                @break
                                            @case('en_curso')
                                                <span class="badge badge-primary">🚌 En Curso</span>
                                                @break
                                            @case('completado')
                                                <span class="badge badge-secondary">✔️ Completado</span>
                                                @break
                                            @case('cancelado')
                                            @case('inactivo')
                                                <span class="badge badge-danger">❌ Cancelado</span>
                                                @break
                                            @case('listo_para_iniciar')
                                                <span class="badge badge-info">🚀 Listo</span>
                                                @break
                                            @default
                                                <span class="badge badge-light">{{ ucfirst($viaje->estado ?? 'N/A') }}</span>
                                        @endswitch
                                    </td>
                                </tr>
                                <tr>
                                
                                </tr>
                            </table>
                        </div>

                        @if($viaje->observaciones)
                        <div class="col-md-12 mt-3">
                            <div class="alert alert-info">
                                <strong><i class="fas fa-sticky-note"></i> Observaciones:</strong><br>
                                {{ $viaje->observaciones }}
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Información del Conductor -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-user-tie"></i> Información del Conductor
                    </h6>
                </div>
                <div class="card-body">
                    @if($viaje->conductor)
                        <div class="text-center mb-3">
                           <div class="conductor-avatar">
                                @if($viaje->conductor && $viaje->conductor->foto)
                                    <img src="{{ asset('storage/' . $viaje->conductor->foto) }}" 
                                        alt="Avatar de {{ $viaje->conductor->name }}" 
                                        class="conductor-image">
                                @else
                                    <img src="{{ asset('img/usuario.png') }}" 
                                        alt="Avatar por defecto" 
                                        class="conductor-image">
                                @endif
                            </div>
                            <h5 class="mt-2">{{ $viaje->conductor->name }}</h5>
                            <p class="text-muted">{{ $viaje->conductor->email }}</p>
                        </div>

                        <table class="table table-sm">
                            <tr>
                                <td><strong>Teléfono:</strong></td>
                                <td>{{ $viaje->conductor->phone ?? 'No especificado' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Verificado:</strong></td>
                          <td>
                            @if($viaje->conductor->verificado)
                                <span class="badge" style="background-color: #4CAF50; color: white; font-weight: 600;">
                                    <i class="fas fa-check-circle"></i> VERIFICADO
                                </span>
                            @else
                                <span class="badge" style="background-color: #FBBC05; color: #3A3A3A; font-weight: 600;">
                                    <i class="fas fa-exclamation-triangle"></i> SIN VERIFICAR
                                </span>
                            @endif
                        </td>
                            </tr>
                            @if($viaje->registroConductor)
                            <tr>
                                <td><strong>Vehículo:</strong></td>
                                <td>{{ $viaje->registroConductor->marca_vehiculo }} {{ $viaje->registroConductor->modelo_vehiculo }}</td>
                            </tr>
                            <tr>
                                <td><strong>Año:</strong></td>
                                <td>{{ $viaje->registroConductor->anio_vehiculo }}</td>
                            </tr>
                            <tr>
                                <td><strong>Patente:</strong></td>
                                <td><code>{{ $viaje->registroConductor->patente }}</code></td>
                            </tr>
                            @endif
                        </table>

                    
                    @else
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            No se encontró información del conductor.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Reservas del Viaje -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-list"></i> Reservas del Viaje ({{ $viaje->reservas->count() }})
                    </h6>
                </div>
                <div class="card-body">
                    @if($viaje->reservas->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-primary">
                                    <tr>
                                        <th>ID</th>
                                        <th>Pasajero</th>
                                        <th>Contacto</th>
                                        <th>Puestos</th>
                                        <th>Monto</th>
                                        <th>Estado</th>
                                        <th>Fecha Reserva</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($viaje->reservas as $reserva)
                                    <tr>
                                        <td><strong>#{{ $reserva->id }}</strong></td>
                                        <td>
                                            <strong>{{ $reserva->user->name ?? 'Usuario eliminado' }}</strong>
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ $reserva->user->email ?? 'N/A' }}</small><br>
                                            <small class="text-muted">{{ $reserva->user->phone ?? 'Sin teléfono' }}</small>
                                        </td>
                                        <td>
                                            <span class="badge badge-info">{{ $reserva->cantidad_puestos }}</span>
                                        </td>
                                        <td class="money-text">
                                            <strong>${{ number_format($reserva->total, 0, ',', '.') }}</strong>
                                        </td>
                                        <td>
                                            @switch($reserva->estado)
                                                @case('confirmada')
                                                    <span class="badge badge-success">✅ Confirmada</span>
                                                    @break
                                                @case('pendiente')
                                                    <span class="badge badge-warning">⏳ Pendiente</span>
                                                    @break
                                                @case('pendiente_pago')
                                                    <span class="">💳 Pendiente Pago</span>
                                                    @break
                                                @case('cancelada')
                                                    <span class="badge badge-danger">❌ Cancelada</span>
                                                    @break
                                                @default
                                                    <span class="">{{ ucfirst($reserva->estado) }}</span>
                                            @endswitch
                                        </td>
                                        <td>
                                            <small>{{ optional($reserva->fecha_reserva)->format('d/m/Y H:i') ?? 'N/A' }}</small>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-sm btn-info" title="Ver Detalle" data-toggle="modal" data-target="#reservaModal{{ $reserva->id }}">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>

                                            <!-- Modal de Detalle de Reserva -->
                                            <div class="modal fade" id="reservaModal{{ $reserva->id }}" tabindex="-1">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Detalle Reserva #{{ $reserva->id }}</h5>
                                                            <button type="button" class="close" data-dismiss="modal">
                                                                <span>&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <table class="table table-sm">
                                                                <tr><td><strong>Pasajero:</strong></td><td>{{ $reserva->user->name ?? 'N/A' }}</td></tr>
                                                                <tr><td><strong>Email:</strong></td><td>{{ $reserva->user->email ?? 'N/A' }}</td></tr>
                                                                <tr><td><strong>Puestos:</strong></td><td>{{ $reserva->cantidad_puestos }}</td></tr>
                                                                <tr><td><strong>Precio por persona:</strong></td><td>${{ number_format($reserva->precio_por_persona ?? 0, 0, ',', '.') }}</td></tr>
                                                                <tr><td><strong>Total:</strong></td><td>${{ number_format($reserva->total, 0, ',', '.') }}</td></tr>
                                                                <tr><td><strong>Estado:</strong></td><td>{{ ucfirst($reserva->estado) }}</td></tr>
                                                                <tr><td><strong>Fecha Reserva:</strong></td><td>{{ optional($reserva->fecha_reserva)->format('d/m/Y H:i') ?? 'N/A' }}</td></tr>
                                                                @if($reserva->uala_checkout_id)
                                                                <tr><td><strong>ID Uala:</strong></td><td><code>{{ $reserva->uala_checkout_id }}</code></td></tr>
                                                                @endif
                                                            </table>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Resumen de Reservas -->
                        <div class="row mt-4">
                            <div class="col-md-3">
                                <div class="card border-left-success">
                                    <div class="card-body py-2">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Confirmadas</div>
                                        <div class="h6 mb-0 font-weight-bold text-gray-800">
                                            {{ $viaje->reservas->where('estado', 'confirmada')->count() }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card border-left-warning">
                                    <div class="card-body py-2">
                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pendientes</div>
                                        <div class="h6 mb-0 font-weight-bold text-gray-800">
                                            {{ $viaje->reservas->whereIn('estado', ['pendiente', 'pendiente_pago'])->count() }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card border-left-info">
                                    <div class="card-body py-2">
                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Puestos</div>
                                        <div class="h6 mb-0 font-weight-bold text-gray-800">
                                            {{ $viaje->reservas->sum('cantidad_puestos') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card border-left-primary">
                                    <div class="card-body py-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Ingresos</div>
                                        <div class="h6 mb-0 font-weight-bold text-gray-800 money-text">
                                            ${{ number_format($viaje->reservas->where('estado', 'confirmada')->sum('total'), 0, ',', '.') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No hay reservas para este viaje</h5>
                            <p class="text-muted">Aún no se han realizado reservas para este viaje.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Estilos personalizados para la vista de detalle */
.ruta-container {
    background: linear-gradient(135deg, var(--color-azul-claro) 0%, var(--color-fondo-base) 100%);
    border-radius: 12px;
    padding: 20px;
    border: 2px solid var(--color-principal);
}

.origen-destino {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 15px;
}

.punto-ruta {
    display: flex;
    align-items: center;
    gap: 10px;
    flex: 1;
    min-width: 200px;
}

.punto-ruta i {
    font-size: 1.5rem;
}

.direccion {
    color: var(--color-neutro-oscuro);
    font-weight: 500;
}

.linea-ruta {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    padding: 0 20px;
}

.linea-ruta i {
    font-size: 2rem;
    margin-bottom: 5px;
}

.distancia {
    font-weight: 600;
    color: var(--color-principal);
    background: white;
    padding: 4px 8px;
    border-radius: 20px;
    border: 2px solid var(--color-principal);
}

.conductor-avatar {
    margin-bottom: 15px;
}

.money-text {
    color: var(--color-complementario);
    font-weight: 700;
    font-family: 'Courier New', monospace;
}

.table-borderless td {
    border: none;
    padding: 0.5rem 0.75rem;
}

.card {
    border-radius: 12px;
    border: none;
    box-shadow: 0 4px 15px rgba(31, 78, 121, 0.1);
}

.card-header {
    background-color: var(--color-azul-claro);
    border-bottom: 2px solid var(--color-principal);
    border-radius: 12px 12px 0 0;
    color: var(--color-principal);
}

.badge {
    border-radius: 6px;
    padding: 6px 12px;
    font-weight: 600;
    color: black;
}

.border-left-success {
    border-left: 0.25rem solid var(--color-complementario) !important;
}

.border-left-warning {
    border-left: 0.25rem solid var(--color-google-yellow) !important;
}

.border-left-info {
    border-left: 0.25rem solid var(--color-google-blue) !important;
}

.border-left-primary {
    border-left: 0.25rem solid var(--color-principal) !important;
}
.conductor-image {
    width: 65px;
    height: 65px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid rgba(31, 78, 121, 0.3);
    box-shadow: 0 4px 15px rgba(31, 78, 121, 0.1);
    transition: all 0.3s ease;
    background-color: var(--color-fondo-base);
}

.conductor-image:hover {
    border-color: var(--color-principal);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(31, 78, 121, 0.2);
}s
/* Responsive */
@media (max-width: 768px) {
    .origen-destino {
        flex-direction: column;
        text-align: center;
    }
    
    .linea-ruta {
        order: 2;
        padding: 10px 0;
    }
    
    .punto-ruta {
        justify-content: center;
        min-width: auto;
    }
}
</style>
@endsection