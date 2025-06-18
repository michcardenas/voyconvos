@extends('layouts.app_admin')

@section('title', 'Usuarios')

@section('content')
<div class="container py-5">
    <h1 class="fw-bold mb-4">Lista de Usuarios</h1>
    @if(session('success'))
    <div class="alert alert-success mb-4">
        {{ session('success') }}
    </div>
    @endif

    {{-- Barra de acciones y filtros --}}
    <div class="card mb-4">
        <div class="card-body py-3">
            <div class="row align-items-end">
                <div class="col-md-6">
                    <a href="{{ route('admin.users.create') }}" class="btn btn-success">
                        <i class="fas fa-plus me-1"></i>Nuevo Usuario
                    </a>
                </div>
                <div class="col-md-6">
                    <div class="row g-2">
                        <div class="col-sm-5">
                            <label for="filtro-rol" class="form-label mb-1 small">Rol:</label>
                            <select id="filtro-rol" class="form-select form-select-sm">
                                <option value="">Todos</option>
                                <option value="admin">Admin</option>
                                <option value="conductor">Conductor</option>
                                <option value="pasajero">Pasajero</option>
                            </select>
                        </div>
                        <div class="col-sm-5">
                            <label for="filtro-verificado" class="form-label mb-1 small">Verificación:</label>
                            <select id="filtro-verificado" class="form-select form-select-sm">
                                <option value="">Todos</option>
                                <option value="verificado">Verificados</option>
                                <option value="no-verificado">No Verificados</option>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <label class="form-label mb-1 small">&nbsp;</label>
                            <button id="limpiar-filtros" class="btn btn-outline-secondary btn-sm d-block w-100" title="Limpiar filtros">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabla --}}
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0" id="tabla-usuarios">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Rol</th>
                <th>Verificado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
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
                        <span class="badge bg-success">Verificado</span>
                    @else
                        <span class="badge bg-danger">No verificado</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-warning">Editar</a>
                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" style="display:inline;">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('¿Seguro?')">Eliminar</button>
                    </form>
                </td>
            </tr>
            @endforeach
                </table>
            </div>
        </div>
        {{-- Paginación dentro de la card --}}
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

    {{-- Información adicional --}}
    <div class="row mt-3">
        <div class="col-md-4">
            <div class="small text-muted">
                <i class="fas fa-info-circle me-1"></i>
                Total de usuarios: <strong>{{ $users->total() }}</strong>
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

@media (max-width: 768px) {
    .row.g-2 > .col-sm-5, .row.g-2 > .col-sm-2 {
        margin-bottom: 0.5rem;
    }
}
</style>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Auto-hide alert
        const alert = document.querySelector('.alert-success');
        if (alert) {
            setTimeout(() => {
                alert.style.transition = "opacity 0.5s ease";
                alert.style.opacity = 0;
                setTimeout(() => alert.remove(), 500);
            }, 2000);
        }

        // Filtros
        const filtroRol = document.getElementById('filtro-rol');
        const filtroVerificado = document.getElementById('filtro-verificado');
        const limpiarFiltros = document.getElementById('limpiar-filtros');
        const tabla = document.getElementById('tabla-usuarios');
        const filas = tabla.querySelectorAll('tbody tr');

        function aplicarFiltros() {
            const rolSeleccionado = filtroRol.value.toLowerCase();
            const verificadoSeleccionado = filtroVerificado.value;

            filas.forEach(fila => {
                const rolTexto = fila.querySelector('td:nth-child(4) .badge').textContent.toLowerCase();
                const verificadoTexto = fila.querySelector('td:nth-child(5) .badge').textContent.toLowerCase();
                
                let mostrarPorRol = true;
                let mostrarPorVerificado = true;

                // Filtro por rol
                if (rolSeleccionado && !rolTexto.includes(rolSeleccionado)) {
                    mostrarPorRol = false;
                }

                // Filtro por verificación
                if (verificadoSeleccionado === 'verificado' && !verificadoTexto.includes('verificado')) {
                    mostrarPorVerificado = false;
                } else if (verificadoSeleccionado === 'no-verificado' && verificadoTexto.includes('verificado')) {
                    mostrarPorVerificado = false;
                }

                // Mostrar u ocultar fila
                if (mostrarPorRol && mostrarPorVerificado) {
                    fila.style.display = '';
                } else {
                    fila.style.display = 'none';
                }
            });

            // Mostrar mensaje si no hay resultados
            actualizarMensajeVacio();
        }

        function actualizarMensajeVacio() {
            const filasVisibles = Array.from(filas).filter(fila => fila.style.display !== 'none');
            let mensajeVacio = tabla.querySelector('.mensaje-vacio');

            if (filasVisibles.length === 0) {
                if (!mensajeVacio) {
                    mensajeVacio = document.createElement('tr');
                    mensajeVacio.className = 'mensaje-vacio';
                    mensajeVacio.innerHTML = '<td colspan="6" class="text-center py-4 text-muted">No se encontraron usuarios con los filtros aplicados</td>';
                    tabla.querySelector('tbody').appendChild(mensajeVacio);
                }
                mensajeVacio.style.display = '';
            } else {
                if (mensajeVacio) {
                    mensajeVacio.style.display = 'none';
                }
            }
        }

        function limpiarTodosFiltros() {
            filtroRol.value = '';
            filtroVerificado.value = '';
            aplicarFiltros();
        }

        // Event listeners
        filtroRol.addEventListener('change', aplicarFiltros);
        filtroVerificado.addEventListener('change', aplicarFiltros);
        limpiarFiltros.addEventListener('click', limpiarTodosFiltros);
    });
</script>
@endpush