@extends('layouts.app_admin')

@section('title', 'Crear Usuario')

@section('content')
<div class="container-fluid py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Usuarios</a></li>
            <li class="breadcrumb-item active">Crear Usuario</li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 fw-bold text-dark mb-1">
                <i class="fas fa-user-plus me-2 text-primary"></i>
                Crear Nuevo Usuario
            </h1>
            <p class="text-muted mb-0">Complete los datos para registrar un nuevo usuario en la plataforma</p>
        </div>
    </div>

    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i>
        <strong>¡Error!</strong> Por favor corrige los siguientes errores:
        <ul class="mb-0 mt-2">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <form method="POST" action="{{ route('admin.users.store') }}" enctype="multipart/form-data">
        @csrf

        <!-- Datos Personales -->
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-gradient-primary">
                <h5 class="mb-0 text-white">
                    <i class="fas fa-user me-2"></i>Datos Personales
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Nombre completo <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user text-primary"></i></span>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}" required placeholder="Ej: Juan Pérez">
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Correo electrónico <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-envelope text-primary"></i></span>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email') }}" required placeholder="ejemplo@email.com">
                            @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Contraseña <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock text-primary"></i></span>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                                   required placeholder="Mínimo 6 caracteres" id="password-input">
                            <button class="btn btn-outline-secondary" type="button" id="toggle-password">
                                <i class="fas fa-eye"></i>
                            </button>
                            @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Confirmar contraseña <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock text-primary"></i></span>
                            <input type="password" name="password_confirmation" class="form-control"
                                   required placeholder="Repite la contraseña">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Rol <span class="text-danger">*</span></label>
                        <select name="role" id="role" class="form-select @error('role') is-invalid @enderror" required>
                            <option value="">Selecciona un rol...</option>
                            @foreach($roles as $role)
                            <option value="{{ $role->name }}" {{ old('role') == $role->name ? 'selected' : '' }}>
                                {{ $role->name == 'admin' ? 'Administrador' : 'Usuario' }}
                            </option>
                            @endforeach
                        </select>
                        @error('role')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">País <span class="text-danger">*</span></label>
                        <select name="pais" class="form-select @error('pais') is-invalid @enderror" required>
                            @php
                                $paises = [
                                    'Argentina', 'Bolivia', 'Brasil', 'Chile', 'Colombia', 'Costa Rica', 'Cuba',
                                    'Ecuador', 'El Salvador', 'Guatemala', 'Honduras', 'México', 'Nicaragua',
                                    'Panamá', 'Paraguay', 'Perú', 'República Dominicana', 'Uruguay', 'Venezuela'
                                ];
                            @endphp
                            @foreach($paises as $pais)
                            <option value="{{ $pais }}" {{ old('pais', 'Argentina') == $pais ? 'selected' : '' }}>
                                {{ $pais }}
                            </option>
                            @endforeach
                        </select>
                        @error('pais')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Ciudad <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-city text-primary"></i></span>
                            <input type="text" name="ciudad" class="form-control @error('ciudad') is-invalid @enderror"
                                   value="{{ old('ciudad') }}" required placeholder="Ej: Buenos Aires">
                            @error('ciudad')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">DNI</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-id-card text-primary"></i></span>
                            <input type="text" name="dni" class="form-control @error('dni') is-invalid @enderror"
                                   value="{{ old('dni') }}" placeholder="Número de documento">
                            @error('dni')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Celular <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-phone text-primary"></i></span>
                            <input type="text" name="celular" class="form-control @error('celular') is-invalid @enderror"
                                   value="{{ old('celular') }}" required placeholder="Ej: +54 9 11 1234-5678">
                            @error('celular')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Fecha de Nacimiento</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-calendar text-primary"></i></span>
                            <input type="date" name="fecha_nacimiento" class="form-control @error('fecha_nacimiento') is-invalid @enderror"
                                   value="{{ old('fecha_nacimiento') }}">
                            @error('fecha_nacimiento')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Foto de perfil</label>
                        <input type="file" name="foto" class="form-control @error('foto') is-invalid @enderror"
                               accept="image/*" id="foto-input">
                        @error('foto')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div id="foto-preview" class="mt-3" style="display: none;">
                            <div class="text-center">
                                <img id="foto-preview-img" src="" alt="Vista previa"
                                     class="rounded-circle" style="width: 120px; height: 120px; object-fit: cover; border: 4px solid #10b981;">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">DNI Foto (Frente)</label>
                        <input type="file" name="dni_foto" class="form-control @error('dni_foto') is-invalid @enderror"
                               accept="image/*" id="dni-foto-input">
                        <small class="text-muted">Foto del frente del documento</small>
                        @error('dni_foto')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div id="dni-foto-preview" class="mt-2" style="display: none;">
                            <img id="dni-foto-preview-img" src="" alt="Vista previa DNI Frente"
                                 class="img-thumbnail" style="max-width: 200px;">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">DNI Foto (Atrás)</label>
                        <input type="file" name="dni_foto_atras" class="form-control @error('dni_foto_atras') is-invalid @enderror"
                               accept="image/*" id="dni-foto-atras-input">
                        <small class="text-muted">Foto del reverso del documento</small>
                        @error('dni_foto_atras')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div id="dni-foto-atras-preview" class="mt-2" style="display: none;">
                            <img id="dni-foto-atras-preview-img" src="" alt="Vista previa DNI Atrás"
                                 class="img-thumbnail" style="max-width: 200px;">
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="verificado" id="verificado" value="1">
                            <label class="form-check-label fw-semibold" for="verificado">
                                <i class="fas fa-check-circle me-1"></i>Verificar usuario inmediatamente
                            </label>
                            <small class="d-block text-muted mt-1">Si activas esta opción, el usuario será verificado automáticamente</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Información del Conductor (oculta por defecto) -->
        <div class="card mb-4 shadow-sm" id="conductor-section" style="display: none;">
            <div class="card-header bg-gradient-conductor">
                <h5 class="mb-0 text-white">
                    <i class="fas fa-car me-2"></i>Perfil de Conductor
                </h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Nota:</strong> Complete estos datos solo si el usuario también será conductor
                </div>

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Marca del vehículo</label>
                        <input type="text" name="marca_vehiculo" class="form-control" value="{{ old('marca_vehiculo') }}" placeholder="Ej: Toyota">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Modelo</label>
                        <input type="text" name="modelo_vehiculo" class="form-control" value="{{ old('modelo_vehiculo') }}" placeholder="Ej: Corolla">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Año</label>
                        <input type="number" name="anio_vehiculo" class="form-control" value="{{ old('anio_vehiculo') }}"
                               min="1990" max="{{ date('Y') + 1 }}" placeholder="{{ date('Y') }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Patente</label>
                        <input type="text" name="patente" class="form-control" value="{{ old('patente') }}" placeholder="Ej: ABC123">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Número de puestos</label>
                        <input type="number" name="numero_puestos" class="form-control" value="{{ old('numero_puestos') }}"
                               min="1" max="50" placeholder="Ej: 4">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Consumo (km/galón)</label>
                        <input type="number" name="consumo_por_galon" class="form-control" value="{{ old('consumo_por_galon') }}"
                               step="0.1" min="0" placeholder="Ej: 15.5">
                    </div>

                    <div class="col-md-12">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="verificar_pasajeros" id="verificar_pasajeros" value="1">
                            <label class="form-check-label fw-semibold" for="verificar_pasajeros">
                                ¿Requiere verificar pasajeros manualmente?
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Documentos del Conductor -->
        <div class="card mb-4 shadow-sm" id="documentos-section" style="display: none;">
            <div class="card-header bg-gradient-warning">
                <h5 class="mb-0 text-dark">
                    <i class="fas fa-file-alt me-2"></i>Documentos del Conductor
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Licencia de conducir</label>
                        <input type="file" name="licencia" class="form-control" accept="image/*,.pdf">
                        <small class="text-muted">Formatos: JPG, PNG, PDF (Máx. 5MB)</small>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Cédula de identidad</label>
                        <input type="file" name="cedula" class="form-control" accept="image/*,.pdf">
                        <small class="text-muted">Formatos: JPG, PNG, PDF (Máx. 5MB)</small>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Cédula verde del vehículo</label>
                        <input type="file" name="cedula_verde" class="form-control" accept="image/*,.pdf">
                        <small class="text-muted">Formatos: JPG, PNG, PDF (Máx. 5MB)</small>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Seguro del vehículo</label>
                        <input type="file" name="seguro" class="form-control" accept="image/*,.pdf">
                        <small class="text-muted">Formatos: JPG, PNG, PDF (Máx. 5MB)</small>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">RTO (Revisión Técnica)</label>
                        <input type="file" name="rto" class="form-control" accept="image/*,.pdf">
                        <small class="text-muted">Formatos: JPG, PNG, PDF (Máx. 5MB)</small>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Antecedentes penales</label>
                        <input type="file" name="antecedentes" class="form-control" accept="image/*,.pdf">
                        <small class="text-muted">Formatos: JPG, PNG, PDF (Máx. 5MB)</small>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Estado de verificación</label>
                        <select name="estado_verificacion" class="form-select">
                            <option value="pendiente" selected>Pendiente</option>
                            <option value="en_revision">En Revisión</option>
                            <option value="aprobado">Aprobado</option>
                            <option value="rechazado">Rechazado</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Estado del registro</label>
                        <select name="estado_registro" class="form-select">
                            <option value="completo" selected>Completo</option>
                            <option value="incompleto">Incompleto</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botones de acción -->
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Cancelar
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Crear Usuario
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
/* Gradientes personalizados para headers */
.bg-gradient-primary {
    background: linear-gradient(135deg, #1f4e79 0%, #245c7d 100%);
}

.bg-gradient-conductor {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.bg-gradient-warning {
    background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%);
}

/* Estilos de cards */
.card {
    border: none;
    border-radius: 12px;
    overflow: hidden;
}

.card-header {
    border-bottom: none;
    padding: 1.25rem 1.5rem;
}

/* Inputs y selects */
.form-control, .form-select {
    border-radius: 8px;
    border: 1px solid #dee2e6;
    padding: 0.625rem 0.75rem;
}

.form-control:focus, .form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.input-group-text {
    background-color: #f8f9fa;
    border: 1px solid #dee2e6;
    border-right: none;
}

/* Breadcrumb */
.breadcrumb {
    background: transparent;
    padding: 0;
    margin-bottom: 0;
}

.breadcrumb-item + .breadcrumb-item::before {
    content: "›";
}

.breadcrumb-item a {
    color: #667eea;
    text-decoration: none;
}

.breadcrumb-item a:hover {
    text-decoration: underline;
}

/* Botones */
.btn {
    border-radius: 8px;
    padding: 0.625rem 1.25rem;
    font-weight: 500;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #5568d3 0%, #6a4190 100%);
}

/* Alerts */
.alert {
    border-radius: 8px;
    border: none;
}

/* Form check switch */
.form-check-input:checked {
    background-color: #10b981;
    border-color: #10b981;
}

/* Preview de foto */
#foto-preview {
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle mostrar/ocultar secciones de conductor
    const roleSelect = document.getElementById('role');
    const conductorSection = document.getElementById('conductor-section');
    const documentosSection = document.getElementById('documentos-section');

    function toggleConductorSections() {
        const selectedRole = roleSelect.value;

        // Por defecto, las secciones están visibles para que el usuario decida
        // si quiere agregar información de conductor
        conductorSection.style.display = 'block';
        documentosSection.style.display = 'block';
    }

    roleSelect.addEventListener('change', toggleConductorSections);
    toggleConductorSections(); // Ejecutar al cargar

    // Función para manejar vista previa de imágenes
    function setupImagePreview(inputId, previewContainerId, previewImgId) {
        const input = document.getElementById(inputId);
        const previewContainer = document.getElementById(previewContainerId);
        const previewImg = document.getElementById(previewImgId);

        if (input && previewContainer && previewImg) {
            input.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file && file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImg.src = e.target.result;
                        previewContainer.style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                } else {
                    previewContainer.style.display = 'none';
                }
            });
        }
    }

    // Configurar vistas previas
    setupImagePreview('foto-input', 'foto-preview', 'foto-preview-img');
    setupImagePreview('dni-foto-input', 'dni-foto-preview', 'dni-foto-preview-img');
    setupImagePreview('dni-foto-atras-input', 'dni-foto-atras-preview', 'dni-foto-atras-preview-img');

    // Toggle mostrar/ocultar contraseña
    const togglePassword = document.getElementById('toggle-password');
    const passwordInput = document.getElementById('password-input');

    togglePassword.addEventListener('click', function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);

        const icon = this.querySelector('i');
        icon.classList.toggle('fa-eye');
        icon.classList.toggle('fa-eye-slash');
    });
});
</script>
@endpush
