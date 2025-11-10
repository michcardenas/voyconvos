@extends('layouts.app_admin')

@section('content')
<style>
    .dashboard-container {
        max-width: 1140px;
        margin: 0 auto;
        padding-top: 100px;
        padding-bottom: 40px;
    }

    .dashboard-title {
        font-size: 2rem;
        font-weight: 600;
        color: #00304b;
        margin-bottom: 30px;
        text-align: center;
    }

    .card-stats {
        width: 250px;
        border-left-width: 5px;
        border-radius: 1rem;
        transition: transform 0.2s ease;
    }

    .card-stats:hover {
        transform: scale(1.02);
    }

    .stat-label {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        margin-bottom: 4px;
    }

    .stat-value {
        font-size: 1.5rem;
        font-weight: bold;
        color: #2e2e2e;
    }

    .stat-subtext {
        font-size: 0.75rem;
        color: #6c757d;
    }

    .stat-icon {
        opacity: 0.2;
    }

    .cards-row {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-around;
        gap: 20px;
    }

    /* Colores personalizados */
    .border-left-primary {
        border-left-color: #4e73df !important;
    }

    .border-left-success {
        border-left-color: #1cc88a !important;
    }

    .border-left-info {
        border-left-color: #36b9cc !important;
    }

    .border-left-warning {
        border-left-color: #f6c23e !important;
    }

    .center-button {
        display: flex;
        justify-content: center;
        margin-top: 40px;
    }

    .btn-view-users {
        background-color: #00304b;
        color: white;
        font-weight: 600;
        padding: 10px 24px;
        border-radius: 8px;
        border: none;
        transition: background-color 0.3s ease;
        text-decoration: none;
    }

    .btn-view-users:hover {
        background-color: #005471;
        color: #fff;
    }
    .table th, .table td {
    vertical-align: middle;
}

.table thead th {
    background-color: #f8f9fc;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.85rem;
}

.table-hover tbody tr:hover {
    background-color: #f1f5f9;
}

.badge {
    padding: 0.5em 0.75em;
    font-size: 0.75rem;
    border-radius: 0.5rem;
}

/* 🔥 ESTILOS CORREGIDOS PARA PAGINACIÓN */
.pagination {
    margin: 0;
    padding: 0;
    justify-content: center !important;
    align-items: center;
}

.pagination .page-item {
    margin: 0 2px;
}

.pagination .page-link {
    font-size: 0.875rem !important;
    padding: 0.5rem 0.75rem !important;
    border: 1px solid #dee2e6;
    border-radius: 6px !important;
    color: #00304b !important;
    background-color: #fff;
    transition: all 0.3s ease;
    min-width: 40px;
    text-align: center;
    line-height: 1.2;
}

.pagination .page-link:hover {
    background-color: #f8f9fa;
    border-color: #00304b;
    color: #00304b !important;
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0, 48, 75, 0.1);
}

.pagination .page-item.active .page-link {
    background-color: #00304b !important;
    border-color: #00304b !important;
    color: #fff !important;
    font-weight: 600;
}

.pagination .page-item.disabled .page-link {
    color: #6c757d !important;
    background-color: #f8f9fa;
    border-color: #dee2e6;
    cursor: not-allowed;
}

/* 🔥 FLECHAS DE NAVEGACIÓN MÁS PEQUEÑAS */
.pagination .page-item:first-child .page-link,
.pagination .page-item:last-child .page-link {
    font-size: 0.7rem !important;
    padding: 0.5rem !important;
    width: 40px !important;
    height: 40px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
}

/* Ocultar el texto original completamente */
.pagination .page-item:first-child .page-link *,
.pagination .page-item:last-child .page-link * {
    display: none !important;
}

/* Reemplazar con flechas pequeñas */
.pagination .page-item:first-child .page-link {
    font-family: Arial, sans-serif !important;
}

.pagination .page-item:first-child .page-link::after {
    content: "◀" !important;
    font-size: 0.7rem !important;
    display: block !important;
}

