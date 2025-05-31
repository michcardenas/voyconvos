@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h2 class="mb-4">Confirmar Reserva</h2>

    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Detalles del Viaje</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Origen:</strong> {{ $viaje->origen_direccion }}</p>
                    <p><strong>Destino:</strong> {{ $viaje->destino_direccion }}</p>
                    <p><strong>Fecha de salida:</strong> {{ \Carbon\Carbon::parse($viaje->fecha_salida)->format('d/m/Y') }}</p>
                    <p><strong>Hora:</strong> {{ $viaje->hora_salida }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Conductor:</strong> {{ $viaje->conductor?->name ?? 'No disponible' }}</p>
                    <p><strong>Puestos disponibles:</strong> {{ $viaje->puestos_disponibles }}</p>
                    @if($viaje->valor_cobrado)
                        <p><strong>Precio:</strong> ${{ number_format($viaje->valor_cobrado, 0, ',', '.') }}</p>
                    @endif
                    <p><strong>Vehículo:</strong> {{ ucfirst(str_replace('_', ' ', $viaje->vehiculo)) }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex gap-3 justify-content-center">
        {{-- Formulario para confirmar la reserva --}}
        <form action="{{ route('pasajero.reservar', ['viaje' => $viaje->id]) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-success btn-lg">
                <i class="fas fa-check"></i> Confirmar Reserva
            </button>
        </form>

        {{-- Botón para volver --}}
        <a href="{{ route('pasajero.dashboard') }}" class="btn btn-secondary btn-lg">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>
</div>
@endsection