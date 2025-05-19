@extends('layouts.app_admin')

@section('title', 'Editar Usuario')

@section('content')
<div class="container_profile">
    <h1 class="title_profile">Editar Usuario</h1>

    <form method="POST" action="{{ route('admin.users.update', $user) }}" enctype="multipart/form-data" class="form_profile">
        @csrf
        @method('PUT')

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

        {{-- DNI (opcional) --}}
        <div class="form-group_profile">
            <label for="dni">DNI</label>
            <input id="dni" type="text" name="dni" value="{{ old('dni', $user->dni) }}">
        </div>

        {{-- Celular --}}
        <div class="form-group_profile">
            <label for="celular">Celular</label>
            <input id="celular" type="text" name="celular" value="{{ old('celular', $user->celular) }}" required>
        </div>

        {{-- Foto --}}
        <div class="form-group_profile">
            <label for="foto">Foto de perfil</label>
            <input type="file" id="foto" name="foto" accept="image/*">

            @if($user->foto)
                <div class="profile-preview_profile">
                    <p class="preview-label_profile">Foto actual:</p>
                    <div class="profile-img-wrapper_profile">
                        <img src="{{ asset('storage/' . $user->foto) }}" alt="Foto actual" class="profile-img_profile">
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

        {{-- Botón --}}
        <button type="submit" class="btn_profile btn-success_profile">Guardar cambios</button>
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
