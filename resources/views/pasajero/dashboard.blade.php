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
        
        <!-- Filtros completos -->
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('pasajero.dashboard', ['estado' => 'activos']) }}" 
               class="btn btn-sm {{ ($estadoFiltro ?? 'activos') === 'activos' ? 'btn-primary' : 'btn-outline-primary' }}">
                Activos
            </a>
            <a href="{{ route('pasajero.dashboard', ['estado' => 'pendiente']) }}" 
               class="btn btn-sm {{ ($estadoFiltro ?? '') === 'pendiente' ? 'btn-warning' : 'btn-outline-warning' }}">
                Pendiente
            </a>
            <a href="{{ route('pasajero.dashboard', ['estado' => 'pendiente_pago']) }}" 
               class="btn btn-sm {{ ($estadoFiltro ?? '') === 'pendiente_pago' ? 'btn-info' : 'btn-outline-info' }}">
                Por Pagar
            </a>
            <a href="{{ route('pasajero.dashboard', ['estado' => 'confirmada']) }}" 
               class="btn btn-sm {{ ($estadoFiltro ?? '') === 'confirmada' ? 'btn-success' : 'btn-outline-success' }}">
                Confirmada
            </a>
            <a href="{{ route('pasajero.dashboard', ['estado' => 'cancelados']) }}" 
               class="btn btn-sm {{ ($estadoFiltro ?? '') === 'cancelados' ? 'btn-danger' : 'btn-outline-danger' }}">
                Cancelados
            </a>
            <a href="{{ route('pasajero.dashboard', ['estado' => 'todos']) }}" 
               class="btn btn-sm {{ ($estadoFiltro ?? '') === 'todos' ? 'btn-dark' : 'btn-outline-dark' }}">
                Todos
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
                            
                            <!-- ESTADO -->
                            <td>
                                @if($reserva->estado === 'pendiente')
                                    <span class="badge bg-warning">⏰ Pendiente</span>
                                @elseif($reserva->estado === 'pendiente_pago')
                                    <span class="badge bg-info">💳 Por Pagar</span>
                                @elseif($reserva->estado === 'confirmada')
                                    <span class="badge bg-success">✅ Confirmado</span>
                                @elseif($reserva->estado === 'cancelada')
                                    <span class="badge bg-danger">❌ Cancelado</span>
                                @elseif($reserva->estado === 'fallida')
                                    <span class="badge bg-dark">⚠️ Fallido</span>
                                @else
                                    <span class="badge bg-secondary">{{ $reserva->estado }}</span>
                                @endif
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
                                    
                                    <!-- Chat -->
                                    @if($reserva->estado === 'confirmada')
                                        <a href="{{ route('chat.ver', $reserva->viaje_id) }}" 
                                           class="btn-custom primary btn-sm" 
                                           title="Abrir Chat">
                                            <i class="fas fa-comments me-1"></i>Chat
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
    </div>
@else
    <div class="alert alert-info text-center">
        <i class="fas fa-inbox fa-2x mb-3"></i>
        <h5>No hay reservas</h5>
        <p>
            @switch($estadoFiltro ?? 'activos')
                @case('activos')
                    No tienes reservas activas. ¡Busca tu próximo viaje!
                    @break
                @case('pendiente')
                    No tienes reservas pendientes de aprobación.
                    @break
                @case('pendiente_pago')
                    No tienes reservas pendientes de pago.
                    @break
                @case('confirmada')
                    No tienes reservas confirmadas.
                    @break
                @case('cancelados')
                    No tienes reservas canceladas.
                    @break
                @case('cancelada')
                    No tienes reservas canceladas.
                    @break
                @case('fallida')
                    No tienes reservas fallidas.
                    @break
                @case('todos')
                    No tienes ninguna reserva.
                    @break
                @default
                    No hay reservas en este estado.
            @endswitch
        </p>
        @if(($estadoFiltro ?? 'activos') === 'activos')
            <a href="{{ route('pasajero.viajes.disponibles') }}" class="btn btn-primary">
                <i class="fas fa-search me-1"></i>Buscar viajes
            </a>
        @endif
    </div>
@endif
        <!-- Calificaciones Section -->
        <div class="section-header">
            <h4><i class="fas fa-star me-2"></i>Tus calificaciones como pasajero</h4>
        </div>

        <div class="ratings-section">
            @php
                // Aquí deberías obtener las calificaciones del usuario desde el controlador
                // Por ahora uso datos de ejemplo, reemplaza con tus datos reales
                $calificaciones = collect([
                    (object)[
                        'calificacion' => 5,
                        'comentario' => 'Excelente pasajero, muy puntual y respetuoso.',
                        'fecha' => '2025-05-15',
                        'conductor' => 'Carlos Rodríguez'
                    ],
                    (object)[
                        'calificacion' => 4,
                        'comentario' => 'Buen pasajero, recomendado.',
                        'fecha' => '2025-05-10',
                        'conductor' => 'María González'
                    ]
                ]);
                
                $promedioCalificacion = $calificaciones->avg('calificacion') ?? 0;
                $totalCalificaciones = 0;
            @endphp

            @if($totalCalificaciones > 0)
                <div class="rating-summary">
                    <p class="rating-average">{{ number_format($promedioCalificacion, 1) }}/5</p>
                    <p class="rating-count">
                        <i class="fas fa-star me-1"></i>
                        Basado en {{ $totalCalificaciones }} calificación{{ $totalCalificaciones > 1 ? 'es' : '' }}
                    </p>
                </div>

                @foreach($calificaciones as $calificacion)
                    <div class="rating-item">
                        <div class="rating-stars">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $calificacion->calificacion)
                                    <i class="fas fa-star"></i>
                                @else
                                    <i class="far fa-star"></i>
                                @endif
                            @endfor
                        </div>
                        <p class="rating-comment">"{{ $calificacion->comentario }}"</p>
                        <div class="rating-meta">
                            <i class="fas fa-user me-1"></i>{{ $calificacion->conductor }}
                            <span class="ms-3">
                                <i class="fas fa-calendar me-1"></i>{{ \Carbon\Carbon::parse($calificacion->fecha)->format('d/m/Y') }}
                            </span>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="empty-state">
                    <i class="fas fa-star-half-alt"></i>
                    <h5>Aún no tienes calificaciones</h5>
                    <p>Completa algunos viajes para ver las calificaciones que te dejan los conductores</p>
                </div>
            @endif
        </div>

        <!-- Action Buttons -->
        <div class="action-buttons">
            <h5 class="mb-3 text-primary">¿Qué quieres hacer?</h5>
            <a href="{{ route('pasajero.viajes.disponibles') }}" class="btn-custom primary me-3">
                <i class="fas fa-search me-2"></i>Buscar viajes disponibles
            </a>
            <a href="#" class="btn-custom outline">
                <i class="fas fa-history me-2"></i>Ver historial completo
            </a>
        </div>
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