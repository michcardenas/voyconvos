@extends('layouts.app_admin')

@section('title', 'Editar Usuario')

@section('content')
<div class="container-fluid py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Usuarios</a></li>
            <li class="breadcrumb-item active">Editar Usuario</li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 fw-bold text-dark mb-1">
                <i class="fas fa-user-edit me-2 text-primary"></i>
                Editar Usuario
            </h1>
            <p class="text-muted mb-0">{{ $user->name }} • {{ $user->email }}</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Volver
            </a>
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

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <form method="POST" action="{{ route('admin.users.update', $user) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            <!-- Columna Izquierda -->
            <div class="col-lg-8">
                <!-- Datos Personales -->
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-gradient-primary">
                        <h5 class="mb-0 text-white">
                            <i class="fas fa-user me-2"></i>Información Personal
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Nombre completo <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-user text-primary"></i></span>
                                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                           value="{{ old('name', $user->name) }}" required>
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
                                           value="{{ old('email', $user->email) }}" required>
                                    @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Rol <span class="text-danger">*</span></label>
                                <select name="role" id="role" class="form-select @error('role') is-invalid @enderror" required>
                                    @foreach($roles as $role)
                                    <option value="{{ $role->name }}" {{ old('role', $user->getRoleNames()->first()) == $role->name ? 'selected' : '' }}>
                                        {{ $role->name == 'admin' ? 'Administrador' : 'Usuario' }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Fecha de Nacimiento</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-calendar text-primary"></i></span>
                                    <input type="date" name="fecha_nacimiento" class="form-control @error('fecha_nacimiento') is-invalid @enderror"
                                           value="{{ old('fecha_nacimiento', $user->fecha_nacimiento) }}">
                                    @error('fecha_nacimiento')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="card bg-light border-0">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div>
                                                <h6 class="mb-1 fw-semibold">
                                                    <i class="fas fa-shield-check me-2 text-primary"></i>
                                                    Estado de Verificación del Usuario
                                                </h6>
                                                <p class="text-muted small mb-0">
                                                    Marca al usuario como verificado para que pueda acceder a todas las funcionalidades de la plataforma
                                                </p>
                                            </div>
                                            <div class="form-check form-switch ms-3" style="transform: scale(1.5);">
                                                <input class="form-check-input"
                                                       type="checkbox"
                                                       name="verificado"
                                                       id="verificado"
                                                       value="1"
                                                       {{ old('verificado', $user->verificado) ? 'checked' : '' }}>
                                                <label class="form-check-label visually-hidden" for="verificado">
                                                    Verificado
                                                </label>
                                            </div>
                                        </div>
                                        <div class="mt-2">
                                            <span class="badge {{ $user->verificado ? 'bg-success' : 'bg-warning text-dark' }}" id="badge-verificacion">
                                                @if($user->verificado)
                                                <i class="fas fa-check-circle me-1"></i>Usuario Verificado
                                                @else
                                                <i class="fas fa-clock me-1"></i>Verificación Pendiente
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
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
                                    <option value="{{ $pais }}" {{ old('pais', $user->pais) == $pais ? 'selected' : '' }}>
                                        {{ $pais }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('pais')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Ciudad <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-city text-primary"></i></span>
                                    <input type="text" name="ciudad" class="form-control @error('ciudad') is-invalid @enderror"
                                           value="{{ old('ciudad', $user->ciudad) }}" required>
                                    @error('ciudad')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">DNI</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-id-card text-primary"></i></span>
                                    <input type="text" name="dni" class="form-control @error('dni') is-invalid @enderror"
                                           value="{{ old('dni', $user->dni) }}">
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
                                           value="{{ old('celular', $user->celular) }}" required>
                                    @error('celular')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Información del Conductor -->
                <div class="card mb-4 shadow-sm" id="conductor-section">
                    <div class="card-header bg-gradient-conductor">
                        <h5 class="mb-0 text-white">
                            <i class="fas fa-car me-2"></i>Perfil de Conductor
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($registroConductor)
                        <div class="alert alert-info mb-3">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Este usuario tiene perfil de conductor activo</strong>
                        </div>
                        @else
                        <div class="alert alert-warning mb-3">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Completa estos datos para convertir al usuario en conductor</strong>
                        </div>
                        @endif

                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Marca del vehículo</label>
                                <input type="text" name="marca_vehiculo" class="form-control"
                                       value="{{ old('marca_vehiculo', $registroConductor->marca_vehiculo ?? '') }}">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Modelo</label>
                                <input type="text" name="modelo_vehiculo" class="form-control"
                                       value="{{ old('modelo_vehiculo', $registroConductor->modelo_vehiculo ?? '') }}">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Año</label>
                                <input type="number" name="anio_vehiculo" class="form-control"
                                       value="{{ old('anio_vehiculo', $registroConductor->anio_vehiculo ?? '') }}"
                                       min="1990" max="{{ date('Y') + 1 }}">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Patente</label>
                                <input type="text" name="patente" class="form-control text-uppercase"
                                       value="{{ old('patente', $registroConductor->patente ?? '') }}">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Número de puestos</label>
                                <input type="number" name="numero_puestos" class="form-control"
                                       value="{{ old('numero_puestos', $registroConductor->numero_puestos ?? '') }}"
                                       min="1" max="50">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Consumo (km/galón)</label>
                                <input type="number" name="consumo_por_galon" class="form-control"
                                       value="{{ old('consumo_por_galon', $registroConductor->consumo_por_galon ?? '') }}"
                                       step="0.1" min="0">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Estado de verificación</label>
                                <select name="estado_verificacion" class="form-select">
                                    <option value="pendiente" {{ old('estado_verificacion', $registroConductor->estado_verificacion ?? 'pendiente') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                    <option value="en_revision" {{ old('estado_verificacion', $registroConductor->estado_verificacion ?? '') == 'en_revision' ? 'selected' : '' }}>En Revisión</option>
                                    <option value="aprobado" {{ old('estado_verificacion', $registroConductor->estado_verificacion ?? '') == 'aprobado' ? 'selected' : '' }}>Aprobado</option>
                                    <option value="rechazado" {{ old('estado_verificacion', $registroConductor->estado_verificacion ?? '') == 'rechazado' ? 'selected' : '' }}>Rechazado</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Estado del registro</label>
                                <select name="estado_registro" class="form-select">
                                    <option value="completo" {{ old('estado_registro', $registroConductor->estado_registro ?? 'completo') == 'completo' ? 'selected' : '' }}>Completo</option>
                                    <option value="incompleto" {{ old('estado_registro', $registroConductor->estado_registro ?? '') == 'incompleto' ? 'selected' : '' }}>Incompleto</option>
                                </select>
                            </div>

                            <div class="col-12">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="verificar_pasajeros" id="verificar_pasajeros"
                                           value="1" {{ old('verificar_pasajeros', $registroConductor->verificar_pasajeros ?? 0) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold" for="verificar_pasajeros">
                                        ¿Requiere verificar pasajeros manualmente?
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Documentos del Conductor -->
                <div class="card mb-4 shadow-sm" id="documentos-section">
                    <div class="card-header bg-gradient-warning">
                        <h5 class="mb-0 text-dark">
                            <i class="fas fa-file-alt me-2"></i>Documentos del Conductor
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            @foreach([
                                'licencia' => 'Licencia de conducir',
                                'cedula' => 'Cédula de identidad',
                                'cedula_verde' => 'Cédula verde del vehículo',
                                'seguro' => 'Seguro del vehículo'
                            ] as $field => $label)
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">{{ $label }}</label>
                                <input type="file" name="{{ $field }}" id="{{ $field }}" class="form-control" accept="image/*,.pdf">
                                <small class="text-muted">Formatos: JPG, PNG, PDF (Máx. 5MB)</small>

                                @if($registroConductor && $registroConductor->$field)
                                <div class="mt-3 p-3 border rounded bg-light">
                                    <p class="text-muted mb-2 small"><i class="fas fa-paperclip me-1"></i>Documento actual:</p>
                                    @php
                                        $extension = pathinfo($registroConductor->$field, PATHINFO_EXTENSION);
                                        $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif']);
                                    @endphp

                                    @if($isImage)
                                    <img src="{{ asset('storage/' . $registroConductor->$field) }}"
                                         alt="{{ $label }}"
                                         class="img-thumbnail cursor-pointer"
                                         style="max-width: 200px; cursor: pointer;"
                                         onclick="window.open(this.src, '_blank')">
                                    @else
                                    <a href="{{ asset('storage/' . $registroConductor->$field) }}" target="_blank" class="btn btn-sm btn-primary">
                                        <i class="fas fa-file-pdf me-1"></i>Ver {{ strtoupper($extension) }}
                                    </a>
                                    @endif
                                </div>
                                @endif

                                <div id="preview-{{ $field }}" class="mt-2" style="display: none;">
                                    <p class="text-muted small">Vista previa:</p>
                                    <img id="img-preview-{{ $field }}" src="" alt="Vista previa" class="img-thumbnail" style="max-width: 200px;">
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Columna Derecha -->
            <div class="col-lg-4">
                <!-- Foto de Perfil -->
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-gradient-success">
                        <h5 class="mb-0 text-white">
                            <i class="fas fa-camera me-2"></i>Foto de Perfil
                        </h5>
                    </div>
                    <div class="card-body text-center">
                        @if($user->foto)
                        <div class="mb-3">
                            <p class="text-muted small mb-2">Foto actual:</p>
                            <img src="{{ asset('storage/' . $user->foto) }}"
                                 alt="{{ $user->name }}"
                                 class="rounded-circle img-thumbnail cursor-pointer"
                                 style="width: 150px; height: 150px; object-fit: cover; cursor: pointer;"
                                 onclick="window.open(this.src, '_blank')">
                        </div>
                        @else
                        <div class="mb-3">
                            <div class="avatar-placeholder mx-auto" style="width: 150px; height: 150px;">
                                <span class="display-4">{{ strtoupper(substr($user->name, 0, 2)) }}</span>
                            </div>
                        </div>
                        @endif

                        <input type="file" name="foto" id="foto" class="form-control" accept="image/*">
                        <small class="text-muted d-block mt-1">JPG, PNG (Máx. 2MB)</small>

                        <div id="preview-nueva-foto" class="mt-3" style="display: none;">
                            <p class="text-muted small">Nueva foto:</p>
                            <img id="img-preview" src="" alt="Vista previa"
                                 class="rounded-circle img-thumbnail"
                                 style="width: 150px; height: 150px; object-fit: cover;">
                        </div>
                    </div>
                </div>

                <!-- Documentos de Identidad -->
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-gradient-info">
                        <h5 class="mb-0 text-white">
                            <i class="fas fa-id-card me-2"></i>Documentos de Identidad
                        </h5>
                    </div>
                    <div class="card-body">
                        @foreach(['dni_foto' => 'DNI (Frente)', 'dni_foto_atras' => 'DNI (Atrás)'] as $field => $label)
                        <div class="mb-3">
                            <label class="form-label fw-semibold">{{ $label }}</label>
                            <input type="file" name="{{ $field }}" id="{{ $field }}" class="form-control" accept="image/*">

                            @if($user->$field)
                            <div class="mt-2 p-2 border rounded bg-light text-center">
                                <p class="text-muted small mb-2">Documento actual:</p>
                                <img src="{{ asset('storage/' . $user->$field) }}"
                                     alt="{{ $label }}"
                                     class="img-thumbnail cursor-pointer"
                                     style="max-width: 100%; cursor: pointer;"
                                     onclick="window.open(this.src, '_blank')">
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Información del Sistema -->
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">
                            <i class="fas fa-info-circle me-2"></i>Información del Sistema
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-2">
                            <small class="text-muted">Usuario ID:</small>
                            <div class="fw-semibold">#{{ $user->id }}</div>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted">Creado:</small>
                            <div>{{ $user->created_at->format('d/m/Y H:i') }}</div>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted">Última actualización:</small>
                            <div>{{ $user->updated_at->format('d/m/Y H:i') }}</div>
                        </div>
                        @if($registroConductor)
                        <div class="mb-0">
                            <small class="text-muted">Perfil de conductor:</small>
                            <div><span class="badge bg-success">Activo</span></div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Botones de acción -->
        <div class="card shadow-sm mt-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-2"></i>Cancelar
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Guardar Cambios
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
/* Gradientes personalizados */
.bg-gradient-primary {
    background: linear-gradient(135deg, #1f4e79 0%, #245c7d 100%);
}

.bg-gradient-conductor {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.bg-gradient-warning {
    background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%);
}

.bg-gradient-success {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
}

.bg-gradient-info {
    background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
}

/* Cards */
.card {
    border: none;
    border-radius: 12px;
    overflow: hidden;
}

.card-header {
    border-bottom: none;
    padding: 1.25rem 1.5rem;
}

/* Inputs */
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

/* Avatar placeholder */
.avatar-placeholder {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 700;
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

/* Cursor pointer */
.cursor-pointer {
    cursor: pointer;
}

/* Form check switch */
.form-check-input:checked {
    background-color: #10b981;
    border-color: #10b981;
}

/* Alerts */
.alert {
    border-radius: 8px;
    border: none;
}

/* Badge de verificación */
#badge-verificacion {
    font-size: 0.875rem;
    padding: 0.5rem 1rem;
    border-radius: 6px;
    font-weight: 500;
}

/* Card de verificación */
.card.bg-light {
    background-color: #f8f9fa !important;
}

.form-check-input {
    width: 3rem;
    height: 1.5rem;
    cursor: pointer;
}

.form-check-input:checked {
    background-color: #10b981;
    border-color: #10b981;
}
</style>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Vista previa de foto de perfil
    const fotoInput = document.getElementById('foto');
    const previewContainer = document.getElementById('preview-nueva-foto');
    const previewImage = document.getElementById('img-preview');

    if (fotoInput) {
        fotoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    previewContainer.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                previewContainer.style.display = 'none';
            }
        });
    }

    // Vista previa de documentos del conductor
    function setupDocumentPreview(inputId, previewId, imgId) {
        const input = document.getElementById(inputId);
        const previewContainer = document.getElementById(previewId);
        const previewImage = document.getElementById(imgId);

        if (input && previewContainer && previewImage) {
            input.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file && file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImage.src = e.target.result;
                        previewContainer.style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                } else {
                    previewContainer.style.display = 'none';
                }
            });
        }
    }

    // Configurar vista previa para todos los documentos
    setupDocumentPreview('licencia', 'preview-licencia', 'img-preview-licencia');
    setupDocumentPreview('cedula', 'preview-cedula', 'img-preview-cedula');
    setupDocumentPreview('cedula_verde', 'preview-cedula_verde', 'img-preview-cedula_verde');
    setupDocumentPreview('seguro', 'preview-seguro', 'img-preview-seguro');

    // Manejar cambio de estado de verificación
    const verificadoCheckbox = document.getElementById('verificado');
    const badgeVerificacion = document.getElementById('badge-verificacion');

    if (verificadoCheckbox && badgeVerificacion) {
        verificadoCheckbox.addEventListener('change', function() {
            if (this.checked) {
                badgeVerificacion.className = 'badge bg-success';
                badgeVerificacion.innerHTML = '<i class="fas fa-check-circle me-1"></i>Usuario Verificado';
            } else {
                badgeVerificacion.className = 'badge bg-warning text-dark';
                badgeVerificacion.innerHTML = '<i class="fas fa-clock me-1"></i>Verificación Pendiente';
            }
        });
    }
});
</script>
@endpush
