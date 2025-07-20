@extends('layouts.app_admin')

@section('content')
<style>
:root {
    --principal: #1F4E79;
    --azul-claro: #DDF2FE;
    --neutro: #3A3A3A;
    --verde: #4CAF50;
    --fondo: #FCFCFD;
    --blanco: #FFFFFF;
    --gris-suave: #F8F9FA;
    --borde: #E1E5E9;
    --texto-gris: #64748B;
    --sombra: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.page-container {
    background-color: var(--fondo);
    min-height: 100vh;
    padding: 2rem 1rem;
}

.content-wrapper {
    max-width: 6xl;
    margin: 0 auto;
}

.page-title {
    color: var(--neutro);
    font-size: 2rem;
    font-weight: bold;
    margin-bottom: 2rem;
    text-align: center;
}

.current-values {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.value-card {
    background: linear-gradient(135deg, var(--principal), #2563eb);
    border-radius: 12px;
    padding: 1.5rem;
    color: var(--blanco);
    box-shadow: var(--sombra);
    text-align: center;
    transition: transform 0.3s ease;
}

.value-card:hover {
    transform: translateY(-2px);
}

.value-card.gasolina {
    background: linear-gradient(135deg, var(--verde), #22c55e);
}

.value-icon {
    font-size: 2rem;
    margin-bottom: 0.5rem;
    display: block;
}

.value-label {
    font-size: 0.9rem;
    opacity: 0.9;
    margin-bottom: 0.5rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.value-amount {
    font-size: 2.5rem;
    font-weight: bold;
    margin-bottom: 0.5rem;
}

.value-date {
    font-size: 0.8rem;
    opacity: 0.8;
}

.actions-section {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    padding: 1rem;
    background-color: var(--blanco);
    border-radius: 8px;
    box-shadow: var(--sombra);
}

.btn-new {
    background: linear-gradient(135deg, var(--principal), #2563eb);
    color: var(--blanco);
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: var(--sombra);
}

.btn-new:hover {
    background: linear-gradient(135deg, #1a4269, #1d4ed8);
    transform: translateY(-1px);
    text-decoration: none;
    color: var(--blanco);
}

.section-title {
    color: var(--neutro);
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: 1rem;
}

.history-table {
    background-color: var(--blanco);
    border-radius: 12px;
    overflow: hidden;
    box-shadow: var(--sombra);
    border: 1px solid var(--borde);
}

.table {
    width: 100%;
    border-collapse: collapse;
}

.table thead {
    background: linear-gradient(135deg, var(--principal), #2563eb);
    color: var(--blanco);
}

.table th {
    padding: 1rem;
    text-align: left;
    font-weight: 600;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.table td {
    padding: 1rem;
    border-bottom: 1px solid var(--borde);
    color: var(--neutro);
}

.table tbody tr:hover {
    background-color: var(--gris-suave);
}

.table tbody tr:last-child td {
    border-bottom: none;
}

.badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 500;
    text-transform: capitalize;
}

.badge.gasolina {
    background-color: var(--verde);
    color: var(--blanco);
}

.badge.comision {
    background-color: var(--principal);
    color: var(--blanco);
}

.no-data {
    text-align: center;
    padding: 3rem;
    color: var(--texto-gris);
    background-color: var(--gris-suave);
}

@media (max-width: 768px) {
    .current-values {
        grid-template-columns: 1fr;
    }
    
    .actions-section {
        flex-direction: column;
        gap: 1rem;
    }
    
    .value-amount {
        font-size: 2rem;
    }
}
</style>

<div class="page-container">
    <div class="content-wrapper">
        <h1 class="page-title">Gestión de Configuración Admin</h1>
        
        {{-- Valores Actuales --}}
        <div class="current-values">
            <div class="value-card comision">
                <span class="value-icon">💰</span>
                <div class="value-label">Comisión Actual</div>
                <div class="value-amount">
                    @if(isset($valorActual['comision']))
                        ${{ number_format($valorActual['comision']->valor, 2) }}
                    @else
                        --
                    @endif
                </div>
                <div class="value-date">
                    @if(isset($valorActual['comision']))
                        Actualizado: {{ $valorActual['comision']->created_at->format('d/m/Y H:i') }}
                    @else
                        Sin configurar
                    @endif
                </div>
            </div>
            
            <div class="value-card gasolina">
                <span class="value-icon">⛽</span>
                <div class="value-label">Precio Gasolina Actual</div>
                <div class="value-amount">
                    @if(isset($valorActual['gasolina']))
                        ${{ number_format($valorActual['gasolina']->valor, 2) }}
                    @else
                        --
                    @endif
                </div>
                <div class="value-date">
                    @if(isset($valorActual['gasolina']))
                        Actualizado: {{ $valorActual['gasolina']->created_at->format('d/m/Y H:i') }}
                    @else
                        Sin configurar
                    @endif
                </div>
            </div>
        </div>

        {{-- Acciones --}}
        <div class="actions-section">
            <h2 class="section-title">📊 Historial de Configuraciones</h2>
            <a href="{{ route('admin.gestion.create') }}" class="btn-new">
                ➕ Nueva Configuración
            </a>
        </div>

        {{-- Tabla Histórica --}}
        <div class="history-table">
            @if($configuraciones->count() > 0)
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tipo</th>
                            <th>Valor</th>
                            <th>Fecha de Creación</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($configuraciones as $config)
                            <tr>
                                <td>{{ $config->id_configuracion }}</td>
                                <td>
                                    <span class="badge {{ $config->nombre }}">
                                        @if($config->nombre == 'gasolina')
                                            ⛽ {{ ucfirst($config->nombre) }}
                                        @else
                                            💰 {{ ucfirst($config->nombre) }}
                                        @endif
                                    </span>
                                </td>
                                <td>${{ number_format($config->valor, 2) }}</td>
                                <td>{{ $config->created_at->format('d/m/Y H:i:s') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="no-data">
                    <p>📋 No hay configuraciones registradas</p>
                    <p class="text-sm mt-2">Comienza creando tu primera configuración</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection