@extends('layouts.app_admin')

@section('title', 'Usuarios')

@section('content')
{{-- resources/views/admin/users/index.blade.php --}}
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
                                <option value="1">Verificados</option>
                                <option value="0">No Verificados</option>
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
                            <th>Fecha Registro</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr data-rol="{{ $user->getRoleNames()->first() }}" data-verificado="{{ $user->verificado ? '1' : '0' }}">
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
                        @endforeach
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
                Total de usuarios: <strong id="total-usuarios">{{ $users->total() }}</strong>
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

@media (max-width: 768px) {
    .row.g-2 > .col-sm-5, .row.g-2 > .col-sm-2 {
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
}
</style>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const filtroRol = document.getElementById('filtro-rol');
    const filtroVerificado = document.getElementById('filtro-verificado');
    const limpiarFiltros = document.getElementById('limpiar-filtros');
    const tabla = document.getElementById('tabla-usuarios');
    const tbody = tabla.querySelector('tbody');
    const infoResultados = document.getElementById('info-resultados');
    const totalUsuarios = document.getElementById('total-usuarios');

    let todasLasFilas = Array.from(tbody.querySelectorAll('tr'));
    const totalOriginal = todasLasFilas.length;

    function aplicarFiltros() {
        const valorRol = filtroRol.value.toLowerCase();
        const valorVerificado = filtroVerificado.value;
        
        let filasVisibles = 0;
        
        todasLasFilas.forEach(fila => {
            const rolFila = fila.getAttribute('data-rol');
            const verificadoFila = fila.getAttribute('data-verificado');
            
            let mostrarPorRol = !valorRol || (rolFila && rolFila.toLowerCase() === valorRol);
            let mostrarPorVerificado = !valorVerificado || verificadoFila === valorVerificado;
            
            if (mostrarPorRol && mostrarPorVerificado) {
                fila.style.display = '';
                filasVisibles++;
            } else {
                fila.style.display = 'none';
            }
        });
        
        // Actualizar información de resultados
        actualizarInfoResultados(filasVisibles);
    }

    function actualizarInfoResultados(visibles) {
        if (visibles === totalOriginal) {
            // Restaurar texto original si no hay filtros activos
            const textoOriginal = infoResultados.getAttribute('data-original') || infoResultados.textContent;
            infoResultados.textContent = textoOriginal;
        } else {
            // Guardar texto original la primera vez
            if (!infoResultados.getAttribute('data-original')) {
                infoResultados.setAttribute('data-original', infoResultados.textContent);
            }
            infoResultados.textContent = `Mostrando ${visibles} de ${totalOriginal} usuarios (filtrado)`;
        }
        totalUsuarios.textContent = visibles;
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