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

    /* FORMULARIO DE FILTROS MEJORADO */
    .filtros-container {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        border: 1px solid #dee2e6;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
    }

    .filtros-container .form-control,
    .filtros-container .form-select {
        border-radius: 8px;
        border: 1px solid #ced4da;
        padding: 0.6rem 0.8rem;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        background-color: white;
    }

    .filtros-container .form-control:focus,
    .filtros-container .form-select:focus {
        border-color: #003366;
        box-shadow: 0 0 0 0.2rem rgba(0, 51, 102, 0.25);
        transform: translateY(-1px);
    }

    .filtros-container .btn-primary {
        background-color: #003366;
        border-color: #003366;
        border-radius: 8px;
        font-weight: 600;
        padding: 0.6rem 1.5rem;
        transition: all 0.3s ease;
    }

    .filtros-container .btn-primary:hover {
        background-color: #002244;
        border-color: #002244;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 51, 102, 0.3);
    }

    .filtros-container .btn-outline-secondary {
        border-color: #6c757d;
        color: #6c757d;
        border-radius: 8px;
        font-weight: 500;
        padding: 0.6rem 1.5rem;
    }

    .filtros-container .btn-outline-secondary:hover {
        background-color: #6c757d;
        border-color: #6c757d;
        transform: translateY(-1px);
    }

    /* Badges de filtros activos */
    .filtros-activos {
        background: white;
        border-radius: 8px;
        padding: 0.8rem 1rem;
        margin-bottom: 1rem;
        border-left: 4px solid #003366;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .filtros-activos .badge {
        margin: 0.2rem 0.3rem 0.2rem 0;
        padding: 0.4rem 0.8rem;
        border-radius: 15px;
        font-weight: 500;
        font-size: 0.75rem;
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

    /* RESPONSIVE MEJORADO */
    @media (max-width: 991px) {
        .filtros-container .col-lg-2,
        .filtros-container .col-lg-3 {
            margin-bottom: 0.75rem;
        }
        
        .table-modern {
            font-size: 0.8rem;
        }
        
        .table-modern thead th {
            padding: 0.75rem 0.5rem;
            font-size: 0.75rem;
        }
        
        .table-modern tbody td {
            padding: 0.75rem 0.5rem;
            font-size: 0.8rem;
        }
    }

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
        
        .filtros-container {
            padding: 1rem;
        }
        
        .filtros-container .row > div {
            margin-bottom: 0.75rem;
        }
        
        .table td,
        .table th {
            font-size: 12px;
            white-space: nowrap;
            padding: 0.5rem 0.3rem;
        }

        .table td form {
            display: inline-block;
            margin-top: 4px;
        }

        .table ul {
            padding-left: 15px;
            font-size: 11px;
        }
        
        .btn-modern {
            font-size: 0.75rem;
            padding: 0.4rem 0.8rem;
        }
        
        .badge-modern {
            font-size: 0.65rem;
            padding: 0.3rem 0.6rem;
        }
        
        /* Hacer que la tabla sea horizontal scrollable en móvil */
        .table-responsive {
            border-radius: 8px;
        }
        
        /* Apilar botones en móvil */
        .d-flex.gap-3 {
            flex-direction: column;
            gap: 0.5rem !important;
        }
        
        /* Filtros en columna en móvil */
        .filtros-container .row {
            flex-direction: column;
        }
        
        .filtros-activos .badge {
            display: inline-block;
            margin: 0.2rem 0.2rem;
            font-size: 0.7rem;
        }
    }

    @media (max-width: 576px) {
        .container {
            padding-left: 10px;
            padding-right: 10px;
        }
        
        .stats-cards .card-body {
            padding: 1rem;
        }
        
        .stats-cards .card-title {
            font-size: 0.7rem;
        }
        
        .stats-number {
            font-size: 1.8rem !important;
        }
        
        .table-modern thead th {
            font-size: 0.7rem;
            padding: 0.5rem 0.2rem;
        }
        
        .table-modern tbody td {
            font-size: 0.7rem;
            padding: 0.5rem 0.2rem;
        }
        
        .btn-modern {
            font-size: 0.7rem;
            padding: 0.3rem 0.6rem;
        }
    }

    /* Mejorar contraste para accesibilidad */
    .form-control::placeholder {
        color: #6c757d;
        opacity: 1;
    }

    /* Animaciones suaves */
    .card, .section-container, .filtros-container {
        transition: all 0.3s ease;
    }

    .card:hover {
        transform: translateY(-2px);
    }

    /* Loading state para botones */
    .btn.loading {
        pointer-events: none;
        opacity: 0.6;
    }

    .btn.loading::after {
        content: "";
        display: inline-block;
        width: 12px;
        height: 12px;
        border: 2px solid transparent;
        border-top: 2px solid currentColor;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin-left: 0.5rem;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>

<div class="container py-4">
    <div class="welcome-header">
        <h2>👋 Bienvenido, {{ auth()->user()->name ?? 'Invitado' }}</h2>
        <p>Gestiona tus viajes y conecta con otros viajeros de forma segura</p>
    </div>

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

        <!-- Filtros simples mejorados -->
        <div class="filtros-container">
            <form method="GET" action="{{ route('dashboard') }}" class="row g-3">
                <div class="col-lg-3 col-md-6">
                    <label for="estado" class="form-label text-muted small">Estado del viaje</label>
                    <select name="estado" id="estado" class="form-select">
                        <option value="todos" {{ $filtros['estado'] == 'todos' ? 'selected' : '' }}>Todos los estados</option>
                        <option value="pendiente" {{ $filtros['estado'] == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                        <option value="confirmado" {{ $filtros['estado'] == 'confirmado' ? 'selected' : '' }}>Confirmado</option>
                        <option value="en_proceso" {{ $filtros['estado'] == 'en_proceso' ? 'selected' : '' }}>En Proceso</option>
                        <option value="ocupado_total" {{ $filtros['estado'] == 'ocupado_total' ? 'selected' : '' }}>Ocupado Total</option>
                        <option value="completado" {{ $filtros['estado'] == 'completado' ? 'selected' : '' }}>Completado</option>
                    </select>
                </div>
                <div class="col-lg-2 col-md-6">
                    <label for="fecha_desde" class="form-label text-muted small">Desde</label>
                    <input type="date" name="fecha_desde" id="fecha_desde" class="form-control" value="{{ $filtros['fecha_desde'] }}">
                </div>
                <div class="col-lg-2 col-md-6">
                    <label for="fecha_hasta" class="form-label text-muted small">Hasta</label>
                    <input type="date" name="fecha_hasta" id="fecha_hasta" class="form-control" value="{{ $filtros['fecha_hasta'] }}">
                </div>
                <div class="col-lg-3 col-md-6">
                    <label for="buscar" class="form-label text-muted small">Buscar</label>
                    <input type="text" name="buscar" id="buscar" class="form-control" placeholder="Origen, destino..." value="{{ $filtros['buscar'] }}">
                </div>
                <div class="col-lg-2 col-md-12 d-flex flex-column justify-content-end">
                    <button type="submit" class="btn btn-primary mb-2">
                        <i class="fas fa-search me-1"></i> Filtrar
                    </button>
                    @if(array_filter($filtros))
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-1"></i> Limpiar
                    </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Mostrar filtros activos -->
        @if(array_filter($filtros))
        <div class="filtros-activos">
            <small class="text-muted fw-bold">Filtros aplicados: </small>
            @if($filtros['estado'] != 'todos')
                <span class="badge bg-primary">{{ ucfirst($filtros['estado']) }}</span>
            @endif
            @if($filtros['fecha_desde'])
                <span class="badge bg-info">Desde: {{ \Carbon\Carbon::parse($filtros['fecha_desde'])->format('d/m/Y') }}</span>
            @endif
            @if($filtros['fecha_hasta'])
                <span class="badge bg-info">Hasta: {{ \Carbon\Carbon::parse($filtros['fecha_hasta'])->format('d/m/Y') }}</span>
            @endif
            @if($filtros['buscar'])
                <span class="badge bg-warning text-dark">"{{ $filtros['buscar'] }}"</span>
            @endif
        </div>
        @endif

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
                        <td>{{ \Carbon\Carbon::parse($viaje->created_at)->format('d/m/Y') }}</td>
                        <td>
                            <div class="text-truncate" style="max-width: 120px;" title="{{ $viaje->origen_direccion }}">
                                {{ $viaje->origen_direccion }}
                            </div>
                        </td>
                        <td>
                            <div class="text-truncate" style="max-width: 120px;" title="{{ $viaje->destino_direccion }}">
                                {{ $viaje->destino_direccion }}
                            </div>
                        </td>
                        <td>{{ $viaje->hora_salida ?? '—' }}</td>
                        <td>{{ \Carbon\Carbon::parse($viaje->fecha_salida)->format('d/m/Y') ?? '—' }}</td>
                        <td>
                            <span class="fw-bold">{{ $viaje->puestos_disponibles }} / {{ $viaje->reservas->sum('cantidad_puestos') }}</span>
                            @if($viaje->reservas->count() > 0)
                            <ul class="mt-1 mb-0 list-unstyled small">
                                @foreach ($viaje->reservas as $reserva)
                                <li>
                                    <a href="{{ route('chat.ver', $reserva->viaje_id) }}" class="passenger-link">
                                        {{ $reserva->user->name }}
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                            @endif
                        </td>
                        <td>
                            @if($viaje->conductor_id === auth()->id())
                            <span class="badge bg-success badge-modern">Conductor</span>
                            @else
                            <span class="badge bg-info text-dark badge-modern">Pasajero</span>
                            @endif
                        </td>
                        <td>
                            @if($viaje->estado === 'ocupado_total')
                                <span class="badge bg-danger text-white badge-modern">
                                    🚫 {{ ucfirst(str_replace('_', ' ', $viaje->estado)) }}
                                </span>
                            @else
                                <span class="badge bg-vcv-info text-white badge-modern">{{ ucfirst($viaje->estado) }}</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex flex-column gap-1">
                                @if($viaje->estado !== 'cancelado')
                                <a href="{{ route('conductor.viaje.detalle', $viaje->id) }}" class="btn btn-sm btn-outline-primary btn-modern">
                                    👁 Ver detalles
                                </a>
                                @if($viaje->conductor_id === auth()->id())
                               
                                @endif
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="alert alert-info alert-modern text-center">
            @if(array_filter($filtros))
                <h6>No se encontraron viajes con los filtros aplicados</h6>
                <p class="mb-2">Intenta ajustar los criterios de búsqueda</p>
                <a href="{{ route('dashboard') }}" class="btn btn-outline-primary btn-sm">Ver todos los viajes</a>
            @else
                <h6>No tienes viajes registrados</h6>
                <p class="mb-2">¡Comienza creando tu primer viaje!</p>
                @auth
                    @role('conductor')
                    <a href="{{ route('conductor.gestion') }}" class="btn btn-primary btn-sm">Crear viaje</a>
                    @endrole
                @endauth
            @endif
        </div>
        @endif
    </div>

    <!-- Nueva sección de calificaciones del conductor -->
    <div class="ratings-section">
        <h4 class="section-title">⭐ Calificaciones como Conductor</h4>
        <!-- Contenido futuro de calificaciones -->
    </div>

@auth
    @role('conductor')
        <div class="action-buttons">
            <div class="d-flex gap-3 flex-wrap">
                <a href="{{ route('conductor.gestion') }}" class="btn btn-outline-primary btn-modern">
                    ➕ Agendar nuevo viaje
                </a>
                <a href="{{ route('contacto.formulario') }}" class="btn btn-link text-decoration-none">
                    ¿Necesitas ayuda?
                </a>
            </div>
        </div>
    @endrole
@endauth

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Validación de fechas
    const fechaDesde = document.getElementById('fecha_desde');
    const fechaHasta = document.getElementById('fecha_hasta');
    
    if (fechaDesde && fechaHasta) {
        fechaDesde.addEventListener('change', function() {
            if (this.value && fechaHasta.value && this.value > fechaHasta.value) {
                fechaHasta.value = this.value;
            }
            fechaHasta.min = this.value;
        });
        
        fechaHasta.addEventListener('change', function() {
            if (this.value && fechaDesde.value && this.value < fechaDesde.value) {
                fechaDesde.value = this.value;
            }
            fechaDesde.max = this.value;
        });
    }

    // Auto-submit en cambio de estado (opcional)
    const selectEstado = document.getElementById('estado');
    if (selectEstado) {
        selectEstado.addEventListener('change', function() {
            // Opcional: auto-submit cuando cambie el estado
            // this.form.submit();
        });
    }

    // Loading state para el botón de filtrar
    const formFiltros = document.querySelector('form[action*="dashboard"]');
    if (formFiltros) {
        formFiltros.addEventListener('submit', function() {
            const btnSubmit = this.querySelector('button[type="submit"]');
            if (btnSubmit) {
                btnSubmit.classList.add('loading');
                btnSubmit.disabled = true;
            }
        });
    }
});
</script>