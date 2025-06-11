@extends('layouts.app')

@section('title', '¡Pago Exitoso!')

@section('content')
<div class="min-vh-100 d-flex align-items-center justify-content-center py-5" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                <!-- Tarjeta principal -->
                <div class="card shadow-lg border-0 rounded-4 overflow-hidden animate__animated animate__fadeInUp">
                    <!-- Header con ícono de éxito -->
                    <div class="card-header bg-success text-white text-center py-5 position-relative">
                        <div class="success-checkmark mb-4">
                            <div class="check-icon">
                                <span class="icon-line line-tip"></span>
                                <span class="icon-line line-long"></span>
                                <div class="icon-circle"></div>
                                <div class="icon-fix"></div>
                            </div>
                        </div>
                        <h1 class="h2 mb-2 fw-bold">¡Pago Exitoso!</h1>
                        <p class="mb-0 opacity-90">Tu reserva ha sido confirmada correctamente</p>
                    </div>

                    <!-- Contenido principal -->
                    <div class="card-body p-5">
                        <!-- Información de la reserva -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="alert alert-success border-0 rounded-3 mb-4" style="background-color: #d1f2eb;">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-check-circle fa-2x text-success me-3"></i>
                                        <div>
                                            <h5 class="alert-heading mb-1 text-success">Reserva Confirmada</h5>
                                            <p class="mb-0 text-dark">Tu pago se procesó correctamente y tu viaje está confirmado.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Detalles del viaje -->
                        <div class="card border-0 bg-light rounded-3 mb-4">
                            <div class="card-header bg-transparent border-0 pb-0">
                                <h5 class="mb-0 text-dark fw-bold">
                                    <i class="fas fa-route me-2 text-primary"></i>
                                    Detalles del Viaje
                                </h5>
                            </div>
                            <div class="card-body pt-3">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-map-marker-alt text-success me-3"></i>
                                            <div>
                                                <small class="text-muted d-block">Origen</small>
                                                <strong class="text-dark">{{ $reserva->viaje->origen }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-flag-checkered text-danger me-3"></i>
                                            <div>
                                                <small class="text-muted d-block">Destino</small>
                                                <strong class="text-dark">{{ $reserva->viaje->destino }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-calendar-alt text-primary me-3"></i>
                                            <div>
                                                <small class="text-muted d-block">Fecha</small>
                                                <strong class="text-dark">{{ \Carbon\Carbon::parse($reserva->viaje->fecha_salida)->format('d/m/Y') }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-clock text-warning me-3"></i>
                                            <div>
                                                <small class="text-muted d-block">Hora</small>
                                                <strong class="text-dark">{{ \Carbon\Carbon::parse($reserva->viaje->hora_salida)->format('H:i') }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Información de la reserva -->
                        <div class="card border-0 bg-light rounded-3 mb-4">
                            <div class="card-header bg-transparent border-0 pb-0">
                                <h5 class="mb-0 text-dark fw-bold">
                                    <i class="fas fa-ticket-alt me-2 text-success"></i>
                                    Tu Reserva
                                </h5>
                            </div>
                            <div class="card-body pt-3">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-hashtag text-info me-3"></i>
                                            <div>
                                                <small class="text-muted d-block">ID de Reserva</small>
                                                <strong class="text-dark">#{{ $reserva->id }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-users text-purple me-3"></i>
                                            <div>
                                                <small class="text-muted d-block">Puestos Reservados</small>
                                                <strong class="text-dark">{{ $reserva->cantidad_puestos }} {{ $reserva->cantidad_puestos == 1 ? 'puesto' : 'puestos' }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-dollar-sign text-success me-3"></i>
                                            <div>
                                                <small class="text-muted d-block">Total Pagado</small>
                                                <strong class="text-dark">${{ number_format($reserva->total, 0, ',', '.') }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-check-circle text-success me-3"></i>
                                            <div>
                                                <small class="text-muted d-block">Estado</small>
                                                <span class="badge bg-success rounded-pill">{{ ucfirst($reserva->estado) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Información del conductor -->
                        @if($reserva->viaje->conductor)
                        <div class="card border-0 bg-light rounded-3 mb-4">
                            <div class="card-header bg-transparent border-0 pb-0">
                                <h5 class="mb-0 text-dark fw-bold">
                                    <i class="fas fa-user-tie me-2 text-primary"></i>
                                    Tu Conductor
                                </h5>
                            </div>
                            <div class="card-body pt-3">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle me-3">
                                        <i class="fas fa-user fa-lg text-white"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1 text-dark">{{ $reserva->viaje->conductor->name }}</h6>
                                        <small class="text-muted">Se pondrá en contacto contigo antes del viaje</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Botones de acción -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <a href="{{ route('pasajero.dashboard') }}" class="btn btn-primary btn-lg w-100 rounded-3">
                                    <i class="fas fa-list me-2"></i>
                                    Ver Mis Reservas
                                </a>
                            </div>
                            <div class="col-md-6">
                                <a href="{{ route('pasajero.viajes.disponibles') }}" class="btn btn-outline-primary btn-lg w-100 rounded-3">
                                    <i class="fas fa-search me-2"></i>
                                    Buscar Más Viajes
                                </a>
                            </div>
                        </div>

                        <!-- Información adicional -->
                        <div class="alert alert-info border-0 rounded-3" style="background-color: #e7f3ff;">
                            <div class="d-flex">
                                <i class="fas fa-info-circle fa-lg text-info me-3 mt-1"></i>
                                <div>
                                    <h6 class="text-info mb-2">¿Qué sigue ahora?</h6>
                                    <ul class="mb-0 text-dark small">
                                        <li>Recibirás un email de confirmación</li>
                                        <li>El conductor se contactará contigo 24h antes del viaje</li>
                                        <li>Puedes ver el estado de tu reserva en "Mis Reservas"</li>
                                        <li>Si necesitas cancelar, hazlo con 24h de anticipación</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="card-footer bg-light border-0 text-center py-4">
                        <p class="mb-2 text-muted">
                            <i class="fas fa-shield-alt me-2 text-success"></i>
                            Tu pago está protegido por Mercado Pago
                        </p>
                        <small class="text-muted">
                            Gracias por elegir VoyConVos para tu viaje
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Animación del check */
.success-checkmark {
    width: 80px;
    height: 80px;
    position: relative;
    margin: 0 auto;
}

.success-checkmark .check-icon {
    width: 80px;
    height: 80px;
    position: relative;
    border-radius: 50%;
    box-sizing: content-box;
    border: 4px solid #4CAF50;
    background-color: #fff;
}

.success-checkmark .check-icon::before {
    top: 3px;
    left: -2px;
    width: 30px;
    transform-origin: 100% 50%;
    border-radius: 100px 0 0 100px;
}

.success-checkmark .check-icon::after {
    top: 0;
    left: 30px;
    width: 60px;
    transform-origin: 0 50%;
    border-radius: 0 100px 100px 0;
    animation: rotate-circle 4.25s ease-in;
}

.success-checkmark .check-icon::before, .success-checkmark .check-icon::after {
    content: '';
    height: 100px;
    position: absolute;
    background: #4CAF50;
    transform: rotate(-45deg);
}

.success-checkmark .check-icon .icon-line {
    height: 5px;
    background-color: #4CAF50;
    display: block;
    border-radius: 2px;
    position: absolute;
    z-index: 10;
}

.success-checkmark .check-icon .icon-line.line-tip {
    top: 46px;
    left: 14px;
    width: 25px;
    transform: rotate(45deg);
    animation: icon-line-tip 0.75s;
}

.success-checkmark .check-icon .icon-line.line-long {
    top: 38px;
    right: 8px;
    width: 47px;
    transform: rotate(-45deg);
    animation: icon-line-long 0.75s;
}

.success-checkmark .check-icon .icon-circle {
    top: -4px;
    left: -4px;
    z-index: 10;
    width: 80px;
    height: 80px;
    border-radius: 50%;
    position: absolute;
    box-sizing: content-box;
    border: 4px solid rgba(76, 175, 80, .5);
}

.success-checkmark .check-icon .icon-fix {
    top: 8px;
    width: 5px;
    left: 26px;
    z-index: 1;
    height: 85px;
    position: absolute;
    transform: rotate(-45deg);
    background-color: #fff;
}

@keyframes rotate-circle {
    0% { transform: rotate(-45deg); }
    5% { transform: rotate(-45deg); }
    12% { transform: rotate(-405deg); }
    100% { transform: rotate(-405deg); }
}

@keyframes icon-line-tip {
    0% { width: 0; left: 1px; top: 19px; }
    54% { width: 0; left: 1px; top: 19px; }
    70% { width: 50px; left: -8px; top: 37px; }
    84% { width: 17px; left: 21px; top: 48px; }
    100% { width: 25px; left: 14px; top: 45px; }
}

@keyframes icon-line-long {
    0% { width: 0; right: 46px; top: 54px; }
    65% { width: 0; right: 46px; top: 54px; }
    84% { width: 55px; right: 0px; top: 35px; }
    100% { width: 47px; right: 8px; top: 38px; }
}

/* Avatar circle */
.avatar-circle {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Colores personalizados */
.text-purple { color: #6f42c1 !important; }
.animate__fadeInUp {
    animation: fadeInUp 0.8s ease-out;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translate3d(0, 40px, 0);
    }
    to {
        opacity: 1;
        transform: translate3d(0, 0, 0);
    }
}

/* Hover effects */
.btn:hover {
    transform: translateY(-2px);
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
    transition: all 0.3s ease;
}
</style>
@endpush

@push('scripts')
<script>
// Confetti animation al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    // Puedes agregar aquí efectos de confetti con una librería como canvas-confetti
    console.log('¡Pago exitoso! 🎉');
});
</script>
@endpush