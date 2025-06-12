@extends('layouts.app')

@section('title', 'Pago Fallido')

@section('content')
<div class="pago-fallido">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">
                
                <!-- Header de error -->
                <div class="error-header text-center mb-4">
                    <div class="error-icon mb-3">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <h1 class="title mb-2">Pago No Procesado</h1>
                    <p class="subtitle">Hubo un problema con tu transacción</p>
                </div>

                <!-- Card principal -->
                <div class="card main-card mb-4">
                    <div class="card-body">
                        
                        <!-- Información del error -->
                        <div class="section mb-4">
                            <h5 class="section-title">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                ¿Qué pasó?
                            </h5>
                            <div class="error-explanation">
                                <div class="error-message">
                                    <p>El pago no pudo ser procesado correctamente. Esto puede deberse a:</p>
                                    <ul class="error-reasons">
                                        <li>Fondos insuficientes en tu cuenta</li>
                                        <li>Datos de la tarjeta incorrectos</li>
                                        <li>Problemas temporales del sistema</li>
                                        <li>Límites de transacción excedidos</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Información del viaje (si está disponible) -->
                        @if(isset($reserva) && $reserva->viaje)
                        <div class="section mb-4">
                            <h5 class="section-title">
                                <i class="fas fa-route me-2"></i>
                                Detalles del Viaje
                            </h5>
                            <div class="info-grid">
                                <div class="info-item">
                                    <span class="label">Origen</span>
                                    <span class="value">{{ $reserva->viaje->origen_direccion ?? 'No disponible' }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="label">Destino</span>
                                    <span class="value">{{ $reserva->viaje->destino_direccion ?? 'No disponible' }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="label">Fecha</span>
                                    <span class="value">{{ $reserva->viaje->fecha_salida ? \Carbon\Carbon::parse($reserva->viaje->fecha_salida)->format('d/m/Y') : 'No disponible' }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="label">Hora</span>
                                    <span class="value">{{ $reserva->viaje->hora_salida ?? 'No disponible' }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Información de la reserva fallida -->
                        <div class="section mb-4">
                            <h5 class="section-title">
                                <i class="fas fa-receipt me-2"></i>
                                Intento de Reserva
                            </h5>
                            <div class="info-grid">
                                @if(isset($reserva))
                                <div class="info-item">
                                    <span class="label">ID de Intento</span>
                                    <span class="value">#{{ $reserva->id ?? 'N/A' }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="label">Puestos Solicitados</span>
                                    <span class="value">{{ $reserva->cantidad_puestos ?? '1' }} {{ ($reserva->cantidad_puestos ?? 1) == 1 ? 'puesto' : 'puestos' }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="label">Monto a Pagar</span>
                                    <span class="value failed-amount">${{ isset($reserva->total) ? number_format($reserva->total, 0, ',', '.') : '0' }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="label">Estado</span>
                                    <span class="badge status-badge-failed">Fallido</span>
                                </div>
                                @else
                                <div class="info-item full-width">
                                    <span class="label">Estado</span>
                                    <span class="value">No se pudo procesar la transacción</span>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endif

                        <!-- Mensaje de tranquilidad -->
                        <div class="section mb-4">
                            <div class="reassurance-box">
                                <div class="reassurance-icon">
                                    <i class="fas fa-shield-alt"></i>
                                </div>
                                <div class="reassurance-content">
                                    <h6>No te preocupes</h6>
                                    <p>No se realizó ningún cargo a tu cuenta. Tu dinero está seguro.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Botones de acción -->
                        <div class="action-buttons">
                           
                            <a href="{{ route('pasajero.viajes.disponibles') }}" class="btn btn-outline">
                                <i class="fas fa-search me-2"></i>
                                Buscar Otros Viajes
                            </a>
                            <a href="{{ route('pasajero.dashboard') }}" class="btn btn-secondary">
                                <i class="fas fa-home me-2"></i>
                                Ir al Inicio
                            </a>
                        </div>

                    </div>
                </div>

                <!-- Información de ayuda -->
                <div class="help-box">
                    <div class="help-box-header">
                        <i class="fas fa-question-circle me-2"></i>
                        ¿Necesitas Ayuda?
                    </div>
                    <div class="help-content">
                        <p>Si el problema persiste, puedes:</p>
                        <ul class="help-list">
                            <li>Verificar los datos de tu tarjeta</li>
                            <li>Intentar con otro método de pago</li>
                            <li>Contactar con tu banco</li>
                            <li>Escribirnos a <a href="mailto:soporte@voyconvos.com">soporte@voyconvos.com</a></li>
                        </ul>
                    </div>
                </div>

                <!-- Footer -->
                <div class="footer-note text-center mt-4">
                    <p class="mb-1">
                        <i class="fas fa-lock me-2"></i>
                        Tus datos están protegidos con encriptación SSL
                    </p>
                    <small>Gracias por confiar en VoyConVos</small>
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
    --color-error: #dc3545;
    --color-warning: #ffc107;
    --color-fondo-base: #FCFCFD;
    --color-blanco: #FFFFFF;
    --color-gris-claro: #f8f9fa;
}

.pago-fallido {
    background-color: var(--color-fondo-base);
    min-height: 100vh;
    padding-top: 120px; /* Espacio para el nav */
    padding-bottom: 60px;
}

/* Header de error */
.error-header .error-icon {
    width: 80px;
    height: 80px;
    background-color: var(--color-error);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    animation: fadeInScale 0.6s ease-out;
}

.error-header .error-icon i {
    font-size: 2.5rem;
    color: var(--color-blanco);
}

.error-header .title {
    color: var(--color-error);
    font-weight: 700;
    font-size: 2rem;
}

.error-header .subtitle {
    color: var(--color-neutro-oscuro);
    margin: 0;
}

/* Card principal */
.main-card {
    background-color: var(--color-blanco);
    border: none;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(220, 53, 69, 0.08);
    transition: transform 0.3s ease;
    padding: 50px;
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

/* Explicación del error */
.error-explanation {
    background-color: #fff3cd;
    border: 1px solid #ffeaa7;
    border-radius: 8px;
    padding: 1.5rem;
}

.error-message p {
    color: var(--color-neutro-oscuro);
    margin-bottom: 1rem;
    font-weight: 500;
}

.error-reasons {
    margin: 0;
    padding-left: 1.25rem;
    color: var(--color-neutro-oscuro);
}

.error-reasons li {
    margin-bottom: 0.5rem;
    font-size: 0.95rem;
}

.error-reasons li:last-child {
    margin-bottom: 0;
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

.info-item.full-width {
    grid-column: 1 / -1;
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

.failed-amount {
    color: var(--color-error) !important;
    font-size: 1.1rem;
}

/* Badge de estado fallido */
.status-badge-failed {
    background-color: var(--color-error);
    color: var(--color-blanco);
    padding: 0.375rem 0.75rem;
    border-radius: 20px;
    font-size: 0.875rem;
    font-weight: 500;
    display: inline-block;
    width: fit-content;
}

/* Caja de tranquilidad */
.reassurance-box {
    background-color: var(--color-gris-claro);
    border-left: 4px solid var(--color-principal);
    padding: 1.5rem;
    border-radius: 8px;
    display: flex;
    align-items: flex-start;
    gap: 1rem;
}

.reassurance-icon {
    width: 40px;
    height: 40px;
    background-color: var(--color-principal);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--color-blanco);
    font-size: 1.1rem;
    flex-shrink: 0;
}

.reassurance-content h6 {
    margin: 0 0 0.5rem 0;
    color: var(--color-principal);
    font-weight: 600;
}

.reassurance-content p {
    margin: 0;
    color: var(--color-neutro-oscuro);
    font-size: 0.95rem;
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

@media (min-width: 768px) {
    .action-buttons {
        grid-template-columns: 1fr 1fr 1fr;
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

.btn-secondary {
    background-color: #6c757d;
    color: var(--color-blanco);
}

.btn-secondary:hover {
    background-color: #5a6268;
    color: var(--color-blanco);
    transform: translateY(-1px);
}

/* Caja de ayuda */
.help-box {
    background-color: var(--color-azul-claro);
    border-radius: 8px;
    padding: 1.5rem;
    margin-top: 1.5rem;
}

.help-box-header {
    color: var(--color-principal);
    font-weight: 600;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
}

.help-content p {
    color: var(--color-neutro-oscuro);
    margin-bottom: 1rem;
}

.help-list {
    margin: 0;
    padding-left: 1.25rem;
    color: var(--color-neutro-oscuro);
}

.help-list li {
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
}

.help-list li:last-child {
    margin-bottom: 0;
}

.help-list a {
    color: var(--color-principal);
    text-decoration: none;
    font-weight: 500;
}

.help-list a:hover {
    text-decoration: underline;
}

/* Footer */
.footer-note {
    color: var(--color-neutro-oscuro);
    opacity: 0.8;
}

.footer-note i {
    color: var(--color-principal);
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
    .pago-fallido {
        padding-top: 100px;
        padding-left: 1rem;
        padding-right: 1rem;
    }
    
    .error-header .title {
        font-size: 1.75rem;
    }
    
    .main-card .card-body {
        padding: 1.5rem;
    }
    
    .reassurance-box {
        flex-direction: column;
        text-align: center;
        gap: 0.75rem;
    }
    
    .action-buttons {
        grid-template-columns: 1fr;
    }
}

@media (min-width: 992px) {
    .pago-fallido {
        padding-top: 140px;
    }
}
</style>
@endpush