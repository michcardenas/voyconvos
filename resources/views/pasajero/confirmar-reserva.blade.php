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

    .confirm-wrapper {
        background: linear-gradient(135deg, #DDF2FE 0%, #FCFCFD 50%, rgba(31, 78, 121, 0.03) 100%);
        min-height: 100vh;
        padding: 2rem 0;
        position: relative;
    }

    .confirm-wrapper::before {
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
        max-width: 700px;
    }

    /* Page Header */
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
        text-align: center;
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

    .page-subtitle {
        margin: 0.5rem 0 0 0;
        opacity: 0.9;
        font-size: 1rem;
        position: relative;
        z-index: 2;
    }

    /* Progress Steps */
    .progress-steps {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 1rem;
        margin-bottom: 2rem;
        padding: 1.5rem;
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 12px rgba(31, 78, 121, 0.08);
    }

    .step {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        border-radius: 25px;
        background: rgba(31, 78, 121, 0.05);
        color: rgba(58, 58, 58, 0.6);
        transition: all 0.3s ease;
    }

    .step.active {
        background: linear-gradient(135deg, var(--vcv-primary), rgba(31, 78, 121, 0.9));
        color: white;
        box-shadow: 0 4px 12px rgba(31, 78, 121, 0.3);
    }

    .step.completed {
        background: linear-gradient(135deg, var(--vcv-accent), rgba(76, 175, 80, 0.9));
        color: white;
    }

    .step-number {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
    }

    .step-arrow {
        color: rgba(58, 58, 58, 0.3);
        font-size: 1.2rem;
    }

    /* Trip Summary Card */
    .trip-summary-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 16px;
        padding: 0;
        border: 1px solid rgba(31, 78, 121, 0.12);
        box-shadow: 0 4px 12px rgba(31, 78, 121, 0.08);
        overflow: hidden;
        margin-bottom: 2rem;
    }

    .trip-header {
        background: linear-gradient(135deg, var(--vcv-primary), rgba(31, 78, 121, 0.9));
        color: white;
        padding: 1.5rem 2rem;
        position: relative;
        overflow: hidden;
    }

    .trip-header::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 100px;
        height: 100px;
        background: radial-gradient(circle, rgba(76, 175, 80, 0.2) 0%, transparent 70%);
        border-radius: 50%;
        transform: translate(30%, -30%);
    }

    .route-display {
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
        position: relative;
        z-index: 2;
    }

    .route-city {
        font-size: 1.3rem;
        font-weight: 600;
        padding: 0 1rem;
    }

    .route-arrow {
        margin: 0 1rem;
        font-size: 1.5rem;
        color: rgba(255, 255, 255, 0.8);
    }

    .trip-date-time {
        text-align: center;
        font-size: 1rem;
        opacity: 0.95;
        position: relative;
        z-index: 2;
    }

    .trip-details {
        padding: 2rem;
    }

    .detail-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }

    .detail-item {
        display: flex;
        align-items: center;
        padding: 1rem;
        background: rgba(221, 242, 254, 0.3);
        border-radius: 12px;
        border-left: 4px solid var(--vcv-primary);
        transition: all 0.3s ease;
    }

    .detail-item:hover {
        background: rgba(221, 242, 254, 0.5);
        border-left-color: var(--vcv-accent);
        transform: translateX(3px);
    }

    .detail-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1.5rem;
        font-size: 1.2rem;
        flex-shrink: 0;
    }

    .detail-icon.time { background: rgba(76, 175, 80, 0.1); color: var(--vcv-accent); }
    .detail-icon.driver { background: rgba(31, 78, 121, 0.1); color: var(--vcv-primary); }
    .detail-icon.seats { background: rgba(255, 193, 7, 0.1); color: #f57c00; }
    .detail-icon.price { background: rgba(76, 175, 80, 0.15); color: var(--vcv-accent); }

    .detail-content h6 {
        margin: 0 0 0.3rem 0;
        color: var(--vcv-dark);
        font-weight: 600;
        font-size: 1rem;
    }

    .detail-content p {
        margin: 0;
        color: rgba(58, 58, 58, 0.8);
        font-size: 0.9rem;
    }

    /* Driver Section */
    .driver-section {
        background: rgba(31, 78, 121, 0.02);
        border-radius: 12px;
        padding: 1.5rem;
        border: 1px solid rgba(31, 78, 121, 0.08);
    }

    .driver-info {
        display: flex;
        align-items: center;
    }

    .driver-avatar {
        width: 60px;
        height: 60px;
        margin-right: 1.5rem;
        flex-shrink: 0;
    }

    .driver-photo {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid var(--vcv-primary);
        box-shadow: 0 2px 8px rgba(31, 78, 121, 0.2);
    }

    .driver-photo-placeholder {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--vcv-primary), rgba(31, 78, 121, 0.8));
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
        border: 3px solid var(--vcv-primary);
        box-shadow: 0 2px 8px rgba(31, 78, 121, 0.2);
    }

    .driver-details h6 {
        margin: 0 0 0.5rem 0;
        color: var(--vcv-dark);
        font-weight: 600;
        font-size: 1.1rem;
    }

    .driver-rating {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.3rem;
    }

    .stars {
        display: flex;
        gap: 0.1rem;
    }

    .stars i {
        font-size: 0.9rem;
        color: #ffc107;
    }

    .stars .far {
        color: rgba(255, 193, 7, 0.3);
    }

    .rating-value {
        font-weight: 600;
        color: var(--vcv-primary);
        font-size: 0.9rem;
    }

    .rating-count {
        color: rgba(58, 58, 58, 0.6);
        font-size: 0.8rem;
    }

    .verified-badge {
        display: inline-block;
        margin-left: 0.5rem;
        color: var(--vcv-accent);
        font-size: 1rem;
    }

    /* Booking Form */
    .booking-form {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 16px;
        padding: 2rem;
        border: 1px solid rgba(31, 78, 121, 0.12);
        box-shadow: 0 4px 12px rgba(31, 78, 121, 0.08);
        margin-bottom: 2rem;
    }

    .form-header {
        text-align: center;
        margin-bottom: 2rem;
    }

    .form-header h4 {
        color: var(--vcv-primary);
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .form-header p {
        color: rgba(58, 58, 58, 0.7);
        margin: 0;
    }

    .seats-selector {
        background: rgba(221, 242, 254, 0.3);
        border-radius: 12px;
        padding: 1.5rem;
        border: 2px solid rgba(31, 78, 121, 0.1);
        margin-bottom: 2rem;
        transition: all 0.3s ease;
    }

    .seats-selector:focus-within {
        border-color: var(--vcv-primary);
        background: rgba(221, 242, 254, 0.5);
    }

    .seats-label {
        display: block;
        color: var(--vcv-primary);
        font-weight: 600;
        margin-bottom: 1rem;
        font-size: 1rem;
    }

    .seats-input {
        width: 100%;
        border: 2px solid rgba(31, 78, 121, 0.2);
        border-radius: 10px;
        padding: 1rem 1.5rem;
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--vcv-dark);
        background: white;
        transition: all 0.3s ease;
        text-align: center;
    }

    .seats-input:focus {
        outline: none;
        border-color: var(--vcv-primary);
        box-shadow: 0 0 0 3px rgba(31, 78, 121, 0.1);
    }

    .seats-info {
        display: flex;
        justify-content: space-between;
        margin-top: 1rem;
        font-size: 0.9rem;
        color: rgba(58, 58, 58, 0.7);
    }

    .price-summary {
        background: rgba(76, 175, 80, 0.1);
        border: 2px solid rgba(76, 175, 80, 0.2);
        border-radius: 12px;
        padding: 1.5rem;
        text-align: center;
        margin-bottom: 2rem;
    }

    .price-label {
        color: rgba(58, 58, 58, 0.7);
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
    }

    .price-amount {
        font-size: 2rem;
        font-weight: 700;
        color: var(--vcv-accent);
        margin: 0;
    }

    .price-per-person {
        color: rgba(58, 58, 58, 0.6);
        font-size: 0.8rem;
        margin-top: 0.3rem;
    }

    /* Summary Section - Hidden by default */
    .summary-section {
        max-height: 0;
        overflow: hidden;
        opacity: 0;
        transition: all 0.5s ease;
        margin-bottom: 2rem;
    }

    .summary-section.show {
        max-height: 3000px;
        opacity: 1;
    }

    .summary-card {
        background: linear-gradient(135deg, rgba(76, 175, 80, 0.05), rgba(31, 78, 121, 0.05));
        border: 2px solid rgba(76, 175, 80, 0.2);
        border-radius: 16px;
        padding: 2rem;
        animation: slideDown 0.5s ease;
    }

    @keyframes slideDown {
        from {
            transform: translateY(-20px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .summary-header {
        text-align: center;
        margin-bottom: 2rem;
    }

    .summary-header .icon {
        font-size: 3rem;
        color: var(--vcv-accent);
        margin-bottom: 1rem;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }

    .summary-header h4 {
        color: var(--vcv-primary);
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .summary-details-list {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }

    .summary-item {
        display: flex;
        justify-content: space-between;
        padding: 0.75rem 0;
        border-bottom: 1px solid rgba(31, 78, 121, 0.1);
    }

    .summary-item:last-child {
        border-bottom: none;
    }

    .summary-item-label {
        color: rgba(58, 58, 58, 0.7);
        font-size: 0.9rem;
    }

    .summary-item-value {
        color: var(--vcv-dark);
        font-weight: 600;
    }

    .total-section {
        background: linear-gradient(135deg, var(--vcv-accent), rgba(76, 175, 80, 0.9));
        border-radius: 12px;
        padding: 2rem;
        text-align: center;
        color: white;
        margin-bottom: 2rem;
        box-shadow: 0 6px 20px rgba(76, 175, 80, 0.3);
    }

    .total-label {
        font-size: 1rem;
        opacity: 0.9;
        margin-bottom: 0.5rem;
    }

    .total-amount {
        font-size: 3rem;
        font-weight: 700;
        margin: 0;
    }

    .total-breakdown {
        font-size: 0.9rem;
        opacity: 0.9;
        margin-top: 0.5rem;
    }

    /* Form Actions */
    .form-actions {
        display: flex;
        gap: 1rem;
    }

    .btn-custom {
        border: none;
        border-radius: 25px;
        padding: 1rem 2rem;
        font-weight: 600;
        transition: all 0.3s ease;
        text-decoration: none;
        font-size: 1rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        justify-content: center;
        flex: 1;
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }

    .btn-custom::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s;
    }

    .btn-custom:hover::before {
        left: 100%;
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
        background: linear-gradient(135deg, var(--vcv-accent), rgba(76, 175, 80, 0.9));
        color: white;
        box-shadow: 0 4px 12px rgba(76, 175, 80, 0.3);
    }

    .btn-custom.success:hover {
        background: linear-gradient(135deg, rgba(76, 175, 80, 0.9), var(--vcv-accent));
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(76, 175, 80, 0.4);
        color: white;
    }

    .btn-custom.secondary {
        background: rgba(58, 58, 58, 0.1);
        color: var(--vcv-dark);
        border: 1px solid rgba(58, 58, 58, 0.3);
    }

    .btn-custom.secondary:hover {
        background: var(--vcv-dark);
        color: white;
        transform: translateY(-2px);
        text-decoration: none;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .confirm-wrapper {
            padding: 1rem 0;
        }
        
        .page-header {
            padding: 1.5rem;
        }
        
        .page-header h2 {
            font-size: 1.5rem;
        }

        .progress-steps {
            flex-direction: column;
            gap: 0.5rem;
        }

        .step-arrow {
            transform: rotate(90deg);
        }
        
        .route-city {
            font-size: 1.1rem;
            padding: 0 0.5rem;
        }
        
        .route-arrow {
            margin: 0 0.5rem;
            font-size: 1.2rem;
        }
        
        .trip-details {
            padding: 1.5rem;
        }
        
        .detail-item {
            flex-direction: column;
            text-align: center;
            padding: 1.5rem 1rem;
        }
        
        .detail-icon {
            margin-right: 0;
            margin-bottom: 1rem;
        }
        
        .driver-info {
            flex-direction: column;
            text-align: center;
        }
        
        .driver-avatar {
            margin-right: 0;
            margin-bottom: 1rem;
        }
        
        .form-actions {
            flex-direction: column;
        }
        
        .btn-custom {
            width: 100%;
            margin: 0.3rem 0;
        }

        .total-amount {
            font-size: 2.5rem;
        }
    }
</style>

<div class="confirm-wrapper">
    <div class="container">
        <!-- Page Header -->
        <div class="page-header">
            <h2>✅ Confirmar Reserva</h2>
            <p class="page-subtitle">Completa tu reserva en simples pasos</p>
        </div>

        <!-- Progress Steps -->
        <div class="progress-steps">
            <div class="step active" id="step1">
                <div class="step-number">1</div>
                <span>Detalles</span>
            </div>
            <div class="step-arrow">→</div>
            <div class="step" id="step2">
                <div class="step-number">2</div>
                <span>Resumen</span>
            </div>
            <div class="step-arrow">→</div>
            <div class="step" id="step3">
                <div class="step-number">3</div>
                <span>Confirmar</span>
            </div>
        </div>

        <!-- Trip Summary Card -->
        <div class="trip-summary-card">
            <div class="trip-header">
                <div class="route-display">
                    <div class="route-city">{{ explode(',', $viaje->origen_direccion)[0] ?? $viaje->origen_direccion }}</div>
                    <div class="route-arrow">
                        <i class="fas fa-arrow-right"></i>
                    </div>
                    <div class="route-city">{{ explode(',', $viaje->destino_direccion)[0] ?? $viaje->destino_direccion }}</div>
                </div>
                <div class="trip-date-time">
                    <i class="fas fa-calendar me-2"></i>
                    {{ \Carbon\Carbon::parse($viaje->fecha_salida)->format('d/m/Y') }}
                    <span class="mx-2">•</span>
                    <i class="fas fa-clock me-2"></i>
                    {{ $viaje->hora_salida }}
                </div>
            </div>

            <div class="trip-details">
                <div class="detail-grid">
                    <!-- Conductor -->
                    <div class="driver-section">
                        <div class="driver-info">
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
                            <div class="driver-details">
                                <h6>
                                    {{ $viaje->conductor->name ?? 'No disponible' }}
                                    @if($viaje->conductor && ($viaje->conductor->verificado ?? ($viaje->conductor->calificacion_promedio ?? 0) >= 4.5))
                                        <span class="verified-badge">
                                            <i class="fas fa-check-circle"></i>
                                        </span>
                                    @endif
                                </h6>
                                
                                @if($viaje->conductor)
                                    @php
                                        $tieneCalificaciones = ($viaje->conductor->total_calificaciones ?? 0) > 0;
                                        $rating = $viaje->conductor->calificacion_promedio ?? 0;
                                    @endphp
                                    
                                    @if($tieneCalificaciones && $rating > 0)
                                        <div class="driver-rating">
                                            @php
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
                                            <span class="rating-count">({{ $viaje->conductor->total_calificaciones }})</span>
                                        </div>
                                    @endif
                                @endif
                                
                                <p style="margin: 0; color: rgba(58, 58, 58, 0.7); font-size: 0.9rem;">
                                    <i class="fas fa-steering-wheel me-1"></i>Tu conductor
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Vehículo -->
                    <div class="detail-item">
                        <div class="detail-icon" style="background: rgba(31, 78, 121, 0.1); color: var(--vcv-primary);">
                            <i class="fas fa-car"></i>
                        </div>
                        <div class="detail-content">
                            <h6>Vehículo</h6>
                            @if($viaje->vehiculo_info)
                                <p>{{ ucfirst($viaje->vehiculo_info->marca_vehiculo) }} {{ ucfirst($viaje->vehiculo_info->modelo_vehiculo) }}</p>
                            @else
                                <p>{{ ucfirst($viaje->vehiculo ?? 'No especificado') }}</p>
                            @endif
                        </div>
                    </div>

                    <!-- Puestos Disponibles -->
                    <div class="detail-item">
                        <div class="detail-icon seats">
                            <i class="fas fa-chair"></i>
                        </div>
                        <div class="detail-content">
                            <h6>Puestos Disponibles</h6>
                            <p>{{ $viaje->puestos_disponibles }} asientos libres</p>
                        </div>
                    </div>

                    <!-- Precio -->
                    <div class="detail-item">
                        <div class="detail-icon price">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                        <div class="detail-content">
                            <h6>Precio por persona</h6>
                            <p>${{ number_format($viaje->valor_persona, 2, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mapa de la ruta -->
        <div class="trip-summary-card" style="margin-top: 1.5rem;">
            <div class="trip-header">
                <div style="display: flex; align-items: center; gap: 0.5rem; justify-content: center;">
                    <i class="fas fa-map" style="color: #4285f4;"></i>
                    <h5 style="margin: 0; font-weight: 600;">Ruta del Viaje</h5>
                </div>
                <p style="margin: 0.5rem 0 0 0; color: rgba(255, 255, 255, 0.7); font-size: 0.9rem;">
                    Visualiza el recorrido de {{ $viaje->distancia_km ?? '—' }} km
                </p>
            </div>
            <div style="padding: 1rem;">
                <div id="map" style="height: 350px; width: 100%; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);"></div>
            </div>
        </div>

        <!-- Booking Form -->
        <div class="booking-form">
            <div class="form-header">
                <h4>💺 Selecciona tus puestos</h4>
                <p>¿Cuántos asientos necesitas para tu viaje?</p>
            </div>

            <div class="seats-selector">
                <label for="cantidad_puestos" class="seats-label">
                    <i class="fas fa-users me-2"></i>Número de pasajeros
                </label>
                <input 
                    type="number" 
                    name="cantidad_puestos" 
                    id="cantidad_puestos" 
                    class="seats-input" 
                    min="1" 
                    max="{{ $viaje->puestos_disponibles }}" 
                    value="1" 
                    required
                    oninput="updatePrice()"
                    onchange="updatePrice()"
                >
                <div class="seats-info">
                    <span>Mínimo: 1 pasajero</span>
                    <span>Máximo: {{ $viaje->puestos_disponibles }} pasajeros</span>
                </div>
            </div>

            <div class="price-summary">
                <div class="price-label">Total estimado</div>
                <p class="price-amount" id="totalPrice">
                    ${{ number_format($viaje->valor_persona, 0, ',', '.') }}
                </p>
                <div class="price-per-person" id="priceBreakdown">
                    1 persona × ${{ number_format($viaje->valor_persona, 0, ',', '.') }}
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('pasajero.viajes.disponibles') }}" class="btn-custom secondary">
                    <i class="fas fa-arrow-left"></i>
                    Cancelar
                </a>
                <button type="button" class="btn-custom primary" onclick="mostrarResumen()">
                    <i class="fas fa-eye"></i>
                    Ver Resumen
                </button>
            </div>
        </div>

        <!-- Summary Section (Hidden initially) -->
        <div class="summary-section" id="summarySection">
            <div class="summary-card">
                <div class="summary-header">
                    <div class="icon">🎉</div>
                    <h4>¡Todo listo para viajar!</h4>
                    <p style="color: rgba(58, 58, 58, 0.7); margin: 0;">
                        Revisa los detalles finales de tu reserva
                    </p>
                </div>

                <div class="summary-details-list">
                    <div class="summary-item">
                        <span class="summary-item-label">
                            <i class="fas fa-map-marker-alt me-2"></i>Origen
                        </span>
                        <span class="summary-item-value">{{ explode(',', $viaje->origen_direccion)[0] }}</span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-item-label">
                            <i class="fas fa-flag-checkered me-2"></i>Destino
                        </span>
                        <span class="summary-item-value">{{ explode(',', $viaje->destino_direccion)[0] }}</span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-item-label">
                            <i class="fas fa-calendar me-2"></i>Fecha
                        </span>
                        <span class="summary-item-value">{{ \Carbon\Carbon::parse($viaje->fecha_salida)->format('d/m/Y') }}</span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-item-label">
                            <i class="fas fa-clock me-2"></i>Hora
                        </span>
                        <span class="summary-item-value">{{ $viaje->hora_salida }}</span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-item-label">
                            <i class="fas fa-user me-2"></i>Conductor
                        </span>
                        <span class="summary-item-value">{{ $viaje->conductor->name ?? 'No disponible' }}</span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-item-label">
                            <i class="fas fa-users me-2"></i>Pasajeros
                        </span>
                        <span class="summary-item-value" id="summaryPasajeros">1 pasajero</span>
                    </div>
                </div>

                <div class="total-section">
                    <div class="total-label">
                        <i class="fas fa-calculator me-2"></i>Total a pagar
                    </div>
                    <p class="total-amount" id="summaryTotal">
                        ${{ number_format($viaje->valor_persona, 0, ',', '.') }}
                    </p>
                    <div class="total-breakdown" id="summaryBreakdown">
                        1 persona × ${{ number_format($viaje->valor_persona, 0, ',', '.') }}
                    </div>
                </div>

                <form id="form-confirmar-reserva" action="{{ route('pasajero.reservar', $viaje->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="cantidad_puestos" id="cantidad_puestos_hidden" value="1">
                    <input type="hidden" name="valor_cobrado" value="{{ $viaje->valor_persona }}">
                    <input type="hidden" name="total" id="total_hidden" value="{{ $viaje->valor_persona }}">
                    <input type="hidden" name="viaje_id" value="{{ $viaje->id }}">

                    <div class="form-actions">
                        <button type="button" class="btn-custom secondary" onclick="ocultarResumen()">
                            <i class="fas fa-arrow-left"></i>
                            Modificar
                        </button>
                        <button type="button" class="btn-custom success" onclick="confirmarReserva()">
                            <i class="fas fa-check-circle"></i>
                            Confirmar Reserva
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script>
// Datos del viaje
const VIAJE_DATA = {
    precio: {{ $viaje->valor_persona ?? 0 }},
    puestos_max: {{ $viaje->puestos_disponibles ?? 1 }}
};

// Actualizar precio
function updatePrice() {
    const cantidadInput = document.getElementById('cantidad_puestos');
    let cantidad = parseInt(cantidadInput.value) || 1;
    
    // Validar límites
    if (cantidad > VIAJE_DATA.puestos_max) {
        cantidad = VIAJE_DATA.puestos_max;
        cantidadInput.value = cantidad;
    }
    if (cantidad < 1) {
        cantidad = 1;
        cantidadInput.value = cantidad;
    }
    
    const total = cantidad * VIAJE_DATA.precio;
    
    // Actualizar precio en la pantalla principal
    document.getElementById('totalPrice').textContent = '$' + total.toLocaleString('es-CO');
    document.getElementById('priceBreakdown').textContent = 
        cantidad + ' persona' + (cantidad > 1 ? 's' : '') + ' × $' + VIAJE_DATA.precio.toLocaleString('es-CO');
}

// Mostrar resumen
function mostrarResumen() {
    const cantidad = parseInt(document.getElementById('cantidad_puestos').value);
    const total = cantidad * VIAJE_DATA.precio;
    
    // Actualizar valores hidden
    document.getElementById('cantidad_puestos_hidden').value = cantidad;
    document.getElementById('total_hidden').value = total;
    
    // Actualizar resumen
    document.getElementById('summaryPasajeros').textContent = cantidad + ' pasajero' + (cantidad > 1 ? 's' : '');
    document.getElementById('summaryTotal').textContent = '$' + total.toLocaleString('es-CO');
    document.getElementById('summaryBreakdown').textContent = 
        cantidad + ' persona' + (cantidad > 1 ? 's' : '') + ' × $' + VIAJE_DATA.precio.toLocaleString('es-CO');
    
    // Mostrar sección resumen
    document.getElementById('summarySection').classList.add('show');
    
    // Actualizar steps
    document.getElementById('step1').classList.add('completed');
    document.getElementById('step2').classList.add('active');
    
    // Scroll suave al resumen
    setTimeout(() => {
        document.getElementById('summarySection').scrollIntoView({ 
            behavior: 'smooth', 
            block: 'start' 
        });
    }, 100);
}

// Ocultar resumen
function ocultarResumen() {
    document.getElementById('summarySection').classList.remove('show');
    document.getElementById('step1').classList.remove('completed');
    document.getElementById('step2').classList.remove('active');
    
    // Scroll de vuelta al formulario
    document.querySelector('.booking-form').scrollIntoView({ 
        behavior: 'smooth', 
        block: 'start' 
    });
}

// Confirmar reserva - Mostrar opciones de pago
function confirmarReserva() {
    // Actualizar step 3
    document.getElementById('step2').classList.add('completed');
    document.getElementById('step3').classList.add('active');

    // Modal de selección de método de pago
    const modal = document.createElement('div');
    modal.id = 'modalMetodoPago';
    modal.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.6);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        backdrop-filter: blur(5px);
        animation: fadeIn 0.3s ease;
    `;

    modal.innerHTML = `
        <div style="
            background: white;
            padding: 0;
            border-radius: 16px;
            max-width: 380px;
            width: 90%;
            margin: 1rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            animation: slideUp 0.3s ease;
            overflow: hidden;
        ">
            <!-- Header -->
            <div style="
                background: linear-gradient(135deg, #1F4E79 0%, rgba(31, 78, 121, 0.9) 100%);
                padding: 1.2rem;
                text-align: center;
                color: white;
            ">
                <div style="font-size: 2rem; margin-bottom: 0.3rem;">💳</div>
                <h3 style="margin: 0; font-size: 1.1rem; font-weight: 600;">Método de pago</h3>
            </div>

            <!-- Body -->
            <div style="padding: 1.2rem;">
                <!-- Total -->
                <div style="
                    background: rgba(76, 175, 80, 0.08);
                    border: 1px solid rgba(76, 175, 80, 0.2);
                    border-radius: 8px;
                    padding: 0.8rem;
                    text-align: center;
                    margin-bottom: 1rem;
                ">
                    <div style="color: #666; font-size: 0.75rem; margin-bottom: 0.2rem;">Total</div>
                    <div style="font-size: 1.3rem; font-weight: 700; color: #4CAF50;">
                        $${document.getElementById('summaryTotal').textContent.replace('$', '')}
                    </div>
                </div>

                <!-- Opciones -->
                <div style="display: grid; gap: 0.6rem; margin-bottom: 1rem;">
                    <!-- UalaBis DESHABILITADO -->
                    <button disabled style="
                        background: #f5f5f5;
                        border: 2px solid #ccc;
                        border-radius: 8px;
                        padding: 0.8rem;
                        cursor: not-allowed;
                        display: flex;
                        align-items: center;
                        gap: 0.7rem;
                        text-align: left;
                        opacity: 0.6;
                    ">
                        <div style="
                            width: 40px;
                            height: 40px;
                            background: #ccc;
                            border-radius: 8px;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            font-size: 1.2rem;
                            flex-shrink: 0;
                        ">💳</div>
                        <div style="flex: 1;">
                            <div style="font-weight: 600; color: #999; font-size: 0.95rem;">UalaBis</div>
                            <div style="font-size: 0.7rem; color: #aaa;">No disponible</div>
                        </div>
                    </button>

                    <!-- Transferencia -->
                    <button onclick="seleccionarTransferencia()" style="
                        background: white;
                        border: 2px solid #4CAF50;
                        border-radius: 8px;
                        padding: 0.8rem;
                        cursor: pointer;
                        transition: all 0.2s ease;
                        display: flex;
                        align-items: center;
                        gap: 0.7rem;
                        text-align: left;
                    " onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(76, 175, 80, 0.2)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                        <div style="
                            width: 40px;
                            height: 40px;
                            background: linear-gradient(135deg, #4CAF50, #66BB6A);
                            border-radius: 8px;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            font-size: 1.2rem;
                            flex-shrink: 0;
                        ">🏦</div>
                        <div style="flex: 1;">
                            <div style="font-weight: 600; color: #1F4E79; font-size: 0.95rem;">Transferencia</div>
                            <div style="font-size: 0.7rem; color: #999;">Subir comprobante</div>
                        </div>
                    </button>
                </div>

                <!-- Cancelar -->
                <button onclick="cerrarModalPago()" style="
                    width: 100%;
                    background: transparent;
                    border: none;
                    padding: 0.6rem;
                    color: #999;
                    font-size: 0.85rem;
                    cursor: pointer;
                    transition: color 0.2s;
                " onmouseover="this.style.color='#666';" onmouseout="this.style.color='#999';">
                    Cancelar
                </button>
            </div>
        </div>
    `;

    document.body.appendChild(modal);
}

// Cerrar modal de pago
function cerrarModalPago() {
    const modal = document.getElementById('modalMetodoPago');
    if (modal) {
        modal.remove();
    }
    // Revertir steps
    document.getElementById('step2').classList.remove('completed');
    document.getElementById('step3').classList.remove('active');
}

// Seleccionar UalaBis
function seleccionarUalaBis() {
    // Agregar método de pago al formulario
    const form = document.getElementById('form-confirmar-reserva');
    let metodoPagoInput = document.getElementById('metodo_pago');
    if (!metodoPagoInput) {
        metodoPagoInput = document.createElement('input');
        metodoPagoInput.type = 'hidden';
        metodoPagoInput.name = 'metodo_pago';
        metodoPagoInput.id = 'metodo_pago';
        form.appendChild(metodoPagoInput);
    }
    metodoPagoInput.value = 'ualabis';

    // Cerrar modal
    cerrarModalPago();

    // Mostrar confirmación y enviar
    mostrarConfirmacionFinal('UalaBis');
}

// Seleccionar Transferencia Manual
function seleccionarTransferencia() {
    cerrarModalPago();
    mostrarModalTransferencia();
}

// Mostrar modal de transferencia
function mostrarModalTransferencia() {
    const modal = document.createElement('div');
    modal.id = 'modalTransferencia';
    modal.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.6);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        backdrop-filter: blur(5px);
        animation: fadeIn 0.3s ease;
        overflow-y: auto;
        padding: 1rem 0;
    `;

    modal.innerHTML = `
        <div style="
            background: white;
            padding: 0;
            border-radius: 12px;
            max-width: 380px;
            width: 90%;
            margin: auto;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            animation: slideUp 0.3s ease;
            overflow: hidden;
            max-height: 95vh;
            display: flex;
            flex-direction: column;
        ">
            <!-- Header -->
            <div style="
                background: linear-gradient(135deg, #4CAF50 0%, rgba(76, 175, 80, 0.9) 100%);
                padding: 1rem;
                text-align: center;
                color: white;
                flex-shrink: 0;
            ">
                <div style="font-size: 1.8rem; margin-bottom: 0.2rem;">🏦</div>
                <h3 style="margin: 0; font-size: 1rem; font-weight: 600;">Transferencia</h3>
            </div>

            <!-- Body Scrollable -->
            <div style="padding: 1rem; overflow-y: auto; flex: 1;">
                <!-- Alerta -->
                <div style="
                    background: #FFF3CD;
                    border-left: 3px solid #FFC107;
                    border-radius: 6px;
                    padding: 0.6rem;
                    margin-bottom: 0.8rem;
                    font-size: 0.75rem;
                ">
                    <div style="font-weight: 600; color: #F57C00; margin-bottom: 0.2rem;">⏰ 1 hora para subir</div>
                    <div style="color: #856404; line-height: 1.3;">Si no subes el comprobante, tu reserva quedará disponible.</div>
                </div>

                <!-- Datos bancarios -->
                <div style="
                    background: rgba(31, 78, 121, 0.05);
                    border: 1px solid rgba(31, 78, 121, 0.15);
                    border-radius: 8px;
                    padding: 0.8rem;
                    margin-bottom: 0.8rem;
                    font-size: 0.8rem;
                ">
                    <div style="font-weight: 600; color: #1F4E79; margin-bottom: 0.5rem; text-align: center; font-size: 0.85rem;">📋 Datos bancarios</div>
                    <div style="color: #666; line-height: 1.6;">
                        <div><strong>Banco:</strong> Banco Ejemplo</div>
                        <div><strong>Titular:</strong> VoyConvos SRL</div>
                        <div><strong>CBU:</strong> 0000...456789</div>
                        <div><strong>Alias:</strong> VOYCONVOS.PAGOS</div>
                        <div style="margin-top: 0.5rem; padding-top: 0.5rem; border-top: 1px solid rgba(31, 78, 121, 0.1);">
                            <strong>Monto:</strong> <span style="color: #4CAF50; font-weight: 700;">${document.getElementById('summaryTotal').textContent}</span>
                        </div>
                    </div>
                </div>

                <!-- Upload -->
                <div style="margin-bottom: 0.8rem;">
                    <label style="
                        display: block;
                        font-weight: 600;
                        color: #1F4E79;
                        margin-bottom: 0.4rem;
                        font-size: 0.85rem;
                    ">
                        📎 Subir comprobante
                    </label>
                    <input type="file"
                           id="comprobanteInput"
                           accept="image/*,.pdf"
                           style="
                               width: 100%;
                               padding: 0.6rem;
                               border: 2px dashed rgba(76, 175, 80, 0.3);
                               border-radius: 6px;
                               background: rgba(76, 175, 80, 0.05);
                               cursor: pointer;
                               font-size: 0.75rem;
                           "
                           onchange="previsualizarComprobante(this)">
                    <div style="font-size: 0.7rem; color: #999; margin-top: 0.3rem;">
                        JPG, PNG, PDF (máx 5MB)
                    </div>
                    <div id="previewComprobante" style="margin-top: 0.5rem; display: none;"></div>
                </div>

                <!-- Botones -->
                <div style="display: grid; gap: 0.5rem;">
                    <!-- Subir ahora -->
                    <button onclick="subirComprobanteAhora()" id="btnSubirAhora" disabled style="
                        background: linear-gradient(135deg, #4CAF50, rgba(76, 175, 80, 0.9));
                        color: white;
                        border: none;
                        border-radius: 6px;
                        padding: 0.7rem;
                        font-weight: 600;
                        cursor: not-allowed;
                        transition: all 0.2s ease;
                        opacity: 0.5;
                        font-size: 0.85rem;
                    ">
                        📤 Subir ahora
                    </button>

                    <!-- Subir después -->
                    <button onclick="subirComprobanteDespues()" style="
                        background: white;
                        color: #1F4E79;
                        border: 2px solid #1F4E79;
                        border-radius: 6px;
                        padding: 0.7rem;
                        font-weight: 600;
                        cursor: pointer;
                        transition: all 0.2s ease;
                        font-size: 0.85rem;
                    " onmouseover="this.style.background='#1F4E79'; this.style.color='white';" onmouseout="this.style.background='white'; this.style.color='#1F4E79';">
                        ⏰ Lo subiré después
                    </button>

                    <!-- Volver -->
                    <button onclick="volverAMetodosPago()" style="
                        background: transparent;
                        border: none;
                        padding: 0.5rem;
                        color: #999;
                        font-size: 0.8rem;
                        cursor: pointer;
                        transition: color 0.2s;
                    " onmouseover="this.style.color='#666';" onmouseout="this.style.color='#999';">
                        ← Volver
                    </button>
                </div>
            </div>
        </div>
    `;

    document.body.appendChild(modal);
}

// Previsualizar comprobante
function previsualizarComprobante(input) {
    const preview = document.getElementById('previewComprobante');
    const btnSubir = document.getElementById('btnSubirAhora');

    if (input.files && input.files[0]) {
        const file = input.files[0];
        const reader = new FileReader();

        // Validar tamaño (5MB)
        if (file.size > 5 * 1024 * 1024) {
            alert('El archivo es muy grande. Máximo 5MB.');
            input.value = '';
            return;
        }

        reader.onload = function(e) {
            preview.style.display = 'block';
            if (file.type.includes('pdf')) {
                preview.innerHTML = `
                    <div style="
                        background: rgba(31, 78, 121, 0.05);
                        border: 1px solid rgba(31, 78, 121, 0.2);
                        border-radius: 8px;
                        padding: 1rem;
                        display: flex;
                        align-items: center;
                        gap: 1rem;
                    ">
                        <i class="fas fa-file-pdf" style="font-size: 2rem; color: #F44336;"></i>
                        <div style="flex: 1;">
                            <div style="font-weight: 600; color: #1F4E79;">${file.name}</div>
                            <div style="font-size: 0.8rem; color: #666;">${(file.size / 1024).toFixed(2)} KB</div>
                        </div>
                        <i class="fas fa-check-circle" style="color: #4CAF50; font-size: 1.5rem;"></i>
                    </div>
                `;
            } else {
                preview.innerHTML = `
                    <img src="${e.target.result}" style="
                        width: 100%;
                        max-height: 200px;
                        object-fit: contain;
                        border-radius: 8px;
                        border: 2px solid rgba(76, 175, 80, 0.3);
                    ">
                `;
            }

            // Habilitar botón
            btnSubir.disabled = false;
            btnSubir.style.cursor = 'pointer';
            btnSubir.style.opacity = '1';
            btnSubir.onmouseover = function() {
                this.style.transform = 'translateY(-2px)';
                this.style.boxShadow = '0 6px 20px rgba(76, 175, 80, 0.3)';
            };
            btnSubir.onmouseout = function() {
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = 'none';
            };
        };

        reader.readAsDataURL(file);
    }
}

// Subir comprobante ahora
async function subirComprobanteAhora() {
    const fileInput = document.getElementById('comprobanteInput');
    if (!fileInput.files || !fileInput.files[0]) {
        alert('Por favor selecciona un comprobante');
        return;
    }

    // Deshabilitar botón para evitar múltiples envíos
    const btnSubir = document.getElementById('btnSubirAhora');
    btnSubir.disabled = true;
    btnSubir.textContent = '⏳ Subiendo...';

    try {
        // Crear FormData con todos los datos del formulario
        const form = document.getElementById('form-confirmar-reserva');
        const formData = new FormData(form);

        // Agregar el archivo del comprobante
        formData.append('comprobante_pago', fileInput.files[0]);

        // Agregar metodo de pago
        formData.append('metodo_pago', 'transferencia');

        // Agregar indicador de subida inmediata
        formData.append('subir_ahora', '1');

        // Obtener token CSRF
        const csrfToken = document.querySelector('input[name="_token"]').value;

        // Enviar con AJAX
        const response = await fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        if (response.ok) {
            const result = await response.json();
            cerrarModalTransferencia();
            mostrarConfirmacionFinal('Transferencia', true);

            // Redirigir después de mostrar mensaje
            setTimeout(() => {
                window.location.href = result.redirect || '{{ route("pasajero.dashboard") }}';
            }, 2000);
        } else {
            throw new Error('Error al procesar la solicitud');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Hubo un error al subir el comprobante. Por favor, intenta nuevamente.');
        btnSubir.disabled = false;
        btnSubir.textContent = '📤 Subir ahora';
    }
}

// Subir comprobante después
function subirComprobanteDespues() {
    const form = document.getElementById('form-confirmar-reserva');
    let metodoPagoInput = document.getElementById('metodo_pago');
    if (!metodoPagoInput) {
        metodoPagoInput = document.createElement('input');
        metodoPagoInput.type = 'hidden';
        metodoPagoInput.name = 'metodo_pago';
        metodoPagoInput.id = 'metodo_pago';
        form.appendChild(metodoPagoInput);
    }
    metodoPagoInput.value = 'transferencia';

    let subirDespuesInput = document.getElementById('subir_despues');
    if (!subirDespuesInput) {
        subirDespuesInput = document.createElement('input');
        subirDespuesInput.type = 'hidden';
        subirDespuesInput.name = 'subir_despues';
        subirDespuesInput.id = 'subir_despues';
        form.appendChild(subirDespuesInput);
    }
    subirDespuesInput.value = '1';

    cerrarModalTransferencia();
    mostrarConfirmacionFinal('Transferencia', false);
}

// Cerrar modal transferencia
function cerrarModalTransferencia() {
    const modal = document.getElementById('modalTransferencia');
    if (modal) {
        modal.remove();
    }
}

// Volver a métodos de pago
function volverAMetodosPago() {
    cerrarModalTransferencia();
    confirmarReserva();
}

// Mostrar confirmación final
function mostrarConfirmacionFinal(metodoPago, subirAhora = false) {
    let mensaje = '';
    let icono = '';

    if (metodoPago === 'UalaBis') {
        icono = '💳';
        mensaje = 'Serás redirigido a UalaBis para completar el pago de forma segura.';
    } else if (metodoPago === 'Transferencia') {
        if (subirAhora) {
            icono = '✅';
            mensaje = 'Tu comprobante ha sido recibido. Nuestro equipo verificará el pago pronto.';
        } else {
            icono = '⏰';
            mensaje = 'Tienes 1 hora para subir el comprobante. Recibirás un email con los datos bancarios.';
        }
    }

    const modal = document.createElement('div');
    modal.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.6);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10000;
        backdrop-filter: blur(5px);
        animation: fadeIn 0.3s ease;
    `;

    modal.innerHTML = `
        <div style="
            background: white;
            padding: 2rem;
            border-radius: 16px;
            text-align: center;
            max-width: 450px;
            width: 90%;
            margin: 1rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: slideUp 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        ">
            <div style="font-size: 4rem; margin-bottom: 1rem;">${icono}</div>
            <h3 style="color: #1F4E79; margin-bottom: 1rem; font-size: 1.4rem;">¡Reserva Confirmada!</h3>
            <p style="color: #666; margin-bottom: 2rem; line-height: 1.6;">${mensaje}</p>
            <button onclick="this.parentElement.parentElement.remove(); document.getElementById('form-confirmar-reserva').submit();"
                    style="
                        background: linear-gradient(135deg, #4CAF50, rgba(76, 175, 80, 0.9));
                        color: white;
                        border: none;
                        padding: 1rem 2.5rem;
                        border-radius: 25px;
                        font-weight: 600;
                        font-size: 1rem;
                        cursor: pointer;
                        transition: all 0.3s ease;
                        box-shadow: 0 4px 12px rgba(76, 175, 80, 0.3);
                    "
                    onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 20px rgba(76, 175, 80, 0.4)';"
                    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(76, 175, 80, 0.3)';">
                <i class="fas fa-arrow-right me-2"></i>Continuar
            </button>
        </div>
    `;

    document.body.appendChild(modal);
}

// Configurar al cargar
document.addEventListener('DOMContentLoaded', function() {
    updatePrice();
});
</script>

<!-- Google Maps Script -->
<script>
// Datos del viaje para el mapa
const datosViaje = {
    origen: {
        lat: parseFloat("{{ $viaje->origen_lat ?? '-34.6037' }}") || -34.6037,
        lng: parseFloat("{{ $viaje->origen_lng ?? '-58.3816' }}") || -58.3816
    },
    destino: {
        lat: parseFloat("{{ $viaje->destino_lat ?? '-34.6158' }}") || -34.6158,
        lng: parseFloat("{{ $viaje->destino_lng ?? '-58.5033' }}") || -58.5033
    },
    origenDireccion: "{{ $viaje->origen_direccion ?? 'Origen' }}",
    destinoDireccion: "{{ $viaje->destino_direccion ?? 'Destino' }}"
};

let mapaInicializado = false;

// Función de callback para Google Maps
function initMapaCallback() {
    if (mapaInicializado) {
        console.log('Mapa ya inicializado');
        return;
    }

    console.log('Inicializando mapa con datos:', datosViaje);

    try {
        const mapElement = document.getElementById('map');
        if (!mapElement) {
            console.error('Elemento #map no encontrado');
            return;
        }

        // Crear el mapa
        const map = new google.maps.Map(mapElement, {
            zoom: 11,
            center: datosViaje.origen,
            mapTypeId: 'roadmap',
            styles: [
                {
                    featureType: 'poi',
                    elementType: 'labels',
                    stylers: [{ visibility: 'off' }]
                }
            ]
        });

        // Marcador de origen (verde)
        const markerOrigen = new google.maps.Marker({
            position: datosViaje.origen,
            map: map,
            title: 'Origen: ' + datosViaje.origenDireccion,
            icon: {
                url: 'https://maps.google.com/mapfiles/ms/icons/green-dot.png',
                scaledSize: new google.maps.Size(40, 40)
            },
            animation: google.maps.Animation.DROP
        });

        // Info window para origen
        const infoOrigen = new google.maps.InfoWindow({
            content: '<div style="padding: 10px;"><strong>Origen</strong><br>' +
                     datosViaje.origenDireccion.split(',').slice(0, 2).join(',') + '</div>'
        });

        markerOrigen.addListener('click', function() {
            infoOrigen.open(map, markerOrigen);
        });

        // Marcador de destino (rojo)
        const markerDestino = new google.maps.Marker({
            position: datosViaje.destino,
            map: map,
            title: 'Destino: ' + datosViaje.destinoDireccion,
            icon: {
                url: 'https://maps.google.com/mapfiles/ms/icons/red-dot.png',
                scaledSize: new google.maps.Size(40, 40)
            },
            animation: google.maps.Animation.DROP
        });

        // Info window para destino
        const infoDestino = new google.maps.InfoWindow({
            content: '<div style="padding: 10px;"><strong>Destino</strong><br>' +
                     datosViaje.destinoDireccion.split(',').slice(0, 2).join(',') + '</div>'
        });

        markerDestino.addListener('click', function() {
            infoDestino.open(map, markerDestino);
        });

        // Ajustar límites para mostrar ambos puntos
        const bounds = new google.maps.LatLngBounds();
        bounds.extend(datosViaje.origen);
        bounds.extend(datosViaje.destino);
        map.fitBounds(bounds);

        // Agregar un pequeño padding
        setTimeout(() => {
            map.panBy(0, -50);
        }, 500);

        // Dibujar la ruta
        const directionsService = new google.maps.DirectionsService();
        const directionsRenderer = new google.maps.DirectionsRenderer({
            suppressMarkers: true, // No mostrar marcadores por defecto de la ruta
            polylineOptions: {
                strokeColor: '#1F4E79',
                strokeWeight: 5,
                strokeOpacity: 0.8
            }
        });

        directionsRenderer.setMap(map);

        // Solicitar la ruta
        directionsService.route({
            origin: datosViaje.origen,
            destination: datosViaje.destino,
            travelMode: google.maps.TravelMode.DRIVING,
            drivingOptions: {
                departureTime: new Date(),
                trafficModel: 'bestguess'
            }
        }, function(response, status) {
            if (status === 'OK') {
                console.log('Ruta cargada exitosamente');
                directionsRenderer.setDirections(response);

                // Obtener información de la ruta
                const route = response.routes[0];
                if (route && route.legs && route.legs[0]) {
                    const leg = route.legs[0];
                    console.log('Distancia:', leg.distance.text);
                    console.log('Duración:', leg.duration.text);
                }
            } else {
                console.error('Error al cargar la ruta:', status);
                // Si falla la ruta, al menos mostramos los marcadores
                showError('No se pudo calcular la ruta. Mostrando ubicaciones.');
            }
        });

        mapaInicializado = true;
        console.log('Mapa inicializado correctamente');

    } catch (error) {
        console.error("Error al crear el mapa:", error);
        showError('Error al cargar el mapa: ' + error.message);
    }
}

// Función para mostrar errores en el mapa
function showError(message) {
    const mapElement = document.getElementById('map');
    if (mapElement) {
        mapElement.innerHTML = `
            <div style="display: flex; justify-content: center; align-items: center; height: 100%; background: #fee; border-radius: 12px;">
                <div style="text-align: center; color: #c00; padding: 20px;">
                    <i class="fas fa-exclamation-triangle" style="font-size: 2rem; margin-bottom: 1rem;"></i>
                    <p>${message}</p>
                </div>
            </div>
        `;
    }
}

// Inicializar el loader cuando cargue el DOM
document.addEventListener('DOMContentLoaded', function() {
    const mapElement = document.getElementById('map');
    if (mapElement) {
        mapElement.innerHTML = `
            <div style="display: flex; justify-content: center; align-items: center; height: 100%; background: #f8f9fa; border-radius: 12px;">
                <div style="text-align: center; color: #4285f4;">
                    <i class="fas fa-spinner fa-spin" style="font-size: 2rem; margin-bottom: 1rem;"></i>
                    <p style="margin: 0; color: #666;">Cargando mapa de Google...</p>
                </div>
            </div>
        `;
    }

    // Log para debug
    console.log('DOM Cargado, esperando Google Maps API...');
});

// Cargar la API de Google Maps si no está cargada
window.addEventListener('load', function() {
    // Verificar si Google Maps ya está cargado
    if (typeof google !== 'undefined' && google.maps) {
        console.log('Google Maps ya está cargado');
        initMapaCallback();
    } else {
        console.log('Cargando Google Maps API...');

        // Crear script dinámicamente
        const script = document.createElement('script');
        script.src = 'https://maps.googleapis.com/maps/api/js?key={{ config("services.google_maps.key") }}&callback=initMapaCallback&libraries=places';
        script.async = true;
        script.defer = true;
        script.onerror = function() {
            console.error('Error al cargar Google Maps API');
            showError('No se pudo cargar Google Maps. Verifica tu conexión o la API key.');
        };
        document.head.appendChild(script);
    }
});
</script>

<style>
@keyframes slideUp {
    from {
        transform: translateY(50px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}
</style>

@endsection