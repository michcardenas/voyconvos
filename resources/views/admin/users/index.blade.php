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
                        <div class="col-sm-3">
                            <label for="filtro-ordenar" class="form-label mb-1 small">Ordenar:</label>
                            <select id="filtro-ordenar" class="form-select form-select-sm">
                                <option value="created_at" {{ request('ordenar') == 'created_at' || !request('ordenar') ? 'selected' : '' }}>Más recientes</option>
                                <option value="updated_at" {{ request('ordenar') == 'updated_at' ? 'selected' : '' }}>Últimas actualizaciones</option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <label for="filtro-rol" class="form-label mb-1 small">Rol:</label>
                            <select id="filtro-rol" class="form-select form-select-sm">
                                <option value="">Todos</option>
                                <option value="admin">Admin</option>
                                <option value="conductor">Conductor</option>
                                <option value="pasajero">Pasajero</option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <label for="filtro-verificado" class="form-label mb-1 small">Verificación:</label>
                            <select id="filtro-verificado" class="form-select form-select-sm">
                                <option value="">Todos</option>
                                <option value="1">Verificados</option>
                                <option value="0">No Verificados</option>
                            </select>
                        </div>
                        <div class="col-sm-3">
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
                        @foreach($users as $user)
                        <tr data-rol="{{ $user->getRoleNames()->first() }}" data-verificado="{{ $user->verificado ? '1' : '0' }}">
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
        <div class="col-md-4">
            <div class="small text-muted">
                <i class="fas fa-sort me-1"></i>
                Ordenado por: <strong>{{ request('ordenar') == 'updated_at' ? 'Últimas actualizaciones' : 'Más recientes' }}</strong>
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

@media (max-width: 768px) {
    .row.g-2 > .col-sm-3 {
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
    const infoResultados = document.getElementById('info-resultados');
    const totalUsuarios = document.getElementById('total-usuarios');

    let todasLasFilas = Array.from(tbody.querySelectorAll('tr'));
    const totalOriginal = todasLasFilas.length;

    // Función para actualizar la hora actual
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

    // Función para manejar el cambio de ordenamiento
    function cambiarOrdenamiento() {
        const ordenar = filtroOrdenar.value;
        const url = new URL(window.location);
        url.searchParams.set('ordenar', ordenar);
        window.location.href = url.toString();
    }

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
        // No limpiar el filtro de ordenamiento ya que afecta la URL
        aplicarFiltros();
    }

    // Event listeners
    filtroRol.addEventListener('change', aplicarFiltros);
    filtroVerificado.addEventListener('change', aplicarFiltros);
    filtroOrdenar.addEventListener('change', cambiarOrdenamiento);
    limpiarFiltros.addEventListener('click', limpiarTodosFiltros);
});
</script>
@endpush