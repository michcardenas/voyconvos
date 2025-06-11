@extends('layouts.app_dashboard')

@section('content')
<style>
    /* TUS CLASES ORIGINALES EXACTAS - SIN CAMBIOS */
    .bg-vcv-primary {
        background-color: #003366 !important;
    }

    .bg-vcv-info {
        background-color: #00BFFF !important;
    }

    .text-vcv {
        color: #003366;
    }

    .shadow-soft {
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
    }

    @media (max-width: 768px) {
        .table td,
        .table th {
            font-size: 13px;
            white-space: nowrap;
        }

        .table td form {
            display: inline-block;
            margin-top: 4px;
        }

        .table ul {
            padding-left: 15px;
        }
    }

    /* ESTILOS ADICIONALES PARA REPLICAR EL DISEÑO DEL PASAJERO */

    /* Header con fondo azul como en la imagen */
    .welcome-header {
        background: linear-gradient(135deg, #003366 0%, #004080 100%);
        color: white;
        padding: 1.5rem;
        border-radius: 8px;
        margin-bottom: 2rem;
        box-shadow: 0 4px 12px rgba(0, 51, 102, 0.15);
    }

    .welcome-header h2 {
        color: white !important;
        margin: 0;
        font-weight: 600;
        font-size: 1.5rem;
    }

    .welcome-header p {
        margin: 0;
        opacity: 0.9;
        font-size: 0.9rem;
    }

    /* Cards de estadísticas como en la imagen del pasajero */
    .stats-cards .card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        overflow: hidden;
        position: relative;
    }

    .stats-cards .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.12);
    }

    .stats-cards .card-body {
        padding: 1.5rem;
        text-align: center;
    }

    .stats-cards .card-title {
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.5rem;
        opacity: 0.9;
    }

    .stats-cards .stats-number {
        font-size: 2.5rem;
        font-weight: 700;
        margin: 0;
        line-height: 1;
    }

    /* Sección de próximos viajes */
    .section-container {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        border: 1px solid #f0f0f0;
    }

    .section-title {
        color: #003366;
        font-weight: 600;
        font-size: 1.3rem;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #f0f0f0;
    }

    /* Tabla mejorada como en el diseño del pasajero */
    .table-modern {
        background: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        border: 1px solid #e5e5e5;
    }

    .table-modern thead th {
        background: #f8f9fa;
        color: #495057;
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        padding: 1rem 0.75rem;
        border: none;
    }

    .table-modern tbody tr {
        border-bottom: 1px solid #f0f0f0;
        transition: background-color 0.2s ease;
    }

    .table-modern tbody tr:hover {
        background-color: #f8f9fa;
    }

    .table-modern tbody td {
        padding: 0.875rem 0.75rem;
        vertical-align: middle;
        border: none;
        font-size: 0.9rem;
    }

    /* Counter de próximos viajes */
    .trips-counter {
        font-size: 2rem;
        font-weight: 700;
        color: #003366;
        margin-bottom: 1rem;
    }

    /* Badges modernos */
    .badge-modern {
        padding: 0.4rem 0.8rem;
        border-radius: 6px;
        font-weight: 500;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.2px;
    }

    /* Botones como en el diseño del pasajero */
    .btn-modern {
        border-radius: 6px;
        font-weight: 500;
        padding: 0.5rem 1rem;
        font-size: 0.85rem;
        transition: all 0.2s ease;
        border: 1px solid transparent;
    }

    .btn-outline-primary.btn-modern {
        border-color: #003366;
        color: #003366;
    }

    .btn-outline-primary.btn-modern:hover {
        background-color: #003366;
        border-color: #003366;
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 2px 6px rgba(0, 51, 102, 0.2);
    }

    /* Sección de calificaciones del conductor */
    .ratings-section {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        padding: 1.5rem;
        margin-top: 1.5rem;
        border: 1px solid #f0f0f0;
    }

    .rating-summary {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
        padding: 1.5rem;
        border-radius: 8px;
        text-align: center;
        margin-bottom: 1.5rem;
    }

    .rating-score {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .rating-text {
        font-size: 0.9rem;
        opacity: 0.9;
    }

    .rating-stars {
        font-size: 1.2rem;
        color: #ffc107;
        margin: 0.5rem 0;
    }

    /* Comentarios individuales */
    .review-item {
        border-bottom: 1px solid #f0f0f0;
        padding: 1rem 0;
    }

    .review-item:last-child {
        border-bottom: none;
    }

    .review-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.5rem;
    }

    .review-stars {
        color: #ffc107;
        font-size: 0.9rem;
    }

    .review-date {
        color: #6c757d;
        font-size: 0.8rem;
    }

    .review-text {
        color: #495057;
        font-size: 0.9rem;
        line-height: 1.4;
    }

    .review-author {
        font-weight: 600;
        color: #003366;
    }

    /* Enlaces de pasajeros */
    .passenger-link {
        color: #003366;
        text-decoration: none;
        font-weight: 500;
        transition: color 0.2s ease;
    }

    .passenger-link:hover {
        color: #00BFFF;
        text-decoration: underline;
    }

    /* Alertas mejoradas */
    .alert-modern {
        border: none;
        border-radius: 8px;
        padding: 1rem 1.5rem;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
        border-left: 4px solid #00BFFF;
        background: rgba(0, 191, 255, 0.05);
    }

    /* Lista de reservas mejorada */
    .reservations-list {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
        overflow: hidden;
        border: 1px solid #f0f0f0;
    }

    .reservation-item {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #f0f0f0;
        transition: background-color 0.2s ease;
    }

    .reservation-item:hover {
        background-color: #f8f9fa;
    }

    .reservation-item:last-child {
        border-bottom: none;
    }

    /* Botones de acción finales */
    .action-buttons {
        background: white;
        border-radius: 8px;
        padding: 1.5rem;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
        border: 1px solid #f0f0f0;
    }

    /* Responsive adicional */
    @media (max-width: 768px) {
        .welcome-header {
            padding: 1rem;
        }
        
        .welcome-header h2 {
            font-size: 1.3rem;
        }
        
        .stats-number {
            font-size: 2rem !important;
        }
        
        .trips-counter {
            font-size: 1.5rem;
        }
        
        .section-container {
            padding: 1rem;
        }
    }
