@extends('layouts.app_dashboard')

@section('content')

@if ($errors->any())
    <div class="alert alert-danger mt-3">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="container py-5">
    <h2 class="text-vcv fw-bold">🧾 Resumen de la reserva</h2>

    <div class="card mt-4">
        <div class="card-body">
            <p><strong>Origen:</strong> {{ $viaje->origen_direccion }}</p>
            <p><strong>Destino:</strong> {{ $viaje->destino_direccion }}</p>
            <p><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($viaje->fecha_salida)->format('d/m/Y') }}</p>
            <p><strong>Hora:</strong> {{ $viaje->hora_salida }}</p>
            <p><strong>Conductor:</strong> {{ $viaje->conductor->name ?? 'No disponible' }}</p>
            <p><strong>Puestos a reservar:</strong> {{ $cantidad }}</p>
            <p><strong>Total a pagar:</strong> ${{ number_format($total, 0, ',', '.') }}</p>
        </div>
    </div>

    <form id="form-confirmar-reserva" action="{{ route('pasajero.reservar', $viaje->id) }}" method="POST" class="mt-4">
    @csrf
    <input type="hidden" name="cantidad_puestos" value="{{ $cantidad }}">

    <button type="button" class="btn btn-success" onclick="mostrarAlertaYEnviar()">
        Confirmar reserva
    </button>

    <a href="{{ route('pasajero.confirmar.mostrar', $viaje->id) }}" class="btn btn-secondary">
        Volver
    </a>
</form>

<script>
    function mostrarAlertaYEnviar() {
        alert('✅ Tu reserva está en espera para configurar el pago');
        document.getElementById('form-confirmar-reserva').submit();
    }
</script>

</div>
@endsection
