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
                        <div class="value">{{ $viaje->fecha_salida }}</div>
                    </div>
                </div>
                
                <div class="info-item">
                    <div class="icon">🕒</div>
                    <div class="content">
                        <div class="label">Hora</div>
                        <div class="value">{{ $viaje->hora_salida ?? 'No definida' }}</div>
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
                        <div class="value">{{ $viaje->vehiculo ?? 'No registrado' }}</div>
                    </div>
                </div>
                
                <div class="info-item">
                    <div class="icon">💰</div>
                    <div class="content">
                        <div class="label">Valor por persona</div>
                        <div class="value">${{ number_format($viaje->valor_cobrado, 0) }}</div>
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

                @if($viaje->estado === 'pendiente')
                <form method="POST" action="{{ route('conductor.viaje.eliminar', $viaje->id) }}" onsubmit="return confirm('¿Cancelar este viaje?')" style="margin: 0;">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-modern">🗑 Cancelar Viaje</button>
                </form>
                @endif
            </div>
        </div>
    </div>

    <!-- Sección de pasajeros -->
    <div class="passengers-section">
        <h4 class="section-header">👥 Pasajeros</h4>

        @if($viaje->reservas->count())
            @foreach($viaje->reservas as $reserva)
            <div class="passenger-card">
                <div class="passenger-info">
                    <div class="passenger-details">
                        <h6>{{ $reserva->user->name }}</h6>
                        <div class="passenger-meta">Reservó {{ $reserva->cantidad_puestos }} puesto(s)</div>
                        @if($reserva->user->calificacion)
                            <div class="rating-display">⭐ Calificación: {{ $reserva->user->calificacion }}/5</div>
                        @endif
                    </div>
                    <a href="{{ route('chat.ver', $viaje->id) }}" class="btn btn-sm btn-outline-primary btn-modern">💬 Chat</a>
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

    <!-- Botón de regreso -->
    <div class="actions-area" style="margin-top: 2rem;">
        <a href="{{ route('dashboard') }}" class="btn btn-link btn-modern">⬅️ Volver al dashboard</a>
    </div>
</div>
@endsection