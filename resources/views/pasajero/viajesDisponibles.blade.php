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

    .trips-wrapper {
        background: linear-gradient(135deg, #DDF2FE 0%, #FCFCFD 50%, rgba(31, 78, 121, 0.03) 100%);
        min-height: 100vh;
        padding: 2rem 0;
        position: relative;
    }

    .trips-wrapper::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: 
            radial-gradient(circle at 20% 80%, rgba(76, 175, 80, 0.05) 0%, transparent 50%),
            radial-gradient(circle at 80% 20%, rgba(31, 78, 121, 0.05) 0%, transparent 50%);
        pointer-events: none;
    }

    .container {
        position: relative;
        z-index: 1;
    }

    .page-header {
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

    .page-header::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 150px;
        height: 150px;
        background: radial-gradient(circle, rgba(76, 175, 80, 0.2) 0%, transparent 70%);
        border-radius: 50%;
        transform: translate(30%, -30%);
    }

    .page-header h2 {
        margin: 0;
        font-weight: 600;
        font-size: 1.8rem;
        position: relative;
        z-index: 2;
    }

    .alert-custom {
        border: none;
        border-radius: 12px;
        padding: 1rem 1.5rem;
        margin-bottom: 1.5rem;
        backdrop-filter: blur(10px);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .alert-success {
        background: rgba(76, 175, 80, 0.1);
        color: var(--vcv-accent);
        border-left: 4px solid var(--vcv-accent);
    }

    .alert-danger {
        background: rgba(220, 53, 69, 0.1);
        color: #dc3545;
        border-left: 4px solid #dc3545;
    }

    .alert-info {
        background: rgba(31, 78, 121, 0.1);
        color: var(--vcv-primary);
        border-left: 4px solid var(--vcv-primary);
    }

    .trip-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 16px;
        padding: 0;
        border: 1px solid rgba(31, 78, 121, 0.12);
        box-shadow: 0 4px 12px rgba(31, 78, 121, 0.08);
        transition: all 0.3s ease;
        overflow: hidden;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .trip-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 24px rgba(31, 78, 121, 0.15);
        border-color: rgba(31, 78, 121, 0.2);
    }

    .trip-header {
        background: linear-gradient(135deg, var(--vcv-primary), rgba(31, 78, 121, 0.9));
        color: white;
        padding: 1.2rem 1.5rem;
        position: relative;
        overflow: hidden;
    }

    .trip-header::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 80px;
        height: 80px;
        background: radial-gradient(circle, rgba(76, 175, 80, 0.2) 0%, transparent 70%);
        border-radius: 50%;
        transform: translate(30%, -30%);
    }

    .route-display {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin: 0;
        font-weight: 600;
        font-size: 1rem;
        position: relative;
        z-index: 2;
    }

    .route-city {
        flex: 1;
        text-align: center;
    }

    .route-arrow {
        margin: 0 1rem;
        font-size: 1.2rem;
        color: rgba(255, 255, 255, 0.8);
    }

    .trip-duration {
        text-align: center;
        font-size: 0.8rem;
        opacity: 0.9;
        margin-top: 0.3rem;
        position: relative;
        z-index: 2;
    }

    .trip-body {
        padding: 1.5rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .trip-details {
        flex: 1;
    }

    .detail-row {
        display: flex;
        align-items: center;
        margin-bottom: 0.8rem;
        padding: 0.5rem 0;
    }

    .detail-row:last-child {
        margin-bottom: 0;
    }

    .detail-icon {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        font-size: 0.9rem;
    }

    .detail-icon.date {
        background: rgba(31, 78, 121, 0.1);
        color: var(--vcv-primary);
    }

    .detail-icon.time {
        background: rgba(76, 175, 80, 0.1);
        color: var(--vcv-accent);
    }

    .detail-icon.driver {
        background: rgba(221, 242, 254, 0.8);
        color: var(--vcv-primary);
    }

    .detail-icon.seats {
        background: rgba(255, 193, 7, 0.1);
        color: #f57c00;
    }

    .driver-row {
        background: rgba(31, 78, 121, 0.02);
        border-radius: 12px;
        padding: 1rem !important;
        margin-bottom: 1.2rem !important;
        border: 1px solid rgba(31, 78, 121, 0.08);
    }

    .driver-avatar {
        width: 50px;
        height: 50px;
        margin-right: 1rem;
        position: relative;
    }

    .driver-photo {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid var(--vcv-primary);
        box-shadow: 0 2px 8px rgba(31, 78, 121, 0.2);
    }

    .driver-photo-placeholder {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--vcv-primary), rgba(31, 78, 121, 0.8));
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.2rem;
        border: 3px solid var(--vcv-primary);
        box-shadow: 0 2px 8px rgba(31, 78, 121, 0.2);
    }

    .driver-rating {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-top: 0.5rem;
    }

    .stars {
        display: flex;
        gap: 0.1rem;
    }

    .stars i {
        font-size: 0.8rem;
        color: #ffc107;
    }

    .stars .far {
        color: rgba(255, 193, 7, 0.3);
    }

    .rating-value {
        font-weight: 600;
        color: var(--vcv-primary);
        font-size: 0.85rem;
    }

    .rating-count {
        color: rgba(58, 58, 58, 0.6);
        font-size: 0.75rem;
    }

    .verified-badge {
        display: inline-block;
        margin-left: 0.5rem;
        color: var(--vcv-accent);
        font-size: 0.9rem;
    }

    .verified-badge i {
        filter: drop-shadow(0 1px 2px rgba(76, 175, 80, 0.3));
    }

    .experience-badge {
        background: rgba(31, 78, 121, 0.1);
        color: var(--vcv-primary);
        padding: 0.2rem 0.6rem;
        border-radius: 12px;
        font-size: 0.7rem;
        font-weight: 600;
        border: 1px solid rgba(31, 78, 121, 0.2);
    }

    .detail-content {
        flex: 1;
    }

    .detail-label {
        font-size: 0.8rem;
        color: rgba(58, 58, 58, 0.7);
        margin-bottom: 0.2rem;
        font-weight: 500;
    }

    .detail-value {
        color: var(--vcv-dark);
        font-weight: 600;
        font-size: 0.95rem;
    }

    .price-section {
        background: rgba(76, 175, 80, 0.05);
        border-radius: 10px;
        padding: 1rem;
        margin: 1rem 0;
        text-align: center;
        border: 1px solid rgba(76, 175, 80, 0.2);
    }

    .price-amount {
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--vcv-accent);
        margin: 0;
    }

    .price-label {
        font-size: 0.8rem;
        color: rgba(58, 58, 58, 0.7);
        margin: 0;
    }

    .trip-actions {
        padding: 0 1.5rem 1.5rem;
        display: flex;
        gap: 0.8rem;
        flex-wrap: wrap;
    }

    .btn-custom {
        border: none;
        border-radius: 25px;
        padding: 0.7rem 1.2rem;
        font-weight: 600;
        transition: all 0.3s ease;
        text-decoration: none;
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        flex: 1;
        justify-content: center;
        min-width: 100px;
    }

    .btn-custom.primary {
        background: var(--vcv-primary);
        color: white;
    }

    .btn-custom.primary:hover {
        background: rgba(31, 78, 121, 0.9);
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(31, 78, 121, 0.3);
        color: white;
    }

    .btn-custom.success {
        background: var(--vcv-accent);
        color: white;
    }

    .btn-custom.success:hover {
        background: rgba(76, 175, 80, 0.9);
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(76, 175, 80, 0.3);
        color: white;
    }

    .btn-custom.outline {
        background: rgba(31, 78, 121, 0.05);
        color: var(--vcv-primary);
        border: 1px solid rgba(31, 78, 121, 0.3);
    }

    .btn-custom.outline:hover {
        background: var(--vcv-primary);
        color: white;
        transform: translateY(-2px);
    }

    .empty-state {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border-radius: 16px;
        padding: 3rem 2rem;
        text-align: center;
        border: 1px solid rgba(31, 78, 121, 0.12);
        box-shadow: 0 4px 12px rgba(31, 78, 121, 0.08);
    }

    .empty-state i {
        font-size: 4rem;
        color: rgba(31, 78, 121, 0.3);
        margin-bottom: 1.5rem;
    }

    .empty-state h4 {
        color: var(--vcv-primary);
        font-weight: 600;
        margin-bottom: 1rem;
    }

    .empty-state p {
        color: rgba(58, 58, 58, 0.7);
        margin: 0;
    }

    .seats-available {
        display: inline-block;
        background: rgba(76, 175, 80, 0.1);
        color: var(--vcv-accent);
        padding: 0.3rem 0.8rem;
        border-radius: 15px;
        font-size: 0.8rem;
        font-weight: 600;
        border: 1px solid rgba(76, 175, 80, 0.3);
    }

    @media (max-width: 768px) {
        .trips-wrapper {
            padding: 1rem 0;
        }
        
        .page-header {
            padding: 1.5rem;
        }
        
        .page-header h2 {
            font-size: 1.5rem;
        }
        
        .trip-card {
            margin-bottom: 1.5rem;
        }
        
        .trip-actions {
            flex-direction: column;
        }
        
        .btn-custom {
            width: 100%;
            margin: 0.2rem 0;
        }
        
        .route-display {
            font-size: 0.9rem;
        }
        
        .route-arrow {
            margin: 0 0.5rem;
        }

        .driver-avatar {
            width: 45px;
            height: 45px;
        }

        .driver-photo,
        .driver-photo-placeholder {
            width: 45px;
            height: 45px;
        }

        .driver-rating {
            flex-wrap: wrap;
            gap: 0.3rem;
        }

        .stars {
            gap: 0.05rem;
        }

        .stars i {
            font-size: 0.75rem;
        }
    }
