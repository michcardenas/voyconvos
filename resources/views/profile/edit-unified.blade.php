@extends('layouts.app_dashboard')

@section('title', 'Editar Perfil')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <h1 class="mb-4">Editar Perfil</h1>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('conductor.perfil.actualizar') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Sección: Información Personal --}}
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0"><i class="fas fa-user me-2"></i>Información Personal</h4>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Nombre completo <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name"
                                       value="{{ old('name', $user->name) }}" required>
                            </div>

                            <div class="col-md-6">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="email"
                                       value="{{ old('email', $user->email) }}" required>
                            </div>

                            <div class="col-md-6">
                                <label for="fecha_nacimiento" class="form-label">Fecha de nacimiento</label>
                                <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento"
                                       value="{{ old('fecha_nacimiento', $user->fecha_nacimiento?->format('Y-m-d')) }}">
                            </div>

                            <div class="col-md-6">
                                <label for="dni" class="form-label">DNI</label>
                                <input type="text" class="form-control" id="dni" name="dni"
                                       value="{{ old('dni', $user->dni) }}">
                            </div>

                            <div class="col-md-6">
                                <label for="celular" class="form-label">Celular</label>
                                <input type="text" class="form-control" id="celular" name="celular"
                                       value="{{ old('celular', $user->celular) }}" placeholder="+54 9 11 1234-5678">
                            </div>

                            <div class="col-md-6">
                                <label for="pais" class="form-label">País</label>
                                <input type="text" class="form-control" id="pais" name="pais"
                                       value="{{ old('pais', $user->pais) }}">
                            </div>

                            <div class="col-md-6">
                                <label for="ciudad" class="form-label">Ciudad</label>
                                <input type="text" class="form-control" id="ciudad" name="ciudad"
                                       value="{{ old('ciudad', $user->ciudad) }}">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Sección: Fotos y Documentos Personales --}}
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-info text-white">
                        <h4 class="mb-0"><i class="fas fa-image me-2"></i>Fotos y Documentos Personales</h4>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="foto" class="form-label">Foto de perfil</label>
                                @if($user->foto)
                                    <div class="mb-2">
                                        <img src="{{ asset('storage/' . $user->foto) }}" alt="Foto actual"
                                             class="img-thumbnail" style="max-width: 150px;">
                                        <p class="text-muted small mt-1">Foto actual</p>
                                    </div>
                                @endif
                                <input type="file" class="form-control" id="foto" name="foto" accept="image/*">
                                <small class="text-muted">Máximo 2MB</small>
                            </div>

                            <div class="col-md-4">
                                <label for="dni_foto" class="form-label">DNI (Frente)</label>
                                @if($user->dni_foto)
                                    <div class="mb-2">
                                        <img src="{{ asset('storage/' . $user->dni_foto) }}" alt="DNI frente"
                                             class="img-thumbnail" style="max-width: 150px;">
                                        <p class="text-muted small mt-1">DNI frente actual</p>
                                    </div>
                                @endif
                                <input type="file" class="form-control" id="dni_foto" name="dni_foto" accept="image/*">
                                <small class="text-muted">Máximo 2MB</small>
                            </div>

                            <div class="col-md-4">
                                <label for="dni_foto_atras" class="form-label">DNI (Reverso)</label>
                                @if($user->dni_foto_atras)
                                    <div class="mb-2">
                                        <img src="{{ asset('storage/' . $user->dni_foto_atras) }}" alt="DNI reverso"
                                             class="img-thumbnail" style="max-width: 150px;">
                                        <p class="text-muted small mt-1">DNI reverso actual</p>
                                    </div>
                                @endif
                                <input type="file" class="form-control" id="dni_foto_atras" name="dni_foto_atras" accept="image/*">
                                <small class="text-muted">Máximo 2MB</small>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Sección: Información del Vehículo (opcional) --}}
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-success text-white">
                        <h4 class="mb-0"><i class="fas fa-car me-2"></i>Información del Vehículo (Opcional)</h4>
                        <small>Completa esta sección si deseas ofrecer viajes como conductor</small>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="marca_vehiculo" class="form-label">Marca del vehículo</label>
                                <input type="text" class="form-control" id="marca_vehiculo" name="marca_vehiculo"
                                       value="{{ old('marca_vehiculo', $registro->marca_vehiculo) }}"
                                       placeholder="Ej: Toyota, Ford, Chevrolet">
                            </div>

                            <div class="col-md-4">
                                <label for="modelo_vehiculo" class="form-label">Modelo del vehículo</label>
                                <input type="text" class="form-control" id="modelo_vehiculo" name="modelo_vehiculo"
                                       value="{{ old('modelo_vehiculo', $registro->modelo_vehiculo) }}"
                                       placeholder="Ej: Corolla, Focus, Cruze">
                            </div>

                            <div class="col-md-4">
                                <label for="anio_vehiculo" class="form-label">Año del vehículo</label>
                                <input type="number" class="form-control" id="anio_vehiculo" name="anio_vehiculo"
                                       value="{{ old('anio_vehiculo', $registro->anio_vehiculo) }}"
                                       min="1900" max="{{ date('Y') + 1 }}" placeholder="2020">
                            </div>

                            <div class="col-md-4">
                                <label for="patente" class="form-label">Patente</label>
                                <input type="text" class="form-control" id="patente" name="patente"
                                       value="{{ old('patente', $registro->patente) }}"
                                       placeholder="ABC123">
                            </div>

                            <div class="col-md-4">
                                <label for="numero_puestos" class="form-label">Número de puestos disponibles</label>
                                <input type="number" class="form-control" id="numero_puestos" name="numero_puestos"
                                       value="{{ old('numero_puestos', $registro->numero_puestos) }}"
                                       min="1" max="8" placeholder="4">
                            </div>

                            <div class="col-md-4">
                                <label for="consumo_por_galon" class="form-label">Consumo por galón (km/gal)</label>
                                <input type="number" step="0.1" class="form-control" id="consumo_por_galon" name="consumo_por_galon"
                                       value="{{ old('consumo_por_galon', $registro->consumo_por_galon) }}"
                                       min="0" max="100" placeholder="15.5">
                            </div>

                            <div class="col-md-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="verificar_pasajeros" name="verificar_pasajeros"
                                           value="1" {{ old('verificar_pasajeros', $registro->verificar_pasajeros) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="verificar_pasajeros">
                                        Quiero verificar manualmente a los pasajeros antes de confirmar sus reservas
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Sección: Documentos del Vehículo (opcional) --}}
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-warning text-dark">
                        <h4 class="mb-0"><i class="fas fa-file-alt me-2"></i>Documentos del Vehículo (Opcional)</h4>
                        <small>Estos documentos son necesarios para ser verificado como conductor</small>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="licencia" class="form-label">Licencia de conducir</label>
                                @if($registro->licencia)
                                    <div class="mb-2">
                                        <a href="{{ asset('storage/' . $registro->licencia) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye me-1"></i>Ver documento actual
                                        </a>
                                    </div>
                                @endif
                                <input type="file" class="form-control" id="licencia" name="licencia"
                                       accept="image/*,.pdf">
                                <small class="text-muted">PDF, JPG, PNG - Máximo 5MB</small>
                            </div>

                            <div class="col-md-4">
                                <label for="cedula" class="form-label">Cédula del vehículo</label>
                                @if($registro->cedula)
                                    <div class="mb-2">
                                        <a href="{{ asset('storage/' . $registro->cedula) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye me-1"></i>Ver documento actual
                                        </a>
                                    </div>
                                @endif
                                <input type="file" class="form-control" id="cedula" name="cedula"
                                       accept="image/*,.pdf">
                                <small class="text-muted">PDF, JPG, PNG - Máximo 5MB</small>
                            </div>

                            <div class="col-md-4">
                                <label for="cedula_verde" class="form-label">Cédula verde</label>
                                @if($registro->cedula_verde)
                                    <div class="mb-2">
                                        <a href="{{ asset('storage/' . $registro->cedula_verde) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye me-1"></i>Ver documento actual
                                        </a>
                                    </div>
                                @endif
                                <input type="file" class="form-control" id="cedula_verde" name="cedula_verde"
                                       accept="image/*,.pdf">
                                <small class="text-muted">PDF, JPG, PNG - Máximo 5MB</small>
                            </div>

                            <div class="col-md-4">
                                <label for="seguro" class="form-label">Seguro del vehículo</label>
                                @if($registro->seguro)
                                    <div class="mb-2">
                                        <a href="{{ asset('storage/' . $registro->seguro) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye me-1"></i>Ver documento actual
                                        </a>
                                    </div>
                                @endif
                                <input type="file" class="form-control" id="seguro" name="seguro"
                                       accept="image/*,.pdf">
                                <small class="text-muted">PDF, JPG, PNG - Máximo 5MB</small>
                            </div>

                            <div class="col-md-4">
                                <label for="rto" class="form-label">RTO (Revisión Técnica)</label>
                                @if($registro->rto)
                                    <div class="mb-2">
                                        <a href="{{ asset('storage/' . $registro->rto) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye me-1"></i>Ver documento actual
                                        </a>
                                    </div>
                                @endif
                                <input type="file" class="form-control" id="rto" name="rto"
                                       accept="image/*,.pdf">
                                <small class="text-muted">PDF, JPG, PNG - Máximo 5MB</small>
                            </div>

                            <div class="col-md-4">
                                <label for="antecedentes" class="form-label">Certificado de antecedentes</label>
                                @if($registro->antecedentes)
                                    <div class="mb-2">
                                        <a href="{{ asset('storage/' . $registro->antecedentes) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye me-1"></i>Ver documento actual
                                        </a>
                                    </div>
                                @endif
                                <input type="file" class="form-control" id="antecedentes" name="antecedentes"
                                       accept="image/*,.pdf">
                                <small class="text-muted">PDF, JPG, PNG - Máximo 5MB</small>
                            </div>
                        </div>

                        @if($registro->exists && $registro->estado_verificacion)
                            <div class="mt-3 p-3 rounded {{ $registro->estado_verificacion === 'verificado' ? 'bg-success bg-opacity-10 border border-success' : ($registro->estado_verificacion === 'rechazado' ? 'bg-danger bg-opacity-10 border border-danger' : 'bg-warning bg-opacity-10 border border-warning') }}">
                                <strong>Estado de verificación:</strong>
                                <span class="text-capitalize">{{ $registro->estado_verificacion }}</span>
                                @if($registro->estado_verificacion === 'pendiente')
                                    <p class="mb-0 mt-2 small">Tus documentos están siendo revisados por nuestro equipo.</p>
                                @elseif($registro->estado_verificacion === 'verificado')
                                    <p class="mb-0 mt-2 small">¡Tu cuenta está verificada! Ya puedes ofrecer viajes.</p>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Botones de acción --}}
                <div class="d-flex justify-content-between">
                    <a href="{{ route('hibrido.dashboard') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Volver al Dashboard
                    </a>
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-save me-1"></i>Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .card-header h4 {
        font-size: 1.25rem;
    }

    .form-label {
        font-weight: 500;
        color: #374151;
    }

    .img-thumbnail {
        border: 2px solid #e5e7eb;
        border-radius: 8px;
    }

    .btn-outline-primary {
        border-width: 2px;
    }

    .text-danger {
        font-weight: 600;
    }
</style>
@endsection
