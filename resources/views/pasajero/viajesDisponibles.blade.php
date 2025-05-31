@extends('layouts.app_dashboard')

@section('content')
<style>
    .card-viaje {
        border: 1px solid #003366;
        border-radius: 12px;
        padding: 1.5rem;
        background-color: #f8f9fa;
        transition: box-shadow 0.3s ease;
    }

    .card-viaje:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .bg-vcv-primary {
        background-color: #003366;
        color: white;
    }

    .btn-outline-vcv {
        border-color: #003366;
        color: #003366;
    }

    .btn-outline-vcv:hover {
        background-color: #003366;
        color: white;
    }
</style>

<div class="container py-5">
    <h2 class="mb-4 text-vcv fw-bold">🧍‍♀️ Viajes Disponibles</h2>

    {{-- Mostrar mensajes de éxito o error --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($viajesDisponibles->isEmpty())
        <div class="alert alert-info">
            No hay viajes disponibles por ahora.
        </div>
    @else
        <div class="row g-4">
            @foreach($viajesDisponibles as $viaje)
                <div class="col-md-4">
                    <div class="card-viaje">
                        <h5 class="fw-bold">{{ $viaje->origen_direccion }} ➡ {{ $viaje->destino_direccion }}</h5>
                        <p class="mb-1">📅 {{ \Carbon\Carbon::parse($viaje->fecha_salida)->format('d/m/Y') }}</p>
                        <p class="mb-1">🕒 {{ $viaje->hora_salida ?? 'No definida' }}</p>
                        <p class="mb-1">🚗 Conductor: <strong>{{ $viaje->conductor?->name ?? 'No disponible' }}</strong></p>
                        <p class="mb-1">💺 Puestos disponibles: <strong>{{ $viaje->puestos_disponibles }}</strong></p>

                        <div class="d-flex justify-content-between align-items-center mt-3">
                            {{-- Botón para ver detalles y confirmar --}}
                           <a href="{{ route('pasajero.confirmar.mostrar', $viaje->id) }}" class="btn btn-primary btn-sm">
                                Ver Detalles
                            </a>

                            {{-- Formulario para reservar directamente --}}
                            <form action="{{ route('pasajero.reservar', $viaje->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm"
                                        onclick="return confirm('¿Estás seguro de que quieres reservar este viaje?')">
                                    Reservar
                                </button>
                            </form>

                            {{-- Botón de chat --}}
                            <a href="{{ route('chat.ver', $reserva->viaje_id ?? $viaje->id) }}" class="btn btn-sm btn-outline-primary">
                                💬 Chat
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection