@extends('layouts.app_admin')

@section('title', 'Configuración Admin')

@section('content')
<div class="container py-5">
    <h1 class="fw-bold mb-4">Gestión de Configuración Admin</h1>
    @if(session('success'))
    <div class="alert alert-success mb-4">
        {{ session('success') }}
    </div>
    @endif

    {{-- Botón de Nueva Configuración --}}
    <div class="card mb-4">
        <div class="card-body py-3">
            <a href="{{ route('admin.gestion.create') }}" class="btn btn-success">
                <i class="fas fa-plus me-1"></i>Nueva Configuración
            </a>
        </div>
    </div>

    {{-- Configuraciones agrupadas por nombre --}}
    @foreach($configuraciones as $nombre => $configs)
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <div class="row align-items-center">
                <div class="col">
                    <h5 class="mb-0">
                        @php
                            $iconos = [
                                'comision' => 'percentage',
                                'maximo' => 'dollar-sign',
                                'costo_km' => 'road',
                            ];
                            $nombres = [
                                'comision' => 'Comisión de la plataforma',
                                'maximo' => 'Monto máximo permitido',
                                'costo_km' => 'Costo por kilómetro recorrido',
                            ];
                            $icono = $iconos[$nombre] ?? 'cog';
                            $nombreMostrar = $nombres[$nombre] ?? ucfirst(str_replace('_', ' ', $nombre));
                        @endphp
                        <i class="fas fa-{{ $icono }} me-2"></i>
                        {{ $nombreMostrar }}
                    </h5>
                </div>
                <div class="col-auto">
                    <span class="badge bg-light text-dark">{{ $configs->count() }} registro(s)</span>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="10%">ID</th>
                            <th width="40%">Valor</th>
                            <th width="25%">Fecha Creación</th>
                            <th width="25%">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($configs->sortByDesc('created_at') as $index => $config)
                        <tr class="{{ $index === 0 ? 'table-success-light' : '' }}">
                            <td>{{ $config->id_configuracion }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <code class="bg-light px-2 py-1 rounded">{{ $config->valor }}</code>
                                    @if($index === 0)
                                        <span class="badge bg-success ms-2" title="Valor actual">
                                            <i class="fas fa-star"></i> Actual
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                @if($config->created_at)
                                    <small class="text-muted">
                                        {{ $config->created_at->format('d/m/Y') }}<br>
                                        <span class="text-xs">{{ $config->created_at->format('H:i') }}</span>
                                    </small>
                                @else
                                    <small class="text-muted">Sin fecha</small>
                                @endif
                            </td>
                            <td>
                                @if($index === 0)
                                    <span class="badge bg-success">
                                        <i class="fas fa-check-circle me-1"></i>Vigente
                                    </span>
                                @else
                                    <span class="badge bg-secondary">
                                        <i class="fas fa-history me-1"></i>Histórico
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endforeach

    {{-- Información adicional --}}
    <div class="row mt-3">
        <div class="col-md-6">
            <div class="small text-muted">
                <i class="fas fa-info-circle me-1"></i>
                Total de tipos: <strong>{{ $configuraciones->count() }}</strong>
            </div>
        </div>
        <div class="col-md-6">
            <div class="small text-muted text-end">
                <i class="fas fa-clock me-1"></i>
                <strong>Ordenado por:</strong> Más recientes primero en cada grupo
            </div>
        </div>
    </div>

    {{-- Resumen de valores actuales --}}
    <div class="card mt-4">
        <div class="card-header bg-info text-white">
            <h6 class="mb-0">
                <i class="fas fa-chart-line me-2"></i>Valores Actuales
            </h6>
        </div>
        <div class="card-body">
            <div class="row">
                @foreach($configuraciones as $nombre => $configs)
                @php
                    $valorActual = $configs->sortByDesc('created_at')->first();
                @endphp
                <div class="col-md-6 mb-3">
                    <div class="d-flex align-items-center p-3 rounded config-card-resumen">
                        <div class="me-3">
                            @php
                                $iconos = [
                                    'comision' => 'percentage',
                                    'maximo' => 'dollar-sign',
                                    'costo_km' => 'road',
                                ];
                                $nombres = [
                                    'comision' => 'Comisión de la plataforma',
                                    'maximo' => 'Monto máximo permitido',
                                    'costo_km' => 'Costo por kilómetro recorrido',
                                ];
                                $icono = $iconos[$nombre] ?? 'cog';
                                $nombreMostrar = $nombres[$nombre] ?? ucfirst(str_replace('_', ' ', $nombre));
                            @endphp
                            <i class="fas fa-{{ $icono }} text-primary fa-2x"></i>
                        </div>
                        <div>
                            <h6 class="mb-1 text-dark fw-bold">{{ $nombreMostrar }}</h6>
                            <p class="mb-0">
                                <code class="fs-5 fw-bold bg-dark text-white px-2 py-1 rounded">{{ $valorActual->valor }}</code>
                            </p>
                            <small class="text-muted">
                                Actualizado: {{ $valorActual->created_at ? $valorActual->created_at->format('d/m/Y H:i') : 'Sin fecha' }}
                            </small>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

{{-- CSS personalizado --}}
<style>
.card {
    border: none;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border-radius: 0.5rem;
}

.table-hover tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.05);
}

.table-success-light {
    background-color: rgba(25, 135, 84, 0.1) !important;
    border-left: 4px solid #198754;
}

.text-xs {
    font-size: 0.65rem;
}

code {
    font-size: 0.85rem;
}

.card-header {
    border-radius: 0.5rem 0.5rem 0 0 !important;
}

/* Forzar color blanco en encabezados de tarjetas */
.card-header.bg-primary h5,
.card-header.bg-primary .badge,
.card-header.bg-primary i {
    color: #ffffff !important;
}

.card-header.bg-info h6,
.card-header.bg-info i {
    color: #ffffff !important;
}

/* Forzar texto oscuro en encabezados de tabla */
.table-light th {
    color: #212529 !important;
    background-color: #f8f9fa !important;
    font-weight: 600;
}

/* Asegurar que el texto del tbody también sea oscuro */
.table tbody td {
    color: #212529 !important;
}

.table tbody td code {
    color: #212529 !important;
}

/* Tarjetas de resumen de configuración */
.config-card-resumen {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border: 2px solid #dee2e6;
    border-left: 4px solid #0d6efd;
    transition: all 0.3s ease;
}

.config-card-resumen:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    border-left-color: #0b5ed7;
}

.config-card-resumen h6 {
    color: #212529 !important;
    font-weight: 600;
}

.config-card-resumen code {
    background-color: #212529 !important;
    color: #ffffff !important;
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
}

@media (max-width: 768px) {
    .table-responsive {
        font-size: 0.875rem;
    }

    .col-md-6 {
        margin-bottom: 1rem;
    }
}
</style>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Auto-hide alert
        const alert = document.querySelector('.alert-success');
        if (alert) {
            setTimeout(() => {
                alert.style.transition = "opacity 0.5s ease";
                alert.style.opacity = 0;
                setTimeout(() => alert.remove(), 500);
            }, 2000);
        }
    });
</script>
@endpush