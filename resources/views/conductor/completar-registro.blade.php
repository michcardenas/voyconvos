@extends('layouts.app_dashboard')

@section('title', 'Registro del Vehículo')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">
                        <i class="fas fa-car me-2"></i>
                        Completar Registro como Conductor
                    </h3>
                </div>
                
                <div class="card-body p-4">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Por favor corrige los siguientes errores:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('info'))
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <i class="fas fa-info-circle me-2"></i>
                            {{ session('info') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('conductor.registro.store') }}" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Información del Vehículo -->
                        <div class="mb-4">
                            <h5 class="text-primary border-bottom pb-2 mb-3">
                                <i class="fas fa-car me-2"></i>
                                Información del Vehículo
                            </h5>
                            
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="marca_vehiculo" class="form-label fw-semibold">
                                        <i class="fas fa-tag me-1"></i>
                                        Marca del Vehículo
                                    </label>
                                    <input type="text" 
                                           name="marca_vehiculo" 
                                           id="marca_vehiculo"
                                           class="form-control" 
                                           placeholder="Ej: Toyota, Honda, Chevrolet..."
                                           value="{{ old('marca_vehiculo') }}"
                                           required>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="modelo_vehiculo" class="form-label fw-semibold">
                                        <i class="fas fa-car-side me-1"></i>
                                        Modelo
                                    </label>
                                    <input type="text" 
                                           name="modelo_vehiculo" 
                                           id="modelo_vehiculo"
                                           class="form-control" 
                                           placeholder="Ej: Corolla, Civic, Aveo..."
                                           value="{{ old('modelo_vehiculo') }}"
                                           required>
                                </div>
                                
                                <div class="col-md-4">
                                    <label for="anio_vehiculo" class="form-label fw-semibold">
                                        <i class="fas fa-calendar-alt me-1"></i>
                                        Año del Vehículo
                                    </label>
                                    <input type="number" 
                                           name="anio_vehiculo" 
                                           id="anio_vehiculo"
                                           class="form-control" 
                                           min="2012" 
                                           max="{{ date('Y') }}" 
                                           placeholder="{{ date('Y') }}"
                                           value="{{ old('anio_vehiculo') }}"
                                           required>
                                </div>
                                
                                <div class="col-md-4">
                                    <label for="numero_puestos" class="form-label fw-semibold">
                                        <i class="fas fa-users me-1"></i>
                                        Número de Puestos
                                    </label>
                                    <input type="number" 
                                           name="numero_puestos" 
                                           id="numero_puestos"
                                           class="form-control" 
                                           min="2" 
                                           max="50" 
                                           placeholder="Ej: 4"
                                           value="{{ old('numero_puestos') }}"
                                           required>
                                    <div class="form-text">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Incluido el conductor
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <label for="consumo_por_galon" class="form-label fw-semibold">
                                        <i class="fas fa-gas-pump me-1"></i>
                                        Consumo (km/galón)
                                    </label>
                                    <input type="number" 
                                           name="consumo_por_galon" 
                                           id="consumo_por_galon"
                                           class="form-control" 
                                           min="1" 
                                           placeholder="Ej: 15"
                                           value="{{ old('consumo_por_galon') }}"
                                           required>
                                    <div class="form-text">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Kilómetros por galón
                                    </div>
                                </div>
                                
                            </div>
                            
                            <div class="row g-3 justify-content-center">
                                <div class="col-md-6">
                                    <label for="patente" class="form-label fw-semibold">
                                        <i class="fas fa-id-card me-1"></i>
                                        Patente (Dominio)
                                    </label>
                                    <input type="text" 
                                           name="patente" 
                                           id="patente"
                                           class="form-control text-uppercase text-center" 
                                           placeholder="AB 123 CD"
                                           value="{{ old('patente') }}"
                                           style="letter-spacing: 3px; font-weight: bold; font-size: 1.1rem;"
                                           required>
                                    <div class="form-text text-center">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Formato: letras + números + letras
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Documentación Requerida -->
                        <div class="mb-4">
                            <h5 class="text-primary border-bottom pb-2 mb-3">
                                <i class="fas fa-file-alt me-2"></i>
                                Documentación Requerida
                            </h5>
                            
                            <div class="row g-3">
                                <div class="col-lg-4">
                                    <label for="licencia" class="form-label fw-semibold">
                                        <i class="fas fa-id-badge me-1"></i>
                                        Licencia de Conducir
                                    </label>
                                    <input type="file" 
                                           name="licencia" 
                                           id="licencia"
                                           class="form-control" 
                                           accept="application/pdf,image/*" 
                                           required>
                                    <div class="form-text">
                                        <i class="fas fa-file-pdf me-1"></i>
                                        PDF o imagen (máx. 5MB)
                                    </div>
                                </div>
                                
                                <div class="col-lg-4">
                                    <label for="cedula" class="form-label fw-semibold">
                                        <i class="fas fa-address-card me-1"></i>
                                        DNI / Cédula
                                    </label>
                                    <input type="file" 
                                           name="cedula" 
                                           id="cedula"
                                           class="form-control" 
                                           accept="application/pdf,image/*" 
                                           required>
                                    <div class="form-text">
                                        <i class="fas fa-file-pdf me-1"></i>
                                        PDF o imagen (máx. 5MB)
                                    </div>
                                </div>
                                
                                <div class="col-lg-4">
                                    <label for="cedula_verde" class="form-label fw-semibold">
                                        <i class="fas fa-certificate me-1"></i>
                                        Cédula Verde
                                    </label>
                                    <input type="file" 
                                           name="cedula_verde" 
                                           id="cedula_verde"
                                           class="form-control" 
                                           accept="application/pdf,image/*" 
                                           required>
                                    <div class="form-text">
                                        <i class="fas fa-file-pdf me-1"></i>
                                        PDF o imagen (máx. 5MB)
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Información Importante -->
                        <div class="alert alert-info mb-4">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Información importante:</strong>
                            <ul class="mb-0 mt-2">
                                <li>Todos los documentos deben estar vigentes y ser legibles</li>
                                <li>El vehículo debe tener como mínimo modelo 2012</li>
                                <li>La información será verificada antes de la aprobación</li>
                            </ul>
                        </div>

                        <!-- Botones de Acción -->
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary me-md-2">
                                <i class="fas fa-arrow-left me-1"></i>
                                Cancelar
                            </a>
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-paper-plane me-2"></i>
                                Enviar para Revisión
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .form-control:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
    }
    
    .text-uppercase {
        text-transform: uppercase;
    }
    
    .card {
        border-radius: 15px;
    }
    
    .card-header {
        border-radius: 15px 15px 0 0 !important;
    }
    
    .alert {
        border-radius: 10px;
    }
    
    .btn {
        border-radius: 8px;
    }
    
    .form-control {
        border-radius: 8px;
    }
    
    .form-label {
        color: #495057;
    }
    
    .text-primary {
        color: #0d6efd !important;
    }
