@extends('layouts.app')

@section('title', 'Editar Perfil de Conductor')

@section('content')
<div class="container py-5">
    <h2 class="mb-4 text-center profile-title">✏️ Editar perfil de conductor</h2>

    @if(session('success'))
        <div class="alert alert-success-custom">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger-custom">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>Hay errores en el formulario:</strong>
            <ul class="mb-0 mt-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('conductor.perfil.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- DATOS PERSONALES --}}
        <div class="custom-card mb-4">
            <div class="custom-card-header">
                <i class="fas fa-user me-2"></i>
                Datos personales
            </div>
            <div class="custom-card-body">
                <div class="row g-3">
                    @foreach([
                        ['name', 'Nombre completo', 'text'],
                        ['email', 'Correo electrónico', 'email'],
                        ['dni', 'Documento (DNI)', 'text'],
                        ['celular', 'Celular', 'text'],
                        ['pais', 'País', 'text'],
                        ['ciudad', 'Ciudad', 'text'],
                    ] as [$campo, $etiqueta, $tipo])
                        <div class="col-sm-6">
                            <label for="{{ $campo }}" class="custom-label">{{ $etiqueta }}</label>
                            <input type="{{ $tipo }}" 
                                   name="{{ $campo }}" 
                                   id="{{ $campo }}"
                                   value="{{ old($campo, $user->$campo) }}" 
                                   class="custom-input @error($campo) is-invalid @enderror" 
                                   placeholder="{{ $etiqueta }}">
                            @error($campo)
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    @endforeach

                    <div class="col-sm-6">
                        <label class="custom-label">Foto de perfil</label>
                        <input type="file" name="foto" class="custom-input" accept="image/*">
                        @if ($user->foto)
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $user->foto) }}" 
                                     class="current-photo" 
                                     alt="Foto actual">
                                <small class="text-muted d-block">Foto actual</small>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- DATOS DE CONDUCTOR --}}
        <div class="custom-card mb-4">
            <div class="custom-card-header">
                <i class="fas fa-car me-2"></i>
                Vehículo y documentos
            </div>
            <div class="custom-card-body">
                <div class="row g-3 mb-4">
                    @foreach([
                        ['marca_vehiculo', 'Marca del vehículo'],
                        ['modelo_vehiculo', 'Modelo'],
                        ['anio_vehiculo', 'Año'],
                        ['patente', 'Patente'],
                    ] as [$campo, $etiqueta])
                        <div class="col-sm-6">
                            <label class="custom-label">{{ $etiqueta }}</label>
                            <input type="text" 
                                   name="{{ $campo }}" 
                                   value="{{ old($campo, $registro->$campo ?? '') }}" 
                                   class="custom-input @error($campo) is-invalid @enderror" 
                                   placeholder="{{ $etiqueta }}">
                            @error($campo)
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    @endforeach
                </div>

                <h5 class="section-subtitle mb-3">
                    <i class="fas fa-file-alt me-2"></i>
                    Documentos
                </h5>
                <div class="row g-3">
                    @foreach([
                        'licencia' => 'Licencia',
                        'cedula' => 'Cédula',
                        'cedula_verde' => 'Cédula verde',
                        'seguro' => 'Seguro',
                        'rto' => 'RTO',
                        'antecedentes' => 'Antecedentes'
                    ] as $campo => $label)
                        <div class="col-sm-6">
                            <label class="custom-label">{{ $label }} (archivo)</label>
                            <input type="file" 
                                   name="{{ $campo }}" 
                                   class="custom-input @error($campo) is-invalid @enderror" 
                                   accept=".pdf,.jpg,.jpeg,.png">
                            @if ($registro->$campo ?? false)
                                <div class="file-current">
                                    <i class="fas fa-file-check text-success me-1"></i>
                                    <a href="{{ asset('storage/' . $registro->$campo) }}" 
                                       target="_blank" 
                                       class="file-link">
                                        Ver {{ $label }} actual
                                    </a>
                                </div>
                            @endif
                            @error($campo)
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- BOTÓN GUARDAR --}}
        <div class="text-center">
            <button type="submit" class="custom-btn-primary">
                <i class="fas fa-save me-2"></i>
                Guardar cambios
            </button>
        </div>
    </form>
