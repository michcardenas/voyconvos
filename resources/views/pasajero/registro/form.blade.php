{{-- resources/views/pasajero/registro/form.blade.php --}}

@extends('layouts.app')
<style>
    .container {
        background-color: var(--color-fondo-base);
        padding: 20px;
    }
    
    .card {
        
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        border: 1px solid #e0e0e0;
    }
    
    .card-header {
        background-color: var(--color-principal);
        color: white;
        padding: 20px;
        border-radius: 10px 10px 0 0;
        text-align: center;
    }
    
    .card-body {
        padding: 30px;
    }
    
    .form-label {
        color: var(--color-neutro-oscuro);
        font-weight: 600;
        margin-bottom: 8px;
    }
    
    .form-control {
        width: 100%;
        padding: 12px;
        border: 2px solid #ddd;
        border-radius: 6px;
        font-size: 16px;
        margin-bottom: 10px;
    }
    
    .form-control:focus {
        border-color: var(--color-principal);
        outline: none;
    }
    
    .btn {
        padding: 12px 24px;
        border-radius: 6px;
        font-weight: 600;
        text-decoration: none;
        display: inline-block;
        margin: 5px;
    }
    
    .btn-primary {
        background-color: var(--color-principal);
        color: white;
        border: none;
    }
    
    .btn-secondary {
        background-color: #6c757d;
        color: white;
        border: none;
    }
    
    .alert {
        padding: 15px;
        border-radius: 6px;
        margin-bottom: 20px;
    }
    
    .alert-danger {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
    
    .alert-info {
        background-color: var(--color-azul-claro);
        color: var(--color-principal);
        border: 1px solid var(--color-principal);
    }
    
    .form-group {
        margin-bottom: 20px;
    }
    
    .d-flex {
        display: flex;
        gap: 15px;
    }
    
    .justify-content-between {
        justify-content: space-between;
    }
    
    img {
        max-width: 200px;
        border: 1px solid #ddd;
        border-radius: 4px;
        margin-top: 10px;
    }
</style>
@section('content')
<div class="container" style="margin-top: 105px;" >
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>Completar Registro de Pasajero</h4>
                    <p class="mb-0">Para verificar tu cuenta necesitamos que completes la información de tu DNI</p>
                </div>

                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('pasajero.registro.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group mb-3">
                            <label for="dni" class="form-label">Número de DNI *</label>
                            <input type="text" 
                                   id="dni" 
                                   name="dni" 
                                   class="form-control @error('dni') is-invalid @enderror" 
                                   value="{{ old('dni', $user->dni) }}" 
                                   required
                                   placeholder="Ej: 12345678">
                            @error('dni')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="dni_foto" class="form-label">Foto del frente del DNI *</label>
                            <input type="file" 
                                   id="dni_foto" 
                                   name="dni_foto" 
                                   class="form-control @error('dni_foto') is-invalid @enderror" 
                                   accept="image/*" 
                                   required>
                            @error('dni_foto')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Asegúrate de que se vean claramente todos los datos</small>

                            {{-- Vista previa frente --}}
                            <div id="preview-dni-frente" class="mt-2" style="display: none;">
                                <p class="text-muted">Vista previa:</p>
                                <img id="img-preview-frente" src="" alt="Vista previa frente" style="max-width: 300px; border: 1px solid #ddd; border-radius: 5px;">
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="dni_foto_atras" class="form-label">Foto del reverso del DNI *</label>
                            <input type="file" 
                                   id="dni_foto_atras" 
                                   name="dni_foto_atras" 
                                   class="form-control @error('dni_foto_atras') is-invalid @enderror" 
                                   accept="image/*" 
                                   required>
                            @error('dni_foto_atras')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Foto del reverso de tu documento</small>

                            {{-- Vista previa reverso --}}
                            <div id="preview-dni-atras" class="mt-2" style="display: none;">
                                <p class="text-muted">Vista previa:</p>
                                <img id="img-preview-atras" src="" alt="Vista previa reverso" style="max-width: 300px; border: 1px solid #ddd; border-radius: 5px;">
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <strong>📋 Información importante:</strong>
                            <ul class="mb-0 mt-2">
                                <li>Las fotos deben ser claras y legibles</li>
                                <li>Asegúrate de que no haya reflejos o sombras</li>
                                <li>Tu cuenta será verificada en un plazo de 24-48 horas</li>
                            </ul>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('pasajero.dashboard') }}" class="btn btn-secondary">
                                Completar más tarde
                            </a>
                            <button type="submit" class="btn btn-primary">
                                Enviar documentos
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Vista previa foto frente
    document.getElementById('dni_foto').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const preview = document.getElementById('preview-dni-frente');
        const imgPreview = document.getElementById('img-preview-frente');
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imgPreview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            preview.style.display = 'none';
        }
    });

    // Vista previa foto reverso
    document.getElementById('dni_foto_atras').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const preview = document.getElementById('preview-dni-atras');
        const imgPreview = document.getElementById('img-preview-atras');
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imgPreview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            preview.style.display = 'none';
        }
    });
});
</script>
@endsection