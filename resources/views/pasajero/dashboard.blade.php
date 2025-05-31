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
</style>

<div class="container py-5">
    <h2 class="mb-4 text-vcv fw-bold">👋 Bienvenido, {{ auth()->user()->name ?? 'Invitado' }}</h2>

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

    <h4 class="mb-3 text-vcv">🧾 Tus reservas</h4>

   @if($reservas->count())
        <div class="table-responsive">
            <table class="table table-bordered align-middle shadow-sm bg-white">
                <thead class="table-light">
                    <tr>
                        <th>Origen</th>
                        <th>Destino</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reservas as $reserva)
                        @if($reserva->viaje)
                        <tr>
                            <td>{{ $reserva->viaje->origen_direccion }}</td>
                            <td>{{ $reserva->viaje->destino_direccion }}</td>
                            <td>
                                <a href="{{ route('pasajero.reserva.detalles', $reserva->id) }}" class="btn btn-sm btn-outline-secondary">Detalles</a>
                                <a href="{{ route('pasajero.reserva.detalles', $reserva->id) }}#mapa" class="btn btn-sm btn-outline-primary">Ver ruta</a>
                                <a href="{{ route('chat.ver', $reserva->viaje_id ?? $viaje->id) }}" class="btn btn-sm btn-outline-primary">
                                    💬 Chat
                                </a>
                            </td>
                        </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="alert alert-info">
            No tienes reservas activas.
        </div>
    @endif


    <div class="mt-4">
        <a href="{{ route('pasajero.viajes.disponibles') }}" class="btn btn-primary">
            🔍 Ver viajes disponibles
        </a>
    </div>
</div>
@endsection
