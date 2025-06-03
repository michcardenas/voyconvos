@extends('layouts.app_dashboard')

@section('content')
<style>
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
</style>

<div class="container py-5">
    <h2 class="mb-4 text-vcv fw-bold">👋 Bienvenido, {{ auth()->user()->name ?? 'Invitado' }}</h2>

    @if($notificaciones > 0)
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        🚨 <strong>{{ $notificaciones }}</strong> nueva(s) reserva(s) en tus viajes.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
    </div>
    @endif

    @if($reservasDetalles->count())
    <div class="mt-3">
        <h5 class="text-vcv fw-bold">📋 Reservas recientes:</h5>
        <ul class="list-group">
            @foreach($reservasDetalles as $reserva)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <strong>{{ $reserva->user->name }}</strong> reservó <strong>{{ $reserva->cantidad_puestos }}</strong> puesto(s)
                    <br>
                    <small>{{ \Carbon\Carbon::parse($reserva->created_at)->diffForHumans() }}</small>
                </div>
                <a href="{{ route('chat.ver', $reserva->viaje_id) }}" class="btn btn-sm btn-outline-primary">
                    💬 Chat
                </a>
            </li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card text-white bg-vcv-primary shadow-soft">
                <div class="card-body">
                    <h5 class="card-title">Total de Viajes</h5>
                    <p class="fs-3">{{ $totalViajes ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success shadow-soft">
                <div class="card-body">
                    <h5 class="card-title">Próximos Viajes</h5>
                    <p class="fs-3">{{ $viajesProximos ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-secondary shadow-soft">
                <div class="card-body">
                    <h5 class="card-title">Viajes Realizados</h5>
                    <p class="fs-3">{{ $viajesRealizados ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <h4 class="mb-3 text-vcv">🚍 Tus próximos viajes</h4>

    <p class="fs-3">
        {{ $viajesProximos ?? 0 }}
        @if($reservasNoVistas > 0)
        <span class="badge bg-success">🔔 {{ $reservasNoVistas }} nuevas reservas</span>
        @endif
    </p>

    @if(isset($viajesProximosList) && count($viajesProximosList) > 0)
    <div class="table-responsive">
        <table class="table table-bordered align-middle shadow-sm bg-white table-responsive-md table-hover text-sm">
            <thead class="table-light">
                <tr>
                    <th>Fecha</th>
                    <th>Origen</th>
                    <th>Destino</th>
                    <th>Hora</th>
                    <th>Fecha de viaje</th>
                    <th>Ocupación</th>
                    <th>Rol</th>
                    <th>Estado</th>
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
                                <a href="{{ route('chat.ver', $reserva->viaje_id) }}">
                                    {{ $reserva->user->name }}
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </td>
                    <td>
                        @if($viaje->conductor_id === auth()->id())
                        <span class="badge bg-success">Conductor</span>
                        @else
                        <span class="badge bg-info text-dark">Pasajero</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex flex-column flex-md-row align-items-start gap-1">
                            <span class="badge bg-vcv-info text-dark">{{ ucfirst($viaje->estado) }}</span>
                            @if($viaje->estado !== 'cancelado')
                            <form method="POST" action="{{ route('conductor.viaje.eliminar', $viaje->id) }}"
                                onsubmit="return confirm('¿Estás seguro de cancelar este viaje?')">
                                @csrf
                                @method('DELETE')
                                <td>
                                    <a href="{{ route('conductor.viaje.detalle', $viaje->id) }}" class="btn btn-sm btn-outline-primary">
                                        👁 Ver detalles
                                    </a>
                                </td>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="alert alert-info">
        No tienes viajes próximos.
    </div>
    @endif

    @if(auth()->user()->hasRole('conductor'))
    <div class="mt-4 d-flex gap-3">
        <a href="{{ route('conductor.gestion') }}" class="btn btn-outline-primary">
            ➕ Agendar nuevo viaje
        </a>
        <a href="#" class="btn btn-link text-decoration-none">
            ¿Necesitas ayuda?
        </a>
    </div>
    @endif
</div>
@endsection