.pagination .page-item:last-child .page-link::after {
    content: "▶" !important;
    font-size: 0.7rem !important;
    display: block !important;
}

/* Asegurar que el texto original no aparezca */
.pagination .page-item:first-child .page-link,
.pagination .page-item:last-child .page-link {
    color: transparent !important;
    overflow: hidden !important;
}

.pagination .page-item:first-child .page-link::after,
.pagination .page-item:last-child .page-link::after {
    color: #00304b !important;
    position: absolute !important;
    top: 50% !important;
    left: 50% !important;
    transform: translate(-50%, -50%) !important;
}

.pagination .page-item:first-child .page-link:hover::after,
.pagination .page-item:last-child .page-link:hover::after {
    color: #00304b !important;
}

.pagination .page-item.active:first-child .page-link::after,
.pagination .page-item.active:last-child .page-link::after {
    color: #fff !important;
}

/* 🔥 RESPONSIVE PARA MÓVILES */
@media (max-width: 768px) {
    .pagination {
        flex-wrap: wrap;
        gap: 5px;
    }
    
    .pagination .page-link {
        padding: 0.4rem 0.6rem !important;
        font-size: 0.8rem !important;
        min-width: 35px;
    }
}

/* Cursor y hover effects */
.cursor-pointer {
    cursor: pointer;
    transition: all 0.3s ease;
}

.cursor-pointer:hover {
    background-color: var(--color-azul-claro) !important;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(31, 78, 121, 0.15);
}

/* Bordes izquierdos personalizados */
.border-left-primary {
    border-left: 0.25rem solid var(--color-principal) !important;
}

.border-left-success {
    border-left: 0.25rem solid var(--color-complementario) !important;
}

.border-left-info {
    border-left: 0.25rem solid var(--color-google-blue) !important;
}

.border-left-warning {
    border-left: 0.25rem solid var(--color-google-yellow) !important;
}

.border-left-danger {
    border-left: 0.25rem solid var(--color-google-red) !important;
}

.border-left-secondary {
    border-left: 0.25rem solid var(--color-neutro-oscuro) !important;
}

/* Mejoras para las tarjetas de estadísticas */
.card {
    border-radius: 12px;
    border: none;
    transition: all 0.3s ease;
    background-color: var(--color-fondo-base);
}

.card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(31, 78, 121, 0.15);
}

.card-header {
    background-color: var(--color-azul-claro) !important;
    border-bottom: 2px solid var(--color-principal);
    border-radius: 12px 12px 0 0 !important;
    color: var(--color-principal);
    font-weight: 600;
}

/* Badges personalizados */
.badge-primary {
    background-color: var(--color-principal) !important;
    color: white;
}

.badge-success {
    background-color: var(--color-complementario) !important;
    color: white;
}

.badge-info {
    background-color: var(--color-google-blue) !important;
    color: white;
}

.badge-warning {
    background-color: var(--color-google-yellow) !important;
    color: var(--color-neutro-oscuro);
    font-weight: 600;
}

.badge-danger {
    background-color: var(--color-google-red) !important;
    color: white;
}

.badge-secondary {
    background-color: var(--color-neutro-oscuro) !important;
    color: white;
}

.badge-outline-primary {
    border: 2px solid var(--color-principal);
    color: var(--color-principal);
    background-color: transparent;
    font-weight: 600;
}

/* Colores de texto personalizados */
.text-primary {
    color: var(--color-principal) !important;
}

.text-success {
    color: var(--color-complementario) !important;
}

.text-info {
    color: var(--color-google-blue) !important;
}

.text-warning {
    color: var(--color-google-yellow) !important;
}

.text-danger {
    color: var(--color-google-red) !important;
}

/* Botones mejorados */
.btn-primary {
    background-color: var(--color-principal);
    border-color: var(--color-principal);
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    background-color: #1a4268;
    border-color: #1a4268;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(31, 78, 121, 0.3);
}

