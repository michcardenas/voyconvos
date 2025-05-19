@extends('layouts.app_admin')

@section('title', 'Crear Usuario')

@section('content')
<div class="container_profile">
    <h1 class="title_profile">Nuevo Usuario</h1>

    <form method="POST" action="{{ route('admin.users.store') }}" enctype="multipart/form-data" class="form_profile">
        @csrf

        <div class="form-group_profile">
            <label for="name">Nombre</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required>
        </div>

        <div class="form-group_profile">
            <label for="email">Correo</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required>
        </div>
        <div class="form-group_profile">
    <label for="password">Contraseña</label>
    <input id="password" type="password" name="password" class="input_profile" required>
</div>

<div class="form-group_profile">
    <label for="password_confirmation">Confirmar Contraseña</label>
    <input id="password_confirmation" type="password" name="password_confirmation" class="input_profile" required>
</div>

        <div class="form-group_profile">
            <label for="role">Rol</label>
            <select name="role" id="role" required>
                @foreach($roles as $role)
                    <option value="{{ $role->name }}" {{ old('role') == $role->name ? 'selected' : '' }}>
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
                    <option value="{{ $pais }}" {{ old('pais', 'Argentina') == $pais ? 'selected' : '' }}>
                        {{ $pais }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group_profile">
            <label for="ciudad">Ciudad</label>
            <input type="text" id="ciudad" name="ciudad" value="{{ old('ciudad') }}" required>
        </div>

        <div class="form-group_profile">
            <label for="dni">DNI</label>
            <input type="text" id="dni" name="dni" value="{{ old('dni') }}">
        </div>

        <div class="form-group_profile">
            <label for="celular">Celular</label>
            <input type="text" id="celular" name="celular" value="{{ old('celular') }}" required>
        </div>

        <div class="form-group_profile">
            <label for="foto">Foto de perfil</label>
            <input type="file" id="foto" name="foto" accept="image/*">
            <div id="preview-nueva-foto" class="profile-preview_profile" style="display: none;">
                <p class="preview-label_profile">Vista previa:</p>
                <div class="profile-img-wrapper_profile">
                    <img id="img-preview" src="" alt="Vista previa" class="profile-img_profile">
                </div>
            </div>
        </div>

        <button type="submit" class="btn_profile btn-success_profile">Crear usuario</button>
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
