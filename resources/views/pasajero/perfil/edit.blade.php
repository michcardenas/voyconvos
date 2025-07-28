@extends('layouts.app_dashboard')

@section('title', 'Editar Perfil de Pasajero')

@section('content')
<div class="main-wrapper">
    <div class="container py-5">
        <div class="page-header">
            <h2 class="mb-4 text-center profile-title">
                <span class="title-icon">✏️</span>
                Editar perfil de pasajero
            </h2>
            <p class="subtitle text-center">Mantén tu información actualizada para una mejor experiencia de viaje</p>
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

        <form action="{{ route('pasajero.perfil.update') }}" method="POST" enctype="multipart/form-data" class="profile-form">
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

                     <!-- FOTO DE PERFIL -->
<div class="col-sm-6">
    <div class="input-group-custom">
        <label class="custom-label">
            <i class="fas fa-camera me-1"></i>
            Foto de perfil
        </label>
        <div class="file-upload-area">
            <input type="file" 
                   name="foto" 
                   class="custom-file-input" 
                   accept="image/*" 
                   id="foto-upload"
                   onchange="previewImage(this, 'foto-preview-container')">
            <label for="foto-upload" class="file-upload-label">
                <i class="fas fa-cloud-upload-alt"></i>
                <span>Seleccionar imagen</span>
            </label>
        </div>
        
        <!-- Contenedor para preview -->
        <div id="foto-preview-container">
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

<!-- DNI FRENTE -->
<div class="col-sm-6">
    <div class="input-group-custom">
        <label class="custom-label">
            <i class="fas fa-id-card-alt me-1"></i>
            Documento DNI (foto frente)
        </label>
        <div class="file-upload-area">
            <input type="file" 
                   name="dni_foto" 
                   class="custom-file-input" 
                   accept="image/*" 
                   id="dni-foto-upload"
                   onchange="previewImage(this, 'dni-foto-preview-container')">
            <label for="dni-foto-upload" class="file-upload-label">
                <i class="fas fa-upload"></i>
                <span>Subir foto del DNI</span>
            </label>
        </div>
        
        <!-- Contenedor para preview -->
        <div id="dni-foto-preview-container">
            @if ($user->dni_foto)
                <div class="current-media">
                    <img src="{{ asset('storage/' . $user->dni_foto) }}" 
                         class="current-photo" 
                         alt="DNI actual">
                    <div class="media-info">
                        <small class="text-muted">DNI actual</small>
                        <i class="fas fa-check-circle text-success"></i>
                    </div>
                </div>
            @else
                <div class="upload-hint">
                    <i class="fas fa-info-circle me-1"></i>
                    <small class="text-muted">Sube una foto clara de tu documento (frente)</small>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- DNI REVERSO -->
<div class="col-sm-6">
    <div class="input-group-custom">
        <label class="custom-label">
            <i class="fas fa-id-card me-1"></i>
            Documento DNI (foto reverso)
        </label>
        <div class="file-upload-area">
            <input type="file" 
                   name="dni_foto_atras" 
                   class="custom-file-input" 
                   accept="image/*" 
                   id="dni-foto-reverso-upload"
                   onchange="previewImage(this, 'dni-reverso-preview-container')">
            <label for="dni-foto-reverso-upload" class="file-upload-label">
                <i class="fas fa-upload"></i>
                <span>Subir reverso del DNI</span>
            </label>
        </div>
        
        <!-- Contenedor para preview -->
        <div id="dni-reverso-preview-container">
            @if ($user->dni_foto_atras)
                <div class="current-media">
                    <img src="{{ asset('storage/' . $user->dni_foto_atras) }}" 
                        class="current-photo" 
                        alt="DNI reverso actual">
                    <div class="media-info">
                        <small class="text-muted">DNI reverso actual</small>
                        <i class="fas fa-check-circle text-success"></i>
                    </div>
                </div>
            @else
                <div class="upload-hint">
                    <i class="fas fa-info-circle me-1"></i>
                    <small class="text-muted">Sube una foto clara del reverso de tu documento</small>
                </div>
            @endif
        </div>
    </div>
</div>

            {{-- INFORMACIÓN ADICIONAL --}}
            <div class="custom-card mb-4" data-aos="fade-up" data-aos-delay="100">
                <div class="custom-card-header">
                    <div class="header-content">
                        <i class="fas fa-info-circle me-2"></i>
                        <span>Información de la cuenta</span>
                    </div>
                    <div class="header-decoration"></div>
                </div>
                <div class="custom-card-body">
                    <div class="row g-4">
                        <div class="col-sm-6">
                            <div class="info-item">
                                <div class="info-header">
                                    <i class="fas fa-calendar-plus text-primary"></i>
                                    <strong class="info-label">Fecha de registro</strong>
                                </div>
                                <span class="info-value">{{ $user->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="info-item">
                                <div class="info-header">
                                    <i class="fas fa-shield-check text-primary"></i>
                                    <strong class="info-label">Estado de verificación</strong>
                                </div>
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
                                <div class="info-header">
                                    <i class="fas fa-sync-alt text-primary"></i>
                                    <strong class="info-label">Última actualización</strong>
                                </div>
                                <span class="info-value">{{ $user->updated_at->format('d/m/Y H:i') }}</span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="info-item">
                                <div class="info-header">
                                    <i class="fas fa-hashtag text-primary"></i>
                                    <strong class="info-label">ID de usuario</strong>
                                </div>
                                <span class="info-value">#{{ $user->id }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- CAMBIAR CONTRASEÑA --}}
            <div class="custom-card mb-4" data-aos="fade-up" data-aos-delay="200">
                <div class="custom-card-header">
                    <div class="header-content">
                        <i class="fas fa-lock me-2"></i>
                        <span>Cambiar contraseña</span>
                    </div>
                    <div class="header-decoration"></div>
                </div>
                <div class="custom-card-body">
                    <div class="row g-4">
                        <div class="col-sm-6">
                            <div class="input-group-custom">
                                <label for="current_password" class="custom-label">
                                    <i class="fas fa-key me-1"></i>
                                    Contraseña actual
                                </label>
                                <div class="password-input-wrapper">
                                    <input type="password" 
                                           name="current_password" 
                                           id="current_password"
                                           class="custom-input @error('current_password') is-invalid @enderror" 
                                           placeholder="Ingresa tu contraseña actual">
                                    <button type="button" class="password-toggle" onclick="togglePassword('current_password')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <!-- Espacio en blanco para mantener diseño -->
                        </div>
                        <div class="col-sm-6">
                            <div class="input-group-custom">
                                <label for="new_password" class="custom-label">
                                    <i class="fas fa-plus-circle me-1"></i>
                                    Nueva contraseña
                                </label>
                                <div class="password-input-wrapper">
                                    <input type="password" 
                                           name="new_password" 
                                           id="new_password"
                                           class="custom-input @error('new_password') is-invalid @enderror" 
                                           placeholder="Ingresa tu nueva contraseña">
                                    <button type="button" class="password-toggle" onclick="togglePassword('new_password')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                @error('new_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="input-group-custom">
                                <label for="new_password_confirmation" class="custom-label">
                                    <i class="fas fa-check-double me-1"></i>
                                    Confirmar nueva contraseña
                                </label>
                                <div class="password-input-wrapper">
                                    <input type="password" 
                                           name="new_password_confirmation" 
                                           id="new_password_confirmation"
                                           class="custom-input" 
                                           placeholder="Confirma tu nueva contraseña">
                                    <button type="button" class="password-toggle" onclick="togglePassword('new_password_confirmation')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
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
            <div class="text-center" data-aos="fade-up" data-aos-delay="300">
                <button type="submit" class="custom-btn-primary">
                    <i class="fas fa-save me-2"></i>
                    <span>Guardar cambios</span>
                    <div class="btn-animation"></div>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const toggle = field.nextElementSibling.querySelector('i');
    
    if (field.type === 'password') {
        field.type = 'text';
        toggle.classList.remove('fa-eye');
        toggle.classList.add('fa-eye-slash');
    } else {
        field.type = 'password';
        toggle.classList.remove('fa-eye-slash');
        toggle.classList.add('fa-eye');
    }
}

function previewImage(input, previewContainerId) {
    const file = input.files[0];
    const previewContainer = document.getElementById(previewContainerId);
    
    if (file) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            // Crear o actualizar el preview
            let previewDiv = previewContainer.querySelector('.image-preview');
            
            if (!previewDiv) {
                previewDiv = document.createElement('div');
                previewDiv.className = 'image-preview mt-2';
                previewContainer.appendChild(previewDiv);
            }
            
            previewDiv.innerHTML = `
                <div class="preview-wrapper">
                    <img src="${e.target.result}" 
                         class="preview-image" 
                         alt="Vista previa"
                         style="max-width: 150px; max-height: 150px; object-fit: cover; border-radius: 8px; border: 2px solid #e3f2fd; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    <div class="preview-info mt-1 d-flex align-items-center justify-content-between">
                        <small class="text-success">
                            <i class="fas fa-image me-1"></i>
                            Nueva imagen seleccionada
                        </small>
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="clearPreview('${input.id}', '${previewContainerId}')">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            `;
        };
        
        reader.readAsDataURL(file);
    }
}

