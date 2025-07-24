@extends('layouts.app_admin')

@section('title', 'Gestión SEO - Metadatos')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-search me-2"></i>Gestión de Metadatos SEO
                    </h4>
                    <p class="text-muted mb-0">Configura los metadatos SEO para cada página de tu sitio web</p>
                </div>
                <div class="card-body">
                    
                    <!-- Selector de Página -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="selector_pagina" class="form-label fw-bold">
                                <i class="fas fa-file-alt me-1"></i>Seleccionar Página
                            </label>
                            <select id="selector_pagina" class="form-select form-select-lg">
                                <option value="">-- Selecciona una página --</option>
                                @foreach($paginasDisponibles as $valor => $nombre)
                                    <option value="{{ $valor }}">{{ $nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 d-flex align-items-end">
                            <div class="d-flex gap-2">
                                <button type="button" id="btn_guardar" class="btn btn-success" disabled>
                                    <i class="fas fa-save me-1"></i>Guardar Metadatos
                                </button>
                                <button type="button" id="btn_limpiar" class="btn btn-secondary" disabled>
                                    <i class="fas fa-broom me-1"></i>Limpiar
                                </button>
                                <button type="button" id="btn_eliminar" class="btn btn-danger" disabled>
                                    <i class="fas fa-trash me-1"></i>Eliminar
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Estado de la Página -->
                    <div id="estado_pagina" class="alert d-none mb-4" role="alert"></div>

                    <!-- Formulario de Metadatos -->
                    <div id="formulario_metadatos" class="d-none">
                        <form id="form_seo">
                            <input type="hidden" id="pagina_actual" name="pagina" value="">
                            
                            <div class="row">
                                <!-- Columna Izquierda -->
                                <div class="col-lg-8">
                                    
                                    <!-- Meta Title -->
                                    <div class="mb-3">
                                        <label for="meta_title" class="form-label fw-bold">
                                            <i class="fas fa-heading me-1"></i>Título SEO (Meta Title)
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" id="meta_title" name="meta_title" class="form-control" 
                                               placeholder="Título que aparece en los resultados de búsqueda" maxlength="60">
                                        <div class="form-text">
                                            <span id="title_counter" class="badge bg-secondary">0/60</span>
                                            <small class="text-muted">Recomendado: 50-60 caracteres</small>
                                        </div>
                                    </div>

                                    <!-- Meta Description -->
                                    <div class="mb-3">
                                        <label for="meta_description" class="form-label fw-bold">
                                            <i class="fas fa-align-left me-1"></i>Descripción SEO (Meta Description)
                                            <span class="text-danger">*</span>
                                        </label>
                                        <textarea id="meta_description" name="meta_description" class="form-control" 
                                                  rows="3" placeholder="Descripción que aparece en los resultados de búsqueda" maxlength="160"></textarea>
                                        <div class="form-text">
                                            <span id="description_counter" class="badge bg-secondary">0/160</span>
                                            <small class="text-muted">Recomendado: 150-160 caracteres</small>
                                        </div>
                                    </div>

                                    <!-- Meta Keywords -->
                                    <div class="mb-3">
                                        <label for="meta_keywords" class="form-label fw-bold">
                                            <i class="fas fa-tags me-1"></i>Palabras Clave (Keywords)
                                        </label>
                                        <input type="text" id="meta_keywords" name="meta_keywords" class="form-control" 
                                               placeholder="palabra1, palabra2, palabra3">
                                        <div class="form-text">
                                            <small class="text-muted">Separa las palabras clave con comas</small>
                                        </div>
                                    </div>

                                    <!-- URL Canónica -->
                                    <div class="mb-3">
                                        <label for="canonical_url" class="form-label fw-bold">
                                            <i class="fas fa-link me-1"></i>URL Canónica
                                        </label>
                                        <input type="url" id="canonical_url" name="canonical_url" class="form-control" 
                                               placeholder="https://tusitio.com/pagina">
                                        <div class="form-text">
                                            <small class="text-muted">URL principal de esta página</small>
                                        </div>
                                    </div>

                                    <!-- Meta Robots -->
                                    <div class="mb-3">
                                        <label for="meta_robots" class="form-label fw-bold">
                                            <i class="fas fa-robot me-1"></i>Meta Robots
                                        </label>
                                        <select id="meta_robots" name="meta_robots" class="form-select">
                                            <option value="index, follow">Index, Follow (Predeterminado)</option>
                                            <option value="noindex, follow">No Index, Follow</option>
                                            <option value="index, nofollow">Index, No Follow</option>
                                            <option value="noindex, nofollow">No Index, No Follow</option>
                                        </select>
                                    </div>

                                    <!-- Meta Tags Extra -->
                                    <div class="mb-3">
                                        <label for="extra_meta" class="form-label fw-bold">
                                            <i class="fas fa-code me-1"></i>Meta Tags Adicionales
                                        </label>
                                        <textarea id="extra_meta" name="extra_meta" class="form-control font-monospace" 
                                                  rows="4" placeholder='<meta property="og:title" content="Mi Título">'></textarea>
                                        <div class="form-text">
                                            <small class="text-muted">Tags adicionales como Open Graph, Twitter Cards, etc.</small>
                                        </div>
                                    </div>

                                </div>

                                <!-- Columna Derecha - Previsualización -->
                                <div class="col-lg-4">
                                    <div class="card bg-light">
                                        <div class="card-header bg-primary text-white">
                                            <h6 class="mb-0">
                                                <i class="fas fa-eye me-1"></i>Previsualización Google
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div id="google_preview">
                                                <div class="text-primary fw-bold" id="preview_title" style="font-size: 18px; line-height: 1.2;">
                                                    Título de la página
                                                </div>
                                                <div class="text-success small mb-1" id="preview_url">
                                                    https://tusitio.com/pagina
                                                </div>
                                                <div class="text-muted small" id="preview_description" style="line-height: 1.4;">
                                                    Descripción de la página que aparecerá en los resultados de búsqueda...
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Consejos SEO -->
                                    <div class="card mt-3">
                                        <div class="card-header bg-info text-white">
                                            <h6 class="mb-0">
                                                <i class="fas fa-lightbulb me-1"></i>Consejos SEO
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <ul class="list-unstyled mb-0 small">
                                                <li class="mb-2">
                                                    <i class="fas fa-check text-success me-1"></i>
                                                    <strong>Título:</strong> 50-60 caracteres
                                                </li>
                                                <li class="mb-2">
                                                    <i class="fas fa-check text-success me-1"></i>
                                                    <strong>Descripción:</strong> 150-160 caracteres
                                                </li>
                                                <li class="mb-2">
                                                    <i class="fas fa-check text-success me-1"></i>
                                                    Incluye palabras clave relevantes
                                                </li>
                                                <li class="mb-0">
                                                    <i class="fas fa-check text-success me-1"></i>
                                                    Haz que sea atractivo para clicks
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Loading Overlay -->
<div id="loading_overlay" class="d-none position-fixed top-0 start-0 w-100 h-100 bg-dark bg-opacity-50 d-flex justify-content-center align-items-center" style="z-index: 9999;">
    <div class="text-center text-white">
        <div class="spinner-border text-light mb-2" role="status"></div>
        <div>Cargando...</div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // Referencias a elementos
    const selectorPagina = document.getElementById('selector_pagina');
    const estadoPagina = document.getElementById('estado_pagina');
    const formularioMetadatos = document.getElementById('formulario_metadatos');
    const btnGuardar = document.getElementById('btn_guardar');
    const btnLimpiar = document.getElementById('btn_limpiar');
    const btnEliminar = document.getElementById('btn_eliminar');
    const loadingOverlay = document.getElementById('loading_overlay');
    
    // Campos del formulario
    const metaTitle = document.getElementById('meta_title');
    const metaDescription = document.getElementById('meta_description');
    const metaKeywords = document.getElementById('meta_keywords');
    const canonicalUrl = document.getElementById('canonical_url');
    const metaRobots = document.getElementById('meta_robots');
    const extraMeta = document.getElementById('extra_meta');
    
    // Contadores
    const titleCounter = document.getElementById('title_counter');
    const descriptionCounter = document.getElementById('description_counter');
    
    // Previsualización
    const previewTitle = document.getElementById('preview_title');
    const previewUrl = document.getElementById('preview_url');
    const previewDescription = document.getElementById('preview_description');

    // Token CSRF
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Función para mostrar loading
    function mostrarLoading() {
        loadingOverlay.classList.remove('d-none');
        loadingOverlay.classList.add('d-flex');
    }

    // Función para ocultar loading
    function ocultarLoading() {
        loadingOverlay.classList.remove('d-flex');
        loadingOverlay.classList.add('d-none');
    }

    // Función para mostrar notificaciones (simple alert por ahora)
    function mostrarNotificacion(mensaje, tipo = 'success') {
        if (tipo === 'success') {
            alert('✓ ' + mensaje);
        } else {
            alert('✗ ' + mensaje);
        }
    }

    // Evento: Cambio de página
    selectorPagina.addEventListener('change', function() {
        const paginaSeleccionada = this.value;
        
        if (paginaSeleccionada) {
            cargarMetadatosPagina(paginaSeleccionada);
        } else {
            ocultarFormulario();
        }
    });

    // Función para cargar metadatos de una página
    function cargarMetadatosPagina(pagina) {
        mostrarLoading();
        
        fetch("{{ route('configuracion.seo.obtener') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ pagina: pagina })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                mostrarFormulario(data.data, data.existe);
            }
        })
        .catch(error => {
            mostrarNotificacion('Error al cargar los metadatos', 'error');
            console.error(error);
        })
        .finally(() => {
            ocultarLoading();
        });
    }

    // Función para mostrar formulario
    function mostrarFormulario(data, existe) {
        // Mostrar estado
        if (existe) {
            estadoPagina.className = 'alert alert-success mb-4';
            estadoPagina.innerHTML = '<i class="fas fa-check-circle me-1"></i>Esta página ya tiene metadatos configurados. Puedes editarlos.';
            btnEliminar.disabled = false;
        } else {
            estadoPagina.className = 'alert alert-info mb-4';
            estadoPagina.innerHTML = '<i class="fas fa-info-circle me-1"></i>Esta página no tiene metadatos configurados. Crea nuevos metadatos.';
            btnEliminar.disabled = true;
        }

        // Llenar formulario
        document.getElementById('pagina_actual').value = data.pagina;
        metaTitle.value = data.meta_title || '';
        metaDescription.value = data.meta_description || '';
        metaKeywords.value = data.meta_keywords || '';
        canonicalUrl.value = data.canonical_url || '';
        metaRobots.value = data.meta_robots || 'index, follow';
        extraMeta.value = data.extra_meta || '';

        // Actualizar contadores
        actualizarContadores();
        
        // Actualizar previsualización
        actualizarPrevisualizacion();

        // Mostrar formulario y habilitar botones
        formularioMetadatos.classList.remove('d-none');
        btnGuardar.disabled = false;
        btnLimpiar.disabled = false;
    }

    // Función para ocultar formulario
    function ocultarFormulario() {
        formularioMetadatos.classList.add('d-none');
        estadoPagina.classList.add('d-none');
        btnGuardar.disabled = true;
        btnLimpiar.disabled = true;
        btnEliminar.disabled = true;
    }

    // Eventos: Actualizar contadores en tiempo real
    metaTitle.addEventListener('input', function() {
        actualizarContadores();
        actualizarPrevisualizacion();
    });

    metaDescription.addEventListener('input', function() {
        actualizarContadores();
        actualizarPrevisualizacion();
    });

    canonicalUrl.addEventListener('input', actualizarPrevisualizacion);

    // Función para actualizar contadores
    function actualizarContadores() {
        const titleLength = metaTitle.value.length;
        const descLength = metaDescription.value.length;
        
        // Contador título
        titleCounter.textContent = `${titleLength}/60`;
        titleCounter.className = 'badge';
        if (titleLength > 60) {
            titleCounter.classList.add('bg-danger');
        } else if (titleLength >= 50) {
            titleCounter.classList.add('bg-success');
        } else {
            titleCounter.classList.add('bg-secondary');
        }
        
        // Contador descripción
        descriptionCounter.textContent = `${descLength}/160`;
        descriptionCounter.className = 'badge';
        if (descLength > 160) {
            descriptionCounter.classList.add('bg-danger');
        } else if (descLength >= 150) {
            descriptionCounter.classList.add('bg-success');
        } else {
            descriptionCounter.classList.add('bg-secondary');
        }
    }

    // Función para actualizar previsualización
    function actualizarPrevisualizacion() {
        const title = metaTitle.value || 'Título de la página';
        const description = metaDescription.value || 'Descripción de la página que aparecerá en los resultados de búsqueda...';
        const url = canonicalUrl.value || 'https://tusitio.com/pagina';
        
        previewTitle.textContent = title;
        previewDescription.textContent = description;
        previewUrl.textContent = url;
    }

    // Evento: Guardar metadatos
    btnGuardar.addEventListener('click', function() {
        const formData = {
            pagina: document.getElementById('pagina_actual').value,
            meta_title: metaTitle.value,
            meta_description: metaDescription.value,
            meta_keywords: metaKeywords.value,
            canonical_url: canonicalUrl.value,
            meta_robots: metaRobots.value,
            extra_meta: extraMeta.value
        };

        mostrarLoading();

        fetch("{{ route('configuracion.seo.guardar') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify(formData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                mostrarNotificacion(data.message, 'success');
                // Actualizar estado a "existe"
                estadoPagina.className = 'alert alert-success mb-4';
                estadoPagina.innerHTML = '<i class="fas fa-check-circle me-1"></i>Esta página ya tiene metadatos configurados. Puedes editarlos.';
                btnEliminar.disabled = false;
            } else {
                mostrarNotificacion(data.message || 'Error al guardar', 'error');
            }
        })
        .catch(error => {
            mostrarNotificacion('Error al guardar los metadatos', 'error');
            console.error(error);
        })
        .finally(() => {
            ocultarLoading();
        });
    });

    // Evento: Limpiar formulario
    btnLimpiar.addEventListener('click', function() {
        if (confirm('¿Estás seguro de que quieres limpiar todos los campos?')) {
            document.getElementById('form_seo').reset();
            metaRobots.value = 'index, follow';
            actualizarContadores();
            actualizarPrevisualizacion();
        }
    });

    // Evento: Eliminar metadatos
    btnEliminar.addEventListener('click', function() {
        const pagina = document.getElementById('pagina_actual').value;
        
        if (confirm(`¿Estás seguro de que quieres eliminar los metadatos de la página "${pagina}"?`)) {
            mostrarLoading();

            fetch("{{ route('configuracion.seo.eliminar') }}", {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ pagina: pagina })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    mostrarNotificacion(data.message, 'success');
                    // Recargar página para mostrar como "nueva"
                    cargarMetadatosPagina(pagina);
                } else {
                    mostrarNotificacion(data.message || 'Error al eliminar', 'error');
                }
            })
            .catch(error => {
                mostrarNotificacion('Error al eliminar los metadatos', 'error');
                console.error(error);
            })
            .finally(() => {
                ocultarLoading();
            });
        }
    });

});
</script>
@endpush

@push('styles')
<style>
#google_preview {
    border: 1px solid #dadce0;
    border-radius: 8px;
    padding: 12px;
    background: white;
    font-family: arial, sans-serif;
}

.form-control:focus, .form-select:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
}

.badge {
    font-size: 0.75em;
}

.card-header {
    border-bottom: 1px solid rgba(0,0,0,.125);
}

.font-monospace {
    font-family: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
    font-size: 0.875em;
}
</style>
@endpush