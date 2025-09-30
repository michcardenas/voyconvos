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
        <label for="fecha_nacimiento">
            <svg class="label-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M8 2V6M16 2V6M3 10H21M5 4H19C20.1046 4 21 4.89543 21 6V20C21 21.1046 20.1046 22 19 22H5C3.89543 22 3 21.1046 3 20V6C3 4.89543 3.89543 4 5 4Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Fecha de Nacimiento
        </label>
        <div class="date-input-wrapper">
            <input id="fecha_nacimiento" type="date" name="fecha_nacimiento" class="date-input-enhanced"
                   value="{{ old('fecha_nacimiento', $user->fecha_nacimiento ? $user->fecha_nacimiento->format('Y-m-d') : '') }}"
                   placeholder="Selecciona tu fecha de nacimiento">
            <div class="date-helper-text">
            </div>
        </div>
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
<style>
/* Estilos mejorados para el campo de fecha de nacimiento */
.form-group_profile label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 600;
    color: var(--neutro, #3A3A3A);
    margin-bottom: 0.75rem;
    font-size: 0.95rem;
}

.label-icon {
    color: var(--principal, #1F4E79);
    flex-shrink: 0;
}

.date-input-wrapper {
    position: relative;
}

.date-input-enhanced {
    width: 100%;
    padding: 14px 16px;
    border: 2px solid var(--borde, #E1E5E9);
    border-radius: 12px;
    font-size: 1rem;
    background: var(--blanco, #FFFFFF);
    color: var(--neutro, #3A3A3A);
    transition: all 0.3s ease;
    font-family: inherit;
    box-sizing: border-box;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%231F4E79' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Crect x='3' y='4' width='18' height='18' rx='2' ry='2'%3E%3C/rect%3E%3Cline x1='16' y1='2' x2='16' y2='6'%3E%3C/line%3E%3Cline x1='8' y1='2' x2='8' y2='6'%3E%3C/line%3E%3Cline x1='3' y1='10' x2='21' y2='10'%3E%3C/line%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 16px center;
    background-size: 18px;
}

.date-input-enhanced:focus {
    outline: none;
    border-color: var(--principal, #1F4E79);
    box-shadow: 0 0 0 4px rgba(31, 78, 121, 0.1);
    transform: translateY(-1px);
}

.date-input-enhanced:hover {
    border-color: var(--principal, #1F4E79);
    background-color: var(--azul-claro, #DDF2FE);
}

.date-helper-text {
    margin-top: 0.5rem;
}

.date-helper-text small {
    color: var(--texto-gris, #64748B);
    font-size: 0.8rem;
    font-style: italic;
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.date-helper-text small::before {
    content: "💡";
    font-size: 0.9rem;
}

/* Estilos específicos para navegadores */
.date-input-enhanced::-webkit-calendar-picker-indicator {
    opacity: 0;
    position: absolute;
    right: 16px;
    width: 18px;
    height: 18px;
    cursor: pointer;
}

/* Responsive */
@media (max-width: 768px) {
    .date-input-enhanced {
        padding: 12px 14px;
        font-size: 16px; /* Evita el zoom en iOS */
    }
}
</style>

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

        // Funcionalidad mejorada para el campo de fecha
        const dateInput = document.getElementById('fecha_nacimiento');
        if (dateInput) {
            // Establecer fecha máxima (hoy)
            const today = new Date().toISOString().split('T')[0];
            dateInput.setAttribute('max', today);

            // Establecer fecha mínima (hace 100 años)
            const minDate = new Date();
            minDate.setFullYear(minDate.getFullYear() - 100);
            dateInput.setAttribute('min', minDate.toISOString().split('T')[0]);

            // Efecto visual al seleccionar fecha
            dateInput.addEventListener('change', function() {
                if (this.value) {
                    this.style.borderColor = 'var(--verde, #4CAF50)';
                    this.style.boxShadow = '0 0 0 3px rgba(76, 175, 80, 0.1)';

                    setTimeout(() => {
                        this.style.borderColor = 'var(--principal, #1F4E79)';
                        this.style.boxShadow = '0 0 0 3px rgba(31, 78, 121, 0.1)';
                    }, 1000);
                }
            });
        }
    });
</script>
@endpush
@endsection