.btn-success {
    background-color: var(--color-complementario);
    border-color: var(--color-complementario);
    border-radius: 8px;
    font-weight: 600;
}

.btn-info {
    background-color: var(--color-google-blue);
    border-color: var(--color-google-blue);
    border-radius: 8px;
}

.btn-warning {
    background-color: var(--color-google-yellow);
    border-color: var(--color-google-yellow);
    color: var(--color-neutro-oscuro);
    border-radius: 8px;
    font-weight: 600;
}

/* Tabla mejorada */
.table {
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(31, 78, 121, 0.1);
}

.table-primary th {
    background-color: var(--color-principal) !important;
    color: white !important;
    border: none;
    font-weight: 600;
    padding: 15px 12px;
}

.table-hover tbody tr:hover {
    background-color: var(--color-azul-claro);
    color: var(--color-principal);
}

/* Títulos mejorados */
h4 {
    color: var(--color-principal) !important;
    font-weight: 700;
    text-shadow: 1px 1px 2px rgba(31, 78, 121, 0.1);
}

/* Estadísticas cards mejoradas */
.stat-value {
    color: var(--color-principal);
    font-weight: 700;
    font-size: 1.8rem;
}

.stat-label {
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-size: 0.85rem;
}
.badge-puestos {
    background-color: var(--color-complementario) !important;
    color: var(--color-complementario) !important;
    font-weight: 600;
    padding: 8px 12px;
    border-radius: 8px;
    font-size: 0.9rem;
}
/* Efectos de animación */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.card {
    animation: fadeInUp 0.6s ease-out;
}

/* Responsive mejoras */
@media (max-width: 768px) {
    .cursor-pointer:hover {
        transform: none;
    }
    
    .card:hover {
        transform: none;
    }
}

/* Sombras personalizadas */
.shadow {
    box-shadow: 0 4px 15px rgba(31, 78, 121, 0.12) !important;
}

.shadow-sm {
    box-shadow: 0 2px 8px rgba(31, 78, 121, 0.08) !important;
}

/* Bordes redondeados consistentes */
.rounded {
    border-radius: 12px !important;
}

/* Texto muted mejorado */
.text-muted {
    color: #6c757d !important;
    font-size: 0.9rem;
}

/* Badge mejorado con animación */
.badge {
    border-radius: 6px;
    padding: 6px 12px;
    font-weight: 600;
    font-size: 0.85rem;
    transition: all 0.3s ease;
}

.badge:hover {
    transform: scale(1.05);
}
</style>

<div class="dashboard-container">
    <h1 class="dashboard-title">📊 Dashboard Administrativo</h1>

    <div class="cards-row">
        <!-- Total Usuarios -->
        <div class="card card-stats border-left-primary shadow p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-label text-primary">Total Usuarios</div>
                    <div class="stat-value">{{ $totalUsuarios }}</div>
                </div>
                <i class="fas fa-users fa-2x stat-icon text-primary"></i>
            </div>
        </div>

        <!-- Conductores -->
        <div class="card card-stats border-left-success shadow p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-label text-success">Conductores</div>
                    <div class="stat-value">{{ $conductores }}</div>
                    <div class="stat-subtext">{{ $conductoresVerificados }} verificados</div>
                </div>
                <i class="fas fa-car fa-2x stat-icon text-success"></i>
            </div>
        </div>

        <!-- Pasajeros -->
        <div class="card card-stats border-left-info shadow p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-label text-info">Pasajeros</div>
                    <div class="stat-value">{{ $pasajeros }}</div>
                    <div class="stat-subtext">{{ $pasajerosVerificados }} verificados</div>
                </div>
                <i class="fas fa-user-friends fa-2x stat-icon text-info"></i>
            </div>
        </div>

        <!-- Sin Verificar -->
        <div class="card card-stats border-left-warning shadow p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-label text-warning">Sin Verificar</div>
                    <div class="stat-value">{{ $totalSinVerificar }}</div>
                    <div class="stat-subtext text-danger">¡Requiere atención!</div>
                </div>
                <i class="fas fa-exclamation-triangle fa-2x stat-icon text-warning"></i>
            </div>
        </div>
    </div>

    <!-- Botones centrados -->
    <div class="center-button">
        <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
            <a href="{{ url('/admin/users') }}" class="btn-view-users">
                👥 Ver Usuarios
            </a>
            <a href="{{ route('admin.gestor-pagos') }}" class="btn-view-users" style="background: linear-gradient(135deg, #1cc88a 0%, #17a673 100%);">
                💳 Gestor de Pagos
            </a>
        </div>
    </div>
