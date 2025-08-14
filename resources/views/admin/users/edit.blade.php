@extends('layouts.app_admin')

@section('title', 'Editar Usuario')

@section('content')
<div class="container_profile">
    <h1 class="title_profile">Editar Usuario</h1>

    <form method="POST" action="{{ route('admin.users.update', $user) }}" enctype="multipart/form-data" class="form_profile">
        @csrf
        @method('PUT')

        <!-- Datos básicos del usuario -->
        <div class="section_profile">
            <h2 class="section-title_profile">👤 Información Personal</h2>
            
            <div class="form-group_profile">
                <label for="name">Nombre</label>
                <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}" required>
            </div>

            <div class="form-group_profile">
                <label for="email">Correo</label>
                <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required>
            </div>

            <div class="form-group_profile">
                <label for="role">Tipo de usuario</label>
                <select name="role" id="role" required>
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}" {{ old('role', $user->getRoleNames()->first()) == $role->name ? 'selected' : '' }}>
                            {{ ucfirst($role->name) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group_profile">
                <label for="pais">Nacionalidad</label>
                <select name="pais" id="pais" required>
                    @php
                        $paises = [
                            'Argentina', 'Bolivia', 'Brasil', 'Chile', 'Colombia', 'Costa Rica', 'Cuba',
                            'Ecuador', 'El Salvador', 'Guatemala', 'Honduras', 'México', 'Nicaragua',
                            'Panamá', 'Paraguay', 'Perú', 'República Dominicana', 'Uruguay', 'Venezuela'
                        ];
                    @endphp
                    @foreach($paises as $pais)
                        <option value="{{ $pais }}" {{ old('pais', $user->pais) == $pais ? 'selected' : '' }}>{{ $pais }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group_profile">
                <label for="ciudad">Ciudad</label>
                <input id="ciudad" type="text" name="ciudad" value="{{ old('ciudad', $user->ciudad) }}" required>
            </div>

            <div class="form-group_profile">
                <label for="dni">DNI</label>
                <input id="dni" type="text" name="dni" value="{{ old('dni', $user->dni) }}">
            </div>

            <div class="form-group_profile">
                <label for="celular">Celular</label>
                <input id="celular" type="text" name="celular" value="{{ old('celular', $user->celular) }}" required>
            </div>

            <div class="form-group_profile">
                <label for="verificado">¿Verificado?</label>
                <select name="verificado" id="verificado" required>
                    <option value="1" {{ old('verificado', $user->verificado) ? 'selected' : '' }}>Sí</option>
                    <option value="0" {{ old('verificado', $user->verificado) == 0 ? 'selected' : '' }}>No</option>
                </select>
            </div>
        </div>

        <!-- Documentos del usuario -->
        <div class="section_profile">
            <h2 class="section-title_profile">📄 Documentos del Usuario</h2>

            <div class="form-group_profile">
                <label for="foto">Foto de perfil</label>
                <input type="file" id="foto" name="foto" accept="image/*">

                @if($user->foto)
                    <div class="profile-preview_profile">
                        <p class="preview-label_profile">Foto actual:</p>
                        <div class="profile-img-wrapper_profile">
                            <img src="{{ asset('storage/' . $user->foto) }}" alt="Foto actual" class="profile-img_profile" style="max-width: 150px; cursor: pointer;" onclick="window.open(this.src, '_blank')">
                        </div>
                    </div>
                @endif

                <div id="preview-nueva-foto" class="profile-preview_profile" style="display: none;">
                    <p class="preview-label_profile">Vista previa:</p>
                    <div class="profile-img-wrapper_profile">
                        <img id="img-preview" src="" alt="Vista previa" class="profile-img_profile">
                    </div>
                </div>
            </div>

            <div class="form-group_profile">
                <label for="dni_foto">DNI (Frente)</label>
                <input type="file" id="dni_foto" name="dni_foto" accept="image/*">
                
                @if($user->dni_foto)
                    <div style="margin-top: 10px;">
                        <p style="color: #666; margin-bottom: 5px;">Documento actual:</p>
                        <img src="{{ asset('storage/' . $user->dni_foto) }}" 
                             alt="DNI Frente" 
                             style="max-width: 200px; border: 1px solid #ddd; cursor: pointer;" 
                             onclick="window.open(this.src, '_blank')">
                    </div>
                @endif
            </div>

            <div class="form-group_profile">
                <label for="dni_foto_atras">DNI (Atrás)</label>
                <input type="file" id="dni_foto_atras" name="dni_foto_atras" accept="image/*">
                
                @if($user->dni_foto_atras)
                    <div style="margin-top: 10px;">
                        <p style="color: #666; margin-bottom: 5px;">Documento actual:</p>
                        <img src="{{ asset('storage/' . $user->dni_foto_atras) }}" 
                             alt="DNI Atrás" 
                             style="max-width: 200px; border: 1px solid #ddd; cursor: pointer;" 
                             onclick="window.open(this.src, '_blank')">
                    </div>
                @endif
            </div>
        </div>

        <!-- Información del conductor (editable) -->
        <div class="section_profile" id="conductor-section" style="{{ $user->hasRole('conductor') ? 'display: block;' : 'display: none;' }}">
            <h2 class="section-title_profile">🚗 Información del Vehículo</h2>
            
            <div class="form-group_profile">
                <label for="marca_vehiculo">Marca del Vehículo</label>
                <input type="text" id="marca_vehiculo" name="marca_vehiculo" value="{{ old('marca_vehiculo', $registroConductor->marca_vehiculo ?? '') }}">
            </div>

            <div class="form-group_profile">
                <label for="modelo_vehiculo">Modelo del Vehículo</label>
                <input type="text" id="modelo_vehiculo" name="modelo_vehiculo" value="{{ old('modelo_vehiculo', $registroConductor->modelo_vehiculo ?? '') }}">
            </div>

            <div class="form-group_profile">
                <label for="anio_vehiculo">Año del Vehículo</label>
                <input type="number" id="anio_vehiculo" name="anio_vehiculo" value="{{ old('anio_vehiculo', $registroConductor->anio_vehiculo ?? '') }}" min="1990" max="{{ date('Y') + 1 }}">
            </div>

            <div class="form-group_profile">
                <label for="patente">Patente</label>
                <input type="text" id="patente" name="patente" value="{{ old('patente', $registroConductor->patente ?? '') }}">
            </div>

            <div class="form-group_profile">
                <label for="numero_puestos">Número de Puestos</label>
                <input type="number" id="numero_puestos" name="numero_puestos" value="{{ old('numero_puestos', $registroConductor->numero_puestos ?? '') }}" min="1" max="50">
            </div>

            <div class="form-group_profile">
                <label for="consumo_por_galon">Consumo por Galón (km/galón)</label>
                <input type="number" id="consumo_por_galon" name="consumo_por_galon" value="{{ old('consumo_por_galon', $registroConductor->consumo_por_galon ?? '') }}" step="0.1" min="0">
            </div>

            <div class="form-group_profile">
                <label for="verificar_pasajeros">¿Verificar Pasajeros?</label>
                <select name="verificar_pasajeros" id="verificar_pasajeros">
                    <option value="0" {{ old('verificar_pasajeros', $registroConductor->verificar_pasajeros ?? 0) == '0' ? 'selected' : '' }}>No</option>
                    <option value="1" {{ old('verificar_pasajeros', $registroConductor->verificar_pasajeros ?? 0) == '1' ? 'selected' : '' }}>Sí</option>
                </select>
            </div>

            <div class="form-group_profile">
                <label for="estado_verificacion">Estado de Verificación</label>
                <select name="estado_verificacion" id="estado_verificacion">
                    <option value="pendiente" {{ old('estado_verificacion', $registroConductor->estado_verificacion ?? 'pendiente') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                    <option value="en_revision" {{ old('estado_verificacion', $registroConductor->estado_verificacion ?? 'pendiente') == 'en_revision' ? 'selected' : '' }}>En Revisión</option>
                    <option value="aprobado" {{ old('estado_verificacion', $registroConductor->estado_verificacion ?? 'pendiente') == 'aprobado' ? 'selected' : '' }}>Aprobado</option>
                    <option value="rechazado" {{ old('estado_verificacion', $registroConductor->estado_verificacion ?? 'pendiente') == 'rechazado' ? 'selected' : '' }}>Rechazado</option>
                </select>
            </div>

            <div class="form-group_profile">
                <label for="estado_registro">Estado de Registro</label>
                <select name="estado_registro" id="estado_registro">
                    <option value="activo" {{ old('estado_registro', $registroConductor->estado_registro ?? 'activo') == 'activo' ? 'selected' : '' }}>Activo</option>
                    <option value="inactivo" {{ old('estado_registro', $registroConductor->estado_registro ?? 'activo') == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                    <option value="suspendido" {{ old('estado_registro', $registroConductor->estado_registro ?? 'activo') == 'suspendido' ? 'selected' : '' }}>Suspendido</option>
                </select>
            </div>
        </div>

        <!-- Documentos del conductor (editables) -->
        <div class="section_profile" id="documentos-section" style="{{ $user->hasRole('conductor') ? 'display: block;' : 'display: none;' }}">
            <h2 class="section-title_profile">📋 Documentos del Conductor</h2>
            
            <div class="form-group_profile">
                <label for="licencia">Licencia de Conducir</label>
                <input type="file" id="licencia" name="licencia" accept="image/*,.pdf">
                
                @if($registroConductor && $registroConductor->licencia)
                    <div style="margin-top: 10px;">
                        <p style="color: #666; margin-bottom: 5px;">Documento actual:</p>
                        @php
                            $extension = pathinfo($registroConductor->licencia, PATHINFO_EXTENSION);
                            $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif']);
                        @endphp
                        
                        @if($isImage)
                            <img src="{{ asset('storage/' . $registroConductor->licencia) }}" 
                                 alt="Licencia" 
                                 style="max-width: 200px; border: 1px solid #ddd; cursor: pointer;" 
                                 onclick="window.open(this.src, '_blank')">
                        @else
                            <a href="{{ asset('storage/' . $registroConductor->licencia) }}" target="_blank" 
                               style="display: inline-block; padding: 8px 12px; background-color: #007bff; color: white; text-decoration: none; border-radius: 4px;">
                                📄 Ver Documento ({{ strtoupper($extension) }})
                            </a>
                        @endif
                    </div>
                @endif

                <div id="preview-licencia" class="document-preview_profile" style="display: none;">
                    <p class="preview-label_profile">Vista previa:</p>
                    <div class="document-wrapper_profile">
                        <img id="img-preview-licencia" src="" alt="Vista previa licencia" class="document-img_profile">
                    </div>
                </div>
            </div>

            <div class="form-group_profile">
                <label for="cedula">Cédula de Identidad</label>
                <input type="file" id="cedula" name="cedula" accept="image/*,.pdf">
                
                @if($registroConductor && $registroConductor->cedula)
                    <div style="margin-top: 10px;">
                        <p style="color: #666; margin-bottom: 5px;">Documento actual:</p>
                        @php
                            $extension = pathinfo($registroConductor->cedula, PATHINFO_EXTENSION);
                            $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif']);
                        @endphp
                        
                        @if($isImage)
                            <img src="{{ asset('storage/' . $registroConductor->cedula) }}" 
                                 alt="Cédula" 
                                 style="max-width: 200px; border: 1px solid #ddd; cursor: pointer;" 
                                 onclick="window.open(this.src, '_blank')">
                        @else
                            <a href="{{ asset('storage/' . $registroConductor->cedula) }}" target="_blank" 
                               style="display: inline-block; padding: 8px 12px; background-color: #007bff; color: white; text-decoration: none; border-radius: 4px;">
                                📄 Ver Documento ({{ strtoupper($extension) }})
                            </a>
                        @endif
                    </div>
                @endif

                <div id="preview-cedula" class="document-preview_profile" style="display: none;">
                    <p class="preview-label_profile">Vista previa:</p>
                    <div class="document-wrapper_profile">
                        <img id="img-preview-cedula" src="" alt="Vista previa cédula" class="document-img_profile">
                    </div>
                </div>
            </div>

            <div class="form-group_profile">
                <label for="cedula_verde">Cédula Verde del Vehículo</label>
                <input type="file" id="cedula_verde" name="cedula_verde" accept="image/*,.pdf">
                
                @if($registroConductor && $registroConductor->cedula_verde)
                    <div style="margin-top: 10px;">
                        <p style="color: #666; margin-bottom: 5px;">Documento actual:</p>
                        @php
                            $extension = pathinfo($registroConductor->cedula_verde, PATHINFO_EXTENSION);
                            $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif']);
                        @endphp
                        
                        @if($isImage)
                            <img src="{{ asset('storage/' . $registroConductor->cedula_verde) }}" 
                                 alt="Cédula Verde" 
                                 style="max-width: 200px; border: 1px solid #ddd; cursor: pointer;" 
                                 onclick="window.open(this.src, '_blank')">
                        @else
                            <a href="{{ asset('storage/' . $registroConductor->cedula_verde) }}" target="_blank" 
                               style="display: inline-block; padding: 8px 12px; background-color: #007bff; color: white; text-decoration: none; border-radius: 4px;">
                                📄 Ver Documento ({{ strtoupper($extension) }})
                            </a>
                        @endif
                    </div>
                @endif

                <div id="preview-cedula_verde" class="document-preview_profile" style="display: none;">
                    <p class="preview-label_profile">Vista previa:</p>
                    <div class="document-wrapper_profile">
                        <img id="img-preview-cedula_verde" src="" alt="Vista previa cédula verde" class="document-img_profile">
                    </div>
                </div>
            </div>

            <div class="form-group_profile">
                <label for="seguro">Seguro del Vehículo</label>
                <input type="file" id="seguro" name="seguro" accept="image/*,.pdf">
                
                @if($registroConductor && $registroConductor->seguro)
                    <div style="margin-top: 10px;">
                        <p style="color: #666; margin-bottom: 5px;">Documento actual:</p>
                        @php
                            $extension = pathinfo($registroConductor->seguro, PATHINFO_EXTENSION);
                            $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif']);
                        @endphp
                        
                        @if($isImage)
                            <img src="{{ asset('storage/' . $registroConductor->seguro) }}" 
                                 alt="Seguro" 
                                 style="max-width: 200px; border: 1px solid #ddd; cursor: pointer;" 
                                 onclick="window.open(this.src, '_blank')">
                        @else
                            <a href="{{ asset('storage/' . $registroConductor->seguro) }}" target="_blank" 
                               style="display: inline-block; padding: 8px 12px; background-color: #007bff; color: white; text-decoration: none; border-radius: 4px;">
                                📄 Ver Documento ({{ strtoupper($extension) }})
                            </a>
                        @endif
                    </div>
                @endif

                <div id="preview-seguro" class="document-preview_profile" style="display: none;">
                    <p class="preview-label_profile">Vista previa:</p>
                    <div class="document-wrapper_profile">
                        <img id="img-preview-seguro" src="" alt="Vista previa seguro" class="document-img_profile">
                    </div>
                </div>
            </div>

            <div class="form-group_profile">
                <label for="rto">RTO (Revisión Técnica Obligatoria)</label>
                <input type="file" id="rto" name="rto" accept="image/*,.pdf">
                
                @if($registroConductor && $registroConductor->rto)
                    <div style="margin-top: 10px;">
                        <p style="color: #666; margin-bottom: 5px;">Documento actual:</p>
                        @php
                            $extension = pathinfo($registroConductor->rto, PATHINFO_EXTENSION);
                            $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif']);
                        @endphp
                        
                        @if($isImage)
                            <img src="{{ asset('storage/' . $registroConductor->rto) }}" 
                                 alt="RTO" 
                                 style="max-width: 200px; border: 1px solid #ddd; cursor: pointer;" 
                                 onclick="window.open(this.src, '_blank')">
                        @else
                            <a href="{{ asset('storage/' . $registroConductor->rto) }}" target="_blank" 
                               style="display: inline-block; padding: 8px 12px; background-color: #007bff; color: white; text-decoration: none; border-radius: 4px;">
                                📄 Ver Documento ({{ strtoupper($extension) }})
                            </a>
                        @endif
                    </div>
                @endif

                <div id="preview-rto" class="document-preview_profile" style="display: none;">
                    <p class="preview-label_profile">Vista previa:</p>
                    <div class="document-wrapper_profile">
                        <img id="img-preview-rto" src="" alt="Vista previa RTO" class="document-img_profile">
                    </div>
                </div>
            </div>

            <div class="form-group_profile">
                <label for="antecedentes">Antecedentes Penales</label>
                <input type="file" id="antecedentes" name="antecedentes" accept="image/*,.pdf">
                
                @if($registroConductor && $registroConductor->antecedentes)
                    <div style="margin-top: 10px;">
                        <p style="color: #666; margin-bottom: 5px;">Documento actual:</p>
                        @php
                            $extension = pathinfo($registroConductor->antecedentes, PATHINFO_EXTENSION);
                            $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif']);
                        @endphp
                        
                        @if($isImage)
                            <img src="{{ asset('storage/' . $registroConductor->antecedentes) }}" 
                                 alt="Antecedentes" 
                                 style="max-width: 200px; border: 1px solid #ddd; cursor: pointer;" 
                                 onclick="window.open(this.src, '_blank')">
                        @else
                            <a href="{{ asset('storage/' . $registroConductor->antecedentes) }}" target="_blank" 
                               style="display: inline-block; padding: 8px 12px; background-color: #007bff; color: white; text-decoration: none; border-radius: 4px;">
                                📄 Ver Documento ({{ strtoupper($extension) }})
                            </a>
                        @endif
                    </div>
                @endif

                <div id="preview-antecedentes" class="document-preview_profile" style="display: none;">
                    <p class="preview-label_profile">Vista previa:</p>
                    <div class="document-wrapper_profile">
                        <img id="img-preview-antecedentes" src="" alt="Vista previa antecedentes" class="document-img_profile">
                    </div>
                </div>
            </div>
        </div>

        <button type="submit" class="btn_profile btn-success_profile" style="margin-top: 30px;">Guardar cambios</button>
    </form>
</div>

@push('styles')
<style>
.section_profile {
    margin-bottom: 2rem;
    padding: 1.5rem;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    background-color: #f9f9f9;
}

.section-title_profile {
    margin-bottom: 1rem;
    color: #333;
    font-size: 1.2rem;
    font-weight: bold;
    border-bottom: 2px solid #007bff;
    padding-bottom: 0.5rem;
}

.document-preview_profile {
    margin-top: 0.5rem;
}

.document-wrapper_profile {
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 0.5rem;
    background-color: white;
}

.document-img_profile {
    max-width: 200px;
    max-height: 150px;
    object-fit: contain;
    border-radius: 4px;
}

.preview-label_profile {
    font-size: 0.9rem;
    color: #666;
    margin-bottom: 0.5rem;
}
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const roleSelect = document.getElementById('role');
        const conductorSection = document.getElementById('conductor-section');
        const documentosSection = document.getElementById('documentos-section');

        // Función para mostrar/ocultar secciones de conductor
        function toggleConductorSections() {
            const selectedRole = roleSelect.value;
            if (selectedRole === 'conductor' || selectedRole === 'driver') {
                conductorSection.style.display = 'block';
                documentosSection.style.display = 'block';
            } else {
                conductorSection.style.display = 'none';
                documentosSection.style.display = 'none';
            }
        }

        // Evento para cambio de rol
        roleSelect.addEventListener('change', toggleConductorSections);

        // Función para vista previa de foto de perfil
        const fotoInput = document.getElementById('foto');
        const previewContainer = document.getElementById('preview-nueva-foto');
        const previewImage = document.getElementById('img-preview');

        fotoInput.addEventListener('change', function (event) {
            const file = event.target.files[0];
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    previewImage.src = e.target.result;
                    previewContainer.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                previewContainer.style.display = 'none';
            }
        });

        // Función para vista previa de documentos
        function setupDocumentPreview(inputId, previewId, imgId) {
            const input = document.getElementById(inputId);
            const previewContainer = document.getElementById(previewId);
            const previewImage = document.getElementById(imgId);

            if (input && previewContainer && previewImage) {
                input.addEventListener('change', function (event) {
                    const file = event.target.files[0];
                    if (file && file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function (e) {
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
        setupDocumentPreview('rto', 'preview-rto', 'img-preview-rto');
        setupDocumentPreview('antecedentes', 'preview-antecedentes', 'img-preview-antecedentes');
    });
</script>
@endpush
@endsection