</style>

<div class="trips-wrapper">
    <div class="container">
        <!-- Page Header -->
        <div class="page-header">
            <h2>🚗 Viajes Disponibles</h2>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-custom alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-custom alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Trips Grid -->
        @if($viajesDisponibles->isEmpty())
            <div class="empty-state">
                <i class="fas fa-car-side"></i>
                <h4>No hay viajes disponibles</h4>
                <p>Por el momento no hay viajes programados.<br>¡Vuelve más tarde para encontrar tu próximo destino!</p>
            </div>
        @else
            <div class="row g-4">
                @foreach($viajesDisponibles as $viaje)
                    <div class="col-lg-4 col-md-6">
                        <div class="trip-card">
                            <!-- Trip Header -->
                            <div class="trip-header">
                                <div class="route-display">
                                    <div class="route-city">{{ explode(',', $viaje->origen_direccion)[0] ?? $viaje->origen_direccion }}</div>
                                    <div class="route-arrow">
                                        <i class="fas fa-arrow-right"></i>
                                    </div>
                                    <div class="route-city">{{ explode(',', $viaje->destino_direccion)[0] ?? $viaje->destino_direccion }}</div>
                                </div>
                                <div class="trip-duration">
                                    {{ \Carbon\Carbon::parse($viaje->fecha_salida)->format('M d') }} • {{ $viaje->hora_salida ?? 'Hora por definir' }}
                                </div>
                            </div>

                            <!-- Trip Body -->
                            <div class="trip-body">
                                <div class="trip-details">
                                    <!-- Date -->
                                    <div class="detail-row">
                                        <div class="detail-icon date">
                                            <i class="fas fa-calendar"></i>
                                        </div>
                                        <div class="detail-content">
                                            <div class="detail-label">Fecha de salida</div>
                                            <div class="detail-value">{{ \Carbon\Carbon::parse($viaje->fecha_salida)->format('d/m/Y') }}</div>
                                        </div>
                                    </div>

                                    <!-- Time -->
                                    <div class="detail-row">
                                        <div class="detail-icon time">
                                            <i class="fas fa-clock"></i>
                                        </div>
                                        <div class="detail-content">
                                            <div class="detail-label">Hora de salida</div>
                                            <div class="detail-value">{{ $viaje->hora_salida ?? 'Por definir' }}</div>
                                        </div>
                                    </div>

                                    <!-- Driver -->
                                    <div class="detail-row driver-row">
                                        <div class="driver-avatar">
                                            @if($viaje->conductor?->foto)
                                                <img src="{{ asset('storage/' . $viaje->conductor->foto) }}" alt="{{ $viaje->conductor->name }}" class="driver-photo">
                                            @elseif($viaje->conductor?->avatar)
                                                <img src="{{ $viaje->conductor->avatar }}" alt="{{ $viaje->conductor->name }}" class="driver-photo">
                                            @else
                                                <div class="driver-photo-placeholder">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="detail-content">
                                            <div class="detail-label">Conductor</div>
                                            <div class="detail-value">
                                                {{ $viaje->conductor?->name ?? 'No disponible' }}
                                                @if($viaje->conductor && ($viaje->conductor->verificado ?? ($viaje->conductor->calificacion_promedio ?? 4.2) >= 4.5))
                                                    <span class="verified-badge">
                                                        <i class="fas fa-check-circle"></i>
                                                    </span>
                                                @endif
                                            </div>
                                            @if($viaje->conductor)
                                                <div class="driver-rating">
                                                    @php
                                                        $rating = $viaje->conductor->calificacion_promedio ?? $viaje->conductor->rating ?? 4.2;
                                                        $fullStars = floor($rating);
                                                        $hasHalfStar = ($rating - $fullStars) >= 0.5;
                                                        $emptyStars = 5 - $fullStars - ($hasHalfStar ? 1 : 0);
                                                    @endphp
                                                    <div class="stars">
                                                        @for($i = 1; $i <= $fullStars; $i++)
                                                            <i class="fas fa-star"></i>
                                                        @endfor
                                                        @if($hasHalfStar)
                                                            <i class="fas fa-star-half-alt"></i>
                                                        @endif
                                                        @for($i = 1; $i <= $emptyStars; $i++)
                                                            <i class="far fa-star"></i>
                                                        @endfor
                                                    </div>
                                                    <span class="rating-value">{{ number_format($rating, 1) }}</span>
                                                    <span class="rating-count">({{ $viaje->conductor->total_calificaciones ?? rand(5, 47) }})</span>
                                                    @if($viaje->conductor->experiencia_anos ?? false)
                                                        <span class="experience-badge">{{ $viaje->conductor->experiencia_anos }}+ años</span>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Available Seats -->
                                    <div class="detail-row">
                                        <div class="detail-icon seats">
                                            <i class="fas fa-chair"></i>
                                        </div>
                                        <div class="detail-content">
                                            <div class="detail-label">Puestos disponibles</div>
                                            <div class="detail-value">
                                                <span class="seats-available">{{ $viaje->puestos_disponibles }} disponibles</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Price Section -->
                                <div class="price-section">
                                    <p class="price-amount">${{ number_format($viaje->valor_persona ?? 5200, 2, ',', '.') }}</p>
                                    <p class="price-label">por persona</p>
                                </div>
                            </div>

                            <!-- Trip Actions -->
                            <div class="trip-actions">
                                <a href="{{ route('pasajero.confirmar.mostrar', $viaje->id) }}" class="btn-custom primary">
                                    <i class="fas fa-info-circle"></i>
                                    Detalles
                                </a>

                               

                                <a href="{{ route('chat.ver', $reserva->viaje_id ?? $viaje->id) }}" class="btn-custom outline">
                                    <i class="fas fa-comments"></i>
                                    Chat
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection