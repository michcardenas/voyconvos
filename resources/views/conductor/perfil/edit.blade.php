@extends('layouts.app')

@section('title', 'Editar Perfil de Conductor')

@section('content')
<div class="main-wrapper">
    <div class="container py-5">
        <div class="page-header">
            <h2 class="mb-4 text-center profile-title">
                <span class="title-icon">✏️</span>
                Editar perfil de conductor
            </h2>
            <p class="subtitle text-center">Mantén actualizada tu información para brindar un mejor servicio</p>
        </div>

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

        <form action="{{ route('conductor.perfil.update') }}" method="POST" enctype="multipart/form-data" class="profile-form">
            @csrf
            @method('PUT')

            {{-- DATOS PERSONALES --}}
            <div class="custom-card mb-4" data-aos="fade-up">
                <div class="custom-card-header">
                    <div class="header-content">
                        <i class="fas fa-user me-2"></i>
                        <span>Datos personales</span>
                    </div>
                    <div class="header-decoration"></div>
                </div>
                <div class="custom-card-body">
                    <div class="row g-4">
                        @foreach([
                            ['name', 'Nombre completo', 'text', 'fas fa-signature'],
                            ['email', 'Correo electrónico', 'email', 'fas fa-envelope'],
                            ['dni', 'Documento (DNI)', 'text', 'fas fa-id-card'],
                            ['celular', 'Celular', 'text', 'fas fa-phone'],
                            ['pais', 'País', 'text', 'fas fa-globe'],
                            ['ciudad', 'Ciudad', 'text', 'fas fa-map-marker-alt'],
                        ] as [$campo, $etiqueta, $tipo, $icono])
                            <div class="col-sm-6">
                                <div class="input-group-custom">
                                    <label for="{{ $campo }}" class="custom-label">
                                        <i class="{{ $icono }} me-1"></i>
                                        {{ $etiqueta }}
                                    </label>
                                    <input type="{{ $tipo }}" 
                                           name="{{ $campo }}" 
                                           id="{{ $campo }}"
                                           value="{{ old($campo, $user->$campo) }}" 
                                           class="custom-input @error($campo) is-invalid @enderror" 
                                           placeholder="Ingresa tu {{ strtolower($etiqueta) }}">
                                    @error($campo)
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        @endforeach

                        <div class="col-sm-6">
                            <div class="input-group-custom">
                                <label class="custom-label">
                                    <i class="fas fa-camera me-1"></i>
                                    Foto de perfil
                                </label>
                                <div class="file-upload-area">
                                    <input type="file" name="foto" class="custom-file-input" accept="image/*" id="foto-upload">
                                    <label for="foto-upload" class="file-upload-label">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                        <span>Seleccionar imagen</span>
                                    </label>
                                </div>
          
                                @if ($user->foto)
                                    <div class="current-media">
                                        <img src="{{ asset('storage/' . $user->foto) }}" 
                                             class="current-photo" 
                                             alt="Foto actual">
                                        <div class="media-info">
                                            <small class="text-muted">Foto actual</small>
                                            <i class="fas fa-check-circle text-success"></i>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- DATOS DE CONDUCTOR --}}
            <div class="custom-card mb-4" data-aos="fade-up" data-aos-delay="100">
                <div class="custom-card-header">
                    <div class="header-content">
                        <i class="fas fa-car me-2"></i>
                        <span>Vehículo y documentos</span>
                    </div>
                    <div class="header-decoration"></div>
                </div>
                <div class="custom-card-body">
                    <div class="vehicle-section">
                        <h5 class="section-subtitle mb-3">
                            <i class="fas fa-car-side me-2"></i>
                            Información del vehículo
                        </h5>
                        <div class="row g-4 mb-5">
                            @foreach([
                                ['marca_vehiculo', 'Marca del vehículo', 'fas fa-tag'],
                                ['modelo_vehiculo', 'Modelo', 'fas fa-car'],
                                ['numero_puestos', 'Numero de puestos (incluidos el conductor)', 'fas fa-chair'],
                                ['anio_vehiculo', 'Año', 'fas fa-calendar'],

                                ['patente', 'Patente', 'fas fa-hashtag'],
                            ] as [$campo, $etiqueta, $icono])
                                <div class="col-sm-6">
                                    <div class="input-group-custom">
                                        <label class="custom-label">
                                            <i class="{{ $icono }} me-1"></i>
                                            {{ $etiqueta }}
                                        </label>
                                        <input type="text" 
                                               name="{{ $campo }}" 
                                               value="{{ old($campo, $registro->$campo ?? '') }}" 
                                               class="custom-input @error($campo) is-invalid @enderror" 
                                               placeholder="Ingresa {{ strtolower($etiqueta) }}">
                                        @error($campo)
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="documents-section">
                        <h5 class="section-subtitle mb-4">
                            <i class="fas fa-file-alt me-2"></i>
                            Documentos requeridos
                        </h5>
                        <div class="row g-4">
                            @foreach([
                                'licencia' => ['Licencia de conducir', 'fas fa-id-badge'],
                                'cedula' => ['Cédula de identidad', 'fas fa-address-card'],
                                'cedula_verde' => ['Cédula verde del vehículo', 'fas fa-file-contract'],
                                'seguro' => ['Póliza de seguro', 'fas fa-shield-alt'],
                                'rto' => ['Revisión técnica (RTO)', 'fas fa-tools'],
                                'antecedentes' => ['Certificado de antecedentes', 'fas fa-certificate']
                            ] as $campo => [$label, $icono])
                                <div class="col-sm-6">
                                    <div class="input-group-custom">
                                        <label class="custom-label">
                                            <i class="{{ $icono }} me-1"></i>
                                            {{ $label }}
                                        </label>
                                        <div class="file-upload-area">
                                            <input type="file" 
                                                   name="{{ $campo }}" 
                                                   class="custom-file-input @error($campo) is-invalid @enderror" 
                                                   accept=".pdf,.jpg,.jpeg,.png"
                                                   id="{{ $campo }}-upload">
                                            <label for="{{ $campo }}-upload" class="file-upload-label">
                                                <i class="fas fa-upload"></i>
                                                <span>Subir archivo</span>
                                            </label>
                                        </div>
                                        @if ($registro->$campo ?? false)
                                            <div class="file-current">
                                                <i class="fas fa-file-check text-success me-2"></i>
                                                <a href="{{ asset('storage/' . $registro->$campo) }}" 
                                                   target="_blank" 
                                                   class="file-link">
                                                    Ver {{ $label }} actual
                                                </a>
                                                <span class="file-status">✓ Subido</span>
                                            </div>
                                        @endif
                                        @error($campo)
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- BOTÓN GUARDAR --}}
            <div class="text-center" data-aos="fade-up" data-aos-delay="200">
                <button type="submit" class="custom-btn-primary">
                    <i class="fas fa-save me-2"></i>
                    <span>Guardar cambios</span>
                    <div class="btn-animation"></div>
                </button>
            </div>
        </form>
    </div>
