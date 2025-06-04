@extends('layouts.app')

@section('title', 'Editar Perfil de Pasajero')

@section('content')
<div class="container py-5">
    <h2 class="mb-4 text-center profile-title">✏️ Editar perfil de pasajero</h2>

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

    <form action="{{ route('pasajero.perfil.update') }}" method="POST" enctype="multipart/form-data">
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
                    <div class="col-sm-6">
                    <label class="custom-label">Documento DNI (foto)</label>
                    <input type="file" name="dni_foto" class="custom-input" accept="image/*">
                    @if ($user->dni_foto)
                        <div class="mt-2">
                            <img src="{{ asset('storage/' . $user->dni_foto) }}" 
                                class="current-photo" 
                                alt="DNI actual">
                            <small class="text-muted d-block">DNI actual</small>
                        </div>
                    @else
                        <small class="text-muted">Sube una foto clara de tu documento</small>
                    @endif
                </div>
                </div>
            </div>
        </div>

        {{-- INFORMACIÓN ADICIONAL --}}
        <div class="custom-card mb-4">
            <div class="custom-card-header">
                <i class="fas fa-info-circle me-2"></i>
                Información de la cuenta
            </div>
            <div class="custom-card-body">
                <div class="row g-3">
                    <div class="col-sm-6">
                        <div class="info-item">
                            <strong class="info-label">Fecha de registro:</strong>
                            <span class="info-value">{{ $user->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="info-item">
                            <strong class="info-label">Estado de verificación:</strong>
                            @if($user->verificado)
                                <span class="badge-verified">
                                    <i class="fas fa-check-circle me-1"></i>
                                    Usuario Verificado
                                </span>
                            @else
                                <span class="badge-pending">
                                    <i class="fas fa-clock me-1"></i>
                                    Pendiente de verificación
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="info-item">
                            <strong class="info-label">Última actualización:</strong>
                            <span class="info-value">{{ $user->updated_at->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="info-item">
                            <strong class="info-label">ID de usuario:</strong>
                            <span class="info-value">#{{ $user->id }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- CAMBIAR CONTRASEÑA --}}
        <div class="custom-card mb-4">
            <div class="custom-card-header">
                <i class="fas fa-lock me-2"></i>
                Cambiar contraseña
            </div>
            <div class="custom-card-body">
                <div class="row g-3">
                    <div class="col-sm-6">
                        <label for="current_password" class="custom-label">Contraseña actual</label>
                        <input type="password" 
                               name="current_password" 
                               id="current_password"
                               class="custom-input @error('current_password') is-invalid @enderror" 
                               placeholder="Contraseña actual">
                        @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-sm-6">
                        <!-- Espacio en blanco para mantener diseño -->
                    </div>
                    <div class="col-sm-6">
                        <label for="new_password" class="custom-label">Nueva contraseña</label>
                        <input type="password" 
                               name="new_password" 
                               id="new_password"
                               class="custom-input @error('new_password') is-invalid @enderror" 
                               placeholder="Nueva contraseña">
                        @error('new_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-sm-6">
                        <label for="new_password_confirmation" class="custom-label">Confirmar nueva contraseña</label>
                        <input type="password" 
                               name="new_password_confirmation" 
                               id="new_password_confirmation"
                               class="custom-input" 
                               placeholder="Confirmar nueva contraseña">
                    </div>
                </div>
                <div class="password-note">
                    <i class="fas fa-info-circle me-2"></i>
                    <small class="text-muted">
                        Deja estos campos vacíos si no deseas cambiar tu contraseña.
                    </small>
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

/* Items de información */
.info-item {
    padding: 1rem;
    background: var(--color-azul-claro);
    border-radius: 8px;
    border-left: 4px solid var(--color-principal);
}

.info-label {
    color: var(--color-principal);
    font-size: 0.9rem;
    display: block;
    margin-bottom: 0.25rem;
}

.info-value {
    color: var(--color-neutro-oscuro);
    font-size: 1rem;
}

/* Badges de estado */
.badge-verified {
    background: linear-gradient(135deg, #d4edda, #c3e6cb);
    color: #155724;
    padding: 0.4rem 0.8rem;
    border-radius: 15px;
    font-size: 0.85rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
}

.badge-pending {
    background: linear-gradient(135deg, #fff3cd, #ffeaa7);
    color: #856404;
    padding: 0.4rem 0.8rem;
    border-radius: 15px;
    font-size: 0.85rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
}

/* Nota de contraseña */
.password-note {
    margin-top: 1rem;
    padding: 1rem;
    background: var(--color-azul-claro);
    border-radius: 8px;
    border-left: 4px solid var(--color-complementario);
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