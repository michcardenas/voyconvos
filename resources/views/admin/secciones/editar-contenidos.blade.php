@extends('layouts.app_dashboard')

@section('title', 'Editar Contenidos')

@php use Illuminate\Support\Str; @endphp

@section('content')
<div class="container py-5" style="max-width: 800px;">
    <h2 class="fw-bold mb-4 text-primary">
        ✏️ Editar contenidos de: <span class="text-dark">{{ $seccion->slug }}</span>
    </h2>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <style>
        .image-preview {
            max-width: 200px;
            max-height: 150px;
            object-fit: cover;
            border-radius: 8px;
            border: 2px solid #e9ecef;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .image-preview:hover {
            border-color: #0d6efd;
            transform: scale(1.05);
        }
        
        .image-container {
            position: relative;
            display: inline-block;
        }
        
        .image-badge {
            position: absolute;
            top: 5px;
            right: 5px;
            background: rgba(0,0,0,0.7);
            color: white;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 11px;
        }
        
        /* Modal para imagen completa */
        .modal-image {
            max-width: 100%;
            max-height: 80vh;
            object-fit: contain;
        }
    </style>

    <form action="{{ route('admin.secciones.actualizar-contenidos', $seccion->slug) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        @foreach($seccion->contenidos as $contenido)
            <div class="mb-4">
                <label class="form-label fw-semibold">
                    {{ ucfirst(str_replace('_', ' ', $contenido->clave)) }}
                </label>

                @if(Str::contains($contenido->clave, ['background', 'imagen', 'img']))
                    @if($contenido->valor)
                        <div class="mb-3">
                            <div class="image-container">
                                <img src="{{ asset($contenido->valor) }}" 
                                     alt="Vista previa" 
                                     class="image-preview"
                                     data-bs-toggle="modal" 
                                     data-bs-target="#imageModal{{ $contenido->id }}">
                                <span class="image-badge">
                                    <i class="fas fa-search-plus"></i>
                                </span>
                            </div>
                            <small class="text-muted d-block mt-1">
                                Click en la imagen para ver en tamaño completo
                            </small>
                        </div>

                        <!-- Modal para mostrar imagen completa -->
                        <div class="modal fade" id="imageModal{{ $contenido->id }}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">{{ ucfirst(str_replace('_', ' ', $contenido->clave)) }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body text-center">
                                        <img src="{{ asset($contenido->valor) }}" 
                                             alt="Imagen completa" 
                                             class="modal-image">
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    <div class="input-group">
                        <input type="file" 
                               name="valor_{{ $contenido->id }}" 
                               class="form-control" 
                               accept="image/*"
                               id="file{{ $contenido->id }}">
                        <label class="input-group-text" for="file{{ $contenido->id }}">
                            <i class="fas fa-upload"></i>
                        </label>
                    </div>
                    <small class="text-muted">Formatos: JPG, PNG, GIF | Máx: 2MB</small>

                @elseif(Str::contains($contenido->clave, ['texto', 'descripcion', 'slogan']))
                    <textarea name="valor_{{ $contenido->id }}" 
                              class="form-control" 
                              rows="4" 
                              placeholder="Ingresa el {{ strtolower(str_replace('_', ' ', $contenido->clave)) }}...">{{ $contenido->valor }}</textarea>
                @else
                    <input type="text" 
                           name="valor_{{ $contenido->id }}" 
                           class="form-control" 
                           value="{{ $contenido->valor }}"
                           placeholder="Ingresa {{ strtolower(str_replace('_', ' ', $contenido->clave)) }}...">
                @endif
            </div>
        @endforeach

        <div class="d-flex justify-content-between align-items-center mt-5 pt-3 border-top">
            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
            <button type="submit" class="btn btn-success">
                <i class="fas fa-save"></i> Guardar cambios
            </button>
        </div>
    </form>
</div>

<script>
// Preview de imágenes al seleccionar archivo
document.addEventListener('DOMContentLoaded', function() {
    const fileInputs = document.querySelectorAll('input[type="file"]');
    
    fileInputs.forEach(input => {
        input.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    // Buscar si ya existe una imagen preview
                    const container = input.closest('.mb-4');
                    let preview = container.querySelector('.image-preview');
                    
                    if (preview) {
                        preview.src = e.target.result;
                    } else {
                        // Crear nueva preview si no existe
                        const imageContainer = document.createElement('div');
                        imageContainer.className = 'mb-3';
                        imageContainer.innerHTML = `
                            <div class="image-container">
                                <img src="${e.target.result}" alt="Nueva imagen" class="image-preview">
                                <span class="image-badge">Nueva</span>
                            </div>
                            <small class="text-muted d-block mt-1">Nueva imagen seleccionada</small>
                        `;
                        input.parentNode.insertBefore(imageContainer, input.parentNode);
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    });
});
</script>
@endsection