// Función para limpiar preview
function clearPreview(inputId, previewContainerId) {
    const input = document.getElementById(inputId);
    const previewContainer = document.getElementById(previewContainerId);
    const previewDiv = previewContainer.querySelector('.image-preview');
    
    input.value = '';
    if (previewDiv) {
        previewDiv.remove();
    }
}

// Función para toggle de contraseñas
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const toggle = field.nextElementSibling.querySelector('i');
    
    if (field.type === 'password') {
        field.type = 'text';
        toggle.classList.remove('fa-eye');
        toggle.classList.add('fa-eye-slash');
    } else {
        field.type = 'password';
        toggle.classList.remove('fa-eye-slash');
        toggle.classList.add('fa-eye');
    }
}
</script>

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
    padding-top: 25px; /* Espacio para el navbar */
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

/* Campos de contraseña con toggle */
.password-input-wrapper {
    position: relative;
}

.password-input-wrapper .custom-input {
    padding-right: 3rem;
}

.password-toggle {
    position: absolute;
    right: 1rem;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: var(--color-neutro-oscuro);
    cursor: pointer;
    padding: 0.25rem;
    border-radius: 4px;
    transition: var(--transition-smooth);
}

.password-toggle:hover {
    color: var(--color-principal);
    background: rgba(31, 78, 121, 0.1);
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

/* Hint para upload */
.upload-hint {
    margin-top: 0.75rem;
    padding: 0.75rem 1rem;
    background: #f8fafc;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
}

.upload-hint i {
    color: var(--color-principal);
}

/* Items de información mejorados */
.info-item {
    padding: 1.5rem;
    background: var(--color-azul-claro);
    border-radius: 12px;
    border-left: 4px solid var(--color-principal);
    transition: var(--transition-smooth);
    height: 100%;
}

.info-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(31, 78, 121, 0.1);
}

.info-header {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 0.5rem;
}

.info-label {
    color: var(--color-principal);
    font-size: 0.9rem;
    font-weight: 600;
}

.info-value {
    color: var(--color-neutro-oscuro);
    font-size: 1rem;
    font-weight: 500;
}

/* Badges de estado mejorados */
.badge-verified {
    background: linear-gradient(135deg, #d4edda, #c3e6cb);
    color: #155724;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    box-shadow: 0 2px 8px rgba(21, 87, 36, 0.2);
}

.badge-pending {
    background: linear-gradient(135deg, #fff3cd, #ffeaa7);
    color: #856404;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    box-shadow: 0 2px 8px rgba(133, 100, 4, 0.2);
}

/* Nota de contraseña mejorada */
.password-note {
    margin-top: 1.5rem;
    padding: 1.25rem;
    background: var(--color-azul-claro);
    border-radius: 12px;
    border-left: 4px solid var(--color-complementario);
    display: flex;
    align-items: center;
}

.password-note i {
    color: var(--color-complementario);
    margin-right: 0.5rem;
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

    .info-item {
        padding: 1.25rem;
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

.custom-card:nth-child(4) {
    animation-delay: 0.3s;
}

/* Mejoras de accesibilidad */
.custom-input:focus-visible,
.custom-btn-primary:focus-visible,
.file-upload-label:focus-within,
.password-toggle:focus-visible {
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