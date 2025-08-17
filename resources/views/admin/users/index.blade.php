@extends('layouts.app_admin')

@section('title', 'Usuarios')

@section('content')
{{-- resources/views/admin/users/index.blade.php --}}
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold mb-0">Lista de Usuarios</h1>
        <div class="text-end">
            <small class="text-muted d-block">Hora actual:</small>
            <span class="fw-bold text-primary" id="hora-actual"></span>
        </div>
    </div>
    
    @if(session('success'))
    <div class="alert alert-success mb-4">
        {{ session('success') }}
    </div>
    @endif

    {{-- Barra de acciones y filtros MEJORADA --}}
    <div class="card mb-4">
        <div class="card-body py-3">
            <div class="row align-items-end">
                <div class="col-md-4">
                    <a href="{{ route('admin.users.create') }}" class="btn btn-success mb-2">
                        <i class="fas fa-plus me-1"></i>Nuevo Usuario
                    </a>
                </div>
                <div class="col-md-8">
                    {{-- 🔥 NUEVA FILA PARA BÚSQUEDA --}}
                    <div class="row g-2">
                        <div class="col-md-6">
                            <label for="busqueda" class="form-label mb-1 small">🔍 Buscar por nombre o correo:</label>
                            <div class="input-group input-group-sm">
                                <input type="text" 
                                       id="busqueda" 
                                       class="form-control" 
                                       placeholder="Escribe nombre o email..."
                                       value="{{ request('buscar') }}"
                                       maxlength="50">
                                <button class="btn btn-outline-secondary" type="button" id="btn-buscar" title="Buscar">
                                    <i class="fas fa-search"></i>
                                </button>
                                @if(request('buscar'))
                                    <button class="btn btn-outline-danger" type="button" id="btn-limpiar-busqueda" title="Limpiar búsqueda">
                                        <i class="fas fa-times"></i>
                                    </button>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row g-2">
                                <div class="col-4">
                                    <label for="filtro-ordenar" class="form-label mb-1 small">Ordenar:</label>
                                    <select id="filtro-ordenar" class="form-select form-select-sm">
                                        <option value="created_at" {{ request('ordenar') == 'created_at' || !request('ordenar') ? 'selected' : '' }}>Más recientes</option>
                                        <option value="updated_at" {{ request('ordenar') == 'updated_at' ? 'selected' : '' }}>Últimas actualizaciones</option>
                                        <option value="name" {{ request('ordenar') == 'name' ? 'selected' : '' }}>Por nombre</option>
                                    </select>
                                </div>
                                <div class="col-4">
                                    <label for="filtro-rol" class="form-label mb-1 small">Rol:</label>
                                    <select id="filtro-rol" class="form-select form-select-sm">
                                        <option value="">Todos</option>
                                        <option value="admin" {{ request('rol') == 'admin' ? 'selected' : '' }}>Admin</option>
                                        <option value="conductor" {{ request('rol') == 'conductor' ? 'selected' : '' }}>Conductor</option>
                                        <option value="pasajero" {{ request('rol') == 'pasajero' ? 'selected' : '' }}>Pasajero</option>
                                    </select>
                                </div>
                                <div class="col-4">
                                    <label for="filtro-verificado" class="form-label mb-1 small">Estado:</label>
                                    <select id="filtro-verificado" class="form-select form-select-sm">
                                        <option value="">Todos</option>
                                        <option value="1" {{ request('verificado') == '1' ? 'selected' : '' }}>Verificados</option>
                                        <option value="0" {{ request('verificado') == '0' ? 'selected' : '' }}>No Verificados</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- BOTÓN LIMPIAR FILTROS --}}
                    <div class="row mt-2">
                        <div class="col-12 text-end">
                            <button id="limpiar-filtros" class="btn btn-outline-secondary btn-sm" title="Limpiar todos los filtros">
                                <i class="fas fa-broom me-1"></i>Limpiar Filtros
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 🔥 MOSTRAR FILTROS ACTIVOS --}}
    @if(request('buscar') || request('rol') || request('verificado') !== null || request('ordenar'))
    <div class="alert alert-info alert-sm mb-3">
        <i class="fas fa-filter me-2"></i>
        <strong>Filtros activos:</strong>
        @if(request('buscar'))
            <span class="badge bg-primary me-1">Búsqueda: "{{ request('buscar') }}"</span>
        @endif
        @if(request('rol'))
            <span class="badge bg-warning text-dark me-1">Rol: {{ ucfirst(request('rol')) }}</span>
        @endif
        @if(request('verificado') !== null)
            <span class="badge bg-info me-1">
                Estado: {{ request('verificado') == '1' ? 'Verificados' : 'No Verificados' }}
            </span>
        @endif
        @if(request('ordenar') && request('ordenar') !== 'created_at')
            <span class="badge bg-secondary me-1">
                Orden: {{ request('ordenar') == 'updated_at' ? 'Últimas actualizaciones' : 'Por nombre' }}
            </span>
        @endif
    </div>
    @endif

    {{-- Tabla --}}
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0" id="tabla-usuarios">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Rol</th>
                            <th>Verificado</th>
                            <th>Fecha Registro</th>
                            <th>Última Actualización</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr data-rol="{{ $user->getRoleNames()->first() }}" data-verificado="{{ $user->verificado ? '1' : '0' }}">
                            <td>
                                <strong>{{ $user->name }}</strong>
                                @if(request('buscar') && stripos($user->name, request('buscar')) !== false)
                                    <small class="text-success d-block">
                                        <i class="fas fa-search"></i> Coincidencia en nombre
                                    </small>
                                @endif
                            </td>
                            <td>
                                {{ $user->email }}
                                @if(request('buscar') && stripos($user->email, request('buscar')) !== false)
                                    <small class="text-success d-block">
                                        <i class="fas fa-search"></i> Coincidencia en email
                                    </small>
                                @endif
                            </td>
                            <td>
                                @php
                                    $rol = $user->getRoleNames()->first();
                                @endphp
                                <span class="badge 
                                    @if($rol == 'admin') bg-danger
                                    @elseif($rol == 'conductor') bg-warning text-dark
                                    @elseif($rol == 'pasajero') bg-info
                                    @else bg-secondary
                                    @endif">
                                    {{ ucfirst($rol) }}
                                </span>
                            </td>
                            <td>
                                @if($user->verificado)
                                    <span class="badge bg-success">
                                        <i class="fas fa-check me-1"></i>Verificado
                                    </span>
                                @else
                                    <span class="badge bg-secondary">
                                        <i class="fas fa-clock me-1"></i>Pendiente
                                    </span>
                                @endif
                            </td>
                            <td>
                                <small class="text-muted">
                                    {{ $user->created_at->format('d/m/Y') }}<br>
                                    <span class="text-xs">{{ $user->created_at->format('H:i') }}</span>
                                </small>
                            </td>
                            <td>
                                <small class="text-muted">
                                    @if($user->updated_at->diffInMinutes($user->created_at) > 5)
                                        <span class="badge bg-warning text-dark mb-1" title="Usuario actualizado recientemente">
                                            <i class="fas fa-sync-alt me-1"></i>Actualizado
                                        </span><br>
                                    @endif
                                    {{ $user->updated_at->format('d/m/Y') }}<br>
                                    <span class="text-xs">{{ $user->updated_at->format('H:i') }}</span>
                                    @if($user->updated_at->isToday())
                                        <br><span class="text-xs text-success">Hoy</span>
                                    @endif
                                </small>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.users.edit', $user) }}" 
                                       class="btn btn-sm btn-warning" 
                                       title="Editar usuario">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.users.destroy', $user) }}" 
                                          method="POST" 
                                          style="display:inline;">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger" 
                                                onclick="return confirm('¿Estás seguro de eliminar este usuario?')"
                                                title="Eliminar usuario">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-search fa-2x mb-2"></i>
                                    <p class="mb-0">No se encontraron usuarios</p>
                                    @if(request('buscar'))
                                        <small>con la búsqueda "{{ request('buscar') }}"</small>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        {{-- Paginación dentro de la card --}}
        @if($users->hasPages())
        <div class="card-footer bg-light">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <small class="text-muted" id="info-resultados">
                        Mostrando {{ $users->firstItem() }} - {{ $users->lastItem() }} de {{ $users->total() }} usuarios
                        @if(request('buscar'))
                            (filtrados por "{{ request('buscar') }}")
                        @endif
                    </small>
                </div>
                <div class="col-md-6">
                    <div class="d-flex justify-content-end">
                        {{ $users->appends(request()->query())->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    {{-- Información adicional --}}
    <div class="row mt-3">
        <div class="col-md-4">
            <div class="small text-muted">
                <i class="fas fa-info-circle me-1"></i>
                Total de usuarios: <strong id="total-usuarios">{{ $users->total() }}</strong>
            </div>
        </div>
        <div class="col-md-4">
            <div class="small text-muted">
                <i class="fas fa-sort me-1"></i>
                Ordenado por: <strong>
                    @switch(request('ordenar'))
                        @case('updated_at') Últimas actualizaciones @break
                        @case('name') Nombre @break
                        @default Más recientes
                    @endswitch
                </strong>
            </div>
        </div>
        <div class="col-md-4">
            <div class="small text-muted">
                <i class="fas fa-clock me-1"></i>
                Última actualización de página: <strong id="ultima-carga"></strong>
            </div>
        </div>
    </div>
</div>

{{-- CSS personalizado --}}
<style>
        .card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border-radius: 0.5rem;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(0, 123, 255, 0.05);
        }

        .pagination {
            margin-bottom: 0;
        }

        .pagination .page-link {
            border-radius: 0.375rem;
            margin: 0 2px;
            border: 1px solid #dee2e6;
        }

        .pagination .page-item.active .page-link {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }

        .badge {
            font-size: 0.75rem;
            padding: 0.375rem 0.75rem;
        }

        .btn-sm {
            padding: 0.25rem 0.75rem;
            font-size: 0.875rem;
        }

        .form-select-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }

        .text-xs {
            font-size: 0.65rem;
        }

        .btn-group .btn {
            border-radius: 0;
        }

        .btn-group .btn:first-child {
            border-radius: 0.375rem 0 0 0.375rem;
        }

        .btn-group .btn:last-child {
            border-radius: 0 0.375rem 0.375rem 0;
        }

        #hora-actual {
            font-size: 1.1rem;
            letter-spacing: 0.5px;
        }

        /* 🔥 ESTILOS PARA LA BÚSQUEDA */
        #busqueda {
            transition: all 0.3s ease;
        }

        #busqueda:focus {
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
            border-color: #0d6efd;
        }

        .alert-sm {
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
        }

        .input-group .btn {
            border-left: none;
        }

        .input-group .form-control:focus + .btn {
            border-color: #0d6efd;
        }

        @media (max-width: 768px) {
            .row.g-2 > .col-md-6,
            .row.g-2 > .col-4 {
                margin-bottom: 0.5rem;
            }
            
            .btn-group {
                display: flex;
                flex-direction: column;
                width: 100%;
            }
            
            .btn-group .btn {
                border-radius: 0.375rem !important;
                margin-bottom: 2px;
            }
            
            .table-responsive {
                font-size: 0.875rem;
            }
        }
