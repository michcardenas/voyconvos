@extends('layouts.app_admin')

@section('title', 'Gestión de Usuarios')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 fw-bold text-dark mb-1">
                <i class="fas fa-users me-2 text-primary"></i>
                Gestión de Usuarios
            </h1>
            <p class="text-muted mb-0">Administra todos los usuarios de la plataforma</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Nuevo Usuario
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Filtros -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="row g-3">
                <!-- Búsqueda -->
                <div class="col-md-4">
                    <label class="form-label small fw-semibold text-muted">
                        <i class="fas fa-search me-1"></i>Buscar
                    </label>
                    <div class="input-group">
                        <input type="text"
                               id="busqueda"
                               class="form-control"
                               placeholder="Nombre o email..."
                               value="{{ request('buscar') }}">
                        <button class="btn btn-outline-primary" type="button" id="btn-buscar">
                            <i class="fas fa-search"></i>
                        </button>
                        @if(request('buscar'))
                        <button class="btn btn-outline-danger" type="button" id="btn-limpiar-busqueda">
                            <i class="fas fa-times"></i>
                        </button>
                        @endif
                    </div>
                </div>

                <!-- Filtro de Perfil -->
                <div class="col-md-2">
                    <label class="form-label small fw-semibold text-muted">
                        <i class="fas fa-user-tag me-1"></i>Perfil
                    </label>
                    <select id="filtro-perfil" class="form-select">
                        <option value="">Todos</option>
                        <option value="conductor" {{ request('perfil') == 'conductor' ? 'selected' : '' }}>Conductores</option>
                        <option value="pasajero" {{ request('perfil') == 'pasajero' ? 'selected' : '' }}>Pasajeros</option>
                    </select>
                </div>

                <!-- Filtro de Rol -->
                <div class="col-md-2">
                    <label class="form-label small fw-semibold text-muted">
                        <i class="fas fa-shield-alt me-1"></i>Rol
                    </label>
                    <select id="filtro-rol" class="form-select">
                        <option value="">Todos</option>
                        <option value="admin" {{ request('rol') == 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                </div>

                <!-- Filtro de Verificado -->
                <div class="col-md-2">
                    <label class="form-label small fw-semibold text-muted">
                        <i class="fas fa-check-circle me-1"></i>Estado
                    </label>
                    <select id="filtro-verificado" class="form-select">
                        <option value="">Todos</option>
                        <option value="1" {{ request('verificado') == '1' ? 'selected' : '' }}>Verificados</option>
                        <option value="0" {{ request('verificado') == '0' ? 'selected' : '' }}>No verificados</option>
                    </select>
                </div>

                <!-- Ordenar -->
                <div class="col-md-2">
                    <label class="form-label small fw-semibold text-muted">
                        <i class="fas fa-sort me-1"></i>Ordenar
                    </label>
                    <select id="filtro-ordenar" class="form-select">
                        <option value="created_at" {{ request('ordenar') == 'created_at' || !request('ordenar') ? 'selected' : '' }}>Más recientes</option>
                        <option value="updated_at" {{ request('ordenar') == 'updated_at' ? 'selected' : '' }}>Actualizados</option>
                        <option value="name" {{ request('ordenar') == 'name' ? 'selected' : '' }}>Por nombre</option>
                    </select>
                </div>
            </div>

            @if(request()->hasAny(['buscar', 'perfil', 'rol', 'verificado', 'ordenar']))
            <div class="mt-3 d-flex align-items-center justify-content-between">
                <div class="d-flex flex-wrap gap-2">
                    @if(request('buscar'))
                    <span class="badge bg-primary">
                        <i class="fas fa-search me-1"></i>Búsqueda: "{{ request('buscar') }}"
                    </span>
                    @endif
                    @if(request('perfil'))
                    <span class="badge bg-info">
                        <i class="fas fa-user-tag me-1"></i>{{ ucfirst(request('perfil')) }}
                    </span>
                    @endif
                    @if(request('rol'))
                    <span class="badge bg-warning text-dark">
                        <i class="fas fa-shield-alt me-1"></i>{{ ucfirst(request('rol')) }}
                    </span>
                    @endif
                    @if(request('verificado') !== null)
                    <span class="badge bg-success">
                        <i class="fas fa-check-circle me-1"></i>{{ request('verificado') == '1' ? 'Verificados' : 'No verificados' }}
                    </span>
                    @endif
                </div>
                <button id="limpiar-filtros" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-broom me-1"></i>Limpiar filtros
                </button>
            </div>
            @endif
        </div>
    </div>

    <!-- Tabla de Usuarios -->
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-4 py-3">Usuario</th>
                            <th class="py-3">Perfil</th>
                            <th class="py-3">Documentos</th>
                            <th class="py-3">Rol</th>
                            <th class="py-3">Estado</th>
                            <th class="py-3">Registrado</th>
                            <th class="py-3 text-end pe-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <!-- Usuario -->
                            <td class="px-4">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle me-3">
                                        @if($user->foto)
                                        <img src="{{ asset('storage/' . $user->foto) }}" alt="{{ $user->name }}">
                                        @else
                                        <span>{{ strtoupper(substr($user->name, 0, 2)) }}</span>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="fw-semibold text-dark">{{ $user->name }}</div>
                                        <small class="text-muted">{{ $user->email }}</small>
                                    </div>
                                </div>
                            </td>

                            <!-- Perfil -->
                            <td>
                                @if($user->registroConductor)
                                <span class="badge badge-conductor">
                                    <i class="fas fa-car me-1"></i>Conductor
                                </span>
                                @else
                                <span class="badge badge-pasajero">
                                    <i class="fas fa-user me-1"></i>Pasajero
                                </span>
                                @endif
                            </td>

                            <!-- Documentos -->
                            <td>
                                <div class="d-flex flex-wrap gap-1">
                                    @php
                                        $documentos = [
                                            'dni_foto' => ['icon' => 'id-card', 'title' => 'DNI Frente', 'field' => 'dni_foto'],
                                            'dni_foto_atras' => ['icon' => 'id-card', 'title' => 'DNI Atrás', 'field' => 'dni_foto_atras'],
                                        ];

                                        $documentosConductor = [];
                                        if($user->registroConductor) {
                                            $documentosConductor = [
                                                'licencia' => ['icon' => 'id-badge', 'title' => 'Licencia'],
                                                'cedula' => ['icon' => 'file-alt', 'title' => 'Cédula'],
                                                'cedula_verde' => ['icon' => 'file-contract', 'title' => 'Cédula Verde'],
                                                'seguro' => ['icon' => 'shield-alt', 'title' => 'Seguro'],
                                            ];
                                        }
                                    @endphp

                                    {{-- Documentos de Usuario (DNI) --}}
                                    @foreach($documentos as $key => $doc)
                                        @if($user->{$doc['field']})
                                        <span class="doc-icon doc-present" title="{{ $doc['title'] }} ✓" data-bs-toggle="tooltip">
                                            <i class="fas fa-{{ $doc['icon'] }}"></i>
                                        </span>
                                        @else
                                        <span class="doc-icon doc-missing" title="{{ $doc['title'] }} ✗" data-bs-toggle="tooltip">
                                            <i class="fas fa-{{ $doc['icon'] }}"></i>
                                        </span>
                                        @endif
                                    @endforeach

                                    {{-- Documentos de Conductor --}}
                                    @foreach($documentosConductor as $key => $doc)
                                        @if($user->registroConductor->{$key})
                                        <span class="doc-icon doc-present" title="{{ $doc['title'] }} ✓" data-bs-toggle="tooltip">
                                            <i class="fas fa-{{ $doc['icon'] }}"></i>
                                        </span>
                                        @else
                                        <span class="doc-icon doc-missing" title="{{ $doc['title'] }} ✗" data-bs-toggle="tooltip">
                                            <i class="fas fa-{{ $doc['icon'] }}"></i>
                                        </span>
                                        @endif
                                    @endforeach
                                </div>
                            </td>

                            <!-- Rol -->
                            <td>
                                @php
                                    $rol = $user->getRoleNames()->first();
                                @endphp
                                @if($rol == 'admin')
                                <span class="badge badge-admin">
                                    <i class="fas fa-shield-alt me-1"></i>Admin
                                </span>
                                @else
                                <span class="badge badge-user">
                                    <i class="fas fa-user me-1"></i>Usuario
                                </span>
                                @endif
                            </td>

                            <!-- Estado -->
                            <td>
                                @if($user->verificado)
                                <span class="badge badge-verified">
                                    <i class="fas fa-check-circle me-1"></i>Verificado
                                </span>
                                @else
                                <span class="badge badge-pending">
                                    <i class="fas fa-clock me-1"></i>Pendiente
                                </span>
                                @endif
                            </td>

                            <!-- Fecha -->
                            <td>
                                <div class="small">
                                    <div class="text-dark">{{ $user->created_at->format('d/m/Y') }}</div>
                                    <div class="text-muted">{{ $user->created_at->format('H:i') }}</div>
                                </div>
                            </td>

                            <!-- Acciones -->
                            <td class="text-end pe-4">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.users.edit', $user) }}"
                                       class="btn btn-sm btn-warning"
                                       title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.users.destroy', $user) }}"
                                          method="POST"
                                          class="d-inline">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger"
                                                onclick="return confirm('¿Eliminar a {{ $user->name }}?')"
                                                title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="fas fa-search fa-3x mb-3 d-block"></i>
                                    <p class="mb-0">No se encontraron usuarios</p>
                                    @if(request('buscar'))
                                    <small>con el término "{{ request('buscar') }}"</small>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($users->hasPages())
        <div class="card-footer bg-light">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <small class="text-muted">
                        Mostrando {{ $users->firstItem() }} - {{ $users->lastItem() }} de {{ $users->total() }} usuarios
                    </small>
                </div>
                <div class="col-md-6">
                    <div class="d-flex justify-content-end">
                        {{ $users->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<style>
/* Estilos personalizados */
.avatar-circle {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    font-size: 0.9rem;
    overflow: hidden;
}

.avatar-circle img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.badge {
    font-weight: 500;
    padding: 0.35rem 0.65rem;
    font-size: 0.75rem;
}

.badge-conductor {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.badge-pasajero {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    color: white;
}

.badge-admin {
    background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
    color: #000;
}

.badge-user {
    background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
    color: #000;
}

.badge-verified {
    background: linear-gradient(135deg, #00b09b 0%, #96c93d 100%);
    color: white;
}

.badge-pending {
    background: linear-gradient(135deg, #bdc3c7 0%, #95a5a6 100%);
    color: white;
}

.card {
    border: none;
    border-radius: 12px;
}

.card-shadow {
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}

.table > thead {
    border-bottom: 2px solid #dee2e6;
}

.table > thead > tr > th {
    font-weight: 600;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #6c757d;
    border-bottom: none;
}

.table > tbody > tr {
    transition: all 0.2s ease;
}

.table > tbody > tr:hover {
    background-color: #f8f9fa;
    transform: scale(1.002);
}

.btn-group .btn {
    padding: 0.375rem 0.75rem;
}

.form-select, .form-control {
    border-radius: 8px;
    border: 1px solid #dee2e6;
}

.form-select:focus, .form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

/* Document icons */
.doc-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 28px;
    height: 28px;
    border-radius: 6px;
    font-size: 0.75rem;
    transition: all 0.2s ease;
}

.doc-icon.doc-present {
    background: linear-gradient(135deg, #00b09b 0%, #96c93d 100%);
    color: white;
}

.doc-icon.doc-missing {
    background: linear-gradient(135deg, #bdc3c7 0%, #95a5a6 100%);
    color: white;
    opacity: 0.5;
}

.doc-icon:hover {
    transform: scale(1.1);
    opacity: 1;
}

@media (max-width: 768px) {
    .table {
        font-size: 0.85rem;
    }

    .avatar-circle {
        width: 35px;
        height: 35px;
        font-size: 0.75rem;
    }

    .doc-icon {
        width: 24px;
        height: 24px;
        font-size: 0.65rem;
    }
}
</style>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar tooltips de Bootstrap
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    const filtroRol = document.getElementById('filtro-rol');
    const filtroVerificado = document.getElementById('filtro-verificado');
    const filtroOrdenar = document.getElementById('filtro-ordenar');
    const filtroPerfil = document.getElementById('filtro-perfil');
    const limpiarFiltros = document.getElementById('limpiar-filtros');
    const campoBusqueda = document.getElementById('busqueda');
    const btnBuscar = document.getElementById('btn-buscar');
    const btnLimpiarBusqueda = document.getElementById('btn-limpiar-busqueda');

    function aplicarFiltros() {
        const url = new URL(window.location);

        url.searchParams.delete('rol');
        url.searchParams.delete('verificado');
        url.searchParams.delete('buscar');
        url.searchParams.delete('perfil');
        url.searchParams.delete('page');

        const ordenar = filtroOrdenar.value;
        if (ordenar && ordenar !== 'created_at') {
            url.searchParams.set('ordenar', ordenar);
        } else {
            url.searchParams.delete('ordenar');
        }

        const terminoBusqueda = campoBusqueda.value.trim();
        if (terminoBusqueda) {
            url.searchParams.set('buscar', terminoBusqueda);
        }

        const valorRol = filtroRol.value;
        if (valorRol) {
            url.searchParams.set('rol', valorRol);
        }

        const valorVerificado = filtroVerificado.value;
        if (valorVerificado !== '') {
            url.searchParams.set('verificado', valorVerificado);
        }

        const valorPerfil = filtroPerfil.value;
        if (valorPerfil) {
            url.searchParams.set('perfil', valorPerfil);
        }

        window.location.href = url.toString();
    }

    function limpiarTodosFiltros() {
        window.location.href = window.location.pathname;
    }

    function limpiarBusqueda() {
        campoBusqueda.value = '';
        aplicarFiltros();
    }

    filtroRol.addEventListener('change', aplicarFiltros);
    filtroVerificado.addEventListener('change', aplicarFiltros);
    filtroOrdenar.addEventListener('change', aplicarFiltros);
    filtroPerfil.addEventListener('change', aplicarFiltros);
    btnBuscar.addEventListener('click', aplicarFiltros);

    if (btnLimpiarBusqueda) {
        btnLimpiarBusqueda.addEventListener('click', limpiarBusqueda);
    }

    campoBusqueda.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            aplicarFiltros();
        }
    });

    limpiarFiltros.addEventListener('click', function(e) {
        e.preventDefault();
        limpiarTodosFiltros();
    });
});
</script>
@endpush