</div>

<!-- Tarjetas de viajes ACTUALIZADAS -->
<h2 class="text-center mt-5 mb-4" style="color: #00304b;">🚌 Estadísticas de Viajes</h2>
<div class="cards-row">
    <div class="card card-stats border-left-primary shadow p-3">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <div class="stat-label text-primary">Total Viajes</div>
                <div class="stat-value">{{ $viajesTotales }}</div>
            </div>
            <i class="fas fa-route fa-2x stat-icon text-primary"></i>
        </div>
    </div>

    <div class="card card-stats border-left-success shadow p-3">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <div class="stat-label text-success">Viajes Activos</div>
                <div class="stat-value">{{ $viajesActivos }}</div>
                <div class="stat-subtext">En curso o por realizar</div>
            </div>
            <i class="fas fa-check-circle fa-2x stat-icon text-success"></i>
        </div>
    </div>

    {{-- ✨ NUEVA TARJETA: Viajes Finalizados Automáticamente --}}
    <div class="card card-stats border-left-warning shadow p-3">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <div class="stat-label text-warning">Finalizados sin realizar viaje </div>
                <div class="stat-value">{{ $viajesFinalizadosAutomaticamente }}</div>
                <div class="stat-subtext">Pasaron +24h de la fecha</div>
            </div>
            <i class="fas fa-clock fa-2x stat-icon text-warning"></i>
        </div>
    </div>

    <div class="card card-stats border-left-danger shadow p-3">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <div class="stat-label text-danger">Viajes Inactivos</div>
                <div class="stat-value">{{ $viajesInactivos }}</div>
                <div class="stat-subtext">Cancelados por el conductor</div>
            </div>
            <i class="fas fa-times-circle fa-2x stat-icon text-danger"></i>
        </div>
    </div>
</div>

{{-- ✨ NUEVA SECCIÓN: Tabla de viajes finalizados automáticamente (opcional) --}}
@if($viajesFinalizadosAutomaticamente > 0)

    
    {{-- Mostrar algunos ejemplos --}}
    @if(isset($viajesFinalizadosDetalles) && $viajesFinalizadosDetalles->count() > 0)
  
    
    @if($viajesFinalizadosDetalles->count() > 5)
    <small class="text-muted">
        Y {{ $viajesFinalizadosDetalles->count() - 5 }} viajes más...
    </small>
    @endif
    @endif
</div>
@endif

