@extends('layouts.app')

@section('title', '¡Pago Exitoso!')

@section('content')
<div class="pago-exitoso">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">
                
                <!-- Header de éxito -->
                <div class="success-header text-center mb-4">
                    <div class="success-icon mb-3">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h1 class="title mb-2">¡Pago Exitoso!</h1>
                    <p class="subtitle">Tu reserva ha sido confirmada correctamente</p>
                </div>

                <!-- Card principal -->
                <div class="card main-card mb-4">
                    <div class="card-body">
                        
                        <!-- Información del viaje -->
                        <div class="section mb-4">
                            <h5 class="section-title">
                                <i class="fas fa-route me-2"></i>
                                Detalles del Viaje
                            </h5>
                            <div class="info-grid">
                                <div class="info-item">
                                    <span class="label">Origen</span>
                                    <span class="value">{{ $reserva->viaje->origen }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="label">Destino</span>
                                    <span class="value">{{ $reserva->viaje->destino }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="label">Fecha</span>
                                    <span class="value">{{ \Carbon\Carbon::parse($reserva->viaje->fecha_salida)->format('d/m/Y') }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="label">Hora</span>
                                    <span class="value">{{ \Carbon\Carbon::parse($reserva->viaje->hora_salida)->format('H:i') }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Información de la reserva -->
                        <div class="section mb-4">
                            <h5 class="section-title">
                                <i class="fas fa-ticket-alt me-2"></i>
                                Tu Reserva
                            </h5>
                            <div class="info-grid">
                                <div class="info-item">
                                    <span class="label">ID de Reserva</span>
                                    <span class="value">#{{ $reserva->id }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="label">Puestos</span>
                                    <span class="value">{{ $reserva->cantidad_puestos }} {{ $reserva->cantidad_puestos == 1 ? 'puesto' : 'puestos' }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="label">Total Pagado</span>
                                    <span class="value total-amount">${{ number_format($reserva->total, 0, ',', '.') }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="label">Estado</span>
                                    <span class="badge status-badge">{{ ucfirst($reserva->estado) }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Información del conductor -->
                        @if($reserva->viaje->conductor)
                        <div class="section mb-4">
                            <h5 class="section-title">
                                <i class="fas fa-user-tie me-2"></i>
                                Tu Conductor
                            </h5>
                            <div class="conductor-info">
                                <div class="conductor-avatar">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="conductor-details">
                                    <h6 class="conductor-name">{{ $reserva->viaje->conductor->name }}</h6>
                                    <p class="conductor-note">Se contactará contigo antes del viaje</p>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Botones de acción -->
                        <div class="action-buttons">
                            <a href="{{ route('pasajero.dashboard') }}" class="btn btn-primary">
                                <i class="fas fa-list me-2"></i>
                                Mis Reservas
                            </a>
                            <a href="{{ route('pasajero.viajes.disponibles') }}" class="btn btn-outline">
                                <i class="fas fa-search me-2"></i>
                                Buscar Más Viajes
                            </a>
                        </div>

                    </div>
                </div>

                <!-- Información adicional -->
                <div class="info-box">
                    <div class="info-box-header">
                        <i class="fas fa-info-circle me-2"></i>
                        ¿Qué sigue ahora?
                    </div>
                    <ul class="info-list">
                        <li>Recibirás un email de confirmación</li>
                        <li>El conductor se contactará contigo 24h antes del viaje</li>
                        <li>Puedes ver el estado en "Mis Reservas"</li>
                        <li>Para cancelar, hazlo con 24h de anticipación</li>
                    </ul>
                </div>

                <!-- Footer -->
                <div class="footer-note text-center mt-4">
                    <p class="mb-1">
                        <i class="fas fa-shield-alt me-2"></i>
                        Tu pago está protegido por Mercado Pago
                    </p>
                    <small>Gracias por elegir VoyConVos</small>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
:root {
    --color-principal: #1F4E79;
    --color-azul-claro: #DDF2FE;
    --color-neutro-oscuro: #3A3A3A;
    --color-complementario: #4CAF50;
    --color-fondo-base: #FCFCFD;
    --color-blanco: #FFFFFF;
}

.pago-exitoso {
    background-color: var(--color-fondo-base);
    min-height: 100vh;
    padding-top: 120px; /* Espacio para el nav */
    padding-bottom: 60px;
}

/* Header de éxito */
.success-header .success-icon {
    width: 80px;
    height: 80px;
    background-color: var(--color-complementario);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    animation: fadeInScale 0.6s ease-out;
}

.success-header .success-icon i {
    font-size: 2.5rem;
    color: var(--color-blanco);
}

.success-header .title {
    color: var(--color-principal);
    font-weight: 700;
    font-size: 2rem;
}

.success-header .subtitle {
    color: var(--color-neutro-oscuro);
    margin: 0;
}

/* Card principal */
.main-card {
    background-color: var(--color-blanco);
    border: none;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(31, 78, 121, 0.08);
    transition: transform 0.3s ease;
}

.main-card:hover {
    transform: translateY(-2px);
}

/* Secciones */
.section {
    border-bottom: 1px solid var(--color-azul-claro);
    padding-bottom: 1.5rem;
}

.section:last-child {
    border-bottom: none;
    padding-bottom: 0;
}

.section-title {
    color: var(--color-principal);
    font-weight: 600;
    margin-bottom: 1rem;
    font-size: 1.1rem;
}

.section-title i {
    color: var(--color-principal);
}

/* Grid de información */
.info-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 1rem;
}

@media (min-width: 576px) {
    .info-grid {
        grid-template-columns: 1fr 1fr;
    }
}

.info-item {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.info-item .label {
    font-size: 0.875rem;
    color: var(--color-neutro-oscuro);
    opacity: 0.8;
}

.info-item .value {
    font-weight: 600;
    color: var(--color-neutro-oscuro);
}

.total-amount {
    color: var(--color-complementario) !important;
    font-size: 1.1rem;
}

/* Badge de estado */
.status-badge {
    background-color: var(--color-complementario);
    color: var(--color-blanco);
    padding: 0.375rem 0.75rem;
    border-radius: 20px;
    font-size: 0.875rem;
    font-weight: 500;
    display: inline-block;
    width: fit-content;
}

/* Información del conductor */
.conductor-info {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.conductor-avatar {
    width: 50px;
    height: 50px;
    background-color: var(--color-principal);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--color-blanco);
    font-size: 1.25rem;
}

.conductor-details .conductor-name {
    margin: 0 0 0.25rem 0;
    color: var(--color-neutro-oscuro);
    font-weight: 600;
}

.conductor-details .conductor-note {
    margin: 0;
    font-size: 0.875rem;
    color: var(--color-neutro-oscuro);
    opacity: 0.8;
}

/* Botones de acción */
.action-buttons {
    display: grid;
    grid-template-columns: 1fr;
    gap: 1rem;
    margin-top: 2rem;
}

@media (min-width: 576px) {
    .action-buttons {
        grid-template-columns: 1fr 1fr;
    }
}

.btn {
    padding: 0.875rem 1.5rem;
    border-radius: 8px;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    border: none;
}

.btn-primary {
    background-color: var(--color-principal);
    color: var(--color-blanco);
}

.btn-primary:hover {
    background-color: #1a4269;
    transform: translateY(-1px);
    color: var(--color-blanco);
}

.btn-outline {
    background-color: transparent;
    color: var(--color-principal);
    border: 2px solid var(--color-principal);
}

.btn-outline:hover {
    background-color: var(--color-principal);
    color: var(--color-blanco);
    transform: translateY(-1px);
}

/* Caja de información */
.info-box {
    background-color: var(--color-azul-claro);
    border-radius: 8px;
    padding: 1.5rem;
    margin-top: 1.5rem;
}

.info-box-header {
    color: var(--color-principal);
    font-weight: 600;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
}

.info-list {
    margin: 0;
    padding-left: 1.25rem;
    color: var(--color-neutro-oscuro);
}

.info-list li {
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
}

.info-list li:last-child {
    margin-bottom: 0;
}

/* Footer */
.footer-note {
    color: var(--color-neutro-oscuro);
    opacity: 0.8;
}

.footer-note i {
    color: var(--color-complementario);
}

/* Animaciones */
@keyframes fadeInScale {
    from {
        opacity: 0;
        transform: scale(0.8);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

/* Responsive */
@media (max-width: 575.98px) {
    .pago-exitoso {
        padding-top: 100px;
        padding-left: 1rem;
        padding-right: 1rem;
    }
    
    .success-header .title {
        font-size: 1.75rem;
    }
    
    .main-card .card-body {
        padding: 1.5rem;
    }
    
    .conductor-info {
        flex-direction: column;
        text-align: center;
        gap: 0.75rem;
    }
}

@media (min-width: 992px) {
    .pago-exitoso {
        padding-top: 140px;
    }
}
</style>
@endpush