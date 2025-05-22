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

    <h4 class="mb-3 text-vcv">🚍 Tus próximos viajes</h4>

    @if(isset($viajesProximosList) && count($viajesProximosList) > 0)
    <div class="table-responsive">
        <table class="table table-bordered align-middle shadow-sm bg-white">
            <thead class="table-light">
                <tr>
                    <th>Fecha</th>
                    <th>Origen</th>
                    <th>Destino</th>
                    <th>Hora</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($viajesProximosList as $viaje)
                <tr>
                    <td>{{ $viaje->fecha ?? '—' }}</td>
                    <td>{{ $viaje->origen ?? '—' }}</td>
                    <td>{{ $viaje->destino ?? '—' }}</td>
                    <td>{{ $viaje->hora ?? '—' }}</td>
                    <td>
                        <span class="badge bg-vcv-info text-dark">{{ ucfirst($viaje->estado ?? 'pendiente') }}</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
        <div class="alert alert-info">
            No tienes viajes próximos. <a href="#" class="alert-link">Agendar uno</a>.
        </div>
    @endif

    <div class="mt-4 d-flex gap-3">
        <a href="{{ route('conductor.gestion') }}" class="btn btn-outline-primary">
            ➕ Agendar nuevo viaje
        </a>
        <a href="#" class="btn btn-link text-decoration-none">
            ¿Necesitas ayuda?
        </a>
    </div>
</div>
@endsection
