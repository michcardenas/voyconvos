@extends('layouts.app_admin')

@section('content')
<style>
:root {
    --principal: #1F4E79;
    --neutro: #3A3A3A;
    --verde: #4CAF50;
    --fondo: #FCFCFD;
    --blanco: #FFFFFF;
    --borde: #E1E5E9;
    --sombra: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.page-container {
    background-color: var(--fondo);
    min-height: 100vh;
    padding: 2rem 1rem;
}

.content-wrapper {
    max-width: 800px;
    margin: 0 auto;
}

.page-title {
    color: var(--neutro);
    font-size: 2rem;
    font-weight: bold;
    margin-bottom: 2rem;
    text-align: center;
}

.form-card {
    background-color: var(--blanco);
    border-radius: 12px;
    padding: 2rem;
    box-shadow: var(--sombra);
    border: 1px solid var(--borde);
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-label {
    display: block;
    margin-bottom: 0.5rem;
    color: var(--neutro);
    font-weight: 600;
}

.form-control {
    width: 100%;
    padding: 0.75rem;
    border: 2px solid var(--borde);
    border-radius: 8px;
    font-size: 1rem;
    transition: border-color 0.3s ease;
}

.form-control:focus {
    outline: none;
    border-color: var(--principal);
    box-shadow: 0 0 0 3px rgba(31, 78, 121, 0.1);
}

.btn {
    padding: 0.75rem 2rem;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    text-decoration: none;
    display: inline-block;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-primary {
    background: linear-gradient(135deg, var(--principal), #2563eb);
    color: var(--blanco);
}

.btn-primary:hover {
    background: linear-gradient(135deg, #1a4269, #1d4ed8);
    transform: translateY(-1px);
}

.btn-secondary {
    background-color: #6c757d;
    color: var(--blanco);
}

.btn-secondary:hover {
    background-color: #5a6268;
    color: var(--blanco);
    text-decoration: none;
}

.form-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
    margin-top: 2rem;
}

@media (max-width: 768px) {
    .form-actions {
        flex-direction: column;
    }
}
</style>

<div class="page-container">
    <div class="content-wrapper">
        <h1 class="page-title">➕ Nueva Configuración</h1>
        
        <div class="form-card">
            <form action="{{ route('admin.gestion.store') }}" method="POST">
                @csrf
                
                <div class="form-group">
                    <label for="nombre" class="form-label">Tipo de Configuración</label>
                    <select id="nombre" name="nombre" class="form-control" required>
                        <option value="">Selecciona el tipo de configuración</option>
                        @foreach($tiposConfiguracion as $valor => $etiqueta)
                            <option value="{{ $valor }}">{{ $etiqueta }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="valor" class="form-label">Valor</label>
                    <input type="number" 
                           id="valor" 
                           name="valor" 
                           class="form-control" 
                           step="0.01" 
                           placeholder="Ingresa el valor" 
                           required>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        💾 Guardar Configuración
                    </button>
                    <!-- <a href="" class="btn btn-secondary">
                        ↩Volver
                    </a> -->
                </div>
            </form>
        </div>
    </div>
</div>
@endsection