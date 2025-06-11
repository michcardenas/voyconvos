@extends('layouts.app_dashboard')

@section('title', 'Registro del Vehículo')

@section('content')
<div class="container py-4">
    <h2 class="mb-4 text-blue">🚗 Completar Registro como Conductor</h2>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('info'))
            <div class="alert alert-warning">
                {{ session('info') }}
            </div>
        @endif

  <form method="POST" action="{{ route('conductor.registro.store') }}" enctype="multipart/form-data">
    @csrf

    <div class="mb-3">
        <label for="marca_vehiculo" class="form-label">Marca del Vehículo</label>
        <input type="text" name="marca_vehiculo" class="form-control" required>
    </div>

    <div class="mb-3">
        <label for="modelo_vehiculo" class="form-label">Modelo</label>
        <input type="text" name="modelo_vehiculo" class="form-control" required>
    </div>

    <div class="mb-3">
        <label for="anio_vehiculo" class="form-label">Año del Vehículo</label>
        <input type="number" name="anio_vehiculo" class="form-control" min="2012" max="{{ date('Y') }}" required>
    </div>

    <div class="mb-3">
        <label for="numero_puestos" class="form-label">Número de Puestos (Incluido el Conductor)</label>
        <input type="number" name="numero_puestos" class="form-control" min="2" max="50" placeholder="Ej: 4, 5, 8..." required>
        <div class="form-text">Ingrese el número total de asientos incluyendo el del conductor</div>
    </div>

    <div class="mb-3">
        <label for="patente" class="form-label">Patente (Dominio)</label>
        <input type="text" name="patente" class="form-control" placeholder="Ej: AB 123 CD" required>
    </div>

    <div class="mb-3">
        <label for="licencia" class="form-label">Licencia de Conducir</label>
        <input type="file" name="licencia" class="form-control" accept="application/pdf,image/*" required>
    </div>

    <div class="mb-3">
        <label for="cedula" class="form-label">DNI / Cédula</label>
        <input type="file" name="cedula" class="form-control" accept="application/pdf,image/*" required>
    </div>

    <div class="mb-3">
        <label for="cedula_verde" class="form-label">Cédula Verde</label>
        <input type="file" name="cedula_verde" class="form-control" accept="application/pdf,image/*" required>
    </div>

    <button type="submit" class="btn btn-success">Enviar para revisión</button>
</form>
</div>
@endsection

<script>
    document.querySelector('input[name="licencia"]').addEventListener('change', function(e) {
        console.log('Licencia MIME type:', e.target.files[0].type);
    });
</script>

