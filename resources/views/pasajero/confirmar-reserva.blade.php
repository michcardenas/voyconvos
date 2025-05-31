@extends('layouts.app_dashboard')

@section('content')
<div class="container py-5">
    <h2 class="text-vcv fw-bold">Confirmar Reserva</h2>

    <div class="card mt-4">
        <div class="card-body">
            <h5 class="fw-bold">{{ $viaje->origen_direccion }} ➡ {{ $viaje->destino_direccion }}</h5>
            <p>📅 {{ \Carbon\Carbon::parse($viaje->fecha_salida)->format('d/m/Y') }}</p>
            <p>🕒 {{ $viaje->hora_salida }}</p>
            <p>🚗 Conductor: {{ $viaje->conductor->name ?? 'No disponible' }}</p>
            <p>💺 Puestos disponibles: {{ $viaje->puestos_disponibles }}</p>
            <p>💰 Valor estimado: ${{ number_format($viaje->valor_cobrado, 0, ',', '.') }}</p>

            <form action="{{ route('pasajero.reserva.resumen', $viaje->id) }}" method="GET" class="mt-3">
                @csrf
                <div class="mb-3">
                    <label for="cantidad_puestos" class="form-label">Cantidad de puestos:</label>
                    <input type="number" name="cantidad_puestos" id="cantidad_puestos" class="form-control" 
                        min="1" max="{{ $viaje->puestos_disponibles }}" value="1" required>
                </div>

                <button type="submit" class="btn btn-primary">Ver resumen</button>
                <a href="{{ route('pasajero.viajes.disponibles') }}" class="btn btn-secondary">Cancelar</a>
            </form>

        </div>
    </div>
</div>
@endsection
