@extends('layouts.app_dashboard')

@section('content')
<style>
    :root {
        --vcv-primary: #1F4E79;
        --vcv-light: #DDF2FE;
        --vcv-dark: #3A3A3A;
        --vcv-accent: #4CAF50;
        --vcv-bg: #FCFCFD;
    }

    .dashboard-wrapper {
        background: linear-gradient(135deg, #DDF2FE 0%, #FCFCFD 50%, rgb(13 111 201 / 68%) 100%);
        min-height: 100vh;
        padding: 2rem 0;
        position: relative;
    }

    .dashboard-wrapper::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: 
            radial-gradient(circle at 20% 80%, rgba(76, 175, 80, 0.05) 0%, transparent 50%),
            radial-gradient(circle at 80% 20%, rgba(31, 78, 121, 0.05) 0%, transparent 50%),
            radial-gradient(circle at 40% 40%, rgba(221, 242, 254, 0.3) 0%, transparent 50%);
        pointer-events: none;
    }

    .container {
        position: relative;
        z-index: 1;
    }

    .welcome-section {
        background: linear-gradient(135deg, var(--vcv-primary) 0%, rgba(31, 78, 121, 0.9) 50%, rgba(58, 58, 58, 0.8) 100%);
        color: white;
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 6px 20px rgba(31, 78, 121, 0.15);
        border: 1px solid rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        position: relative;
        overflow: hidden;
    }

    .welcome-section::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 200px;
        height: 200px;
        background: radial-gradient(circle, rgba(76, 175, 80, 0.2) 0%, transparent 70%);
        border-radius: 50%;
        transform: translate(50%, -50%);
    }

    .welcome-section h2 {
        margin: 0;
        font-weight: 600;
        font-size: 2rem;
        position: relative;
        z-index: 2;
    }

    .welcome-section p {
        margin: 0.5rem 0 0 0;
        opacity: 0.95;
        font-size: 1rem;
        position: relative;
        z-index: 2;
    }

    .stats-card {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border-radius: 12px;
        padding: 1.5rem;
        border: 1px solid rgba(31, 78, 121, 0.12);
        box-shadow: 0 4px 12px rgba(31, 78, 121, 0.08);
        transition: all 0.3s ease;
        height: 100%;
        position: relative;
        overflow: hidden;
    }

    .stats-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        border-radius: 12px 12px 0 0;
    }

    .stats-card.primary::before {
        background: var(--vcv-primary);
    }

    .stats-card.success::before {
        background: var(--vcv-accent);
    }

    .stats-card.info::before {
        background: rgba(31, 78, 121, 0.6);
    }

    .stats-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.08);
        border-color: rgba(31, 78, 121, 0.15);
    }

    .stats-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        margin-bottom: 1rem;
    }

    .stats-icon.primary {
        background: rgba(31, 78, 121, 0.1);
        color: var(--vcv-primary);
    }

    .stats-icon.success {
        background: rgba(76, 175, 80, 0.1);
        color: var(--vcv-accent);
    }

    .stats-icon.info {
        background: rgba(221, 242, 254, 0.8);
        color: var(--vcv-primary);
    }

    .stats-number {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--vcv-primary);
        margin: 0;
    }

    .stats-label {
        color: var(--vcv-dark);
        font-weight: 600;
        margin: 0;
    }

    .section-header {
        background: white;
        padding: 1.2rem 1.8rem;
        border-radius: 12px;
        margin-bottom: 1.2rem;
        border-left: 3px solid var(--vcv-primary);
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.03);
        border: 1px solid rgba(31, 78, 121, 0.08);
    }

    .section-header h4 {
        margin: 0;
        color: var(--vcv-primary);
        font-weight: 600;
        font-size: 1.1rem;
    }

    .table-container {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        margin-bottom: 2rem;
        border: 1px solid rgba(31, 78, 121, 0.08);
    }

    .table {
        margin: 0;
        border: none;
    }

    .table thead th {
        background: linear-gradient(135deg, rgba(31, 78, 121, 0.9), rgba(31, 78, 121, 0.8));
        color: white;
        border: none;
        padding: 1rem;
        font-weight: 600;
        font-size: 0.9rem;
    }

    .table tbody tr {
        border-bottom: 1px solid rgba(31, 78, 121, 0.08);
        transition: all 0.2s ease;
        background: rgba(255, 255, 255, 0.7);
    }

    .table tbody tr:hover {
        background: rgba(221, 242, 254, 0.4);
    }

    .table tbody td {
        padding: 1rem;
        border: none;
        vertical-align: middle;
        color: var(--vcv-dark);
    }

    .btn-custom {
        border: none;
        border-radius: 20px;
        padding: 0.4rem 1rem;
        font-weight: 500;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-block;
        margin: 0.2rem;
        font-size: 0.85rem;
    }

    .btn-custom.primary {
        background: var(--vcv-primary);
        color: white;
    }

    .btn-custom.primary:hover {
        background: rgba(31, 78, 121, 0.9);
        transform: translateY(-1px);
        box-shadow: 0 3px 8px rgba(31, 78, 121, 0.2);
        color: white;
    }

    .btn-custom.outline {
        background: transparent;
        border: 1px solid rgba(31, 78, 121, 0.3);
        color: var(--vcv-primary);
    }

    .btn-custom.outline:hover {
        background: rgba(31, 78, 121, 0.05);
        border-color: var(--vcv-primary);
        color: var(--vcv-primary);
    }

    .btn-custom.accent {
        background: var(--vcv-accent);
        color: white;
    }

    .btn-custom.accent:hover {
        background: rgba(76, 175, 80, 0.9);
        transform: translateY(-1px);
        color: white;
    }

    .ratings-section {
        background: white;
        border-radius: 15px;
        padding: 2rem;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        margin-bottom: 2rem;
    }

    .rating-item {
        background: var(--vcv-bg);
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        border-left: 4px solid var(--vcv-accent);
        transition: all 0.3s ease;
    }

    .rating-item:hover {
        transform: translateX(5px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .rating-stars {
        color: #ffc107;
        font-size: 1.2rem;
        margin-bottom: 0.5rem;
    }

    .rating-comment {
        color: var(--vcv-dark);
        font-style: italic;
        margin-bottom: 0.5rem;
    }

    .rating-meta {
        color: #666;
        font-size: 0.9rem;
    }

    .empty-state {
        text-align: center;
        padding: 3rem;
        color: var(--vcv-dark);
    }

    .empty-state i {
        font-size: 4rem;
        color: var(--vcv-light);
        margin-bottom: 1rem;
    }

    .action-buttons {
        background: white;
        border-radius: 15px;
        padding: 2rem;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        text-align: center;
    }

    .rating-summary {
        background: linear-gradient(135deg, var(--vcv-accent), #66BB6A);
        color: white;
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        text-align: center;
    }

    .rating-average {
        font-size: 2.5rem;
        font-weight: 700;
        margin: 0;
    }

    .rating-count {
        opacity: 0.9;
        margin: 0;
    }

    @media (max-width: 768px) {
        .welcome-section {
            padding: 1.5rem;
        }
        
        .welcome-section h2 {
            font-size: 1.8rem;
        }
        
        .table-responsive {
            border-radius: 15px;
        }
    }
</style>

<div class="dashboard-wrapper">
    <div class="container">
        <!-- Welcome Section -->
        <div class="welcome-section">
            <h2>👋 Bienvenido, {{ auth()->user()->name ?? 'Invitado' }}</h2>
            <p>Gestiona tus viajes y revisa tu actividad reciente</p>
        </div>

          <div class="action-buttons mb-4">
            <h5 class="mb-4 text-primary">¿Qué quieres hacer?</h5>
            <a href="{{ route('pasajero.viajes.disponibles') }}" class="btn-custom primary me-3">
                <i class="fas fa-search me-2"></i>Buscar viajes disponibles
            </a>
            <a href="#" class="btn-custom outline">
                <i class="fas fa-history me-2"></i>Ver historial completo
            </a>
        </div>

        <!-- Stats Cards -->
        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="stats-card primary">
                    <div class="stats-icon primary">
                        <i class="fas fa-route"></i>
                    </div>
                    <p class="stats-number">{{ $totalViajes ?? 0 }}</p>
                    <p class="stats-label">Total de Viajes</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-card success">
                    <div class="stats-icon success">
                        <i class="fas fa-clock"></i>
                    </div>
                    <p class="stats-number">{{ $viajesProximos ?? 0 }}</p>
                    <p class="stats-label">Próximos Viajes</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-card info">
                    <div class="stats-icon info">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <p class="stats-number">{{ $viajesRealizados ?? 0 }}</p>
                    <p class="stats-label">Viajes Realizados</p>
                </div>
            </div>
        </div>

      <div class="section-header">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4><i class="fas fa-list-alt me-2"></i>Tus reservas</h4>
        
        <!-- Filtros completos con nuevos estados -->
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('pasajero.dashboard', ['estado' => 'todos']) }}" 
               class="btn btn-sm {{ ($estadoFiltro ?? 'todos') === 'todos' ? 'btn-dark' : 'btn-outline-dark' }}">
                Todos
                @if(isset($estadisticas))
                    <span class="badge bg-light text-dark ms-1">{{ $reservas->total() ?? $reservas->count() }}</span>
                @endif
            </a>
            <a href="{{ route('pasajero.dashboard', ['estado' => 'activos']) }}" 
               class="btn btn-sm {{ ($estadoFiltro ?? '') === 'activos' ? 'btn-primary' : 'btn-outline-primary' }}">
                Activos
                @if(isset($estadisticas))
                    <span class="badge bg-light text-dark ms-1">{{ $estadisticas['activos'] }}</span>
                @endif
            </a>
            <a href="{{ route('pasajero.dashboard', ['estado' => 'pendiente_confirmacion']) }}" 
               class="btn btn-sm {{ ($estadoFiltro ?? '') === 'pendiente_confirmacion' ? 'btn-warning' : 'btn-outline-warning' }}">
                Esperando
                @if(isset($estadisticas))
                    <span class="badge bg-light text-dark ms-1">{{ $estadisticas['pendiente_confirmacion'] }}</span>
                @endif
            </a>
            <a href="{{ route('pasajero.dashboard', ['estado' => 'pendiente_pago']) }}" 
               class="btn btn-sm {{ ($estadoFiltro ?? '') === 'pendiente_pago' ? 'btn-info' : 'btn-outline-info' }}">
                Por Pagar
                @if(isset($estadisticas))
                    <span class="badge bg-light text-dark ms-1">{{ $estadisticas['pendiente_pago'] }}</span>
                @endif
            </a>
            <a href="{{ route('pasajero.dashboard', ['estado' => 'confirmada']) }}" 
               class="btn btn-sm {{ ($estadoFiltro ?? '') === 'confirmada' ? 'btn-success' : 'btn-outline-success' }}">
                Confirmadas
                @if(isset($estadisticas))
                    <span class="badge bg-light text-dark ms-1">{{ $estadisticas['confirmada'] }}</span>
                @endif
            </a>
            <a href="{{ route('pasajero.dashboard', ['estado' => 'cancelados']) }}" 
               class="btn btn-sm {{ ($estadoFiltro ?? '') === 'cancelados' ? 'btn-danger' : 'btn-outline-danger' }}">
                Cancelados
                @if(isset($estadisticas))
                    <span class="badge bg-light text-dark ms-1">{{ $estadisticas['cancelados'] }}</span>
                @endif
            </a>
        </div>
    </div>
</div>

@if(isset($reservas) && $reservas->count() > 0)
    <div class="table-container">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Ruta</th>
                        <th class="d-none d-md-table-cell">Fecha</th>
                        <th>Estado</th>
                        <th class="d-none d-lg-table-cell">Puestos</th>
                        <th class="d-none d-md-table-cell">Total</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reservas as $reserva)
                        @if($reserva->viaje)
                        <tr>
                            <!-- RUTA -->
                            <td>
                                <strong>{{ Str::limit($reserva->viaje->origen_direccion ?? 'Origen', 15) }}</strong>
                                <br>
                                <small class="text-muted">→ {{ Str::limit($reserva->viaje->destino_direccion ?? 'Destino', 15) }}</small>
                            </td>
                            
                            <!-- FECHA -->
                            <td class="d-none d-md-table-cell">
                                @if($reserva->viaje->fecha_salida)
                                    {{ \Carbon\Carbon::parse($reserva->viaje->fecha_salida)->format('d/m/Y') }}
                                    <br>
                                    <small>{{ \Carbon\Carbon::parse($reserva->viaje->fecha_salida)->format('H:i') }}</small>
                                @else
                                    <span class="text-muted">No definida</span>
                                @endif
                            </td>
                            
                            <!-- ESTADO con nuevos estados -->
                            <td>
                                @switch($reserva->estado)
                                    @case('pendiente')
                                        <span class="badge bg-warning">⏰ Pendiente</span>
                                        @break
                                    @case('pendiente_pago')
                                        <span class="badge bg-info">💳 Por Pagar</span>
                                        @break
                                    @case('pendiente_confirmacion')
                                        <span class="badge bg-warning">🕐 Esperando Confirmación</span>
                                        @break
                                    @case('confirmada')
                                        <span class="badge bg-success">✅ Confirmado</span>
                                        @break
                                    @case('cancelada')
                                        <span class="badge bg-danger">❌ Cancelado</span>
                                        @break
                                    @case('cancelada_por_conductor')
                                        <span class="badge bg-danger">🚫 Cancelado por Conductor</span>
                                        @break
                                    @case('fallida')
                                        <span class="badge bg-dark">⚠️ Fallido</span>
                                        @break
                                    @case('completada')
                                        <span class="badge bg-success">🎉 Completado</span>
                                        @break
                                    @default
                                        <span class="badge bg-secondary">{{ ucfirst($reserva->estado) }}</span>
                                @endswitch
                            </td>
                            
                            <!-- PUESTOS -->
                            <td class="d-none d-lg-table-cell">
                                <span class="badge bg-light text-dark">{{ $reserva->cantidad_puestos ?? 1 }} 👤</span>
                            </td>
                            
                            <!-- TOTAL -->
                            <td class="d-none d-md-table-cell">
                                <strong class="text-success">${{ number_format($reserva->total ?? 0, 0) }}</strong>
                            </td>
                            
                            <!-- ACCIONES -->
                            <td>
                                <div class="d-flex gap-1 flex-wrap">
                                    <!-- Detalles -->
                                    <a href="{{ route('pasajero.reserva.detalles', $reserva->id) }}" 
                                       class="btn btn-outline-primary btn-sm" title="Ver detalles">
                                        <i class="fas fa-info-circle"></i>
                                    </a>
                                    
                                    <!-- Pagar (si está pendiente de pago) -->
                                    @if($reserva->estado === 'pendiente_pago' && $reserva->mp_init_point)
                                        <a href="{{ $reserva->mp_init_point }}" 
                                           class="btn btn-info btn-sm" 
                                           title="Proceder al pago">
                                            <i class="fas fa-credit-card"></i>
                                        </a>
                                    @endif
                                    
                                    <!-- Chat -->
                                    @if($reserva->estado === 'confirmada')
                                        <a href="{{ route('chat.ver', $reserva->viaje_id) }}" 
                                           class="btn btn-success btn-sm" 
                                           title="Abrir Chat">
                                            <i class="fas fa-comments"></i>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Paginación -->
        @if(method_exists($reservas, 'links'))
            <div class="d-flex justify-content-center mt-4">
                {{ $reservas->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
@else
    <div class="alert alert-info text-center">
        <i class="fas fa-inbox fa-2x mb-3"></i>
        <h5>No hay reservas</h5>
        <p>
            @switch($estadoFiltro ?? 'todos')
                @case('activos')
                    No tienes reservas activas. ¡Busca tu próximo viaje!
                    @break
                @case('pendiente')
                    No tienes reservas pendientes de aprobación.
                    @break
                @case('pendiente_pago')
                    No tienes reservas pendientes de pago.
                    @break
                @case('pendiente_confirmacion')
                    No tienes reservas esperando confirmación del conductor.
                    @break
                @case('confirmada')
                    No tienes reservas confirmadas.
                    @break
                @case('cancelados')
                    No tienes reservas canceladas.
                    @break
                @case('cancelada')
                    No tienes reservas canceladas por ti.
                    @break
                @case('cancelada_por_conductor')
                    No tienes reservas canceladas por conductores.
                    @break
                @case('fallida')
                    No tienes reservas fallidas.
                    @break
                @case('completada')
                    No tienes viajes completados.
                    @break
                @case('todos')
                    No tienes ninguna reserva aún.
                    @break
                @default
                    No hay reservas en este estado.
            @endswitch
        </p>
        @if(in_array($estadoFiltro ?? 'todos', ['activos', 'todos']))
            <a href="{{ route('pasajero.viajes.disponibles') }}" class="btn btn-primary">
                <i class="fas fa-search me-1"></i>Buscar viajes
            </a>
        @endif
    </div>
@endif
        <!-- Calificaciones Section -->
    {-- Código para la vista con verificaciones de seguridad --}}
<div class="section-header">
    <h4><i class="fas fa-star me-2"></i>Tus calificaciones como pasajero</h4>
</div>

<div class="ratings-section">
    @php
        // 🛡️ VERIFICACIONES DE SEGURIDAD
        $calificacionesUsuarios = $calificacionesUsuarios ?? collect();
        $calificacionesDetalle = $calificacionesDetalle ?? collect();
        $misCalificaciones = $misCalificaciones ?? collect();
        
        // Obtener calificaciones del usuario como pasajero
        $misCalificacionesPasajero = isset($misCalificaciones['conductor_a_pasajero']) 
            ? $misCalificaciones['conductor_a_pasajero'] 
            : null;
        
        // Filtrar calificaciones detalle del usuario actual como pasajero
        $calificacionesDetallePasajero = $calificacionesDetalle->filter(function($cal) {
            return auth()->check() && 
                   $cal->usuario_calificado_id == auth()->id() && 
                   $cal->tipo == 'conductor_a_pasajero';
        });
        
        // Calcular estadísticas
        $promedioCalificacion = $misCalificacionesPasajero 
            ? $misCalificacionesPasajero->promedio_calificacion 
            : 0;
            
        $totalCalificaciones = $misCalificacionesPasajero 
            ? $misCalificacionesPasajero->total_calificaciones 
            : 0;
    @endphp

    @if($totalCalificaciones > 0)
        {{-- Resumen de calificaciones --}}
        <div class="rating-summary">
            <p class="rating-average">{{ number_format($promedioCalificacion, 1) }}/5</p>
            <p class="rating-count">
                <i class="fas fa-star me-1"></i>
                Basado en {{ $totalCalificaciones }} calificación{{ $totalCalificaciones > 1 ? 'es' : '' }}
            </p>
        </div>

        {{-- Lista de calificaciones detalladas --}}
        @forelse($calificacionesDetallePasajero as $calificacion)
            <div class="rating-item">
                {{-- Estrellas --}}
                <div class="rating-stars">
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= $calificacion->calificacion)
                            <i class="fas fa-star"></i>
                        @else
                            <i class="far fa-star"></i>
                        @endif
                    @endfor
                    <span class="rating-number">({{ $calificacion->calificacion }}/5)</span>
                </div>

                {{-- Comentario --}}
                @if(isset($calificacion->comentario) && trim($calificacion->comentario) !== '')
                    <p class="rating-comment">"{{ $calificacion->comentario }}"</p>
                @else
                    <p class="rating-comment text-muted">Sin comentario</p>
                @endif

                {{-- Información del viaje --}}
                <div class="rating-meta">
                    <div class="conductor-info">
                        <i class="fas fa-user me-1"></i>
                        <strong>{{ $calificacion->nombre_conductor ?? 'Conductor' }}</strong>
                    </div>
                    @if(isset($calificacion->origen_direccion) && isset($calificacion->destino_direccion))
                        <div class="trip-info">
                            <i class="fas fa-route me-1"></i>
                            {{ Str::limit($calificacion->origen_direccion, 20) }} → 
                            {{ Str::limit($calificacion->destino_direccion, 20) }}
                        </div>
                    @endif
                    <div class="date-info">
                        <i class="fas fa-calendar me-1"></i>
                        {{ \Carbon\Carbon::parse($calificacion->fecha_calificacion)->format('d/m/Y') }}
                    </div>
                </div>

                {{-- Estado del viaje --}}
                @if(isset($calificacion->estado_reserva))
                    <div class="trip-status">
                        <span class="badge bg-{{ $calificacion->estado_reserva === 'finalizado' ? 'success' : 'secondary' }}">
                            {{ ucfirst($calificacion->estado_reserva) }}
                        </span>
                        @if(isset($calificacion->cantidad_puestos) && $calificacion->cantidad_puestos > 1)
                            <span class="badge bg-info ms-2">{{ $calificacion->cantidad_puestos }} puestos</span>
                        @endif
                        @if(isset($calificacion->total_pagado) && $calificacion->total_pagado)
                            <span class="badge bg-warning text-dark ms-2">${{ number_format($calificacion->total_pagado, 0) }}</span>
                        @endif
                    </div>
                @endif
            </div>
        @empty
            {{-- Si hay promedio pero no detalles --}}
            <div class="rating-item">
                <div class="rating-stars">
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= round($promedioCalificacion))
                            <i class="fas fa-star"></i>
                        @else
                            <i class="far fa-star"></i>
                        @endif
                    @endfor
                </div>
                <p class="rating-comment text-muted">
                    Tienes {{ $totalCalificaciones }} calificación{{ $totalCalificaciones > 1 ? 'es' : '' }}, 
                    pero los detalles no están disponibles.
                </p>
            </div>
        @endforelse

    @else
        {{-- Estado vacío --}}
        <div class="empty-state">
            <i class="fas fa-star-half-alt"></i>
            <h5>Aún no tienes calificaciones</h5>
            <p>Completa algunos viajes para ver las calificaciones que te dejan los conductores</p>
            
            {{-- Calificación por defecto --}}
            <div class="default-rating mt-3">
                <div class="rating-stars">
                    @for($i = 1; $i <= 3; $i++)
                        <i class="fas fa-star text-warning"></i>
                    @endfor
                    @for($i = 4; $i <= 5; $i++)
                        <i class="far fa-star text-muted"></i>
                    @endfor
                </div>
                <p class="text-muted small">
                    Calificación inicial: 3.0/5 (se actualizará con tus primeros viajes)
                </p>
            </div>
        </div>
    @endif
