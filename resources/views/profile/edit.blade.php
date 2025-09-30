@extends('layouts.app_admin')

@section('title', 'Editar Usuario')

@section('content')
<div class="container_profile">
    <h1 class="title_profile">Editar Usuario</h1>

<form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="form_profile" id="editarUsuarioForm">
    @csrf
    @method('PATCH')

    <div class="form-group_profile">
        <label for="name">Nombre</label>
        <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}" required>
    </div>

    <div class="form-group_profile">
        <label for="email">Correo</label>
        <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required>
    </div>

    <div class="form-group_profile">
        <label for="fecha_nacimiento">Fecha de Nacimiento</label>
        <input id="fecha_nacimiento" type="date" name="fecha_nacimiento"
               value="{{ old('fecha_nacimiento', $user->fecha_nacimiento ? $user->fecha_nacimiento->format('Y-m-d') : '') }}">
    </div>

    <select name="role" class="form-control" required style="margin-bottom: 23px;">
        <option value="pasajero" {{ $user->hasRole('pasajero') ? 'selected' : '' }}>Pasajero</option>
        <option value="conductor" {{ $user->hasRole('conductor') ? 'selected' : '' }}>Conductor</option>
    </select>

    <div class="form-group_profile">
        <label for="pais">Nacionalidad</label>
        <select name="pais" id="pais">
            <option value="">Selecciona tu país</option>
            @php
                $paises = [
                    'Argentina', 'Bolivia', 'Brasil', 'Chile', 'Colombia', 'Costa Rica', 'Cuba',
                    'Ecuador', 'El Salvador', 'Guatemala', 'Honduras', 'México', 'Nicaragua',
                    'Panamá', 'Paraguay', 'Perú', 'República Dominicana', 'Uruguay', 'Venezuela'
                ];
            @endphp

            @foreach($paises as $pais)
                <option value="{{ $pais }}" {{ old('pais', $user->pais ?? '') == $pais ? 'selected' : '' }}>
                    {{ $pais }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group_profile">
        <label for="ciudad">Ciudad</label>
        <input type="text" id="ciudad" name="ciudad" value="{{ old('ciudad', $user->ciudad) }}">
    </div>

    <div class="form-group_profile">
        <label for="celular">Celular</label>
        <input type="text" id="celular" name="celular" value="{{ old('celular', $user->celular) }}">
    </div>

    <!-- Foto -->
    <div class="form-group_profile">
        <label for="foto">Foto de perfil</label>
        <input type="file" id="foto" name="foto" accept="image/*">

        {{-- Imagen actual si existe --}}
        @if($user->foto)
            <div class="profile-preview_profile">
                <p class="preview-label_profile">Foto actual:</p>
                <div class="profile-img-wrapper_profile">
                    <img src="{{ asset('storage/' . $user->foto) }}" alt="Foto actual" class="profile-img_profile">
                </div>
            </div>
        @endif

        {{-- Vista previa dinámica al seleccionar nueva imagen --}}
        <div id="preview-nueva-foto" class="profile-preview_profile" style="display: none;">
            <p class="preview-label_profile">Vista previa:</p>
            <div class="profile-img-wrapper_profile">
                <img id="img-preview" src="" alt="Vista previa" class="profile-img_profile">
            </div>
        </div>
    </div>

    <div class="form-actions_profile">
        <button type="submit" class="btn_profile btn-success_profile">Guardar cambios</button>

        <a href="{{ route('dashboard') }}" class="btn_profile btn-skip_profile">Quiero omitir este paso por ahora</a>
    </div>
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