</div>

<style>
:root {
    --color-principal: #1F4E79;
    --color-azul-claro: #DDF2FE;
    --color-neutro-oscuro: #3A3A3A;
    --color-complementario: #4CAF50;
    --color-fondo-base: #FCFCFD;
    --color-blanco: #FFFFFF;
    --shadow-light: rgba(31, 78, 121, 0.08);
    --shadow-medium: rgba(31, 78, 121, 0.15);
    --shadow-strong: rgba(31, 78, 121, 0.25);
    --transition-smooth: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    --border-radius: 16px;
    --spacing-unit: 1rem;
}

/* Estructura principal */
.main-wrapper {
    background: linear-gradient(135deg, var(--color-fondo-base) 0%, #f8fafc 100%);
    min-height: 100vh;
    padding-top: 80px; /* Espacio para el navbar */
}

/* Header de la página */
.page-header {
    margin-bottom: 2.5rem;
    text-align: center;
}

.profile-title {
    color: var(--color-principal);
    font-weight: 700;
    font-size: clamp(1.8rem, 4vw, 2.5rem);
    margin-bottom: 0.5rem;
    position: relative;
    display: inline-block;
}

.title-icon {
    font-size: 0.8em;
    margin-right: 0.5rem;
    opacity: 0.8;
}

.subtitle {
    color: var(--color-neutro-oscuro);
    font-size: 1.1rem;
    opacity: 0.8;
    font-weight: 400;
    max-width: 600px;
    margin: 0 auto;
    line-height: 1.5;
}

/* Formulario */
.profile-form {
    max-width: 1200px;
    margin: 0 auto;
}

/* Cards mejoradas */
.custom-card {
    background: var(--color-blanco);
    border-radius: var(--border-radius);
    box-shadow: 0 8px 30px var(--shadow-light);
    border: 1px solid rgba(31, 78, 121, 0.08);
    overflow: hidden;
    transition: var(--transition-smooth);
    position: relative;
}

.custom-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 40px var(--shadow-medium);
}