</div>
 <div style="background: #f8f9fa; padding: 1rem; margin: 1rem 0; border-radius: 8px; font-family: monospace; font-size: 0.8rem;">
        <strong>🔍 DEBUG - Variables disponibles:</strong><br>
        - calificacionesUsuarios: {{ isset($calificacionesUsuarios) ? 'SÍ (' . $calificacionesUsuarios->count() . ')' : 'NO' }}<br>
        - calificacionesDetalle: {{ isset($calificacionesDetalle) ? 'SÍ (' . $calificacionesDetalle->count() . ')' : 'NO' }}<br>
        - misCalificaciones: {{ isset($misCalificaciones) ? 'SÍ' : 'NO' }}<br>
        - Usuario autenticado: {{ auth()->check() ? 'SÍ (ID: ' . auth()->id() . ')' : 'NO' }}
    </div>

        <!-- Action Buttons -->
      
    </div>
</div>
<style>
/* Estilos para btn-custom (del diseño original) */
.btn-custom {
    display: inline-flex;
    align-items: center;
    padding: 8px 16px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 500;
    font-size: 0.9em;
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.btn-custom.primary {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    color: white;
    border-color: #007bff;
}

.btn-custom.primary:hover {
    background: linear-gradient(135deg, #0056b3 0%, #004085 100%);
    color: white;
    text-decoration: none;
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0, 123, 255, 0.3);
}

.btn-custom.btn-sm {
    padding: 6px 12px;
    font-size: 0.8em;
}

@media (max-width: 768px) {
    .d-flex.gap-2 {
        gap: 5px !important;
        justify-content: center;
        flex-wrap: wrap;
    }
    .btn-sm {
        font-size: 0.7em;
        padding: 4px 8px;
        white-space: nowrap;
    }
    .btn-custom.btn-sm {
        padding: 4px 8px;
        font-size: 0.7em;
    }
    .d-flex.justify-content-between {
        flex-direction: column;
        gap: 15px;
        text-align: center;
    }
    /* En móvil, hacer los filtros más compactos */
    .d-flex.gap-2.flex-wrap {
        max-width: 100%;
        overflow-x: auto;
        flex-wrap: nowrap;
        scrollbar-width: none;
        -ms-overflow-style: none;
    }
    .d-flex.gap-2.flex-wrap::-webkit-scrollbar {
        display: none;
    }
}
</style>
@endsection