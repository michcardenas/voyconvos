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

    .summary-wrapper {
        background: linear-gradient(135deg, #DDF2FE 0%, #FCFCFD 50%, rgba(31, 78, 121, 0.03) 100%);
        min-height: 100vh;
        padding: 2rem 0;
        position: relative;
    }

    .summary-wrapper::before {
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

    .page-header {
        background: linear-gradient(135deg, var(--vcv-accent) 0%, rgba(76, 175, 80, 0.9) 50%, rgba(31, 78, 121, 0.8) 100%);
        color: white;
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 6px 20px rgba(76, 175, 80, 0.15);
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
        background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
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
        opacity: 0.95;
        font-size: 1rem;
        position: relative;
        z-index: 2;
    }

    .alert-custom {
        border: none;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        backdrop-filter: blur(10px);
        box-shadow: 0 4px 12px rgba(220, 53, 69, 0.1);
        background: rgba(220, 53, 69, 0.1);
        border-left: 4px solid #dc3545;
    }

    .alert-custom ul {
        margin: 0;
        padding-left: 1.5rem;
    }

    .alert-custom li {
        color: #dc3545;
        font-weight: 500;
        margin-bottom: 0.5rem;
    }

    .summary-card {
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

    .summary-details {
        padding: 2rem;
    }

    .detail-item {
        display: flex;
        align-items: center;
        padding: 1rem 0;
        border-bottom: 1px solid rgba(31, 78, 121, 0.1);
        transition: all 0.3s ease;
    }

    .detail-item:last-child {
        border-bottom: none;
    }

    .detail-item:hover {
        background: rgba(221, 242, 254, 0.3);
        margin: 0 -1rem;
        padding-left: 1rem;
        padding-right: 1rem;
        border-radius: 8px;
    }

    .detail-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1.5rem;
        font-size: 1rem;
    }

    .detail-icon.route {
        background: rgba(31, 78, 121, 0.1);
        color: var(--vcv-primary);
    }

    .detail-icon.date {
        background: rgba(76, 175, 80, 0.1);
        color: var(--vcv-accent);
    }

    .detail-icon.time {
        background: rgba(255, 193, 7, 0.1);
        color: #f57c00;
    }

    .detail-icon.driver {
        background: rgba(221, 242, 254, 0.8);
        color: var(--vcv-primary);
    }

    .detail-icon.seats {
        background: rgba(156, 39, 176, 0.1);
        color: #9c27b0;
    }

    .detail-icon.total {
        background: rgba(76, 175, 80, 0.15);
        color: var(--vcv-accent);
    }

    .detail-content {
        flex: 1;
    }

    .detail-label {
        font-size: 0.85rem;
        color: rgba(58, 58, 58, 0.7);
        margin-bottom: 0.2rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .detail-value {
        color: var(--vcv-dark);
        font-weight: 600;
        font-size: 1rem;
    }

    .driver-section {
        background: rgba(31, 78, 121, 0.02);
        border-radius: 12px;
        padding: 1.5rem;
        margin: 1rem 0;
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

    .verified-badge {
        display: inline-block;
        margin-left: 0.5rem;
        color: var(--vcv-accent);
        font-size: 1rem;
    }

    .total-section {
        background: linear-gradient(135deg, rgba(76, 175, 80, 0.1), rgba(76, 175, 80, 0.05));
        border: 2px solid rgba(76, 175, 80, 0.2);
        border-radius: 16px;
        padding: 2rem;
        text-align: center;
        margin: 2rem 0;
        position: relative;
        overflow: hidden;
    }

    .total-section::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 80px;
        height: 80px;
        background: radial-gradient(circle, rgba(76, 175, 80, 0.1) 0%, transparent 70%);
        border-radius: 50%;
        transform: translate(30%, -30%);
    }

    .total-label {
        color: rgba(58, 58, 58, 0.7);
        font-size: 1rem;
        margin-bottom: 1rem;
        position: relative;
        z-index: 2;
    }

    .total-amount {
        font-size: 3rem;
        font-weight: 700;
        color: var(--vcv-accent);
        margin: 0;
        position: relative;
        z-index: 2;
    }

    .total-breakdown {
        color: rgba(58, 58, 58, 0.6);
        font-size: 0.9rem;
        margin-top: 0.5rem;
        position: relative;
        z-index: 2;
    }

    .action-section {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 16px;
        padding: 2rem;
        border: 1px solid rgba(31, 78, 121, 0.12);
        box-shadow: 0 4px 12px rgba(31, 78, 121, 0.08);
        text-align: center;
    }

    .action-header {
        margin-bottom: 2rem;
    }

    .action-header h4 {
        color: var(--vcv-primary);
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .action-header p {
        color: rgba(58, 58, 58, 0.7);
        margin: 0;
    }

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

    .confirmation-icon {
        font-size: 3rem;
        color: var(--vcv-accent);
        margin-bottom: 1rem;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }

    @media (max-width: 768px) {
        .summary-wrapper {
            padding: 1rem 0;
        }
        
        .page-header {
            padding: 1.5rem;
        }
        
        .page-header h2 {
            font-size: 1.5rem;
        }
        
        .route-city {
            font-size: 1.1rem;
            padding: 0 0.5rem;
        }
        
        .route-arrow {
            margin: 0 0.5rem;
            font-size: 1.2rem;
        }
        
        .summary-details {
            padding: 1.5rem;
        }
        
        .detail-item {
            flex-direction: column;
            text-align: center;
            padding: 1.5rem 0;
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
        
        .total-amount {
            font-size: 2.5rem;
        }
        
        .form-actions {
            flex-direction: column;
        }
        
        .btn-custom {
            width: 100%;
            margin: 0.3rem 0;
        }
    }
</style>

<div class="summary-wrapper">
    <div class="container">
        <!-- Page Header -->
        <div class="page-header">
            <i class="confirmation-icon fas fa-clipboard-check"></i>
            <h2>🧾 Resumen de la reserva</h2>
            <p class="page-subtitle">Revisa todos los detalles antes de confirmar</p>
        </div>

        <!-- Error Messages -->
        @if ($errors->any())
            <div class="alert-custom">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li><i class="fas fa-exclamation-circle me-2"></i>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Summary Card -->
        <div class="summary-card">
            <!-- Trip Header -->
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

            <!-- Summary Details -->
            <div class="summary-details">
                <!-- Origen -->
                <div class="detail-item">
                    <div class="detail-icon route">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div class="detail-content">
                        <div class="detail-label">Origen</div>
                        <div class="detail-value">{{ $viaje->origen_direccion }}</div>
                    </div>
                </div>

                <!-- Destino -->
                <div class="detail-item">
                    <div class="detail-icon route">
                        <i class="fas fa-flag-checkered"></i>
                    </div>
                    <div class="detail-content">
                        <div class="detail-label">Destino</div>
                        <div class="detail-value">{{ $viaje->destino_direccion }}</div>
                    </div>
                </div>

                <!-- Fecha -->
                <div class="detail-item">
                    <div class="detail-icon date">
                        <i class="fas fa-calendar"></i>
                    </div>
                    <div class="detail-content">
                        <div class="detail-label">Fecha de salida</div>
                        <div class="detail-value">{{ \Carbon\Carbon::parse($viaje->fecha_salida)->format('d/m/Y') }}</div>
                    </div>
                </div>

                <!-- Hora -->
                <div class="detail-item">
                    <div class="detail-icon time">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="detail-content">
                        <div class="detail-label">Hora de salida</div>
                        <div class="detail-value">{{ $viaje->hora_salida }}</div>
                    </div>
                </div>

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
                                @if($viaje->conductor && ($viaje->conductor->verificado ?? ($viaje->conductor->calificacion_promedio ?? 4.2) >= 4.5))
                                    <span class="verified-badge">
                                        <i class="fas fa-check-circle"></i>
                                    </span>
                                @endif
                            </h6>
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
                                </div>
                            @endif
                            <p style="margin: 0; color: rgba(58, 58, 58, 0.7); font-size: 0.9rem;">
                                <i class="fas fa-steering-wheel me-1"></i>Tu conductor para este viaje
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Puestos -->
                <div class="detail-item">
                    <div class="detail-icon seats">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="detail-content">
                        <div class="detail-label">Puestos a reservar</div>
                        <div class="detail-value">{{ $cantidad }} {{ $cantidad == 1 ? 'pasajero' : 'pasajeros' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Section -->
        <div class="total-section">
            <div class="total-label">
                <i class="fas fa-calculator me-2"></i>Total a pagar
            </div>
            <p class="total-amount">${{ number_format($total, 0, ',', '.') }}</p>
            <div class="total-breakdown">
                {{ $cantidad }} {{ $cantidad == 1 ? 'pasajero' : 'pasajeros' }} × ${{ number_format($total / $cantidad, 0, ',', '.') }}
            </div>
        </div>

        <!-- Action Section -->
        <div class="action-section">
            <div class="action-header">
                <h4>🚀 ¡Todo listo para viajar!</h4>
                <p>Confirma tu reserva y prepárate para una experiencia increíble</p>
            </div>

          <!-- ✅ SOLO REEMPLAZA ESTA PARTE DEL FORMULARIO - MANTÉN TODO LO DEMÁS IGUAL -->
<form id="form-confirmar-reserva" action="{{ route('pasajero.reservar', $viaje->id) }}" method="POST">
    @csrf
    <!-- ✅ Campo original que ya tenías -->
    <input type="hidden" name="cantidad_puestos" value="{{ $cantidad }}">
    
    <!-- ✅ NUEVOS CAMPOS - Solo agregar estas 3 líneas -->
    <input type="hidden" name="valor_cobrado" value="{{ $viaje->valor_persona }}">
    <input type="hidden" name="total" value="{{ $total }}">
    <input type="hidden" name="viaje_id" value="{{ $viaje->id }}">

    <div class="form-actions">
        <a href="{{ route('pasajero.confirmar.mostrar', $viaje->id) }}" class="btn-custom secondary">
            <i class="fas fa-arrow-left"></i>
            Volver
        </a>
        <button type="button" class="btn-custom success" onclick="mostrarAlertaYEnviar()">
            <i class="fas fa-check-circle"></i>
            Confirmar Reserva
        </button>
    </div>
</form>
        </div>
    </div>
</div>

<script>
function mostrarAlertaYEnviar() {
    // Crear modal personalizado en lugar de alert básico
    const modal = document.createElement('div');
    modal.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        backdrop-filter: blur(5px);
    `;
    
    modal.innerHTML = `
        <div style="
            background: white;
            padding: 2rem;
            border-radius: 16px;
            text-align: center;
            max-width: 400px;
            margin: 1rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        ">
            <div style="font-size: 3rem; color: #4CAF50; margin-bottom: 1rem;">✅</div>
            <h3 style="color: #1F4E79; margin-bottom: 1rem;">¡Reserva Confirmada!</h3>
            <p style="color: #666; margin-bottom: 2rem;">Tu reserva está en espera para configurar el pago</p>
            <button onclick="this.parentElement.parentElement.remove(); document.getElementById('form-confirmar-reserva').submit();" 
                    style="
                        background: #4CAF50;
                        color: white;
                        border: none;
                        padding: 0.8rem 2rem;
                        border-radius: 25px;
                        font-weight: 600;
                        cursor: pointer;
                        transition: all 0.3s ease;
                    "
                    onmouseover="this.style.background='#45a049'"
                    onmouseout="this.style.background='#4CAF50'">
                Continuar
            </button>
        </div>
    `;
    
    document.body.appendChild(modal);
}
</script>
@endsection