</style>

<script>
    // Script para manejar la carga de archivos y mostrar el nombre del archivo
    document.querySelectorAll('input[type="file"]').forEach(function(input) {
        input.addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name;
            const fileSize = e.target.files[0]?.size;
            
            // Log para debugging (puedes remover esto en producción)
            console.log(`${e.target.name} - Archivo: ${fileName}, Tamaño: ${fileSize}, MIME: ${e.target.files[0]?.type}`);
            
            // Verificar tamaño del archivo (5MB máximo)
            if (fileSize > 5 * 1024 * 1024) {
                alert('El archivo es demasiado grande. Máximo 5MB permitido.');
                e.target.value = '';
                return;
            }
        });
    });
    
    // Script para formatear automáticamente la patente
    document.getElementById('patente').addEventListener('input', function(e) {
        let value = e.target.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
        
        // Formato: XX 123 XX
        if (value.length > 2 && value.length <= 5) {
            value = value.substring(0, 2) + ' ' + value.substring(2);
        } else if (value.length > 5) {
            value = value.substring(0, 2) + ' ' + value.substring(2, 5) + ' ' + value.substring(5, 7);
        }
        
        e.target.value = value;
    });
    
    // Validación del año del vehículo
    document.getElementById('anio_vehiculo').addEventListener('blur', function(e) {
        const currentYear = new Date().getFullYear();
        const inputYear = parseInt(e.target.value);
        
        if (inputYear && (inputYear < 2012 || inputYear > currentYear)) {
            alert(`El año del vehículo debe estar entre 2012 y ${currentYear}`);
            e.target.focus();
        }
    });
</script>
@endsection