{{-- Continúa con el resto de la vista... --}}
<!-- Tabla de Viajes -->
<div class="card shadow">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">Lista de Viajes Recientes</h6>
        <a href="{{ route('admin.viajes.todos') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-list"></i> Ver Todos los Viajes
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-primary text-center">
                    <tr>
                        <th>Conductor</th>
                        <th>Ruta</th>
                        <th>Fecha/Hora</th>
                        <th>Puestos</th>
                        <th>Valor</th>
                        <th>Estado</th>
                        <th>Reservas</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($viajes as $viaje)
                        @php
                            // ✨ NUEVO: Verificar si el viaje debe mostrarse como finalizado
                            $fechaLimite = \Carbon\Carbon::now()->subHours(24);
                            
                            try {
                                // Obtener solo la fecha (sin hora) de fecha_salida
                                $fechaSoloFecha = \Carbon\Carbon::parse($viaje->fecha_salida)->format('Y-m-d');
                                
                                $fechaViaje = $viaje->hora_salida 
                                    ? \Carbon\Carbon::parse($fechaSoloFecha . ' ' . $viaje->hora_salida)
                                    : \Carbon\Carbon::parse($fechaSoloFecha)->endOfDay();
                                    
                                $estaFinalizado = $fechaViaje->addHours(24)->isPast() && 
                                                 in_array($viaje->estado, ['pendiente', 'confirmado', 'en_proceso', 'completado']);
                            } catch (\Exception $e) {
                                $estaFinalizado = false;
                            }
                        @endphp
                        
                        <tr class="align-middle text-center cursor-pointer {{ $estaFinalizado ? 'table-warning' : '' }}" 
                            onclick="window.location='{{ route('admin.viajes.detalle', $viaje->id) }}'"
                            @if($estaFinalizado) title="Viaje finalizado automáticamente - Pasaron más de 24h" @endif>
                            <td>
                                <strong>{{ $viaje->conductor->name ?? 'N/A' }}</strong><br>
                                <small class="text-muted">{{ $viaje->conductor->email ?? 'N/A' }}</small>
                            </td>
                            <td>
                                <div class="text-left">
                                    <strong>🅰️ {{ Str::limit($viaje->origen_direccion ?? 'N/A', 25) }}</strong><br>
                                    <small class="text-muted">⬇️</small><br>
                                    <strong>🅱️ {{ Str::limit($viaje->destino_direccion ?? 'N/A', 25) }}</strong>
                                </div>
                            </td>
                            <td>
                                @if($viaje->fecha_salida)
                                    <strong>{{ \Carbon\Carbon::parse($viaje->fecha_salida)->format('d/m/Y') }}</strong><br>
                                @else
                                    <strong>N/A</strong><br>
                                @endif
                                
                                @if($viaje->hora_salida)
                                    <small class="text-muted">{{ \Carbon\Carbon::parse($viaje->hora_salida)->format('H:i') }}</small>
                                @else
                                    <small class="text-muted">N/A</small>
                                @endif
                                
                                {{-- ✨ NUEVO: Indicador de finalización automática --}}
                                @if($estaFinalizado)
                                    <br><small class="text-danger">
                                        <i class="fas fa-clock"></i> Finalizado auto
                                    </small>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-puestos">{{ $viaje->puestos_disponibles ?? 0 }}/{{ $viaje->puestos_totales ?? 0 }}</span>
                            </td>
                            <td>
                                <strong>${{ number_format($viaje->valor_cobrado ?? 0, 0, ',', '.') }}</strong><br>
                                <small class="text-muted">total del viaje</small>
                            </td>
                            <td>
                                @if($estaFinalizado)
                                    {{-- ✨ NUEVO: Mostrar estado finalizado --}}
                                    <span class="badge badge-success">✅ Finalizado</span>
                                    <br><small class="text-muted">({{ ucfirst($viaje->estado) }})</small>
                                @else
                                    {{-- Estado original --}}
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
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-outline-primary">{{ $viaje->reservas->count() }} reserva(s)</span>
                            </td>
                            <td onclick="event.stopPropagation();">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.viajes.detalle', $viaje->id) }}" class="btn btn-sm btn-info" title="Ver Detalle">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted">No hay viajes registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- 🔥 PAGINACIÓN CORREGIDA CON ESTILOS FORZADOS -->
        @if($viajes->hasPages())
        <div class="d-flex justify-content-center mt-4">
            <nav aria-label="Page navigation">
                <div style="display: flex; align-items: center; gap: 5px;">
                    @if ($viajes->onFirstPage())
                        <span style="display: inline-block; width: 32px; height: 32px; line-height: 30px; text-align: center; border: 1px solid #ddd; border-radius: 4px; color: #ccc; font-size: 12px; background: #f8f9fa;">‹</span>
                    @else
                        <a href="{{ $viajes->previousPageUrl() }}" style="display: inline-block; width: 32px; height: 32px; line-height: 30px; text-align: center; border: 1px solid #00304b; border-radius: 4px; color: #00304b; text-decoration: none; font-size: 12px; background: #fff; transition: all 0.2s;" onmouseover="this.style.background='#00304b'; this.style.color='#fff';" onmouseout="this.style.background='#fff'; this.style.color='#00304b';">‹</a>
                    @endif

                    @foreach ($viajes->getUrlRange(1, $viajes->lastPage()) as $page => $url)
                        @if ($page == $viajes->currentPage())
                            <span style="display: inline-block; width: 32px; height: 32px; line-height: 30px; text-align: center; border: 1px solid #00304b; border-radius: 4px; color: #fff; font-size: 12px; background: #00304b; font-weight: 600;">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" style="display: inline-block; width: 32px; height: 32px; line-height: 30px; text-align: center; border: 1px solid #dee2e6; border-radius: 4px; color: #00304b; text-decoration: none; font-size: 12px; background: #fff; transition: all 0.2s;" onmouseover="this.style.background='#f8f9fa'; this.style.borderColor='#00304b';" onmouseout="this.style.background='#fff'; this.style.borderColor='#dee2e6';">{{ $page }}</a>
                        @endif
                    @endforeach

                    @if ($viajes->hasMorePages())
                        <a href="{{ $viajes->nextPageUrl() }}" style="display: inline-block; width: 32px; height: 32px; line-height: 30px; text-align: center; border: 1px solid #00304b; border-radius: 4px; color: #00304b; text-decoration: none; font-size: 12px; background: #fff; transition: all 0.2s;" onmouseover="this.style.background='#00304b'; this.style.color='#fff';" onmouseout="this.style.background='#fff'; this.style.color='#00304b';">›</a>
                    @else
                        <span style="display: inline-block; width: 32px; height: 32px; line-height: 30px; text-align: center; border: 1px solid #ddd; border-radius: 4px; color: #ccc; font-size: 12px; background: #f8f9fa;">›</span>
                    @endif
                </div>
            </nav>
        </div>
        @endif
    </div>