.custom-card-header {
    background: linear-gradient(135deg, var(--color-principal) 0%, #2A5A8A 100%);
    color: var(--color-blanco);
    padding: 1.5rem 2rem;
    position: relative;
    overflow: hidden;
}

.header-content {
    display: flex;
    align-items: center;
    font-weight: 600;
    font-size: 1.1rem;
    position: relative;
    z-index: 2;
}

.header-decoration {
    position: absolute;
    top: -20px;
    right: -20px;
    width: 80px;
    height: 80px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
    z-index: 1;
}

.custom-card-body {
    padding: 2rem;
}

/* Secciones dentro de las cards */
.vehicle-section, .documents-section {
    position: relative;
}

.section-subtitle {
    color: var(--color-principal);
    font-weight: 600;
    font-size: 1.1rem;
    padding: 0.75rem 0;
    margin-bottom: 1.5rem;
    border-bottom: 2px solid var(--color-azul-claro);
    position: relative;
}

.section-subtitle::after {
    content: '';
    position: absolute;
    bottom: -2px;
    left: 0;
    width: 60px;
    height: 2px;
    background: var(--color-principal);
}

/* Grupos de inputs mejorados */
.input-group-custom {
    position: relative;
    margin-bottom: 0.5rem;
}

.custom-label {
    font-weight: 600;
    color: var(--color-neutro-oscuro);
    margin-bottom: 0.75rem;
    display: flex;
    align-items: center;
    font-size: 0.95rem;
    transition: var(--transition-smooth);
}

.custom-label i {
    color: var(--color-principal);
    width: 16px;
    opacity: 0.7;
}

.custom-input {
    width: 100%;
    padding: 1rem 1.25rem;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    font-size: 1rem;
    transition: var(--transition-smooth);
    background: var(--color-blanco);
    font-family: inherit;
}

.custom-input:focus {
    outline: none;
    border-color: var(--color-principal);
    box-shadow: 0 0 0 4px rgba(31, 78, 121, 0.1);
    transform: translateY(-1px);
}

.custom-input:hover:not(:focus) {
    border-color: #cbd5e0;
}

.custom-input.is-invalid {
    border-color: #e53e3e;
    box-shadow: 0 0 0 4px rgba(229, 62, 62, 0.1);
}

/* Subida de archivos mejorada */
.file-upload-area {
    position: relative;
    margin-bottom: 1rem;
}

.custom-file-input {
    position: absolute;
    width: 1px;
    height: 1px;
    padding: 0;
    margin: -1px;
    overflow: hidden;
    clip: rect(0, 0, 0, 0);
    white-space: nowrap;
    border: 0;
}

.file-upload-label {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 1.25rem;
    border: 2px dashed #cbd5e0;
    border-radius: 12px;
    background: #f8fafc;
    color: var(--color-neutro-oscuro);
    cursor: pointer;
    transition: var(--transition-smooth);
    font-weight: 500;
    margin-bottom: 0;
}

.file-upload-label:hover {
    border-color: var(--color-principal);
    background: var(--color-azul-claro);
    color: var(--color-principal);
    transform: translateY(-2px);
}

.file-upload-label i {
    margin-right: 0.5rem;
    font-size: 1.1rem;
}

/* Media actual */
.current-media {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: var(--color-azul-claro);
    border-radius: 12px;
    margin-top: 1rem;
}

.current-photo {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 12px;
    border: 2px solid var(--color-blanco);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.media-info {
    flex: 1;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

/* Archivos actuales */
.file-current {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0.75rem 1rem;
    background: var(--color-azul-claro);
    border-radius: 8px;
    margin-top: 0.75rem;
    border: 1px solid rgba(31, 78, 121, 0.1);
}

.file-link {
    color: var(--color-principal);
    text-decoration: none;
    font-size: 0.9rem;
    font-weight: 500;
    transition: var(--transition-smooth);
}

.file-link:hover {
    text-decoration: underline;
    color: var(--color-principal);
}

.file-status {
    font-size: 0.8rem;
    color: var(--color-complementario);
    font-weight: 600;
    background: rgba(76, 175, 80, 0.1);
    padding: 0.25rem 0.5rem;
    border-radius: 6px;
}

/* Botón principal mejorado */
.custom-btn-primary {
    position: relative;
    background: linear-gradient(135deg, var(--color-complementario) 0%, #45A049 100%);
    color: var(--color-blanco);
    border: none;
    padding: 1.25rem 3.5rem;
    border-radius: 50px;
    font-size: 1.1rem;
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition-smooth);
    box-shadow: 0 8px 25px rgba(76, 175, 80, 0.3);
    overflow: hidden;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.custom-btn-primary:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 35px rgba(76, 175, 80, 0.4);
    background: linear-gradient(135deg, #45A049 0%, #388E3C 100%);
}

.custom-btn-primary:active {
    transform: translateY(-1px);
}

.btn-animation {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 0;
    height: 0;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    transition: var(--transition-smooth);
}

.custom-btn-primary:hover .btn-animation {
    width: 300px;
    height: 300px;
}

/* Alertas mejoradas */
.alert-success-custom, .alert-danger-custom {
    border-radius: 12px;
    padding: 1.25rem 1.5rem;
    margin-bottom: 2rem;
    border: none;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    position: relative;
    overflow: hidden;
}

.alert-success-custom {
    background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
    color: #155724;
}

.alert-danger-custom {
    background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
    color: #721c24;
}

.alert-danger-custom ul {
    margin-left: 1.5rem;
    margin-top: 0.5rem;
}

/* Mensajes de error */
.invalid-feedback {
    display: block;
    width: 100%;
    margin-top: 0.5rem;
    font-size: 0.875rem;
    color: #e53e3e;
    font-weight: 500;
}

/* Responsive mejorado */
@media (max-width: 768px) {
    .main-wrapper {
        padding-top: 70px;
    }
    
    .custom-card-body {
        padding: 1.5rem;
    }
    
    .custom-btn-primary {
        width: 100%;
        max-width: 350px;
        padding: 1rem 2rem;
    }
    
    .file-upload-label {
        padding: 1rem;
        font-size: 0.9rem;
    }
    
    .current-media {
        flex-direction: column;
        text-align: center;
        gap: 0.75rem;
    }
}

@media (max-width: 576px) {
    .custom-card-header {
        padding: 1.25rem 1.5rem;
    }
    
    .custom-card-body {
        padding: 1.25rem;
    }
    
    .subtitle {
        font-size: 1rem;
        padding: 0 1rem;
    }
}

/* Animaciones sutiles */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.custom-card {
    animation: fadeInUp 0.6s ease-out forwards;
}

.custom-card:nth-child(2) {
    animation-delay: 0.1s;
}

.custom-card:nth-child(3) {
    animation-delay: 0.2s;
}

/* Mejoras de accesibilidad */
.custom-input:focus-visible,
.custom-btn-primary:focus-visible,
.file-upload-label:focus-within {
    outline: 2px solid var(--color-principal);
    outline-offset: 2px;
}

/* Estados de hover mejorados */
.input-group-custom:hover .custom-label i {
    color: var(--color-principal);
    opacity: 1;
}
</style>
@endsection