</style>

<div class="container py-4">
    <!-- Header de bienvenida como en el pasajero -->
    <div class="welcome-header">
        <h2>👋 Bienvenido, {{ auth()->user()->name ?? 'Invitado' }}</h2>
        <p>Gestiona tus viajes y conecta con otros viajeros de forma segura</p>
    </div>
<!-- 
    @if($notificaciones > 0)
    <div class="alert alert-info alert-dismissible fade show alert-modern" role="alert">
        🚨 <strong>{{ $notificaciones }}</strong> nueva(s) reserva(s) en tus viajes.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
    </div>
    @endif -->

    @if($reservasDetalles->count())
    <div class="section-container">
        <h5 class="section-title">📋 Reservas recientes</h5>
        <div class="reservations-list">
            @foreach($reservasDetalles as $reserva)
            <div class="reservation-item d-flex justify-content-between align-items-center">
                <div>
                    <strong>{{ $reserva->user->name }}</strong> reservó <strong>{{ $reserva->cantidad_puestos }}</strong> puesto(s)
                    <br>
                    <small class="text-muted">{{ \Carbon\Carbon::parse($reserva->created_at)->diffForHumans() }}</small>
                </div>
                <a href="{{ route('chat.ver', $reserva->viaje_id) }}" class="btn btn-sm btn-outline-primary btn-modern">
                    💬 Chat
                </a>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Cards de estadísticas -->
    <div class="row g-4 mb-4 stats-cards">
        <div class="col-md-4">
            <div class="card text-white bg-vcv-primary shadow-soft">
                <div class="card-body">
                    <h5 class="card-title">Total de Viajes</h5>
                    <p class="fs-3 stats-number">{{ $totalViajes ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success shadow-soft">
                <div class="card-body">
                    <h5 class="card-title">Próximos Viajes</h5>
                    <p class="fs-3 stats-number">{{ $viajesProximos ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-secondary shadow-soft">
                <div class="card-body">
                    <h5 class="card-title">Viajes Realizados</h5>
                    <p class="fs-3 stats-number">{{ $viajesRealizados ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Sección de próximos viajes -->
    <div class="section-container">
        <h4 class="section-title">🚍 Tus próximos viajes</h4>

        <div class="d-flex align-items-center mb-3">
            <p class="trips-counter me-3">
                {{ $viajesProximos ?? 0 }}
            </p>
            @if($reservasNoVistas > 0)
            <span class="badge bg-success badge-modern">🔔 {{ $reservasNoVistas }} nuevas reservas</span>
            @endif
        </div>

        @if(isset($viajesProximosList) && count($viajesProximosList) > 0)
        <div class="table-responsive">
            <table class="table table-modern align-middle table-hover">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Origen</th>
                        <th>Destino</th>
                        <th>Hora</th>
                        <th>Fecha de viaje</th>
                        <th>Ocupación</th>
                        <th>Rol</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($viajesProximosList as $viaje)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($viaje->created_at)->format('Y-m-d') }}</td>
                        <td>{{ $viaje->origen_direccion }}</td>
                        <td>{{ $viaje->destino_direccion }}</td>
                        <td>{{ $viaje->hora_salida ?? '—' }}</td>
                        <td>{{ $viaje->fecha_salida ?? '—' }}</td>
                        <td>
                            {{ $viaje->puestos_disponibles }} / {{ $viaje->reservas->sum('cantidad_puestos') }}
                            <ul class="mt-1 mb-0">
                                @foreach ($viaje->reservas as $reserva)
                                <li>
                                    <a href="{{ route('chat.ver', $reserva->viaje_id) }}" class="passenger-link">
                                        {{ $reserva->user->name }}
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        </td>
                        <td>
                            @if($viaje->conductor_id === auth()->id())
                            <span class="badge bg-success badge-modern">Conductor</span>
                            @else
                            <span class="badge bg-info text-dark badge-modern">Pasajero</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-vcv-info text-white badge-modern">{{ ucfirst($viaje->estado) }}</span>
                        </td>
                        <td>
                            @if($viaje->estado !== 'cancelado')
                            <a href="{{ route('conductor.viaje.detalle', $viaje->id) }}" class="btn btn-sm btn-outline-primary btn-modern">
                                👁 Ver detalles
                            </a>
                            <form method="POST" action="{{ route('conductor.viaje.eliminar', $viaje->id) }}"
                                onsubmit="return confirm('¿Estás seguro de cancelar este viaje?')" style="display: inline-block; margin-top: 4px;">
                                @csrf
                                @method('DELETE')
                            </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="alert alert-info alert-modern">
            No tienes viajes próximos.
        </div>
        @endif
    </div>

    <!-- Nueva sección de calificaciones del conductor -->
    <div class="ratings-section">
        <h4 class="section-title">⭐ Calificaciones como Conductor</h4>
        
        <!-- <div class="rating-summary">
            <div class="rating-score">4.5</div>
            <div class="rating-stars">★★★★★</div>
            <div class="rating-text">Basado en 42 calificaciones</div>
        </div>

        <div class="reviews-list">
            <div class="review-item">
                <div class="review-header">
                    <span class="review-author">María González</span>
                    <span class="review-date">hace 2 días</span>
                </div>
                <div class="review-stars">★★★★★</div>
                <div class="review-text">"Excelente conductor, muy puntual y respetuoso. El viaje fue muy cómodo y seguro."</div>
            </div>
            
            <div class="review-item">
                <div class="review-header">
                    <span class="review-author">Carlos Ruiz</span>
                    <span class="review-date">hace 1 semana</span>
                </div>
                <div class="review-stars">★★★★☆</div>
                <div class="review-text">"Muy buena experiencia, recomendado para futuros viajes."</div>
            </div>
        </div> -->
    </div>

    @if(auth()->user()->hasRole('conductor'))
    <div class="action-buttons">
        <div class="d-flex gap-3 flex-wrap">
            <a href="{{ route('conductor.gestion') }}" class="btn btn-outline-primary btn-modern">
                ➕ Agendar nuevo viaje
            </a>
            <a href="#" class="btn btn-link text-decoration-none">
                ¿Necesitas ayuda?
            </a>
        </div>
    </div>
    @endif
</div>
@endsection