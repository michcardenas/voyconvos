@extends('layouts.app_dashboard')

@section('content')
<style>
    /* Variables para mantener consistencia */
    :root {
        --vcv-primary: #003366;
        --vcv-primary-light: #004080;
        --vcv-info: #00BFFF;
        --vcv-success: #28a745;
        --vcv-warning: #ffc107;
        --vcv-danger: #dc3545;
        --border-color: rgba(0, 51, 102, 0.1);
        --shadow-card: 0 4px 12px rgba(0, 51, 102, 0.1);
        --shadow-soft: 0 2px 8px rgba(0, 51, 102, 0.08);
        --border-radius: 12px;
        --transition: all 0.3s ease;
    }

    /* TUS CLASES ORIGINALES */
    .text-vcv {
        color: #003366;
    }

    .shadow-sm {
        box-shadow: var(--shadow-soft);
    }

    /* Header de la página */
    .page-header {
        background: linear-gradient(135deg, var(--vcv-primary) 0%, var(--vcv-primary-light) 100%);
        color: white;
        padding: 2rem;
        border-radius: var(--border-radius);
        margin-bottom: 2rem;
        box-shadow: var(--shadow-card);
    }

    .page-header h2 {
        margin: 0;
        font-weight: 600;
        font-size: 1.8rem;
        color: white;
    }

    .page-header p {
        margin: 0.5rem 0 0 0;
        opacity: 0.9;
        font-size: 1rem;
    }

    /* Cards modernas */
    .modern-card {
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow-card);
        border: 1px solid var(--border-color);
        overflow: hidden;
        margin-bottom: 2rem;
        transition: var(--transition);
    }

    .modern-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 51, 102, 0.15);
    }

    .card-header-custom {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 1.5rem;
        border-bottom: 2px solid #f0f0f0;
    }

    .card-title-custom {
        color: var(--vcv-primary);
        font-weight: 600;
        font-size: 1.3rem;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .card-body-custom {
        padding: 2rem;
    }

    /* Información del viaje en grid */
    .trip-info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .info-item {
        display: flex;
        align-items: center;
        padding: 1rem;
        background: #f8f9fa;
        border-radius: 8px;
        border-left: 4px solid var(--vcv-info);
        transition: var(--transition);
    }

    .info-item:hover {
        background: #e9ecef;
        transform: translateX(5px);
    }

    .info-item .icon {
        margin-right: 0.75rem;
        font-size: 1.2rem;
    }

    .info-item .content {
        flex: 1;
    }

    .info-item .label {
        font-weight: 600;
        color: var(--vcv-primary);
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        margin-bottom: 0.25rem;
    }

    .info-item .value {
        color: #495057;
        font-weight: 500;
        font-size: 1rem;
    }

    /* Estado del viaje */
    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        box-shadow: var(--shadow-soft);
    }

    .status-badge.bg-primary {
        background: linear-gradient(45deg, var(--vcv-primary), var(--vcv-primary-light)) !important;
    }

    /* Sección de pasajeros */
    .passengers-section {
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow-card);
        border: 1px solid var(--border-color);
        overflow: hidden;
    }

    .section-header {
        background: linear-gradient(135deg, var(--vcv-primary) 0%, var(--vcv-primary-light) 100%);
        color: white;
        padding: 1.5rem;
        margin: 0;
        font-weight: 600;
        font-size: 1.2rem;
    }

    .passenger-card {
        border-bottom: 1px solid #f0f0f0;
        padding: 1.5rem;
        transition: var(--transition);
            display: flex
;
    justify-content: space-between;
    }

    .passenger-card:hover {
        background: #f8f9fa;
    }

    .passenger-card:last-child {
        border-bottom: none;
    }

    .passenger-info {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 1rem;
    }

    .passenger-details h6 {
        color: var(--vcv-primary);
        font-weight: 600;
        margin-bottom: 0.5rem;
        font-size: 1.1rem;
    }

    .passenger-meta {
        color: #6c757d;
        font-size: 0.9rem;
        margin-bottom: 0.25rem;
    }

    .rating-display {
        color: var(--vcv-warning);
        font-weight: 600;
        font-size: 0.9rem;
    }

    /* Sección de calificaciones */
    .ratings-section {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 1.5rem;
        margin-top: 1rem;
        border: 1px solid #e9ecef;
    }

    .ratings-title {
        color: var(--vcv-primary);
        font-weight: 600;
        font-size: 1rem;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .rating-item {
        background: white;
        border-radius: 6px;
        padding: 1rem;
        margin-bottom: 1rem;
        border-left: 3px solid var(--vcv-success);
        box-shadow: var(--shadow-soft);
    }

    .rating-item:last-child {
        margin-bottom: 0;
    }

    .rating-header {
        font-weight: 600;
        color: var(--vcv-primary);
        margin-bottom: 0.5rem;
    }

    .rating-comment {
        color: #495057;
        line-height: 1.5;
        margin-bottom: 0.5rem;
    }

    .rating-stars {
        color: var(--vcv-warning);
        font-weight: 600;
    }

    .no-rating {
        color: #6c757d;
        font-style: italic;
        background: #f8f9fa;
        padding: 0.75rem;
        border-radius: 6px;
        border-left: 3px solid #dee2e6;
    }

    /* Botones modernos */
    .btn-modern {
        border-radius: 6px;
        font-weight: 500;
        padding: 0.6rem 1.2rem;
        font-size: 0.9rem;
        transition: var(--transition);
        border: 1px solid transparent;
    }

    .btn-outline-primary.btn-modern {
        border-color: var(--vcv-primary);
        color: var(--vcv-primary);
    }

    .btn-outline-primary.btn-modern:hover {
        background-color: var(--vcv-primary);
        border-color: var(--vcv-primary);
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 3px 10px rgba(0, 51, 102, 0.2);
    }

    .btn-danger.btn-modern {
        background: linear-gradient(45deg, var(--vcv-danger), #c82333);
        border: none;
        color: white;
    }

    .btn-danger.btn-modern:hover {
        transform: translateY(-1px);
        box-shadow: 0 3px 10px rgba(220, 53, 69, 0.3);
    }

    .btn-link.btn-modern {
        color: var(--vcv-info);
        font-weight: 500;
        text-decoration: none;
    }

    .btn-link.btn-modern:hover {
        color: var(--vcv-primary);
        transform: translateX(5px);
    }

    /* Alert mejorado */
    .alert-modern {
        border: none;
        border-radius: 8px;
        padding: 1.5rem;
        background: rgba(0, 191, 255, 0.05);
        border-left: 4px solid var(--vcv-info);
        color: #495057;
    }

    /* Área de acciones */
    .actions-area {
        background: white;
        border-radius: var(--border-radius);
        padding: 1.5rem;
        box-shadow: var(--shadow-card);
        border: 1px solid var(--border-color);
        text-align: center;
    }
    .passenger-actions {
    display: flex;
    gap: 0.5rem;
    align-items: center;
}

.passenger-info {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1rem;
}

.modal-content {
    border-radius: 12px;
    border: none;
}
.passenger-name-clickable {
    cursor: pointer;
    color: #0066cc;
    text-decoration: none;
    transition: all 0.2s ease;
    border-bottom: 1px dotted #0066cc;
    display: inline-block;
}

.passenger-name-clickable:hover {
    color: #004499;
    border-bottom: 1px solid #004499;
    transform: translateY(-1px);
}

.passenger-profile {
    padding: 1rem 0;
}

.profile-photo-container {
    position: relative;
    display: inline-block;
}

.profile-photo {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid #e9ecef;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.no-photo-placeholder {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    background: linear-gradient(135deg, #6c757d, #adb5bd);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 2.5rem;
    border: 4px solid #e9ecef;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.rating-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: linear-gradient(135deg, #ffc107, #ff8c00);
    color: white;
    padding: 0.375rem 1rem;
    border-radius: 20px;
    font-weight: 500;
    font-size: 0.9rem;
    box-shadow: 0 2px 8px rgba(255, 193, 7, 0.3);
}

.passenger-details-grid {
    display: grid;
    gap: 1rem;
}

.detail-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 10px;
    border-left: 4px solid transparent;
    transition: all 0.2s ease;
}

.detail-item:hover {
    background: #e9ecef;
    transform: translateX(5px);
}

.detail-item:nth-child(1) { border-left-color: #007bff; }
.detail-item:nth-child(2) { border-left-color: #28a745; }
.detail-item:nth-child(3) { border-left-color: #dc3545; }
.detail-item:nth-child(4) { border-left-color: #17a2b8; }

.detail-icon {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: white;
    border-radius: 50%;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.detail-content {
    flex: 1;
}

.detail-label {
    font-size: 0.85rem;
    color: #6c757d;
    font-weight: 500;
    margin-bottom: 0.25rem;
}

.detail-value {
    font-size: 1rem;
    font-weight: 600;
    color: #2c3e50;
}
/* Estilos para el estado de verificación */
.verification-status-container {
    display: flex;
    justify-content: center;
    margin-bottom: 1rem;
}

.verification-badge {
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.875rem;
    font-weight: 500;
    text-align: center;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    border: 1px solid;
    transition: all 0.2s ease;
}

/* Estado verificado - verde suave */
.verification-badge.verified {
    background-color: #d1f2eb;
    color: #0d6d3f;
    border-color: #85d1b2;
}

/* Estado no verificado - rojo suave */
.verification-badge.not-verified {
    background-color: #fdeaea;
    color: #c53030;
    border-color: #f5b7b7;
}

/* Estado pendiente - amarillo suave */
.verification-badge.pending {
    background-color: #fff3cd;
    color: #856404;
    border-color: #ffd60a;
}

/* Iconos para los estados */
.verification-badge i {
    font-size: 0.875rem;
}

/* Responsive para dispositivos móviles */
@media (max-width: 576px) {
    .verification-badge {
        font-size: 0.8rem;
        padding: 0.4rem 0.8rem;
    }
}
@media (max-width: 768px) {
    .profile-photo,
    .no-photo-placeholder {
        width: 80px;
        height: 80px;
    }
    
    .no-photo-placeholder i {
        font-size: 2rem;
    }
    
    .detail-item {
        padding: 0.75rem;
    }
    
    .detail-icon {
        width: 35px;
        height: 35px;
    }
}
</style>

<style>
.passenger-actions {
    display: flex;
    gap: 0.5rem;
    align-items: center;
}

.passenger-info {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1rem;
}

.modal-content {
    border-radius: 12px;
    border: none;
}

@media (max-width: 768px) {
    .passenger-info {
        flex-direction: column;
        gap: 1rem;
    }
    
    .passenger-actions {
        justify-content: flex-start;
    }
}
@media (max-width: 768px) {
    .passenger-info {
        flex-direction: column;
        gap: 1rem;
    }
    
    .passenger-actions {
        justify-content: flex-start;
    }
}
    /* Responsive */
    @media (max-width: 768px) {
        .page-header {
            padding: 1.5rem;
        }
        
        .page-header h2 {
            font-size: 1.5rem;
        }
        
        .trip-info-grid {
            grid-template-columns: 1fr;
        }
        
        .passenger-info {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .card-body-custom {
            padding: 1.5rem;
        }
    }

    /* Animación de entrada */
    .modern-card {
        animation: slideUp 0.5s ease-out;
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<div class="container py-4">
    <!-- Header de la página -->
    <div class="page-header">
        <h2>🛣️ Detalles del Viaje</h2>
        <p>Información completa sobre tu viaje y pasajeros</p>
    </div>

    <!-- Card principal con detalles del viaje -->
    <div class="modern-card">
        <div class="card-header-custom">
            <h5 class="card-title-custom">📍 {{ $viaje->origen_direccion }} → {{ $viaje->destino_direccion }}</h5>
        </div>
        <div class="card-body-custom">
            <div class="trip-info-grid">
                <div class="info-item">
                    <div class="icon">🗓</div>
                    <div class="content">
                   <div class="label">Fecha</div>
<div class="value">{{ $viaje->fecha_salida ? \Carbon\Carbon::parse($viaje->fecha_salida)->format('d/m/Y') : 'No definida' }}</div>
                    </div>
                </div>
                
                <div class="info-item">
                    <div class="icon">🕒</div>
                    <div class="content">
                        <div class="label">Hora</div>
                        <div class="value">    {{ $viaje->hora_salida ? substr($viaje->hora_salida, 0, 10) : 'No definida' }}
</div>
                    </div>
                </div>
                
                <div class="info-item">
                    <div class="icon">🎯</div>
                    <div class="content">
                        <div class="label">Distancia estimada</div>
                        <div class="value">{{ $viaje->distancia_km ?? '—' }} km</div>
                    </div>
                </div>
                
                <div class="info-item">
                    <div class="icon">🚗</div>
                    <div class="content">
                        <div class="label">Vehículo</div>
                       <div class="value">
                        @php
                            $marca = $viaje->registroConductor->marca_vehiculo ?? null;
                            $modelo = $viaje->registroConductor->modelo_vehiculo ?? null;
                        @endphp

                        {{ $viaje->vehiculo !== $marca ? ($viaje->vehiculo . ' - ') : '' }}
                        {{ $marca }} {{ $modelo }}
                    </div>

                    </div>
                </div>
                
                <div class="info-item">
                    <div class="icon">💰</div>
                    <div class="content">
                        <div class="label">Valor por persona</div>
                        <div class="value">${{ number_format($viaje->valor_persona, 2) }}</div>
                    </div>
                </div>
                
                <div class="info-item">
                    <div class="icon">🪑</div>
                    <div class="content">
                        <div class="label">Puestos disponibles</div>
                        <div class="value">{{ $viaje->puestos_disponibles }}</div>
                    </div>
                </div>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 1.5rem;">
                <div>
                    <span class="label" style="font-weight: 600; color: var(--vcv-primary); font-size: 0.85rem; text-transform: uppercase; margin-right: 0.5rem;">📦 Estado:</span>
                    <span class="status-badge bg-primary text-light">{{ ucfirst($viaje->estado) }}</span>
                </div>

                @if($viaje->conductor_id === auth()->id())
                <form method="POST" action="{{ route('conductor.viaje.eliminar', $viaje->id) }}" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-sm btn-outline-danger btn-modern btn-cancelar-viaje">
                        ❌ Cancelar
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>

    <!-- NUEVA SECCIÓN: Mapa de la ruta -->
    <div class="modern-card" style="margin-top: 1.5rem;">
        <div class="card-header-custom">
            <h5 class="card-title-custom">🗺️ Ruta del Viaje</h5>
        </div>
        <div class="card-body-custom">
            <div id="map" style="height: 400px; width: 100%; border-radius: 8px;"></div>
        </div>
    </div>


<!-- Sección de pasajeros -->
<!-- Sección de pasajeros -->
<div class="passengers-section">
    <h4 class="section-header">👥 Pasajeros</h4>
    
    @if($viaje->reservas->count())
        @foreach($viaje->reservas as $reserva)
        <div class="passenger-card">
          <div class="passenger-details">
                        <h6 class="passenger-name-clickable"
                            onclick="showPassengerModal({{ $reserva->user->id }}, '{{ $reserva->user->name }}', '{{ $reserva->user->foto ? asset('storage/' . $reserva->user->foto) : '' }}', '{{ $reserva->user->email }}', '{{ $reserva->user->celular ?? 'No especificado' }}', '{{ $reserva->user->ciudad ?? 'No especificado' }}', {{ $reserva->user->calificacion ?? 0 }}, {{ $reserva->cantidad_puestos }}, {{ $reserva->user->verificado }})">
                            {{ $reserva->user->name }}
                        </h6>
                        
                        <!-- Badge de verificación en la tarjeta -->
                        @if($reserva->user->verificado == 1)
                            <span class="badge verification-mini verified">
                                <i class="fas fa-shield-check"></i> Verificado
                            </span>
                        @else
                            <span class="badge verification-mini not-verified">
                                <i class="fas fa-shield-exclamation"></i> No Verificado
                            </span>
                        @endif
                        
                        <div class="passenger-meta">Reservó {{ $reserva->cantidad_puestos }} puesto(s)</div>
                        @if($reserva->user->calificacion)
                            <div class="rating-display">⭐ Calificación: {{ $reserva->user->calificacion }}/5</div>
                        @endif
                    </div>
                <div class="passenger-actions">
                    <a href="{{ route('chat.ver', $viaje->id) }}" class="btn btn-sm btn-outline-primary btn-modern">💬 Chat</a>
                    
                    @if($requiereVerificacion && $reserva->estado == 'pendiente_confirmacion')
                        <button type="button"
                                class="btn btn-sm btn-success btn-modern"
                                data-bs-toggle="modal"
                                data-bs-target="#aprobarModal"
                                onclick="setApprovalData({{ $reserva->id }}, '{{ $reserva->user->name }}', 'verificar')">
                            ✅ Aprobar
                        </button>
                        <button type="button"
                                class="btn btn-sm btn-danger btn-modern"
                                data-bs-toggle="modal"
                                data-bs-target="#rechazarModal"
                                onclick="setRejectionData({{ $reserva->id }}, '{{ $reserva->user->name }}')">
                            ❌ Rechazar
                        </button>
                    @elseif($requiereVerificacion && $reserva->estado == 'pendiente_pago')
                        <span class="badge bg-success">✅ Aprobado</span>
                        <button type="button"
                                class="btn btn-sm btn-warning btn-modern"
                                data-bs-toggle="modal"
                                data-bs-target="#rechazarModal"
                                onclick="setRejectionData({{ $reserva->id }}, '{{ $reserva->user->name }}')">
                            🚫 Cancelar
                        </button>
                    @elseif($reserva->estado == 'cancelar_por_conductor')
                        <span class="badge bg-danger">❌ Cancelado por conductor</span>
                    @endif
                </div>
            </div>
            
            <div class="ratings-section">
                <h5 class="ratings-title">🗣️ Calificaciones</h5>
                
                {{-- Comentario del pasajero al conductor --}}
                @if($reserva->calificacionPasajero)
                    <div class="rating-item">
                        <div class="rating-header">Pasajero comentó:</div>
                        <div class="rating-comment">{{ $reserva->calificacionPasajero->comentario }}</div>
                        <div class="rating-stars">⭐ Calificación: {{ $reserva->calificacionPasajero->calificacion }}/5</div>
                    </div>
                @else
                    <div class="no-rating">Este pasajero no ha calificado aún al conductor.</div>
                @endif
                
                {{-- Comentario del conductor al pasajero --}}
                @if($reserva->calificacionConductor)
                    <div class="rating-item">
                        <div class="rating-header">Conductor comentó:</div>
                        <div class="rating-comment">{{ $reserva->calificacionConductor->comentario }}</div>
                        <div class="rating-stars">⭐ Calificación: {{ $reserva->calificacionConductor->calificacion }}/5</div>
                    </div>
                @else
                    <div class="no-rating">Aún no has calificado a este pasajero.</div>
                @endif
            </div>
        </div>
        @endforeach
    @else
        <div style="padding: 2rem;">
            <div class="alert alert-secondary alert-modern">
                Aún no hay pasajeros en este viaje.
            </div>
        </div>
    @endif
</div>

<!-- Modal Simple de Aprobación -->
<div class="modal fade" id="aprobarModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Aprobar Pasajero</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <div class="mb-3">
                    <i class="fas fa-check-circle text-success" style="font-size: 3rem;"></i>
                </div>
                <h6 id="modalMessage">¿Estás seguro de aprobar a este pasajero?</h6>
                <p class="text-muted">El pasajero pasará a estado "pendiente de pago"</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                
                <form id="approvalForm" method="POST" style="display: inline;">
                    @csrf
                    <input type="hidden" name="accion" value="verificar">
                    <button type="submit" class="btn btn-success">Sí, Aprobar</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Modal de Rechazar Pasajero -->
<div class="modal fade" id="rechazarModal" tabindex="-1" aria-labelledby="rechazarModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rechazarModalLabel">❌ Rechazar Pasajero</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="modalRejectionMessage">¿Estás seguro de rechazar a este pasajero?</p>
                <div class="alert alert-warning">
                    <small>⚠️ Esta acción no se puede deshacer. El pasajero será notificado del rechazo.</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form id="rejectionForm" method="POST" style="display: inline;">
                    @csrf
                    <input type="hidden" name="accion" value="rechazar">
                    <button type="submit" class="btn btn-danger">❌ Confirmar Rechazo</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Modal de Información del Pasajero -->
<div class="modal fade" id="passengerInfoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary bg-opacity-10">
                <h5 class="modal-title">
                    <i class="fas fa-user me-2"></i>
                    Información del Pasajero
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="passenger-profile">
                    <div class="profile-photo-section text-center mb-4">
                        <div class="profile-photo-container">
                            <img id="passengerPhoto"
                                  src=""
                                  alt="Foto del pasajero"
                                  class="profile-photo">
                            <div id="noPhotoPlaceholder" class="no-photo-placeholder" style="display: none;">
                                <i class="fas fa-user"></i>
                            </div>
                        </div>
                        <h5 id="passengerName" class="mt-3 mb-1"></h5>
                        <div id="passengerRating" class="rating-badge"></div>
                    </div>
                    
                    <!-- Label de verificación -->
                    <div class="verification-status-container mb-3">
                        <div id="verificationStatus" class="verification-badge">
                            <!-- Este contenido se llenará dinámicamente -->
                        </div>
                    </div>
                    
                    <div class="passenger-details-grid">
                        <div class="detail-item">
                            <div class="detail-icon">
                                <i class="fas fa-envelope text-primary"></i>
                            </div>
                            <div class="detail-content">
                                <div class="detail-label">Email</div>
                                <div class="detail-value" id="passengerEmail"></div>
                            </div>
                        </div>
                        
                        <div class="detail-item">
                            <div class="detail-icon">
                                <i class="fas fa-phone text-success"></i>
                            </div>
                            <div class="detail-content">
                                <div class="detail-label">Teléfono</div>
                                <div class="detail-value" id="passengerPhone"></div>
                            </div>
                        </div>
                        
                        <div class="detail-item">
                            <div class="detail-icon">
                                <i class="fas fa-map-marker-alt text-danger"></i>
                            </div>
                            <div class="detail-content">
                                <div class="detail-label">Ciudad</div>
                                <div class="detail-value" id="passengerCity"></div>
                            </div>
                        </div>
                        
                        <div class="detail-item">
                            <div class="detail-icon">
                                <i class="fas fa-chair text-info"></i>
                            </div>
                            <div class="detail-content">
                                <div class="detail-label">Puestos reservados</div>
                                <div class="detail-value" id="passengerSeats"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

    <!-- Botón de regreso -->
    <div class="actions-area" style="margin-top: 2rem;">
        <a href="{{ route('dashboard') }}" class="btn btn-link btn-modern">⬅️ Volver al dashboard</a>
    </div>
</div>
<div class="modal fade" id="modalCancelarViaje" tabindex="-1" aria-labelledby="modalCancelarViajeLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="modalCancelarViajeLabel">❌ Cancelar Viaje</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="formCancelarViaje" method="POST">
        @csrf
        @method('DELETE')
        <div class="modal-body">
          <div class="alert alert-warning">
            <strong>⚠️ Atención:</strong> Esta acción no se puede deshacer. El viaje será cancelado permanentemente.
          </div>
          
          <div class="mb-3">
            <label for="motivoCancelacion" class="form-label">
              <strong>Motivo de cancelación</strong> <span class="text-danger">*</span>
            </label>
            <textarea class="form-control" id="motivoCancelacion" name="motivo_cancelacion" rows="4" 
                      placeholder="Explica brevemente por qué cancelas este viaje..." required></textarea>
            <div class="form-text">Este motivo será visible para los pasajeros que tenían reservas.</div>
          </div>
          
          <div class="mb-3">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="confirmarCancelacion" required>
              <label class="form-check-label" for="confirmarCancelacion">
                Confirmo que deseo cancelar este viaje
              </label>
            </div>
          </div>
        </div>
        
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="fas fa-times me-1"></i> Cerrar
          </button>
          <button type="submit" class="btn btn-danger">
            <i class="fas fa-ban me-1"></i> Cancelar Viaje
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- Cargar Google Maps para esta vista -->
<script async defer src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.key') }}&callback=initViajeDetalleMapa&v=3.55"></script>

<!-- Script para el mapa -->
<script>
// Función para configurar los datos de aprobación
function setApprovalData(reservaId, nombrePasajero, accion = 'verificar') {
    // Configurar el formulario de aprobación
    const form = document.getElementById('approvalForm');
    form.action = `/conductor/verificar-pasajero/${reservaId}`;
    
    // Actualizar el mensaje del modal
    const modalMessage = document.getElementById('modalMessage');
    modalMessage.textContent = `¿Estás seguro de aprobar a ${nombrePasajero}?`;
}

// Función para configurar los datos de rechazo
function setRejectionData(reservaId, nombrePasajero) {
    // Configurar el formulario de rechazo
    const form = document.getElementById('rejectionForm');
    form.action = `/conductor/verificar-pasajero/${reservaId}`;
    
    // Actualizar el mensaje del modal
    const modalMessage = document.getElementById('modalRejectionMessage');
    modalMessage.textContent = `¿Estás seguro de rechazar a ${nombrePasajero}?`;
}

function showPassengerModal(userId, name, photo, email, phone, city, rating, seats, userVerified = 0) {
    // Llenar información básica
    document.getElementById('passengerName').textContent = name;
    document.getElementById('passengerEmail').textContent = email;
    document.getElementById('passengerPhone').textContent = phone;
    document.getElementById('passengerCity').textContent = city;
    document.getElementById('passengerSeats').textContent = seats;
    
    // Manejar foto del perfil
    const photoElement = document.getElementById('passengerPhoto');
    const placeholderElement = document.getElementById('noPhotoPlaceholder');
    
    if (photo && photo.trim() !== '') {
        photoElement.src = photo;
        photoElement.style.display = 'block';
        placeholderElement.style.display = 'none';
    } else {
        photoElement.style.display = 'none';
        placeholderElement.style.display = 'flex';
    }
    
    // Manejar calificación
    const ratingElement = document.getElementById('passengerRating');
    if (rating && rating > 0) {
        ratingElement.innerHTML = `<i class="fas fa-star text-warning"></i> ${rating}/5`;
        ratingElement.style.display = 'block';
    } else {
        ratingElement.innerHTML = '<span class="text-muted">Sin calificación</span>';
        ratingElement.style.display = 'block';
    }
    
    // Manejar estado de verificación del usuario
    const verificationElement = document.getElementById('verificationStatus');
    verificationElement.className = 'verification-badge'; // Reset clases
    
    if (parseInt(userVerified) === 1) {
        verificationElement.classList.add('verified');
        verificationElement.innerHTML = '<i class="fas fa-shield-check"></i> Usuario Verificado';
    } else {
        verificationElement.classList.add('not-verified');
        verificationElement.innerHTML = '<i class="fas fa-shield-exclamation"></i> Usuario No Verificado';
    }
    
    // Mostrar el modal
    const modal = new bootstrap.Modal(document.getElementById('passengerInfoModal'));
    modal.show();
}
function setApprovalData(reservaId, nombrePasajero) {
    // Configurar el formulario
    const form = document.getElementById('approvalForm');
    form.action = `/conductor/verificar-pasajero/${reservaId}`;
    
    // Actualizar el mensaje del modal
    const modalMessage = document.getElementById('modalMessage');
    modalMessage.textContent = `¿Estás seguro de aprobar a ${nombrePasajero}?`;
}
function initViajeDetalleMapa() {
    try {
        // Coordenadas del origen y destino desde Laravel
        const origen = {
            lat: parseFloat({{ $viaje->origen_lat }}),
            lng: parseFloat({{ $viaje->origen_lng }})
        };
        
        const destino = {
            lat: parseFloat({{ $viaje->destino_lat }}),
            lng: parseFloat({{ $viaje->destino_lng }})
        };

        console.log('Coordenadas origen:', origen);
        console.log('Coordenadas destino:', destino);

        // Inicializar el mapa
        const map = new google.maps.Map(document.getElementById('map'), {
            zoom: 12,
            center: origen,
            mapTypeId: 'roadmap'
        });

        // Crear marcadores primero
        const markerOrigen = new google.maps.Marker({
            position: origen,
            map: map,
            title: 'Origen: {{ addslashes($viaje->origen_direccion) }}',
            icon: {
                url: 'https://maps.google.com/mapfiles/ms/icons/green-dot.png'
            }
        });
        
        const markerDestino = new google.maps.Marker({
            position: destino,
            map: map,
            title: 'Destino: {{ addslashes($viaje->destino_direccion) }}',
            icon: {
                url: 'https://maps.google.com/mapfiles/ms/icons/red-dot.png'
            }
        });

        // Ajustar vista para mostrar ambos puntos
        const bounds = new google.maps.LatLngBounds();
        bounds.extend(origen);
        bounds.extend(destino);
        map.fitBounds(bounds);

        // Intentar mostrar la ruta (si falla, al menos tenemos los marcadores)
        const directionsService = new google.maps.DirectionsService();
        const directionsRenderer = new google.maps.DirectionsRenderer({
            suppressMarkers: true, // Usamos nuestros marcadores personalizados
            polylineOptions: {
                strokeColor: '#4285f4',
                strokeWeight: 4
            }
        });
        
        directionsRenderer.setMap(map);

        directionsService.route({
            origin: origen,
            destination: destino,
            travelMode: google.maps.TravelMode.DRIVING
        }, function(response, status) {
            if (status === 'OK') {
                directionsRenderer.setDirections(response);
                console.log('Ruta cargada exitosamente');
            } else {
                console.log('No se pudo cargar la ruta, pero los marcadores están visibles. Error:', status);
            }
        });

    } catch (error) {
        console.error('Error al inicializar el mapa:', error);
        document.getElementById('map').innerHTML = '<div style="padding: 20px; text-align: center; color: #666;">Error al cargar el mapa. Verifica la configuración de Google Maps.</div>';
    }
}

// Función para cargar el mapa cuando esté listo
function loadMap() {
    if (typeof google !== 'undefined' && google.maps) {
        initViajeDetalleMapa();
    } else {
        setTimeout(loadMap, 100); // Intentar cada 100ms
    }
}

// Inicializar cuando carga la página
document.addEventListener('DOMContentLoaded', function() {

    loadMap();
     // Obtener todos los botones de cancelar
    const botonesCancelar = document.querySelectorAll('.btn-cancelar-viaje');
    const modal = new bootstrap.Modal(document.getElementById('modalCancelarViaje'));
    const form = document.getElementById('formCancelarViaje');
    
    botonesCancelar.forEach(boton => {
        boton.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Obtener la URL del formulario original
            const actionUrl = this.closest('form').action;
            
            // Establecer la acción del modal al mismo endpoint
            form.action = actionUrl;
            
            // Mostrar el modal
            modal.show();
        });
    });
    
    // Validación del formulario
    form.addEventListener('submit', function(e) {
        const motivo = document.getElementById('motivoCancelacion').value.trim();
        const confirmacion = document.getElementById('confirmarCancelacion').checked;
        
        if (!motivo || motivo.length < 10) {
            e.preventDefault();
            alert('El motivo debe tener al menos 10 caracteres');
            return false;
        }
        
        if (!confirmacion) {
            e.preventDefault();
            alert('Debes confirmar la cancelación');
            return false;
        }
        
        // Deshabilitar el botón para evitar doble envío
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Cancelando...';
    });
});

// También exponer la función globalmente por si acaso
window.initViajeDetalleMapa = initViajeDetalleMapa;

</script>
@endsection