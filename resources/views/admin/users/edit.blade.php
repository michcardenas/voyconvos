@extends('layouts.app_admin')

@section('title', 'Editar Usuario')

@section('content')

<div class="container_profile">
    <h1 class="title_profile">Editar Usuario</h1>

    <form method="POST" action="{{ route('admin.users.update', $user) }}" enctype="multipart/form-data" class="form_profile">
        @csrf
        @method('PUT')

        {{-- INFORMACIÓN PERSONAL --}}
        <h3 style="margin-top: 20px; margin-bottom: 15px; color: #333; border-bottom: 2px solid #007bff; padding-bottom: 5px;">👤 Información Personal</h3>

        {{-- Nombre --}}
        <div class="form-group_profile">
            <label for="name">Nombre</label>
            <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}" required>
        </div>

        {{-- Email --}}
        <div class="form-group_profile">
            <label for="email">Correo</label>
            <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required>
        </div>

        {{-- Rol --}}
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

        {{-- País --}}
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

        {{-- Ciudad --}}
        <div class="form-group_profile">
            <label for="ciudad">Ciudad</label>
            <input id="ciudad" type="text" name="ciudad" value="{{ old('ciudad', $user->ciudad) }}" required>
        </div>

        {{-- DNI --}}
        <div class="form-group_profile">
            <label for="dni">DNI</label>
            <input id="dni" type="text" name="dni" value="{{ old('dni', $user->dni) }}">
        </div>

        {{-- Celular --}}
        <div class="form-group_profile">
            <label for="celular">Celular</label>
            <input id="celular" type="text" name="celular" value="{{ old('celular', $user->celular) }}" required>
        </div>

        {{-- Verificado --}}
        <div class="form-group_profile">
            <label for="verificado">¿Verificado?</label>
            <select name="verificado" id="verificado" required>
                <option value="1" {{ old('verificado', $user->verificado) ? 'selected' : '' }}>Sí</option>
                <option value="0" {{ old('verificado', $user->verificado) == 0 ? 'selected' : '' }}>No</option>
            </select>
        </div>

        {{-- DOCUMENTOS DEL USUARIO --}}
        <h3 style="margin-top: 30px; margin-bottom: 15px; color: #333; border-bottom: 2px solid #28a745; padding-bottom: 5px;">📄 Documentos del Usuario</h3>

        {{-- Foto de perfil --}}
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

        {{-- DNI Frente --}}
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

        {{-- DNI Atrás --}}
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

        {{-- INFORMACIÓN DE CONDUCTOR (solo si es conductor) --}}
        @if($user->hasRole('conductor') && $registroConductor)
        <h3 style="margin-top: 40px; margin-bottom: 15px; color: #333; border-bottom: 2px solid #ffc107; padding-bottom: 5px;">🚗 Información del Vehículo</h3>

        <div class="form-group_profile">
            <label>Marca del Vehículo</label>
            <input type="text" value="{{ $registroConductor->marca_vehiculo }}" readonly style="background-color: #f8f9fa; border: 1px solid #dee2e6;">
        </div>

        <div class="form-group_profile">
            <label>Modelo del Vehículo</label>
            <input type="text" value="{{ $registroConductor->modelo_vehiculo }}" readonly style="background-color: #f8f9fa; border: 1px solid #dee2e6;">
        </div>

        <div class="form-group_profile">
            <label>Año del Vehículo</label>
            <input type="text" value="{{ $registroConductor->anio_vehiculo }}" readonly style="background-color: #f8f9fa; border: 1px solid #dee2e6;">
        </div>

        <div class="form-group_profile">
            <label>Patente</label>
            <input type="text" value="{{ $registroConductor->patente }}" readonly style="background-color: #f8f9fa; border: 1px solid #dee2e6;">
        </div>

        <div class="form-group_profile">
            <label>Número de Puestos</label>
            <input type="text" value="{{ $registroConductor->numero_puestos }}" readonly style="background-color: #f8f9fa; border: 1px solid #dee2e6;">
        </div>

        <div class="form-group_profile">
            <label>Estado de Verificación</label>
            <input type="text" value="{{ ucfirst($registroConductor->estado_verificacion) }}" readonly style="background-color: #f8f9fa; border: 1px solid #dee2e6;">
        </div>

        <div class="form-group_profile">
            <label>Estado de Registro</label>
            <input type="text" value="{{ ucfirst($registroConductor->estado_registro) }}" readonly style="background-color: #f8f9fa; border: 1px solid #dee2e6;">
        </div>

        {{-- DOCUMENTOS DEL CONDUCTOR --}}
        <h3 style="margin-top: 30px; margin-bottom: 15px; color: #333; border-bottom: 2px solid #dc3545; padding-bottom: 5px;">📋 Documentos del Conductor</h3>

        {{-- Licencia --}}
        @if($registroConductor->licencia)
        <div class="form-group_profile">
            <label>Licencia de Conducir</label>
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
        </div>
        @endif

        {{-- Cédula --}}
        @if($registroConductor->cedula)
        <div class="form-group_profile">
            <label>Cédula</label>
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
        </div>
        @endif

        {{-- Cédula Verde --}}
        @if($registroConductor->cedula_verde)
        <div class="form-group_profile">
            <label>Cédula Verde</label>
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
        </div>
        @endif

        {{-- Seguro --}}
        @if($registroConductor->seguro)
        <div class="form-group_profile">
            <label>Seguro</label>
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
        </div>
        @endif

        {{-- RTO --}}
        @if($registroConductor->rto)
        <div class="form-group_profile">
            <label>RTO (Revisión Técnica Obligatoria)</label>
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
        </div>
        @endif

        {{-- Antecedentes --}}
        @if($registroConductor->antecedentes)
        <div class="form-group_profile">
            <label>Antecedentes Penales</label>
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
        </div>
        @endif

        @endif

        {{-- Botón --}}
        <button type="submit" class="btn_profile btn-success_profile" style="margin-top: 30px;">Guardar cambios</button>
    </form>
</div>

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const input = document.getElementById('foto');
        const previewContainer = document.getElementById('preview-nueva-foto');
        const previewImage = document.getElementById('img-preview');

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
    });
</script>
@endpush

@endsection