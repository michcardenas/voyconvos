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

        <!-- Reservas Section -->
        <div class="section-header">
            <h4><i class="fas fa-list-alt me-2"></i>Tus reservas</h4>
        </div>

        @if($reservas->count())
            <div class="table-container">
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th><i class="fas fa-map-marker-alt me-2"></i>Origen</th>
                                <th><i class="fas fa-flag-checkered me-2"></i>Destino</th>
                                <th><i class="fas fa-cogs me-2"></i>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reservas as $reserva)
                                @if($reserva->viaje)
                                <tr>
                                    <td>
                                        <strong>{{ $reserva->viaje->origen_direccion }}</strong>
                                    </td>
                                    <td>
                                        <strong>{{ $reserva->viaje->destino_direccion }}</strong>
                                    </td>
                                    <td>
                                        <a href="{{ route('pasajero.reserva.detalles', $reserva->id) }}" 
                                           class="btn-custom outline">
                                            <i class="fas fa-info-circle me-1"></i>Detalles
                                        </a>
                                        <a href="{{ route('pasajero.reserva.detalles', $reserva->id) }}#mapa" 
                                           class="btn-custom primary">
                                            <i class="fas fa-map me-1"></i>Ver ruta
                                        </a>
                                        <a href="{{ route('chat.ver', $reserva->viaje_id ?? $viaje->id) }}" 
                                           class="btn-custom accent">
                                            <i class="fas fa-comments me-1"></i>Chat
                                        </a>
                                    </td>
                                </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <h5>No tienes reservas activas</h5>
                <p>¡Busca y reserva tu próximo viaje!</p>
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
@endsection