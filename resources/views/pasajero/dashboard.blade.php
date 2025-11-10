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

    body {
        background: var(--vcv-bg);
    }

    /* Dashboard Wrapper - Fondo limpio */
    .dashboard-wrapper {
        background: linear-gradient(135deg, #f8fafc 0%, #ffffff 50%, #f1f5f9 100%);
        min-height: 100vh;
        padding: 2rem 0;
    }

    /* Welcome Section - Estilo hero mejorado */
    .welcome-section {
        background: linear-gradient(135deg, var(--vcv-primary) 0%, rgba(76, 175, 80, 0.85) 100%),
                    url('https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?w=1600') center/cover;
        color: white;
        border-radius: 16px;
        padding: 3rem 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 8px 24px rgba(31, 78, 121, 0.15);
        position: relative;
        overflow: hidden;
    }

    .welcome-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(31, 78, 121, 0.9) 0%, rgba(76, 175, 80, 0.8) 100%);
    }

    .welcome-content {
        position: relative;
        z-index: 2;
    }

    .welcome-section h2 {
        margin: 0;
        font-weight: 700;
        font-size: 2.5rem;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
    }

    .welcome-section p {
        margin: 0.5rem 0 0 0;
        opacity: 0.95;
        font-size: 1.1rem;
    }

    /* Action Buttons - Estilo landing */
    .action-buttons {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
        text-align: center;
        margin-bottom: 2rem;
        border: 1px solid rgba(31, 78, 121, 0.08);
    }

    .action-buttons h5 {
        color: var(--vcv-primary);
        font-weight: 600;
        margin-bottom: 1.5rem;
    }

    /* Stats Cards - Mejoradas */
    .stats-container {
        max-width: 900px;
        margin: 0 auto;
    }

    .stats-card {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        border: 1px solid rgba(31, 78, 121, 0.08);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
        transition: all 0.3s ease;
        height: 100%;
        position: relative;
        overflow: hidden;
        text-align: center;
    }

    .stats-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        border-radius: 16px 16px 0 0;
    }

    .stats-card.primary::before {
        background: var(--vcv-primary);
    }

    .stats-card.success::before {
        background: var(--vcv-accent);
    }

    .stats-card.info::before {
        background: #00a8e1;
    }

    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
    }

    .stats-icon {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        margin: 0 auto 1rem;
    }

    .stats-icon.primary {
        background: linear-gradient(135deg, rgba(31, 78, 121, 0.1) 0%, rgba(31, 78, 121, 0.05) 100%);
        color: var(--vcv-primary);
    }

    .stats-icon.success {
        background: linear-gradient(135deg, rgba(76, 175, 80, 0.1) 0%, rgba(76, 175, 80, 0.05) 100%);
        color: var(--vcv-accent);
    }

    .stats-icon.info {
        background: linear-gradient(135deg, rgba(0, 168, 225, 0.1) 0%, rgba(0, 168, 225, 0.05) 100%);
        color: #00a8e1;
    }

    .stats-number {
        font-size: 3rem;
        font-weight: 700;
        color: var(--vcv-primary);
        margin: 0.5rem 0;
        line-height: 1;
    }

    .stats-label {
        color: #64748b;
        font-weight: 500;
        margin: 0;
        font-size: 0.95rem;
    }

    /* Section Header - Estilo limpio */
    .section-header {
        background: white;
        padding: 1.5rem 2rem;
        border-radius: 16px;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
        border: 1px solid rgba(31, 78, 121, 0.08);
    }

    .section-header h4 {
        margin: 0 0 1rem 0;
        color: var(--vcv-primary);
        font-weight: 600;
        font-size: 1.3rem;
    }

    /* Filtros - Estilo tabs mejorado */
    .filters-container {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .filter-btn {
        padding: 0.625rem 1.25rem;
        border: 2px solid #e2e8f0;
        background: white;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 600;
        color: var(--vcv-dark);
        text-decoration: none;
        font-size: 0.9rem;
    }

    .filter-btn:hover {
        border-color: var(--vcv-primary);
        background: var(--vcv-light);
        color: var(--vcv-primary);
        transform: translateY(-1px);
    }

    .filter-btn.active {
        border-color: var(--vcv-primary);
        background: var(--vcv-primary);
        color: white;
    }

    .filter-btn .badge {
        padding: 0.25rem 0.5rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    /* Table Container - Modernizada */
    .table-container {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
        margin-bottom: 2rem;
        border: 1px solid rgba(31, 78, 121, 0.08);
    }

    .table {
        margin: 0;
        border: none;
    }

    .table thead th {
        background: linear-gradient(135deg, var(--vcv-primary) 0%, rgba(31, 78, 121, 0.9) 100%);
        color: white;
        border: none;
        padding: 1.2rem 1rem;
        font-weight: 600;
        font-size: 0.95rem;
        letter-spacing: 0.3px;
    }

    .table tbody tr {
        border-bottom: 1px solid rgba(31, 78, 121, 0.08);
        transition: all 0.2s ease;
    }

    .table tbody tr:hover {
        background: rgba(221, 242, 254, 0.3);
    }

    .table tbody td {
        padding: 1.2rem 1rem;
        border: none;
        vertical-align: middle;
        color: var(--vcv-dark);
    }

    /* Badges mejorados */
    .badge {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }

    /* Botones de acción - Estilo landing */
    .btn-custom {
        border: none;
        border-radius: 10px;
        padding: 0.625rem 1.25rem;
        font-weight: 600;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.9rem;
    }

    .btn-custom.primary {
        background: var(--vcv-primary);
        color: white;
    }

    .btn-custom.primary:hover {
        background: #173d61;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(31, 78, 121, 0.3);
        color: white;
    }

    .btn-custom.accent {
        background: var(--vcv-accent);
        color: white;
    }

    .btn-custom.accent:hover {
        background: #45a049;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(76, 175, 80, 0.3);
        color: white;
    }

    .btn-custom.outline {
        background: transparent;
        border: 2px solid rgba(31, 78, 121, 0.3);
        color: var(--vcv-primary);
    }

    .btn-custom.outline:hover {
        background: var(--vcv-light);
        border-color: var(--vcv-primary);
        color: var(--vcv-primary);
    }

    /* Action buttons pequeños */
    .btn-action {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        border: 2px solid;
        background: white;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        text-decoration: none;
        font-size: 0.9rem;
    }

    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }

    .btn-action.info {
        border-color: #0dcaf0;
        color: #0dcaf0;
    }

    .btn-action.info:hover {
        background: #0dcaf0;
        color: white;
    }

    .btn-action.pay {
        border-color: #00a8e1;
        color: #00a8e1;
    }

    .btn-action.pay:hover {
        background: #00a8e1;
        color: white;
    }

    .btn-action.chat {
        border-color: var(--vcv-accent);
        color: var(--vcv-accent);
    }

    .btn-action.chat:hover {
        background: var(--vcv-accent);
        color: white;
    }

    /* Ratings Section - Mejorada */
    .passenger-ratings-section {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
        margin-bottom: 2rem;
        border: 1px solid rgba(31, 78, 121, 0.08);
    }

    .ratings-summary {
        background: linear-gradient(135deg, var(--vcv-accent) 0%, #66BB6A 100%);
        border-radius: 12px;
        padding: 2rem;
        margin-bottom: 2rem;
        color: white;
        text-align: center;
    }

    .rating-score {
        font-size: 3rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .rating-stars {
        font-size: 1.5rem;
        margin: 0.5rem 0;
    }

    .rating-count {
        opacity: 0.95;
        font-size: 1rem;
    }

    .rating-comment {
        background: var(--vcv-bg);
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        border-left: 4px solid var(--vcv-accent);
        transition: all 0.3s ease;
        border: 1px solid rgba(31, 78, 121, 0.08);
    }

    .rating-comment:hover {
        transform: translateX(5px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .no-ratings {
        text-align: center;
        padding: 4rem 2rem;
    }

    .no-ratings i {
        font-size: 4rem;
        color: rgba(31, 78, 121, 0.2);
        margin-bottom: 1rem;
    }

    /* Alert mejorado */
    .alert {
        border-radius: 12px;
        border: none;
        padding: 1.5rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    }

    .alert-info {
        background: linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%);
        color: #0c5460;
    }

    /* Empty state */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
    }

    .empty-state i {
        font-size: 4rem;
        color: rgba(31, 78, 121, 0.2);
        margin-bottom: 1.5rem;
    }

    .empty-state h5 {
        color: var(--vcv-primary);
        font-weight: 600;
        margin-bottom: 1rem;
    }

    .empty-state p {
        color: #64748b;
        margin-bottom: 2rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .welcome-section {
            padding: 2rem 1.5rem;
        }
        
        .welcome-section h2 {
            font-size: 1.8rem;
        }

        .stats-number {
            font-size: 2.5rem;
        }

        .filters-container {
            justify-content: center;
        }

        .filter-btn {
            font-size: 0.8rem;
            padding: 0.5rem 1rem;
        }

        .table-container {
            border-radius: 12px;
        }

        .action-buttons {
            padding: 1.5rem;
        }

        .btn-custom {
            width: 100%;
            justify-content: center;
            margin-bottom: 0.5rem;
        }

        /* Cards en mobile */
        .mobile-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            border-left: 4px solid var(--vcv-primary);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        }

        .mobile-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
    }

    @media (max-width: 576px) {
        .stats-container {
            max-width: 100%;
        }

        .stats-card {
            padding: 1.5rem;
        }

        .stats-icon {
            width: 60px;
            height: 60px;
            font-size: 1.5rem;
        }
    }
</style>

<div class="dashboard-wrapper">
    <div class="container">
        <!-- Welcome Section -->
        <div class="welcome-section">
            <div class="welcome-content">
                <h2>👋 Bienvenido, {{ auth()->user()->name ?? 'Invitado' }}</h2>
                <p>Gestiona tus viajes y revisa tu actividad reciente</p>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="action-buttons">
            <h5>¿Qué quieres hacer hoy?</h5>
            <div class="d-flex gap-3 justify-content-center flex-wrap">
                <a href="{{ route('pasajero.viajes.disponibles') }}" class="btn-custom primary">
                    <i class="fas fa-search"></i>
                    Buscar viajes disponibles
                </a>
                <a href="{{ route('conductor.gestion') }}" class="btn-custom accent">
                    <i class="fas fa-plus-circle"></i>
                    Publicar un viaje
                </a>
                <a href="#" class="btn-custom outline">
                    <i class="fas fa-history"></i>
                    Ver historial completo
                </a>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="stats-container">
            <div class="row g-4 mb-4">
                <div class="col-lg-4 col-md-4 col-sm-6">
                    <div class="stats-card primary">
                        <div class="stats-icon primary">
                            <i class="fas fa-route"></i>
                        </div>
                        <p class="stats-number">{{ $totalViajes ?? 0 }}</p>
                        <p class="stats-label">Total de Viajes</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6">
                    <div class="stats-card success">
                        <div class="stats-icon success">
                            <i class="fas fa-clock"></i>
                        </div>
                        <p class="stats-number">{{ $viajesProximos ?? 0 }}</p>
                        <p class="stats-label">Próximos Viajes</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6">
                    <div class="stats-card info">
                        <div class="stats-icon info">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <p class="stats-number">{{ $viajesRealizados ?? 0 }}</p>
                        <p class="stats-label">Viajes Realizados</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reservas Section -->
        <div class="section-header">
            <h4><i class="fas fa-list-alt me-2"></i>Tus reservas</h4>
            
            <!-- Filtros -->
            <div class="filters-container">
                <a href="{{ route('pasajero.dashboard', ['estado' => 'todos']) }}" 
                   class="filter-btn {{ ($estadoFiltro ?? 'todos') === 'todos' ? 'active' : '' }}">
                    Todos
                    @if(isset($estadisticas))
                        <span class="badge bg-light text-dark">{{ $reservas->total() ?? $reservas->count() }}</span>
                    @endif
                </a>
                <a href="{{ route('pasajero.dashboard', ['estado' => 'activos']) }}" 
                   class="filter-btn {{ ($estadoFiltro ?? '') === 'activos' ? 'active' : '' }}">
                    Activos
                    @if(isset($estadisticas))
                        <span class="badge bg-light text-dark">{{ $estadisticas['activos'] }}</span>
                    @endif
                </a>
                <a href="{{ route('pasajero.dashboard', ['estado' => 'pendiente_confirmacion']) }}" 
                   class="filter-btn {{ ($estadoFiltro ?? '') === 'pendiente_confirmacion' ? 'active' : '' }}">
                    Esperando
                    @if(isset($estadisticas))
                        <span class="badge bg-light text-dark">{{ $estadisticas['pendiente_confirmacion'] }}</span>
                    @endif
                </a>
                <a href="{{ route('pasajero.dashboard', ['estado' => 'pendiente_pago']) }}" 
                   class="filter-btn {{ ($estadoFiltro ?? '') === 'pendiente_pago' ? 'active' : '' }}">
                    Por Pagar
                    @if(isset($estadisticas))
                        <span class="badge bg-light text-dark">{{ $estadisticas['pendiente_pago'] }}</span>
                    @endif
                </a>
                <a href="{{ route('pasajero.dashboard', ['estado' => 'confirmada']) }}" 
                   class="filter-btn {{ ($estadoFiltro ?? '') === 'confirmada' ? 'active' : '' }}">
                    Confirmadas
                    @if(isset($estadisticas))
                        <span class="badge bg-light text-dark">{{ $estadisticas['confirmada'] }}</span>
                    @endif
                </a>
                <a href="{{ route('pasajero.dashboard', ['estado' => 'cancelados']) }}" 
                   class="filter-btn {{ ($estadoFiltro ?? '') === 'cancelados' ? 'active' : '' }}">
                    Cancelados
                    @if(isset($estadisticas))
                        <span class="badge bg-light text-dark">{{ $estadisticas['cancelados'] }}</span>
                    @endif
                </a>
            </div>
        </div>

        @if(isset($reservas) && $reservas->count() > 0)
            <div class="table-container">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
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
                                        <div style="max-width: 200px;">
                                            <strong class="d-block">{{ Str::limit($reserva->viaje->origen_direccion ?? 'Origen', 20) }}</strong>
                                            <small class="text-muted">
                                                <i class="fas fa-arrow-right" style="font-size: 0.7rem;"></i>
                                                {{ Str::limit($reserva->viaje->destino_direccion ?? 'Destino', 20) }}
                                            </small>
                                        </div>
                                    </td>
                                    
                                    <!-- FECHA -->
                                    <td class="d-none d-md-table-cell">
                                        @if($reserva->viaje->fecha_salida)
                                            <strong class="d-block">{{ \Carbon\Carbon::parse($reserva->viaje->fecha_salida)->format('d/m/Y') }}</strong>
                                            <small class="text-muted">{{ \Carbon\Carbon::parse($reserva->viaje->fecha_salida)->format('H:i') }}</small>
                                        @else
                                            <span class="text-muted">No definida</span>
                                        @endif
                                    </td>
                                    
                                    <!-- ESTADO -->
                                    <td>
                                        @switch($reserva->estado)
                                            @case('pendiente')
                                                <span class="badge bg-warning">⏰ Pendiente</span>
                                                @break
                                            @case('pendiente_pago')
                                                <span class="badge bg-info">💳 Por Pagar</span>
                                                @break
                                            @case('pendiente_confirmacion')
                                                <span class="badge bg-warning">🕐 Esperando</span>
                                                @break
                                            @case('confirmada')
                                                <span class="badge bg-success">✅ Confirmado</span>
                                                @break
                                            @case('cancelada')
                                                <span class="badge bg-danger">❌ Cancelado</span>
                                                @break
                                            @case('cancelada_por_conductor')
                                                <span class="badge bg-danger">🚫 Cancelado</span>
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
                                        <span class="badge bg-light text-dark" style="font-size: 0.9rem;">
                                            {{ $reserva->cantidad_puestos ?? 1 }} <i class="fas fa-user" style="font-size: 0.75rem;"></i>
                                        </span>
                                    </td>
                                    
                                    <!-- TOTAL -->
                                    <td class="d-none d-md-table-cell">
                                        <strong class="text-success" style="font-size: 1.1rem;">
                                            ${{ number_format($reserva->total ?? 0, 0) }}
                                        </strong>
                                    </td>
                                    
                                    <!-- ACCIONES -->
                                    <td>
                                        <div class="d-flex gap-2">
                                            <!-- Detalles -->
                                            <a href="{{ route('pasajero.reserva.detalles', $reserva->id) }}" 
                                               class="btn-action info" 
                                               title="Ver detalles">
                                                <i class="fas fa-info-circle"></i>
                                            </a>
                                            
                                            <!-- Pagar -->
                                            @if($reserva->estado === 'pendiente_pago' && $reserva->mp_init_point)
                                                <a href="{{ $reserva->mp_init_point }}" 
                                                   class="btn-action pay" 
                                                   title="Proceder al pago">
                                                    <i class="fas fa-credit-card"></i>
                                                </a>
                                            @endif
                                            
                                            <!-- Chat -->
                                            @if($reserva->estado === 'confirmada')
                                                <a href="{{ route('chat.ver', $reserva->viaje_id) }}" 
                                                   class="btn-action chat" 
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
                    <div class="d-flex justify-content-center mt-4 p-3">
                        {{ $reservas->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <h5>No hay reservas</h5>
                <p>
                    @switch($estadoFiltro ?? 'todos')
                        @case('activos')
                            No tienes reservas activas. ¡Busca tu próximo viaje!
                            @break
                        @case('pendiente_confirmacion')
                            No tienes reservas esperando confirmación del conductor.
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
                        @case('completada')
                            No tienes viajes completados.
                            @break
                        @default
                            No tienes ninguna reserva aún.
                    @endswitch
                </p>
                @if(in_array($estadoFiltro ?? 'todos', ['activos', 'todos']))
                    <a href="{{ route('pasajero.viajes.disponibles') }}" class="btn-custom primary">
                        <i class="fas fa-search"></i>
                        Buscar viajes
                    </a>
                @endif
            </div>
        @endif

        <!-- Calificaciones Section -->
        <div class="section-header">
            <h4><i class="fas fa-star me-2"></i>Calificaciones que has recibido como pasajero</h4>
        </div>
        
        <div class="passenger-ratings-section">
            @php
                $comentariosRecibidos = collect();
                $calificacionesRecibidas = null;
                
                if(isset($calificacionesDetalle)) {
                    $comentariosRecibidos = $calificacionesDetalle
                        ->where('usuario_calificado_id', auth()->id())
                        ->where('tipo', 'conductor_a_pasajero');
                        
                    if($comentariosRecibidos->count() > 0) {
                        $calificacionesRecibidas = (object) [
                            'total_calificaciones' => $comentariosRecibidos->count(),
                            'promedio_calificacion' => $comentariosRecibidos->avg('calificacion')
                        ];
                    }
                }
                
                if(isset($misCalificaciones) && isset($misCalificaciones['conductor_a_pasajero'])) {
                    $calificacionesRecibidas = $misCalificaciones['conductor_a_pasajero'];
                }
            @endphp
            
            @if($calificacionesRecibidas && $calificacionesRecibidas->total_calificaciones > 0)
                <!-- Resumen -->
                <div class="ratings-summary">
                    <div class="rating-score">{{ number_format($calificacionesRecibidas->promedio_calificacion, 1) }}</div>
                    <div class="rating-stars">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= round($calificacionesRecibidas->promedio_calificacion))
                                <i class="fas fa-star"></i>
                            @else
                                <i class="far fa-star"></i>
                            @endif
                        @endfor
                    </div>
                    <p class="rating-count">Basado en {{ $calificacionesRecibidas->total_calificaciones }} calificación(es)</p>
                </div>

                <!-- Comentarios -->
                @if($comentariosRecibidos->count() > 0)
                    <div class="recent-ratings">
                        <h5 class="mb-3" style="color: var(--vcv-primary); font-weight: 600;">Comentarios de conductores</h5>
                        
                        @foreach($comentariosRecibidos->take(5) as $comentario)
                            <div class="rating-comment">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <div class="rating-stars-small mb-1" style="color: #ffc107;">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $comentario->calificacion)
                                                    <i class="fas fa-star"></i>
                                                @else
                                                    <i class="far fa-star"></i>
                                                @endif
                                            @endfor
                                            <span class="ms-2 fw-bold">{{ $comentario->calificacion }}/5</span>
                                        </div>
                                        <small class="text-muted">
                                            De conductor: <strong>{{ $comentario->nombre_conductor ?? 'Conductor' }}</strong>
                                        </small>
                                    </div>
                                    <small class="text-muted">{{ \Carbon\Carbon::parse($comentario->fecha_calificacion)->format('d/m/Y') }}</small>
                                </div>
                                
                                @if($comentario->comentario)
                                    <p class="mb-2" style="color: var(--vcv-dark); font-style: italic;">
                                        "{{ $comentario->comentario }}"
                                    </p>
                                @endif
                                
                                @if($comentario->origen_direccion && $comentario->destino_direccion)
                                    <small class="text-muted">
                                        <i class="fas fa-route me-1"></i>
                                        Viaje: {{ Str::limit($comentario->origen_direccion, 30) }} → {{ Str::limit($comentario->destino_direccion, 30) }}
                                    </small>
                                @endif
                            </div>
                        @endforeach

                        @if($comentariosRecibidos->count() > 5)
                            <div class="text-center mt-3">
                                <button class="btn-custom outline" onclick="toggleAllReceivedRatings()">
                                    <span id="toggle-received-text">Ver todas las calificaciones ({{ $comentariosRecibidos->count() }})</span>
                                    <i class="fas fa-chevron-down" id="toggle-received-icon"></i>
                                </button>
                            </div>
                            
                            <div id="all-received-ratings" style="display: none;" class="mt-3">
                                @foreach($comentariosRecibidos->skip(5) as $comentario)
                                    <div class="rating-comment">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div>
                                                <div class="rating-stars-small mb-1" style="color: #ffc107;">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        @if($i <= $comentario->calificacion)
                                                            <i class="fas fa-star"></i>
                                                        @else
                                                            <i class="far fa-star"></i>
                                                        @endif
                                                    @endfor
                                                    <span class="ms-2 fw-bold">{{ $comentario->calificacion }}/5</span>
                                                </div>
                                                <small class="text-muted">
                                                    De conductor: <strong>{{ $comentario->nombre_conductor ?? 'Conductor' }}</strong>
                                                </small>
                                            </div>
                                            <small class="text-muted">{{ \Carbon\Carbon::parse($comentario->fecha_calificacion)->format('d/m/Y') }}</small>
                                        </div>
                                        
                                        @if($comentario->comentario)
                                            <p class="mb-2" style="color: var(--vcv-dark); font-style: italic;">
                                                "{{ $comentario->comentario }}"
                                            </p>
                                        @endif
                                        
                                        @if($comentario->origen_direccion && $comentario->destino_direccion)
                                            <small class="text-muted">
                                                <i class="fas fa-route me-1"></i>
                                                Viaje: {{ Str::limit($comentario->origen_direccion, 30) }} → {{ Str::limit($comentario->destino_direccion, 30) }}
                                            </small>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endif
            @else
                <!-- Sin calificaciones -->
                <div class="no-ratings">
                    <i class="fas fa-user-friends"></i>
                    <h5 style="color: var(--vcv-primary); font-weight: 600;">Aún no has recibido calificaciones como pasajero</h5>
                    <p style="color: #64748b;">Completa un viaje para recibir calificaciones de los conductores.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function toggleAllReceivedRatings() {
    const allRatings = document.getElementById('all-received-ratings');
    const toggleText = document.getElementById('toggle-received-text');
    const toggleIcon = document.getElementById('toggle-received-icon');
    
    if (allRatings && toggleText && toggleIcon) {
        if (allRatings.style.display === 'none' || allRatings.style.display === '') {
            allRatings.style.display = 'block';
            toggleText.textContent = 'Ocultar calificaciones';
            toggleIcon.className = 'fas fa-chevron-up';
        } else {
            allRatings.style.display = 'none';
            toggleText.textContent = 'Ver todas las calificaciones ({{ $comentariosRecibidos->count() }})';
            toggleIcon.className = 'fas fa-chevron-down';
        }
    }
}
</script>
@endsection