</div>


<div class="mt-5">
    <h4 class="text-center mb-4" style="color: #00304b;">🧾 Últimas Reservas Realizadas</h4>
    
    <div class="table-responsive">
        <table class="table table-hover shadow-sm rounded">
            <thead class="table-primary text-center">
                <tr>
                    <th>Pasajero</th>
                    <th>Conductor</th>
                    <th>Origen</th>
                    <th>Destino</th>
                    <th>Fecha Reserva</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($reservasRecientes as $reserva)
                    <tr class="align-middle text-center">
                        <td>{{ $reserva->pasajero->name ?? 'Desconocido' }}</td>
                        <td>{{ $reserva->viaje->conductor->name ?? 'Desconocido' }}</td>
                        <td>{{ Str::limit($reserva->viaje->origen_direccion ?? 'N/A', 25) }}</td>
                        <td>{{ Str::limit($reserva->viaje->destino_direccion ?? 'N/A', 25) }}</td>
                        <td>{{ optional($reserva->fecha_reserva)->format('d M Y, H:i') ?? 'N/D' }}</td>
                        <td>
                            @switch($reserva->estado)
                                @case('confirmada')
                                    <span class="badge bg-success">Confirmada</span>
                                    @break
                                @case('pendiente')
                                @case('pendiente_pago')
                                    <span class="badge bg-warning text-dark">Pendiente</span>
                                    @break
                                @case('cancelada')
                                @case('fallida')
                                    <span class="badge bg-danger">{{ ucfirst($reserva->estado) }}</span>
                                    @break
                                @default
                                    <span class="badge bg-secondary">{{ ucfirst($reserva->estado) }}</span>
                            @endswitch
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">No hay reservas recientes.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection