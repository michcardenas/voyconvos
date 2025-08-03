@extends('layouts.app_admin')

@section('content')
<style>
    .dashboard-container {
        max-width: 1140px;
        margin: 0 auto;
        padding-top: 100px;
        padding-bottom: 40px;
    }

    .dashboard-title {
        font-size: 2rem;
        font-weight: 600;
        color: #00304b;
        margin-bottom: 30px;
        text-align: center;
    }

    .card-stats {
        width: 250px;
        border-left-width: 5px;
        border-radius: 1rem;
        transition: transform 0.2s ease;
    }

    .card-stats:hover {
        transform: scale(1.02);
    }

    .stat-label {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        margin-bottom: 4px;
    }

    .stat-value {
        font-size: 1.5rem;
        font-weight: bold;
        color: #2e2e2e;
    }

    .stat-subtext {
        font-size: 0.75rem;
        color: #6c757d;
    }

    .stat-icon {
        opacity: 0.2;
    }

    .cards-row {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-around;
        gap: 20px;
    }

    /* Colores personalizados */
    .border-left-primary {
        border-left-color: #4e73df !important;
    }

    .border-left-success {
        border-left-color: #1cc88a !important;
    }

    .border-left-info {
        border-left-color: #36b9cc !important;
    }

    .border-left-warning {
        border-left-color: #f6c23e !important;
    }

    .center-button {
        display: flex;
        justify-content: center;
        margin-top: 40px;
    }

    .btn-view-users {
        background-color: #00304b;
        color: white;
        font-weight: 600;
        padding: 10px 24px;
        border-radius: 8px;
        border: none;
        transition: background-color 0.3s ease;
        text-decoration: none;
    }

    .btn-view-users:hover {
        background-color: #005471;
        color: #fff;
    }
    .table th, .table td {
    vertical-align: middle;
}

.table thead th {
    background-color: #f8f9fc;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.85rem;
}

.table-hover tbody tr:hover {
    background-color: #f1f5f9;
}

.badge {
    padding: 0.5em 0.75em;
    font-size: 0.75rem;
    border-radius: 0.5rem;
}

</style>

<div class="dashboard-container">
    <h1 class="dashboard-title">📊 Dashboard Administrativo</h1>

    <div class="cards-row">
        <!-- Total Usuarios -->
        <div class="card card-stats border-left-primary shadow p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-label text-primary">Total Usuarios</div>
                    <div class="stat-value">{{ $totalUsuarios }}</div>
                </div>
                <i class="fas fa-users fa-2x stat-icon text-primary"></i>
            </div>
        </div>

        <!-- Conductores -->
        <div class="card card-stats border-left-success shadow p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-label text-success">Conductores</div>
                    <div class="stat-value">{{ $conductores }}</div>
                    <div class="stat-subtext">{{ $conductoresVerificados }} verificados</div>
                </div>
                <i class="fas fa-car fa-2x stat-icon text-success"></i>
            </div>
        </div>

        <!-- Pasajeros -->
        <div class="card card-stats border-left-info shadow p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-label text-info">Pasajeros</div>
                    <div class="stat-value">{{ $pasajeros }}</div>
                    <div class="stat-subtext">{{ $pasajerosVerificados }} verificados</div>
                </div>
                <i class="fas fa-user-friends fa-2x stat-icon text-info"></i>
            </div>
        </div>

        <!-- Sin Verificar -->
        <div class="card card-stats border-left-warning shadow p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-label text-warning">Sin Verificar</div>
                    <div class="stat-value">{{ $totalSinVerificar }}</div>
                    <div class="stat-subtext text-danger">¡Requiere atención!</div>
                </div>
                <i class="fas fa-exclamation-triangle fa-2x stat-icon text-warning"></i>
            </div>
        </div>
    </div>

    <!-- Botón centrado -->
    <div class="center-button">
        <a href="{{ url('/admin/users') }}" class="btn-view-users">
            👥 Ver Usuarios
        </a>
    </div>
</div>
<div class="center-button mt-3">
    <a href="{{ route('admin.gestor-pagos') }}" class="btn btn-primary btn-lg">
        💳 Gestor de Pagos
    </a>
</div>

<!-- Tarjetas de viajes -->
<h2 class="text-center mt-5 mb-4" style="color: #00304b;">🚌 Estadísticas de Viajes</h2>
<div class="cards-row">
    <div class="card card-stats border-left-primary shadow p-3">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <div class="stat-label text-primary">Total Viajes</div>
                <div class="stat-value">{{ $viajesTotales }}</div>
            </div>
            <i class="fas fa-route fa-2x stat-icon text-primary"></i>
        </div>
    </div>

    <div class="card card-stats border-left-success shadow p-3">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <div class="stat-label text-success">Viajes Activos</div>
                <div class="stat-value">{{ $viajesActivos }}</div>
            </div>
            <i class="fas fa-check-circle fa-2x stat-icon text-success"></i>
        </div>
    </div>

    <div class="card card-stats border-left-danger shadow p-3">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <div class="stat-label text-danger">Viajes Inactivos</div>
                <div class="stat-value">{{ $viajesInactivos }}</div>
            </div>
            <i class="fas fa-times-circle fa-2x stat-icon text-danger"></i>
        </div>
    </div>
</div>
<div class="mt-5">
    <h4 class="text-center mb-4" style="color: #00304b;">🧾 Últimas Reservas Realizadas</h4>
    
    <div class="table-responsive">
        <table class="table table-hover shadow-sm rounded">
            <thead class="table-primary text-center">
                <tr>
                    <th>Pasajero</th>
                    <th>Conductor</th>
                    <th>Origen</th>
                    <th>Destino</th>
                    <th>Fecha Reserva</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($reservasRecientes as $reserva)
                    <tr class="align-middle text-center">
                        <td>{{ $reserva->pasajero->name ?? 'Desconocido' }}</td>
                        <td>{{ $reserva->viaje->conductor->name ?? 'Desconocido' }}</td>
                        <td>{{ Str::limit($reserva->viaje->origen_direccion ?? 'N/A', 25) }}</td>
                        <td>{{ Str::limit($reserva->viaje->destino_direccion ?? 'N/A', 25) }}</td>
                        <td>{{ optional($reserva->fecha_reserva)->format('d M Y, H:i') ?? 'N/D' }}</td>
                        <td>
                            @switch($reserva->estado)
                                @case('confirmada')
                                    <span class="badge bg-success">Confirmada</span>
                                    @break
                                @case('pendiente')
                                @case('pendiente_pago')
                                    <span class="badge bg-warning text-dark">Pendiente</span>
                                    @break
                                @case('cancelada')
                                @case('fallida')
                                    <span class="badge bg-danger">{{ ucfirst($reserva->estado) }}</span>
                                    @break
                                @default
                                    <span class="badge bg-secondary">{{ ucfirst($reserva->estado) }}</span>
                            @endswitch
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">No hay reservas recientes.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
