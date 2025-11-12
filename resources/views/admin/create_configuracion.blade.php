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
                    <select id="nombre" name="nombre" class="form-control" required onchange="updateValorField()">
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
                    <small id="valor-help" style="display: block; margin-top: 0.5rem; color: #6c757d;"></small>
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

<script>
function updateValorField() {
    const tipoSelect = document.getElementById('nombre');
    const valorInput = document.getElementById('valor');
    const valorHelp = document.getElementById('valor-help');
    const selectedTipo = tipoSelect.value;

    // Reset
    valorInput.removeAttribute('max');
    valorInput.setAttribute('min', '0');
    valorInput.setAttribute('step', '0.01');
    valorHelp.textContent = '';

    // Configuraciones específicas por tipo
    switch(selectedTipo) {
        case 'comision':
            valorInput.setAttribute('max', '100');
            valorInput.setAttribute('placeholder', 'Ej: 15.5');
            valorHelp.textContent = '💡 Ingresa el porcentaje de comisión (0-100%)';
            break;

        case 'maximo':
            valorInput.setAttribute('max', '100');
            valorInput.setAttribute('placeholder', 'Ej: 80');
            valorHelp.textContent = '💡 Ingresa el porcentaje máximo permitido (0-100%)';
            break;

        case 'costo_km':
            valorInput.setAttribute('placeholder', 'Ej: 250.50');
            valorHelp.textContent = '💡 Ingresa el costo por cada kilómetro recorrido';
            break;

        case 'costo_combustible':
            valorInput.setAttribute('placeholder', 'Ej: 1500.75');
            valorHelp.textContent = '💡 Ingresa el costo del combustible por litro o galón';
            break;

        case 'numero_galones':
            valorInput.setAttribute('max', '100');
            valorInput.setAttribute('step', '1');
            valorInput.setAttribute('placeholder', 'Ej: 50');
            valorHelp.textContent = '💡 Ingresa el número de galones (máximo 100)';
            break;

        default:
            valorInput.setAttribute('placeholder', 'Ingresa el valor');
            valorHelp.textContent = '';
    }
}

// Validación adicional en el formulario
document.querySelector('form').addEventListener('submit', function(e) {
    const tipoSelect = document.getElementById('nombre');
    const valorInput = document.getElementById('valor');
    const valor = parseFloat(valorInput.value);
    const tipo = tipoSelect.value;

    if ((tipo === 'comision' || tipo === 'maximo' || tipo === 'numero_galones') && valor > 100) {
        e.preventDefault();
        alert('⚠️ El valor no puede ser mayor a 100 para este tipo de configuración');
        return false;
    }

    if (valor < 0) {
        e.preventDefault();
        alert('⚠️ El valor no puede ser negativo');
        return false;
    }
});
</script>
@endsection