</div>

<style>
:root {
    --color-principal: #1F4E79;
    --color-azul-claro: #DDF2FE;
    --color-neutro-oscuro: #3A3A3A;
    --color-complementario: #4CAF50;
    --color-fondo-base: #FCFCFD;
    --color-blanco: #FFFFFF;
}

/* Título principal */
.profile-title {
    color: var(--color-principal);
    font-weight: 700;
    font-size: 2.2rem;
    margin-bottom: 2rem;
}

/* Cards personalizadas */
.custom-card {
    background: var(--color-blanco);
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(31, 78, 121, 0.1);
    border: 1px solid rgba(31, 78, 121, 0.1);
    overflow: hidden;
    transition: transform 0.3s ease;
}

.custom-card:hover {
    transform: translateY(-2px);
}

.custom-card-header {
    background: linear-gradient(135deg, var(--color-principal), #2A5A8A);
    color: var(--color-blanco);
    padding: 1.2rem 1.5rem;
    font-weight: 600;
    font-size: 1.1rem;
}

.custom-card-body {
    padding: 1.5rem;
}

/* Labels y inputs */
.custom-label {
    font-weight: 600;
    color: var(--color-neutro-oscuro);
    margin-bottom: 0.5rem;
    display: block;
    font-size: 0.95rem;
}

.custom-input {
    width: 100%;
    padding: 0.75rem;
    border: 2px solid #e1e5e9;
    border-radius: 8px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: var(--color-blanco);
}

.custom-input:focus {
    outline: none;
    border-color: var(--color-principal);
    box-shadow: 0 0 0 3px rgba(31, 78, 121, 0.1);
}

.custom-input.is-invalid {
    border-color: #dc3545;
}

/* Foto actual */
.current-photo {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 8px;
    border: 2px solid var(--color-azul-claro);
}

/* Archivos actuales */
.file-current {
    margin-top: 0.5rem;
    padding: 0.5rem;
    background: var(--color-azul-claro);
    border-radius: 6px;
}

.file-link {
    color: var(--color-principal);
    text-decoration: none;
    font-size: 0.9rem;
}

.file-link:hover {
    text-decoration: underline;
    color: var(--color-principal);
}

/* Subtítulo de sección */
.section-subtitle {
    color: var(--color-principal);
    font-weight: 600;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid var(--color-azul-claro);
}

/* Botón principal */
.custom-btn-primary {
    background: linear-gradient(45deg, var(--color-complementario), #45A049);
    color: var(--color-blanco);
    border: none;
    padding: 1rem 3rem;
    border-radius: 25px;
    font-size: 1.1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(76, 175, 80, 0.3);
}

.custom-btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(76, 175, 80, 0.4);
    background: linear-gradient(45deg, #45A049, #388E3C);
}

/* Alertas */
.alert-success-custom {
    background: linear-gradient(135deg, #d4edda, #c3e6cb);
    color: #155724;
    border: 1px solid #c3e6cb;
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: 1.5rem;
}

.alert-danger-custom {
    background: linear-gradient(135deg, #f8d7da, #f5c6cb);
    color: #721c24;
    border: 1px solid #f5c6cb;
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: 1.5rem;
}

.alert-danger-custom ul {
    margin-left: 1rem;
}

/* Responsive */
@media (max-width: 768px) {
    .profile-title {
        font-size: 1.8rem;
    }
    
    .custom-card-body {
        padding: 1rem;
    }
    
    .custom-btn-primary {
        width: 100%;
        max-width: 300px;
    }
}
</style>
@endsection