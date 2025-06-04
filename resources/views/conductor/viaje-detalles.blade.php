@extends('layouts.app_dashboard')

@section('content')
<div class="container py-5">
    <h2 class="text-vcv fw-bold mb-4">🛣️ Detalles del Viaje</h2>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h5 class="card-title mb-3">📍 {{ $viaje->origen_direccion }} → {{ $viaje->destino_direccion }}</h5>

            <p><strong>🗓 Fecha:</strong> {{ $viaje->fecha_salida }}</p>
            <p><strong>🕒 Hora:</strong> {{ $viaje->hora_salida ?? 'No definida' }}</p>
            <p><strong>🎯 Distancia estimada:</strong> {{ $viaje->distancia_km ?? '—' }} km</p>
            <p><strong>🚗 Vehículo:</strong> {{ $viaje->vehiculo ?? 'No registrado' }}</p>
            <p><strong>💰 Valor por persona:</strong> ${{ number_format($viaje->valor_cobrado, 0) }}</p>
            <p><strong>🪑 Puestos disponibles:</strong> {{ $viaje->puestos_disponibles }}</p>
            <p><strong>📦 Estado:</strong>
                <span class="badge bg-primary text-light">{{ ucfirst($viaje->estado) }}</span>
            </p>

            @if($viaje->estado === 'pendiente')
            <form method="POST" action="{{ route('conductor.viaje.eliminar', $viaje->id) }}" onsubmit="return confirm('¿Cancelar este viaje?')">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger">🗑 Cancelar Viaje</button>
            </form>
            @endif
        </div>
    </div>

    <h4 class="text-vcv mb-3">👥 Pasajeros</h4>

    @if($viaje->reservas->count())
    <div class="list-group mb-4">
        @foreach($viaje->reservas as $reserva)
        <div class="list-group-item d-flex justify-content-between align-items-center flex-column flex-md-row">
            <div>
                <strong>{{ $reserva->user->name }}</strong><br>
                <small>Reservó {{ $reserva->cantidad_puestos }} puesto(s)</small>
                @if($reserva->user->calificacion)
                    <br><small>⭐ Calificación: {{ $reserva->user->calificacion }}/5</small>
                @endif
            </div>
            <a href="{{ route('chat.ver', $viaje->id) }}" class="btn btn-sm btn-outline-primary mt-2 mt-md-0">💬 Chat</a>
        </div>
        <div class="mt-4 border-top pt-3">
            <h5 class="text-primary">🗣️ Calificaciones</h5>

            {{-- Comentario del pasajero al conductor --}}
            @if($reserva->calificacionPasajero)
                <p><strong>Pasajero comentó:</strong> {{ $reserva->calificacionPasajero->comentario }}</p>
                <p>⭐ Calificación: {{ $reserva->calificacionPasajero->calificacion }}/5</p>
            @else
                <p><em>Este pasajero no ha calificado aún al conductor.</em></p>
            @endif

            {{-- Comentario del conductor al pasajero --}}
            @if($reserva->calificacionConductor)
                <p><strong>Conductor comentó:</strong> {{ $reserva->calificacionConductor->comentario }}</p>
                <p>⭐ Calificación: {{ $reserva->calificacionConductor->calificacion }}/5</p>
            @else
                <p><em>Aún no has calificado a este pasajero.</em></p>
            @endif
        </div>
        @endforeach
    </div>
    @else
        <div class="alert alert-secondary">
            Aún no hay pasajeros en este viaje.
        </div>
    @endif

    <a href="{{ route('dashboard') }}" class="btn btn-link">⬅️ Volver al dashboard</a>
</div>
@endsection
