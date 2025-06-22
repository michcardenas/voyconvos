@extends('layouts.app_dashboard')

@section('title', 'Detalle de tu reserva')

@section('content')
<style>
    :root {
        --vcv-primary: #1F4E79;
        --vcv-light: #DDF2FE;
        --vcv-dark: #3A3A3A;
        --vcv-accent: #4CAF50;
        --vcv-bg: #FCFCFD;
    }

    .details-wrapper {
        background: linear-gradient(135deg, #DDF2FE 0%, #FCFCFD 50%, rgba(31, 78, 121, 0.03) 100%);
        min-height: 100vh;
        padding: 2rem 0;
        position: relative;
    }

    .details-wrapper::before {
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

    .info-card {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border-radius: 12px;
        padding: 1.8rem;
        margin-bottom: 1.5rem;
        border: 1px solid rgba(31, 78, 121, 0.12);
        box-shadow: 0 4px 12px rgba(31, 78, 121, 0.08);
        transition: all 0.3s ease;
    }

    .info-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(31, 78, 121, 0.12);
    }

    .card-header-custom {
        display: flex;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 0.8rem;
        border-bottom: 2px solid rgba(31, 78, 121, 0.1);
    }

    .card-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        font-size: 1.1rem;
    }

    .card-icon.route {
        background: rgba(31, 78, 121, 0.1);
        color: var(--vcv-primary);
    }

    .card-icon.driver {
        background: rgba(76, 175, 80, 0.1);
        color: var(--vcv-accent);
    }

    .card-icon.booking {
        background: rgba(221, 242, 254, 0.8);
        color: var(--vcv-primary);
    }

    .card-title {
        margin: 0;
        color: var(--vcv-primary);
        font-weight: 600;
        font-size: 1.1rem;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }

    .info-item {
        background: rgba(221, 242, 254, 0.3);
        padding: 1rem;
        border-radius: 8px;
        border-left: 3px solid var(--vcv-primary);
        transition: all 0.2s ease;
    }

    .info-item:hover {
        background: rgba(221, 242, 254, 0.5);
        border-left-color: var(--vcv-accent);
    }

    .info-label {
        font-weight: 600;
        color: var(--vcv-dark);
        font-size: 0.85rem;
        margin-bottom: 0.3rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .info-value {
        color: var(--vcv-primary);
        font-weight: 500;
        font-size: 1rem;
    }

    .status-badge {
        display: inline-block;
        padding: 0.4rem 1rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-confirmado {
        background: rgba(76, 175, 80, 0.1);
        color: var(--vcv-accent);
        border: 1px solid rgba(76, 175, 80, 0.3);
    }

    .status-pendiente {
        background: rgba(255, 193, 7, 0.1);
        color: #f57c00;
        border: 1px solid rgba(255, 193, 7, 0.3);
    }

    .status-cancelado {
        background: rgba(220, 53, 69, 0.1);
        color: #dc3545;
        border: 1px solid rgba(220, 53, 69, 0.3);
    }

    .map-container {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        border: 1px solid rgba(31, 78, 121, 0.12);
        box-shadow: 0 4px 12px rgba(31, 78, 121, 0.08);
    }

    .map-header {
        display: flex;
        align-items: center;
        margin-bottom: 1rem;
    }

    .map-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: rgba(31, 78, 121, 0.1);
        color: var(--vcv-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        font-size: 1.1rem;
    }

    .map-title {
        margin: 0;
        color: var(--vcv-primary);
        font-weight: 600;
        font-size: 1.1rem;
    }

    #mapa {
        width: 100%;
        height: 400px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .action-section {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border-radius: 12px;
        padding: 1.5rem;
        border: 1px solid rgba(31, 78, 121, 0.12);
        box-shadow: 0 4px 12px rgba(31, 78, 121, 0.08);
        text-align: center;
    }

    .btn-custom {
        border: none;
        border-radius: 25px;
        padding: 0.8rem 2rem;
        font-weight: 600;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
        margin: 0.5rem;
        font-size: 0.9rem;
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

    .btn-custom.accent {
        background: var(--vcv-accent);
        color: white;
    }

    .btn-custom.accent:hover {
        background: rgba(76, 175, 80, 0.9);
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(76, 175, 80, 0.3);
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
    }

    .rating-status {
        background: rgba(76, 175, 80, 0.1);
        border: 1px solid rgba(76, 175, 80, 0.3);
        color: var(--vcv-accent);
        padding: 1rem;
        border-radius: 10px;
        margin: 1rem 0;
        text-align: center;
        font-weight: 600;
    }

    .route-summary {
        background: rgba(31, 78, 121, 0.05);
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1rem;
        border: 1px solid rgba(31, 78, 121, 0.1);
    }

    .route-item {
        display: flex;
        align-items: center;
        margin-bottom: 0.5rem;
    }

    .route-item:last-child {
        margin-bottom: 0;
    }

    .route-marker {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        margin-right: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.7rem;
        font-weight: bold;
        color: white;
    }

    .route-marker.origin {
        background: var(--vcv-accent);
    }

    .route-marker.destination {
        background: #dc3545;
    }

    .route-text {
        color: var(--vcv-dark);
        font-weight: 500;
    }
    .btn-pay {
    background: linear-gradient(135deg, #28a745, #20c997);
    border: none;
    padding: 12px 30px;
    font-weight: bold;
    font-size: 16px;
    border-radius: 8px;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
}

.btn-pay:hover {
    background: linear-gradient(135deg, #218838, #1ea085);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(40, 167, 69, 0.4);
}

.btn-pay:active {
    transform: translateY(0);
}

.btn-pay i {
    margin-right: 8px;
}

.payment-button-container {
    border-top: 1px solid #eee;
    padding-top: 15px;
}

/* Estilo específico para el estado pendiente_pago */
.status-pendiente_pago {
    background-color: #fff3cd;
    color: #856404;
    border: 1px solid #ffeaa7;
    animation: pulse-payment 2s infinite;
}

@keyframes pulse-payment {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.7;
    }
}

    @media (max-width: 768px) {
        .details-wrapper {
            padding: 1rem 0;
        }
        
        .page-header {
            padding: 1.5rem;
        }
        
        .page-header h2 {
            font-size: 1.5rem;
        }
        
        .info-grid {
            grid-template-columns: 1fr;
        }
        
        .btn-custom {
            width: 100%;
            margin: 0.3rem 0;
        }
    }
</style>

<div class="details-wrapper">
    <div class="container">
        <!-- Header -->
        <div class="page-header">
            <h2>📋 Detalles de tu reserva</h2>
        </div>

        <!-- Información del Viaje -->
        <div class="info-card">
            <div class="card-header-custom">
                <div class="card-icon route">
                    <i class="fas fa-route"></i>
                </div>
                <h5 class="card-title">Información del Viaje</h5>
            </div>
            
            <div class="route-summary">
                <div class="route-item">
                    <div class="route-marker origin">A</div>
                    <div class="route-text">{{ $reserva->viaje->origen_direccion }}</div>
                </div>
                <div class="route-item">
                    <div class="route-marker destination">B</div>
                    <div class="route-text">{{ $reserva->viaje->destino_direccion }}</div>
                </div>
            </div>

            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Origen</div>
                    <div class="info-value">{{ $reserva->viaje->origen_direccion }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Destino</div>
                    <div class="info-value">{{ $reserva->viaje->destino_direccion }}</div>
                </div>
           
                <div class="info-item">
                    <div class="info-label">Hora</div>
                    <div class="info-value">{{ $reserva->viaje->hora_salida }}</div>
                </div>
           <div class="info-item">
                <div class="info-label">Fecha de Salida</div>
                <div class="info-value">{{ \Carbon\Carbon::parse($reserva->viaje->fecha_salida)->format('d/m/Y') }}</div>
            </div>
                <div class="info-item">
                    <div class="info-label">Puestos Reservados</div>
                    <div class="info-value">{{ $reserva->cantidad_puestos }}</div>
                </div>
            </div>
        </div>

        <!-- Información del Conductor -->
        <div class="info-card">
            <div class="card-header-custom">
                <div class="card-icon driver">
                    <i class="fas fa-user-tie"></i>
                </div>
                <h5 class="card-title">Conductor</h5>
            </div>
            
         
<!-- INFORMACIÓN DEL CONDUCTOR CON FOTO - RESPONSIVE -->
<div class="conductor-section" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border-radius: 15px; padding: 20px; margin: 20px 0; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
    <h5 style="margin-bottom: 15px; color: #333; text-align: center;">
        <i class="fas fa-user-tie"></i> Tu Conductor
    </h5>
    
    <!-- LEYENDA DE CONFIANZA -->
    <div class="trust-badge" style="background: linear-gradient(45deg, #28a745, #20c997); color: white; padding: 12px; border-radius: 10px; text-align: center; margin-bottom: 20px; font-size: 0.95em; box-shadow: 0 2px 8px rgba(40, 167, 69, 0.3);">
        <i class="fas fa-shield-alt" style="margin-right: 8px;"></i>
        <strong>Conductor y vehículo verificados por nuestra plataforma.</strong> 
        <br class="d-sm-none">¡Puedes viajar seguro!
    </div>
    
    <div class="conductor-content" style="display: flex; flex-direction: column; gap: 20px;">
        <!-- FOTO DEL CONDUCTOR - CENTRADA EN MÓVIL -->
        <div class="conductor-photo" style="text-align: center;">
            @if($reserva->viaje->conductor->foto)
                <img src="{{ asset('storage/' . $reserva->viaje->conductor->foto) }}" 
                     alt="Foto de {{ $reserva->viaje->conductor->name }}"
                     style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover; border: 4px solid #007bff; box-shadow: 0 4px 12px rgba(0,123,255,0.3);">
            @else
                <div style="width: 100px; height: 100px; border-radius: 50%; background: linear-gradient(45deg, #6c757d, #495057); display: inline-flex; align-items: center; justify-content: center; color: white; font-size: 32px; box-shadow: 0 4px 12px rgba(108,117,125,0.3);">
                    <i class="fas fa-user"></i>
                </div>
            @endif
        </div>
        
        <!-- DATOS DEL CONDUCTOR - GRID RESPONSIVE -->
        <div class="conductor-details">
            <div class="row g-3">
                <div class="col-12 col-md-6">
                    <div class="info-item" style="background: white; padding: 15px; border-radius: 10px; box-shadow: 0 2px 6px rgba(0,0,0,0.05);">
                        <div class="info-label" style="font-weight: 600; color: #6c757d; font-size: 0.85em; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 5px;">
                            <i class="fas fa-user" style="margin-right: 5px;"></i> Nombre
                        </div>
                        <div class="info-value" style="color: #333; font-size: 1.15em; font-weight: 500;">
                            {{ $reserva->viaje->conductor->name ?? 'N/D' }}
                        </div>
                    </div>
                </div>
                
                <div class="col-12 col-md-6">
                    <div class="info-item" style="background: white; padding: 15px; border-radius: 10px; box-shadow: 0 2px 6px rgba(0,0,0,0.05);">
                        <div class="info-label" style="font-weight: 600; color: #6c757d; font-size: 0.85em; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 5px;">
                            <i class="fas fa-envelope" style="margin-right: 5px;"></i> Email
                        </div>
                        <div class="info-value" style="color: #333; font-size: 1rem; word-break: break-all;">
                            {{ $reserva->viaje->conductor->email ?? 'N/D' }}
                        </div>
                    </div>
                </div>
                
                <div class="col-12">
                    <div class="info-item" style="background: white; padding: 15px; border-radius: 10px; box-shadow: 0 2px 6px rgba(0,0,0,0.05);">
                        <div class="info-label" style="font-weight: 600; color: #6c757d; font-size: 0.85em; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 5px;">
                            <i class="fas fa-phone" style="margin-right: 5px;"></i> Contacto
                        </div>
                        <div class="info-value">
                            @if($reserva->viaje->conductor->celular)
                                <a href="tel:{{ $reserva->viaje->conductor->celular }}" 
                                   style="color: #007bff; text-decoration: none; font-size: 1.1em; font-weight: 500;">
                                    <i class="fas fa-phone-alt" style="margin-right: 8px;"></i>
                                    {{ $reserva->viaje->conductor->celular }}
                                </a>
                            @else
                                <span style="color: #6c757d;">No disponible</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- BOTONES DE CONTACTO - RESPONSIVE -->
    @if($reserva->viaje->conductor->celular)
        <div class="contact-buttons" style="margin-top: 20px;">
            <div class="row g-2">
                <div class="col-12 col-sm-6">
                    <a href="{{ route('chat.ver', $reserva->viaje_id) }}" class="btn-custom primary">
                        <i class="fas fa-comments me-2"></i>Abrir Chat
                    </a>
                </div>
                
                <div class="col-12 col-sm-6">
                    <a href="tel:{{ $reserva->viaje->conductor->celular }}" 
                       class="btn btn-outline-primary w-100"
                       style="padding: 12px; font-weight: 600; border-radius: 10px; border-width: 2px; transition: all 0.3s ease;">
                        <i class="fas fa-phone" style="margin-right: 8px;"></i> 
                        Llamar ahora
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>

            @if (!$reserva->calificacionEnviadaPorPasajero())
                <div style="margin-top: 1.5rem; text-align: center;">
                    <a href="{{ route('pasajero.calificar.formulario', $reserva->id) }}" 
                       class="btn-custom accent">
                        <i class="fas fa-star me-2"></i>Calificar al conductor
                    </a>
                </div>
            @else
                <div class="rating-status">
                    <i class="fas fa-check-circle me-2"></i>Ya calificaste al conductor
                </div>
            @endif
        </div>

        <!-- Tu Reserva -->
       <div class="info-card">
    <div class="card-header-custom">
        <div class="card-icon booking">
            <i class="fas fa-ticket-alt"></i>
        </div>
        <h5 class="card-title">Tu Reserva</h5>
    </div>
    
    <div class="info-grid">
        <div class="info-item">
            <div class="info-label">Estado</div>
            <div class="info-value">
                <span class="status-badge status-{{ strtolower($reserva->estado) }}">
                    @if($reserva->estado == 'pendiente_pago')
                        Pendiente por pago
                    @else
                        {{ ucfirst($reserva->estado) }}
                    @endif
                </span>
            </div>
        </div>
        <div class="info-item">
            <div class="info-label">Fecha de Reserva</div>
            <div class="info-value">{{ $reserva->created_at->format('d/m/Y H:i') }}</div>
        </div>
    </div>
    
    {{-- Botón de pago que solo aparece cuando el estado es pendiente_pago --}}
@if($reserva->estado == 'pendiente_pago' || $reserva->estado == 'cancelada')
    <div class="payment-button-container" style="margin-top: 15px; text-align: center;">
        <button type="button" class="btn btn-primary btn-pay" onclick="procesarPago({{ $reserva->id }})">
            <i class="fas fa-credit-card"></i> 
            @if($reserva->estado == 'cancelada')
                REINTENTAR PAGO
            @else
                PAGAR
            @endif
        </button>
        
        @if($reserva->estado == 'cancelada')
            <p class="text-muted mt-2" style="font-size: 0.9em;">
                <i class="fas fa-info-circle"></i> El pago anterior fue cancelado. Puedes intentar nuevamente.
            </p>
        @endif
    </div>
@endif
</div>

      <!-- Mapa -->
<div class="map-container">
    <div class="map-header">
        <div class="map-icon">
            <i class="fas fa-map-marked-alt"></i>
        </div>
        <h5 class="map-title">Ruta del viaje</h5>
    </div>
    <div id="mapa" style="height: 400px; width: 100%; border-radius: 8px; overflow: hidden; background: #f8f9fa; border: 2px solid #ddd;">
        <div style="display: flex; justify-content: center; align-items: center; height: 100%; color: #666;">
            <div style="text-align: center;">
                <i class="fas fa-map" style="font-size: 2rem; margin-bottom: 1rem;"></i>
                <p>Preparando mapa...</p>
            </div>
        </div>
    </div>
</div>

<!-- Acciones -->
<div class="action-section">
    <h5 class="mb-3" style="color: var(--vcv-primary); font-weight: 600;">¿Qué quieres hacer?</h5>
    <a href="{{ route('pasajero.dashboard') }}" class="btn-custom secondary">
        <i class="fas fa-arrow-left me-2"></i>Volver al listado
    </a>
  
</div>

<!-- 🔧 SCRIPT INLINE PARA DEBUGGING -->
<script>
    function procesarPago(reservaId) {
    console.log('🚀 Procesando pago para reserva:', reservaId);
    
    // Crear formulario
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '/pasajero/reservar/{{ $reserva->viaje_id }}'; // URL directa
    
    // Campos requeridos
    const campos = {
        'cantidad_puestos': {{ $reserva->cantidad_puestos }},
        'valor_cobrado': {{ $reserva->precio_por_persona }},
        'total': {{ $reserva->total }},
        'viaje_id': {{ $reserva->viaje_id }},
        '_token': '{{ csrf_token() }}'
    };
    
    // Crear inputs
    Object.entries(campos).forEach(([name, value]) => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = name;
        input.value = value;
        form.appendChild(input);
    });
    
    // Deshabilitar botón
    const btn = document.querySelector('.btn-pay');
    if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Procesando...';
    }
    
    // Enviar
    document.body.appendChild(form);
    form.submit();
}
// ⚠️ TEST BÁSICO - Esto debería aparecer en consola
console.log("🚨 SCRIPT EJECUTÁNDOSE - Si ves esto, JavaScript funciona");
console.log("📍 Coordenadas:", {
    origen: "{{ $reserva->viaje->origen_lat }}, {{ $reserva->viaje->origen_lng }}",
    destino: "{{ $reserva->viaje->destino_lat }}, {{ $reserva->viaje->destino_lng }}"
});

// 🏃‍♂️ Ejecutar inmediatamente
document.getElementById("mapa").innerHTML = `
    <div style="padding: 20px; text-align: center; background: #e3f2fd; border-radius: 8px; margin: 10px;">
        <h5 style="color: #1976d2; margin-bottom: 15px;">🔧 Modo Debug</h5>
        <p><strong>Script funcionando:</strong> ✅</p>
        <p><strong>Origen:</strong> {{ $reserva->viaje->origen_lat }}, {{ $reserva->viaje->origen_lng }}</p>
        <p><strong>Destino:</strong> {{ $reserva->viaje->destino_lat }}, {{ $reserva->viaje->destino_lng }}</p>
        <p><strong>API Key configurada:</strong> ${typeof window.google !== 'undefined' ? '✅' : '❌'}</p>
        <button onclick="initMapaReal()" style="
            background: #1976d2; 
            color: white; 
            border: none; 
            padding: 10px 20px; 
            border-radius: 5px; 
            cursor: pointer;
            margin-top: 10px;
        ">
            🗺️ Intentar cargar mapa
        </button>
    </div>
`;

// 🗺️ Función para cargar el mapa real
function initMapaReal() {
    console.log("🗺️ Intentando cargar mapa real...");
    
    if (typeof google === 'undefined') {
        console.error("❌ Google Maps no disponible");
        document.getElementById("mapa").innerHTML = `
            <div style="padding: 20px; text-align: center; background: #ffebee; border-radius: 8px;">
                <h5 style="color: #d32f2f;">❌ Google Maps no disponible</h5>
                <p>Verifica tu API Key en el archivo .env</p>
                <code style="background: #f5f5f5; padding: 5px; border-radius: 3px;">
                    GOOGLE_MAPS_API_KEY=tu_api_key_aqui
                </code>
            </div>
        `;
        return;
    }
    
    try {
        const origen = {
            lat: {{ $reserva->viaje->origen_lat }},
            lng: {{ $reserva->viaje->origen_lng }}
        };
        
        const destino = {
            lat: {{ $reserva->viaje->destino_lat }},
            lng: {{ $reserva->viaje->destino_lng }}
        };
        
        console.log("📍 Creando mapa con:", { origen, destino });
        
        const map = new google.maps.Map(document.getElementById("mapa"), {
            zoom: 13,
            center: origen,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });
        
        // Marcador origen
        new google.maps.Marker({
            position: origen,
            map: map,
            title: "Origen",
            icon: 'https://maps.google.com/mapfiles/ms/icons/green-dot.png'
        });
        
        // Marcador destino
        new google.maps.Marker({
            position: destino,
            map: map,
            title: "Destino",
            icon: 'https://maps.google.com/mapfiles/ms/icons/red-dot.png'
        });
        
        // Ruta
        const directionsService = new google.maps.DirectionsService();
        const directionsRenderer = new google.maps.DirectionsRenderer({
            map: map,
            suppressMarkers: true
        });
        
        directionsService.route({
            origin: origen,
            destination: destino,
            travelMode: google.maps.TravelMode.DRIVING
        }, function(response, status) {
            if (status === "OK") {
                directionsRenderer.setDirections(response);
                console.log("✅ Ruta cargada");
            } else {
                console.warn("⚠️ Error en ruta:", status);
                const bounds = new google.maps.LatLngBounds();
                bounds.extend(origen);
                bounds.extend(destino);
                map.fitBounds(bounds);
            }
        });
        
    } catch (error) {
        console.error("❌ Error:", error);
        document.getElementById("mapa").innerHTML = `
            <div style="padding: 20px; text-align: center; background: #ffebee; border-radius: 8px;">
                <h5 style="color: #d32f2f;">❌ Error: ${error.message}</h5>
            </div>
        `;
    }
}

// 🔄 Función global para Google Maps callback
window.initReservaMapa = function() {
    console.log("🔔 Callback de Google Maps ejecutado");
    setTimeout(initMapaReal, 1000);
};
</script>

<!-- Google Maps API -->
<script async defer
    src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.key') }}&callback=initReservaMapa&v=3.55">
</script>