</style>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const filtroRol = document.getElementById('filtro-rol');
        const filtroVerificado = document.getElementById('filtro-verificado');
        const filtroOrdenar = document.getElementById('filtro-ordenar');
        const limpiarFiltros = document.getElementById('limpiar-filtros');
        const tabla = document.getElementById('tabla-usuarios');
        const tbody = tabla.querySelector('tbody');
        
        // 🔥 NUEVOS ELEMENTOS PARA BÚSQUEDA
        const campoBusqueda = document.getElementById('busqueda');
        const btnBuscar = document.getElementById('btn-buscar');
        const btnLimpiarBusqueda = document.getElementById('btn-limpiar-busqueda');

        // ===============================================
        // FUNCIONALIDAD DE HORA (mantener como está)
        // ===============================================
        function actualizarHora() {
            const ahora = new Date();
            const opciones = { 
                timeZone: 'America/Argentina/Buenos_Aires',
                year: 'numeric',
                month: '2-digit',
                day: '2-digit',
                hour: '2-digit', 
                minute: '2-digit', 
                second: '2-digit',
                hour12: false 
            };
            const horaFormateada = ahora.toLocaleString('es-AR', opciones);
            document.getElementById('hora-actual').textContent = horaFormateada;
            
            // Actualizar última carga
            document.getElementById('ultima-carga').textContent = ahora.toLocaleTimeString('es-AR', {
                timeZone: 'America/Argentina/Buenos_Aires',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
        }

        // Actualizar hora cada segundo
        actualizarHora();
        setInterval(actualizarHora, 1000);

        // ===============================================
        // FUNCIÓN PRINCIPAL PARA APLICAR FILTROS CON BÚSQUEDA
        // ===============================================
        function aplicarFiltros() {
            const url = new URL(window.location);
            
            // Limpiar parámetros existentes
            url.searchParams.delete('rol');
            url.searchParams.delete('verificado');
            url.searchParams.delete('buscar');
            url.searchParams.delete('page'); // Resetear a página 1 cuando se aplican filtros
            
            // Mantener el ordenamiento actual
            const ordenar = filtroOrdenar.value;
            if (ordenar && ordenar !== 'created_at') {
                url.searchParams.set('ordenar', ordenar);
            } else {
                url.searchParams.delete('ordenar');
            }
            
            // 🔥 AGREGAR BÚSQUEDA
            const terminoBusqueda = campoBusqueda.value.trim();
            if (terminoBusqueda) {
                url.searchParams.set('buscar', terminoBusqueda);
            }
            
            // Agregar filtro de rol si está seleccionado
            const valorRol = filtroRol.value;
            if (valorRol) {
                url.searchParams.set('rol', valorRol);
            }
            
            // Agregar filtro de verificado si está seleccionado
            const valorVerificado = filtroVerificado.value;
            if (valorVerificado !== '') {
                url.searchParams.set('verificado', valorVerificado);
            }
            
            // Redirigir con los nuevos parámetros
            window.location.href = url.toString();
        }

        // ===============================================
        // FUNCIÓN PARA LIMPIAR TODOS LOS FILTROS
        // ===============================================
        function limpiarTodosFiltros() {
            const url = new URL(window.location);
            
            // Eliminar todos los parámetros de filtro
            url.searchParams.delete('rol');
            url.searchParams.delete('verificado');
            url.searchParams.delete('ordenar');
            url.searchParams.delete('buscar');
            url.searchParams.delete('page');
            
            // Redirigir a la URL limpia
            window.location.href = url.toString();
        }

        // 🔥 FUNCIÓN PARA LIMPIAR SOLO LA BÚSQUEDA
        function limpiarBusqueda() {
            campoBusqueda.value = '';
            aplicarFiltros();
        }

        // ===============================================
        // EVENT LISTENERS
        // ===============================================
        filtroRol.addEventListener('change', aplicarFiltros);
        filtroVerificado.addEventListener('change', aplicarFiltros);
        filtroOrdenar.addEventListener('change', aplicarFiltros);
        
        // 🔥 EVENTOS PARA BÚSQUEDA
        btnBuscar.addEventListener('click', aplicarFiltros);
        if (btnLimpiarBusqueda) {
            btnLimpiarBusqueda.addEventListener('click', limpiarBusqueda);
        }
        
        // Buscar al presionar Enter
        campoBusqueda.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                aplicarFiltros();
            }
        });
        
        // Buscar en tiempo real (opcional - con debounce)
        let timeoutBusqueda;
        campoBusqueda.addEventListener('input', function() {
            clearTimeout(timeoutBusqueda);
            timeoutBusqueda = setTimeout(() => {
                if (this.value.length >= 2 || this.value.length === 0) {
                    aplicarFiltros();
                }
            }, 500); // Esperar 500ms después de que el usuario deje de escribir
        });
        
        limpiarFiltros.addEventListener('click', function(e) {
            e.preventDefault();
            limpiarTodosFiltros();
        });

        // ===============================================
        // RESALTAR FILAS ACTUALIZADAS RECIENTEMENTE
        // ===============================================
        const filas = tbody.querySelectorAll('tr');
        filas.forEach(function(fila) {
            const celdaActualizacion = fila.cells[5];
            if (celdaActualizacion) {
                const badgeActualizado = celdaActualizacion.querySelector('.badge.bg-warning');
                if (badgeActualizado && badgeActualizado.textContent.includes('Actualizado')) {
                    fila.style.backgroundColor = 'rgba(255, 193, 7, 0.08)';
                }
            }
        });

        // ===============================================
        // CONFIRMACIÓN AL ELIMINAR USUARIO
        // ===============================================
        const botonesEliminar = document.querySelectorAll('button[onclick*="confirm"]');
        botonesEliminar.forEach(boton => {
            boton.onclick = function(e) {
                e.preventDefault();
                const form = this.closest('form');
                const fila = this.closest('tr');
                const nombreUsuario = fila.cells[0].textContent.trim();
                
                if (confirm(`¿Estás seguro de eliminar al usuario "${nombreUsuario}"?\n\nEsta acción no se puede deshacer.`)) {
                    form.submit();
                }
            };
        });

        // 🔥 FOCUS AUTOMÁTICO EN EL CAMPO DE BÚSQUEDA AL CARGAR
        if (!campoBusqueda.value) {
            campoBusqueda.focus();
        }
    });
</script>
@endpush