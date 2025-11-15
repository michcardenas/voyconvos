@extends('layouts.app_dashboard')

@section('title', 'Planifica tu viaje')

@section('content')
<style>
    :root {
        --primary: #003366;
        --success: #00C853;
        --danger: #FF1744;
        --warning: #FFC107;
        --light: #f5f7fa;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Segoe UI', system-ui, sans-serif;
        background: var(--light);
    }

    .container-mapa {
        max-width: 1400px;
        margin: 0 auto;
        padding: 2rem 1.5rem;
        width: 100%;
        overflow-x: hidden;
    }

    .page-header {
        background: linear-gradient(135deg, #003366 0%, #0066CC 100%);
        padding: 2.5rem 2rem;
        border-radius: 20px;
        margin-bottom: 2rem;
        text-align: center;
        box-shadow: 0 8px 32px rgba(0, 51, 102, 0.15);
    }

    .page-header h2 {
        color: white;
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .page-subtitle {
        color: rgba(255, 255, 255, 0.9);
        font-size: 1rem;
    }

    .search-panel {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 8px 32px rgba(0, 51, 102, 0.1);
        margin-bottom: 2rem;
    }

    .search-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
        margin-bottom: 1rem;
    }

    .search-item label {
        display: block;
        font-weight: 600;
        color: var(--primary);
        margin-bottom: 0.75rem;
        font-size: 0.95rem;
    }

    .search-input {
        width: 100%;
        padding: 1rem 1.25rem;
        font-size: 1rem;
        border: 2px solid #e0e7ff;
        border-radius: 12px;
        background: #f8fafc;
        transition: all 0.3s ease;
    }

    .search-input:focus {
        outline: none;
        border-color: #00BFFF;
        background: white;
        box-shadow: 0 0 0 4px rgba(0, 191, 255, 0.1);
    }

    /* Sección de paradas */
    .paradas-section {
        margin-top: 1.5rem;
        padding-top: 1.5rem;
        border-top: 2px dashed #e0e7ff;
    }

    .paradas-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .paradas-title {
        font-weight: 600;
        color: var(--primary);
        font-size: 1rem;
    }

    .btn-add-parada {
        background: linear-gradient(135deg, var(--warning) 0%, #FFD54F 100%);
        color: #000;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        font-weight: 600;
        cursor: pointer;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(255, 193, 7, 0.3);
    }

    .btn-add-parada:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(255, 193, 7, 0.4);
    }

    .paradas-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .parada-item {
        background: linear-gradient(135deg, #fff8e1 0%, #ffecb3 100%);
        border: 2px solid #ffe082;
        border-radius: 12px;
        padding: 1rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        animation: slideIn 0.3s ease;
    }

    .parada-number {
        background: var(--warning);
        color: #000;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.9rem;
        flex-shrink: 0;
    }

    .parada-input-wrapper {
        flex: 1;
    }

    .parada-input {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 2px solid #ffe082;
        border-radius: 10px;
        background: white;
        font-size: 0.95rem;
    }

    .parada-input:focus {
        outline: none;
        border-color: var(--warning);
        box-shadow: 0 0 0 3px rgba(255, 193, 7, 0.1);
    }

    .btn-remove-parada {
        background: var(--danger);
        color: white;
        border: none;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        cursor: pointer;
        font-size: 1.2rem;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        flex-shrink: 0;
    }

    .btn-remove-parada:hover {
        transform: scale(1.1);
        box-shadow: 0 4px 12px rgba(255, 23, 68, 0.3);
    }

    /* Sección de programación del viaje */
    .trip-details {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 8px 32px rgba(0, 51, 102, 0.1);
        margin-bottom: 2rem;
    }

    .trip-details-title {
        font-weight: 600;
        color: var(--primary);
        font-size: 1.1rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .trip-options {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
    }

    .trip-option-item {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .trip-option-item label {
        font-weight: 600;
        color: var(--primary);
        font-size: 0.95rem;
    }

    .trip-input {
        width: 100%;
        padding: 0.75rem 1rem;
        font-size: 1rem;
        border: 2px solid #e0e7ff;
        border-radius: 10px;
        background: #f8fafc;
        transition: all 0.3s ease;
    }

    .trip-input:focus {
        outline: none;
        border-color: #00BFFF;
        background: white;
        box-shadow: 0 0 0 3px rgba(0, 191, 255, 0.1);
    }

    /* Forzar formato de 24 horas en inputs de tiempo */
    input[type="time"]::-webkit-calendar-picker-indicator {
        filter: invert(0.5);
    }

    input[type="time"] {
        -webkit-appearance: textfield;
        -moz-appearance: textfield;
    }

    /* Modal de éxito */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(5px);
        z-index: 9999;
        animation: fadeIn 0.3s ease;
    }

    .modal-overlay.show {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .modal-success {
        background: white;
        border-radius: 24px;
        padding: 3rem 2.5rem;
        max-width: 500px;
        width: 90%;
        box-shadow: 0 20px 60px rgba(0, 51, 102, 0.3);
        animation: slideUp 0.4s ease;
        text-align: center;
    }

    .modal-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #00C853 0%, #00E676 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        font-size: 3rem;
        animation: bounce 0.6s ease;
    }

    .modal-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 1rem;
    }

    .modal-message {
        font-size: 1.1rem;
        color: #64748b;
        line-height: 1.6;
        margin-bottom: 2rem;
    }

    .modal-button {
        background: linear-gradient(135deg, #003366 0%, #0066CC 100%);
        color: white;
        border: none;
        padding: 1rem 2.5rem;
        border-radius: 12px;
        font-size: 1.1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(0, 51, 102, 0.2);
    }

    .modal-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0, 51, 102, 0.3);
    }

    .modal-error {
        background: white;
        border-radius: 24px;
        padding: 3rem 2.5rem;
        max-width: 500px;
        width: 90%;
        box-shadow: 0 20px 60px rgba(255, 23, 68, 0.3);
        animation: slideUp 0.4s ease;
        text-align: center;
    }

    .modal-icon-error {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #FF1744 0%, #FF5252 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        font-size: 3rem;
        animation: shake 0.5s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes bounce {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.1); }
    }

    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-10px); }
        75% { transform: translateX(10px); }
    }

    .checkbox-container {
        display: flex;
        align-items: center;
        gap: 0.70rem;
        padding: 0.75rem;
        background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
        border-radius: 12px;
        border: 2px solid #bae6fd;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .checkbox-container:hover {
        border-color: #00BFFF;
        background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%);
    }

    .checkbox-container input[type="checkbox"] {
        width: 22px;
        height: 22px;
        cursor: pointer;
        accent-color: var(--primary);
    }

    .checkbox-label {
        font-weight: 600;
        color: var(--primary);
        font-size: 1rem;
        cursor: pointer;
        user-select: none;
    }

    .return-section {
        display: none;
        margin-top: 1rem;
        padding: 1rem;
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        border-radius: 12px;
        border: 2px solid #fcd34d;
        animation: slideIn 0.3s ease;
    }

    .return-section.show {
        display: block;
    }

    .return-label {
        font-weight: 600;
        color: #92400e;
        font-size: 0.95rem;
        margin-bottom: 0.5rem;
        display: block;
    }

    .map-container {
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 8px 32px rgba(0, 51, 102, 0.1);
        margin-bottom: 2rem;
    }

    #map {
        width: 100%;
        height: 600px;
    }

    .route-info {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 8px 32px rgba(0, 51, 102, 0.1);
        display: none;
    }

    .route-info.show {
        display: block;
        animation: slideUp 0.4s ease;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
    }

    .info-item {
        text-align: center;
        padding: 1.5rem;
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-radius: 16px;
        border: 2px solid #e0e7ff;
    }

    .info-icon {
        font-size: 2rem;
        margin-bottom: 0.5rem;
    }

    .info-label {
        font-size: 0.85rem;
        color: #64748b;
        font-weight: 500;
        margin-bottom: 0.5rem;
        text-transform: uppercase;
    }

    .info-value {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--primary);
    }

    .status-badge {
        display: inline-block;
        padding: 0.75rem 1.5rem;
        background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.9rem;
        color: #15803d;
        margin-bottom: 2rem;
        box-shadow: 0 4px 12px rgba(0, 200, 83, 0.2);
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateX(-20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @media (max-width: 768px) {
        .container-mapa {
            padding: 1rem;
        }

        .page-header {
            padding: 1.5rem 1rem;
            margin-bottom: 1.5rem;
        }

        .page-header h2 {
            font-size: 1.5rem;
        }

        .search-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .search-panel,
        .trip-details,
        .route-info {
            padding: 1.5rem;
        }

        #map {
            height: 400px;
        }

        .paradas-header {
            flex-direction: column;
            align-items: stretch;
            gap: 1rem;
        }

        .btn-add-parada {
            width: 100%;
        }

        .trip-options {
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .info-grid {
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .info-item {
            padding: 1rem;
        }

        .info-value {
            font-size: 1.25rem;
        }
    }

    /* Resultados del cálculo */
.calculation-results {
    background: white;
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 8px 32px rgba(0, 51, 102, 0.1);
    margin-top: 2rem;
    animation: fadeIn 0.5s ease;
}

.results-header h3 {
    color: var(--primary);
    font-size: 1.3rem;
    margin-bottom: 1.5rem;
    text-align: center;
}

.results-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.result-item {
    background: linear-gradient(135deg, #f8fafc 0%, #e0e7ff 100%);
    border: 2px solid #cbd5e1;
    border-radius: 12px;
    padding: 1rem;
    text-align: center;
    transition: all 0.3s ease;
}

.result-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 51, 102, 0.15);
}

.result-item.tarifa-input-item {
    background: linear-gradient(135deg, #fff8e1 0%, #ffecb3 100%);
    border-color: #ffe082;
}

.result-item.commission {
    background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
    border-color: #90caf9;
}

.result-item.final {
    background: linear-gradient(135deg, #00C853 0%, #69F0AE 100%);
    border-color: #00E676;
    grid-column: span 2;
}

.result-label {
    font-size: 0.9rem;
    color: #475569;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.result-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--primary);
}

.result-value-final {
    font-size: 2.2rem;
    font-weight: 700;
    color: white;
}

.result-input {
    width: 100%;
    padding: 0.75rem;
    font-size: 1.3rem;
    border: 2px solid #cbd5e1;
    border-radius: 8px;
    text-align: center;
    font-weight: 600;
    margin-top: 0.5rem;
    background: white;
}

.result-input:focus {
    outline: none;
    border-color: #FFC107;
    box-shadow: 0 0 0 3px rgba(255, 193, 7, 0.2);
}

.calculation-note {
    background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
    border-left: 4px solid #00BFFF;
    border-radius: 8px;
    padding: 1rem 1.5rem;
    margin-top: 1rem;
}

.calculation-note p {
    margin: 0;
    color: #334155;
    font-size: 0.9rem;
    line-height: 1.6;
}

@media (max-width: 768px) {
    .calculation-results {
        padding: 1.5rem;
        margin-top: 1.5rem;
    }

    .results-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }

    .result-item.suggested {
        grid-column: span 1 !important;
    }

    .result-item.final {
        grid-column: span 1;
    }

    .result-value {
        font-size: 1.25rem;
    }

    .result-value-final {
        font-size: 1.75rem;
    }
}

@media (max-width: 480px) {
    .container-mapa {
        padding: 0.75rem;
    }

    .page-header h2 {
        font-size: 1.25rem;
    }

    .page-subtitle {
        font-size: 0.875rem;
    }

    .search-input,
    .trip-input {
        padding: 0.75rem 1rem;
        font-size: 0.9rem;
    }

    #map {
        height: 350px;
    }

    .info-grid {
        grid-template-columns: 1fr;
    }

    #btn-guardar-viaje {
        padding: 1rem 2rem;
        font-size: 1rem;
        width: 100%;
    }
}
   .result-item.suggested {
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
    border-color: #fcd34d;
}

.result-item.max-allowed {
    background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
    border-color: #fca5a5;
}

/* Input de valor manual */
#valor_viaje_manual {
    transition: all 0.3s ease;
}

#valor_viaje_manual:focus {
    outline: none;
    box-shadow: 0 0 0 4px rgba(252, 211, 77, 0.3);
    transform: scale(1.02);
}

#mensaje-validacion {
    animation: slideIn 0.3s ease;
}

.calculation-note ul {
    list-style-type: none;
    padding-left: 0;
}

.calculation-note ul li {
    padding-left: 1.5rem;
    position: relative;
}

.calculation-note ul li:before {
    content: "✓";
    position: absolute;
    left: 0;
    color: #00BFFF;
    font-weight: bold;
}

.ganancia-info {
    margin-top: 1rem;
    animation: slideIn 0.3s ease;
} 
/* Estilo para selectores de hora */
.hora-select {
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23003366' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 1rem center;
    background-size: 12px;
    padding-right: 2.5rem;
    cursor: pointer;
    font-weight: 600;
    color: var(--primary);
}

.hora-select:hover {
    border-color: #00BFFF;
}

.hora-select option {
    padding: 0.5rem;
    font-size: 1rem;
}

.hora-select:focus {
    outline: none;
    border-color: #00BFFF;
    background-color: white;
    box-shadow: 0 0 0 4px rgba(0, 191, 255, 0.1);
}

/* Eliminar flecha por defecto en IE */
.hora-select::-ms-expand {
    display: none;
}
</style>

<div class="container-mapa">
    <div class="page-header">
        <h2>🗺️ Planifica tu viaje</h2>
        <p class="page-subtitle">Buenos Aires, Argentina</p>
    </div>
 <div class="trip-details">
        <div class="trip-details-title">📅 Programación del viaje</div>
       <div class="trip-options">
    <div class="trip-option-item">
        <label>📅 Fecha del viaje</label>
        <input type="date" id="fecha_viaje" class="trip-input" min="" required>
    </div>
    
     <div class="trip-option-item">
        <label>🕐 Hora de salida</label>
        <select id="hora_salida" class="trip-input hora-select" required>
            <option value="">Selecciona una hora</option>
        </select>
    </div>
    
    <div class="trip-option-item">
        <label style="opacity: 0; pointer-events: none;">Espacio</label> <!-- Label invisible para alineación -->
        <div class="checkbox-container" onclick="toggleIdaVuelta()">
            <input type="checkbox" id="ida_vuelta" onchange="toggleIdaVuelta()">
            <label class="checkbox-label" for="ida_vuelta">🔄 Viaje ida y vuelta</label>
        </div>
    </div>
</div>

<!-- Sección de regreso fuera del grid -->
<!-- Hora de regreso -->
<div id="return-section" class="return-section" style="margin-top: 1.5rem;">
    <label class="return-label">🕐 Hora de regreso</label>
    <select id="hora_regreso" class="trip-input hora-select">
        <option value="">Selecciona una hora</option>
    </select>
</div>
    </div>
    <center>
        <div class="status-badge" id="status">
            📍 Haz clic en el mapa para el origen
        </div>
    </center>

    <div class="search-panel">
        <div class="search-grid">
            <div class="search-item">
                <label>📍 Punto de partida</label>
                <input type="text" id="origen_input" class="search-input" placeholder="Busca o haz clic en el mapa">
            </div>
            <div class="search-item">
                <label>🎯 Destino final</label>
                <input type="text" id="destino_input" class="search-input" placeholder="Busca o haz clic en el mapa">
            </div>
        </div>

        <!-- Sección de paradas intermedias -->
        <div class="paradas-section">
            <div class="paradas-header">
                <span class="paradas-title">🛑 Paradas intermedias</span>
                <button class="btn-add-parada" onclick="agregarNuevaParada()">
                    ➕ Agregar parada
                </button>
            </div>
            <div id="paradas-list" class="paradas-list">
                <!-- Las paradas se agregarán aquí dinámicamente -->
            </div>
        </div>
    </div>

    <!-- Sección de programación del viaje -->
   

    <div class="map-container">
        <div id="map"></div>
    </div>

    <div class="route-info" id="route-info">
        <div class="info-grid">
            <div class="info-item">
                <div class="info-icon">📏</div>
                <div class="info-label">Distancia</div>
                <div class="info-value" id="distancia">-</div>
            </div>
            <div class="info-item">
                <div class="info-icon">⏱️</div>
                <div class="info-label">Tiempo</div>
                <div class="info-value" id="tiempo">-</div>
            </div>
            <div class="info-item">
                <div class="info-icon">🛑</div>
                <div class="info-label">Paradas</div>
                <div class="info-value" id="num-paradas">0</div>
            </div>
            <div class="info-item" id="fecha-programada-card" style="display: none;">
                <div class="info-icon">📅</div>
                <div class="info-label">Programado</div>
                <div class="info-value" style="font-size: 1rem;" id="fecha-programada">-</div>
            </div>
        </div>
    </div>

    <input type="hidden" id="origen_coords">
    <input type="hidden" id="destino_coords">
    <input type="hidden" id="origen_direccion">
    <input type="hidden" id="destino_direccion">
    <input type="hidden" id="distancia_km">
    <input type="hidden" id="tiempo_estimado">
    <input type="hidden" id="es_ida_vuelta">
    <input type="hidden" id="fecha_programada">
    <input type="hidden" id="hora_salida_programada">
    <input type="hidden" id="hora_regreso_programada">
</div>
<!-- Sección de Resultados del Cálculo -->
<!-- Sección de Resultados del Cálculo -->
<div class="calculation-results" id="calculation-results" style="display: none;">
    <div class="results-header">
        <h3>📊 Cálculo Automático de Tarifa</h3>
    </div>

    <div class="results-grid">
        <div class="result-item">
            <div class="result-label">📏 Distancia total</div>
            <div class="result-value" id="calc-distancia">-- km</div>
        </div>

        <div class="result-item">
            <div class="result-label">⏱️ Tiempo estimado</div>
            <div class="result-value" id="calc-tiempo">-- min</div>
        </div>

        <div class="result-item suggested" hidden>
            <div class="result-label">💡 Tarifa mínima sugerida</div>
            <div class="result-value" id="calc-minimo" style="color: #059669;">ARS $0.00</div>
        </div>

        <div class="result-item max-allowed">
            <div class="result-label">⚠️ Máximo permitido</div>
            <div class="result-value" id="calc-maximo" style="color: #dc2626;">ARS $0.00</div>
        </div>
    </div>

    <!-- Input para establecer el valor del viaje -->
    <div style="margin-top: 2rem; padding: 1.5rem; background: linear-gradient(135deg, #fff8e1 0%, #ffecb3 100%); border-radius: 16px; border: 2px solid #fcd34d;">
        <h4 style="color: #92400e; margin-bottom: 1rem; text-align: center; font-size: 1.1rem;">💰 Establece el valor total del viaje</h4>

        <div style="max-width: 400px; margin: 0 auto;">
            <label style="display: block; font-weight: 600; color: #92400e; margin-bottom: 0.5rem; font-size: 0.95rem; text-align: center;">
                Precio sugerido (puedes modificarlo)
            </label>
            <input type="text"
                   id="valor_viaje_manual"
                   class="result-input"
                   placeholder="$0"
                   oninput="formatearInputValor(this)"
                   readonly
                   onfocus="this.removeAttribute('readonly')"
                   style="font-size: 2rem; padding: 1rem; text-align: center; border: 3px solid #fcd34d;">

            <div id="mensaje-validacion" style="margin-top: 1rem; padding: 0.75rem; border-radius: 8px; text-align: center; font-weight: 600; display: none;"></div>

            <div class="rango-container" style="margin-top: 1rem; display: flex; gap: 0.5rem; justify-content: center; font-size: 0.85rem; flex-wrap: wrap;">
                <span style="color: #64748b;">
                    Rango válido:
                    <strong style="color: #059669;" id="rango-minimo">ARS $0.00</strong> -
                    <strong style="color: #dc2626;" id="rango-maximo">ARS $0.00</strong>
                </span>
            </div>
        </div>
    </div>

    <!-- Sección de puestos y cálculo por pasajero -->
    <div class="puestos-section" style="margin-top: 2rem; padding: 1.5rem; background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%); border-radius: 16px; border: 2px solid #bae6fd;">
        <h4 style="color: var(--primary); margin-bottom: 1.5rem; text-align: center; font-size: 1.1rem;">👥 Distribución de puestos</h4>

        <div class="puestos-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
            <div style="background: white; padding: 1rem; border-radius: 12px; text-align: center;">
                <label style="display: block; font-weight: 600; color: #64748b; margin-bottom: 0.5rem; font-size: 0.9rem;">🚗 Puestos Disponibles del vehículo </label>
                <input type="number"
                       id="puestos_totales"
                       value="{{ $numero_puestos ?? 0 }}"
                       readonly
                       style="width: 100%; padding: 0.75rem; font-size: 1.5rem; font-weight: 700; color: var(--primary); text-align: center; border: 2px solid #cbd5e1; border-radius: 8px; background: #f8fafc; cursor: not-allowed;">
            </div>

            <div style="background: white; padding: 1rem; border-radius: 12px; text-align: center;">
                <label style="display: block; font-weight: 600; color: #64748b; margin-bottom: 0.5rem; font-size: 0.9rem;">✅ Puestos disponibles en este viaje</label>
                <input type="number"
                       id="puestos_disponibles"
                       min="1"
                       max="{{ $numero_puestos ?? 0 }}"
                       value="{{ $numero_puestos ?? 0 }}"
                       oninput="calcularPorPasajero()"
                       style="width: 100%; padding: 0.75rem; font-size: 1.5rem; font-weight: 700; color: #0066CC; text-align: center; border: 2px solid #00BFFF; border-radius: 8px; background: white;">
            </div>
        </div>

        <div class="totales-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div style="background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); padding: 1.25rem; border-radius: 12px; text-align: center; border: 2px solid #fcd34d;">
                <div style="color: #92400e; font-size: 0.85rem; font-weight: 600; margin-bottom: 0.5rem;">💰 TOTAL DEL VIAJE</div>
                <div style="color: #92400e; font-size: 2rem; font-weight: 700;" id="total-viaje">$--</div>
            </div>

            <div style="background: linear-gradient(135deg, #00C853 0%, #69F0AE 100%); padding: 1.25rem; border-radius: 12px; text-align: center; border: 2px solid #00E676;">
                <div style="color: white; font-size: 0.85rem; font-weight: 600; margin-bottom: 0.5rem;">💵 PRECIO POR PASAJERO</div>
                <div style="color: white; font-size: 2rem; font-weight: 700;" id="precio-por-pasajero">$--</div>
            </div>
        </div>
    </div>

    <!-- <div class="calculation-note">
        <p><strong>ℹ️ Cómo funciona el sistema de tarifas:</strong></p>
        <ul style="margin: 0.5rem 0 0 1.5rem; line-height: 1.8;">
            <li><strong>Paso 1:</strong> El sistema calcula el costo operativo (Distancia × $0.30/km)</li>
            <li><strong>Paso 2:</strong> Se suma la comisión de plataforma ({{ $comision_plataforma ?? 0 }}%)</li>
            <li><strong>Paso 3:</strong> Se establece la <strong>tarifa mínima</strong> (costo + comisión)</li>
            <li><strong>Paso 4:</strong> Se calcula la <strong>tarifa máxima permitida</strong> (+{{ $maximo_ganancia ?? 30 }}%)</li>
            <li><strong>Paso 5:</strong> Tú decides el precio final dentro del rango permitido</li>
            <li><strong>Paso 6:</strong> El sistema divide automáticamente entre los puestos disponibles</li>
        </ul>
        <p style="margin-top: 1rem; padding-top: 1rem; border-top: 2px solid #bae6fd;">
            <strong>💡 Recomendación:</strong> Puedes ajustar el precio según la demanda, condiciones del viaje, o gastos adicionales (peajes, estacionamiento, etc.),
            siempre dentro del rango establecido para garantizar precios justos tanto para ti como para los pasajeros.
        </p>
    </div> -->

    <!-- Botón para guardar el viaje -->
    <div style="margin-top: 2rem; text-align: center;">
        <button type="button"
                id="btn-guardar-viaje"
                onclick="guardarViaje()"
                style="background: linear-gradient(135deg, #003366 0%, #0066CC 100%);
                       color: white;
                       border: none;
                       padding: 1.25rem 3rem;
                       font-size: 1.2rem;
                       font-weight: 700;
                       border-radius: 16px;
                       cursor: pointer;
                       box-shadow: 0 8px 24px rgba(0, 51, 102, 0.3);
                       transition: all 0.3s ease;
                       display: inline-flex;
                       align-items: center;
                       gap: 0.75rem;">
            <span style="font-size: 1.5rem;">🚀</span>
            PUBLICAR VIAJE
        </button>
        <p style="margin-top: 1rem; color: #64748b; font-size: 0.9rem;">
            Al publicar, tu viaje estará disponible para que los pasajeros reserven
        </p>
    </div>
</div>

<style>
#btn-guardar-viaje:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 32px rgba(0, 51, 102, 0.4);
}

#btn-guardar-viaje:active {
    transform: translateY(-1px);
}

#btn-guardar-viaje:disabled {
    background: linear-gradient(135deg, #94a3b8 0%, #cbd5e1 100%);
    cursor: not-allowed;
    box-shadow: none;
    transform: none;
}

/* Estilos responsive adicionales */
@media (max-width: 768px) {
    .puestos-section {
        padding: 1rem !important;
    }

    .puestos-grid,
    .totales-grid {
        grid-template-columns: 1fr !important;
    }

    .puestos-section h4 {
        font-size: 1rem !important;
    }

    .totales-grid > div {
        padding: 1rem !important;
    }

    .totales-grid > div > div:last-child {
        font-size: 1.5rem !important;
    }
}

@media (max-width: 480px) {
    .puestos-section input[type="number"] {
        font-size: 1.25rem !important;
    }

    #valor_viaje_manual {
        font-size: 1.5rem !important;
        padding: 0.75rem !important;
    }

    .result-input {
        font-size: 1.25rem !important;
    }

    .rango-container {
        font-size: 0.75rem !important;
        padding: 0 0.5rem;
    }

    .rango-container span {
        text-align: center;
        display: block;
    }
}

/* Asegurar que los elementos no se salgan en pantallas muy pequeñas */
@media (max-width: 360px) {
    .container-mapa {
        padding: 0.5rem;
    }

    .page-header h2 {
        font-size: 1.1rem;
    }

    .search-panel,
    .trip-details,
    .route-info,
    .calculation-results,
    .puestos-section {
        padding: 1rem !important;
    }

    #valor_viaje_manual {
        font-size: 1.25rem !important;
    }

    .totales-grid > div > div:last-child {
        font-size: 1.25rem !important;
    }
}
</style>
<script>
// ========================================
// 🌍 VARIABLES GLOBALES
// ========================================
let map, directionsService, directionsRenderer, geocoder;
let origenMarker = null;
let destinoMarker = null;
let paradas = [];
let paso = 'origen';
let paradaCounter = 0;
let paradaEnEspera = null;

// Iconos de marcadores
let iconoVerde, iconoRojo, iconoAmarillo;

// Rutas alternativas
let rutasDisponibles = [];
let rutaSeleccionada = 0;

// Configuración del servidor
const CONFIG = {
    comisionPlataforma: {{ $costo_mantenimiento ?? 15 }},
    maximoGanancia: {{ $maximo_ganancia ?? 30 }},
    costoPorKm: {{ $costo_por_km ?? 250 }},
    costoCombustible: {{ $costo_combustible ?? 1500 }},
    numeroGalones: {{ $numero_galones ?? 50 }},
    kmPorGalon: 10
};

// Tarifas calculadas
let tarifaMinima = 0;
let tarifaMaxima = 0;

// ========================================
// 💰 FUNCIONES DE FORMATEO DE MONEDA
// ========================================
function formatearMoneda(valor) {
    return new Intl.NumberFormat('es-AR', {
        style: 'currency',
        currency: 'ARS',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    }).format(valor);
}

function formatearNumero(valor) {
    return new Intl.NumberFormat('es-AR', {
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    }).format(valor);
}

function desformatearNumero(valorFormateado) {
    return parseFloat(valorFormateado.replace(/\./g, '').replace(/,/g, '.')) || 0;
}

function formatearInputValor(input) {
    let valor = input.value.replace(/\./g, '').replace(/[^0-9]/g, '');
    
    if (valor === '') {
        input.value = '';
        validarValorViaje();
        return;
    }
    
    input.value = formatearNumero(parseInt(valor));
    validarValorViaje();
}

// ========================================
// 🕐 FUNCIONES DE FORMATO HORA 24HRS
// ========================================
function configurarInputsHora() {
    const inputsHora = document.querySelectorAll('.hora-input');
    
    inputsHora.forEach(input => {
        // Aplicar máscara mientras escribe
        input.addEventListener('input', function(e) {
            aplicarMascaraHora(e.target);
        });

        // Validar al perder el foco
        input.addEventListener('blur', function(e) {
            validarFormatoHora(e.target);
        });

        // Permitir solo números y ":"
        input.addEventListener('keypress', function(e) {
            const char = e.key;
            const valor = e.target.value;
            
            if (e.ctrlKey || e.metaKey || ['Backspace', 'Delete', 'ArrowLeft', 'ArrowRight', 'Tab'].includes(char)) {
                return;
            }
            
            if (!/[0-9:]/.test(char)) {
                e.preventDefault();
                return;
            }
            
            if (char === ':' && valor.includes(':')) {
                e.preventDefault();
            }
        });

        // Autocompletar con Tab o Enter
        input.addEventListener('keydown', function(e) {
            if (['Tab', 'Enter'].includes(e.key)) {
                validarFormatoHora(e.target);
            }
        });
    });
}

function aplicarMascaraHora(input) {
    let valor = input.value.replace(/[^0-9]/g, '');
    
    if (valor.length >= 2) {
        valor = valor.substring(0, 2) + ':' + valor.substring(2, 4);
    }
    
    input.value = valor;
    
    if (valor.length === 5) {
        validarFormatoHora(input);
    }
}

function validarFormatoHora(input) {
    const valor = input.value.trim();
    
    // Si está vacío
    if (valor === '') {
        input.classList.remove('valido', 'invalido');
        return !input.required;
    }
    
    // Verificar formato HH:MM
    const regex = /^([0-1][0-9]|2[0-3]):([0-5][0-9])$/;
    
    if (!regex.test(valor)) {
        // Intentar autocompletar
        if (/^[0-9]{1,4}$/.test(valor)) {
            autocompletarHora(input);
            return;
        }
        
        input.classList.remove('valido');
        input.classList.add('invalido');
        mostrarErrorHora(input, '⚠️ Formato inválido. Use HH:MM en formato 24 horas (00:00 - 23:59)');
        return false;
    }
    
    // Validar rangos
    const [horas, minutos] = valor.split(':').map(Number);
    
    if (horas > 23 || minutos > 59) {
        input.classList.remove('valido');
        input.classList.add('invalido');
        mostrarErrorHora(input, '⚠️ Hora inválida. Horas: 00-23, Minutos: 00-59');
        return false;
    }
    
    // Válido
    input.classList.remove('invalido');
    input.classList.add('valido');
    input.value = String(horas).padStart(2, '0') + ':' + String(minutos).padStart(2, '0');
    
    return true;
}

function autocompletarHora(input) {
    let valor = input.value.replace(/[^0-9]/g, '');
    
    if (valor.length === 1 || valor.length === 2) {
        const horas = parseInt(valor);
        if (horas <= 23) {
            input.value = String(horas).padStart(2, '0') + ':00';
            validarFormatoHora(input);
            return;
        }
    }
    
    if (valor.length === 3) {
        const horas = parseInt(valor[0]);
        const minutos = parseInt(valor.substring(1));
        if (horas <= 9 && minutos <= 59) {
            input.value = '0' + horas + ':' + String(minutos).padStart(2, '0');
            validarFormatoHora(input);
            return;
        }
    }
    
    if (valor.length === 4) {
        const horas = parseInt(valor.substring(0, 2));
        const minutos = parseInt(valor.substring(2));
        if (horas <= 23 && minutos <= 59) {
            input.value = String(horas).padStart(2, '0') + ':' + String(minutos).padStart(2, '0');
            validarFormatoHora(input);
            return;
        }
    }
}

function mostrarErrorHora(input, mensaje) {
    const mensajeAnterior = input.parentElement.querySelector('.error-hora');
    if (mensajeAnterior) mensajeAnterior.remove();
    
    const errorDiv = document.createElement('div');
    errorDiv.className = 'error-hora';
    errorDiv.style.cssText = `
        color: #dc2626;
        font-size: 0.85rem;
        margin-top: 0.5rem;
        padding: 0.5rem 0.75rem;
        background: #fef2f2;
        border-left: 3px solid #dc2626;
        border-radius: 4px;
        animation: slideIn 0.3s ease;
    `;
    errorDiv.textContent = mensaje;
    
    input.parentElement.appendChild(errorDiv);
    
    setTimeout(() => {
        if (errorDiv.parentElement) {
            errorDiv.style.opacity = '0';
            errorDiv.style.transition = 'opacity 0.3s ease';
            setTimeout(() => errorDiv.remove(), 300);
        }
    }, 5000);
}

// ========================================
// 🗺️ INICIALIZACIÓN DEL MAPA
// ========================================
function initMap() {
    console.log('🗺️ Iniciando mapa...');
    
    // Definir iconos
    iconoVerde = {
        path: google.maps.SymbolPath.CIRCLE,
        fillColor: '#00C853',
        fillOpacity: 1,
        strokeColor: '#ffffff',
        strokeWeight: 4,
        scale: 12
    };

    iconoRojo = {
        path: google.maps.SymbolPath.CIRCLE,
        fillColor: '#FF1744',
        fillOpacity: 1,
        strokeColor: '#ffffff',
        strokeWeight: 4,
        scale: 12
    };

    iconoAmarillo = {
        path: google.maps.SymbolPath.CIRCLE,
        fillColor: '#FFC107',
        fillOpacity: 1,
        strokeColor: '#ffffff',
        strokeWeight: 4,
        scale: 10
    };
    
    // Crear mapa centrado en Buenos Aires
    map = new google.maps.Map(document.getElementById('map'), {
        zoom: 12,
        center: { lat: -34.6037, lng: -58.3816 },
        mapTypeControl: false,
        streetViewControl: false
    });

    // Inicializar servicios
    directionsService = new google.maps.DirectionsService();
    directionsRenderer = new google.maps.DirectionsRenderer({
        map: map,
        suppressMarkers: true,
        polylineOptions: {
            strokeColor: '#003366',
            strokeWeight: 5
        }
    });
    geocoder = new google.maps.Geocoder();

    // Configurar autocompletado
    configurarAutocompletado();

    // Listener para clicks en el mapa
    map.addListener('click', manejarClickMapa);

    console.log('✅ Mapa listo');
}

function configurarAutocompletado() {
    const origenAuto = new google.maps.places.Autocomplete(
        document.getElementById('origen_input'),
        { componentRestrictions: { country: 'ar' } }
    );

    const destinoAuto = new google.maps.places.Autocomplete(
        document.getElementById('destino_input'),
        { componentRestrictions: { country: 'ar' } }
    );

    origenAuto.addListener('place_changed', () => {
        const place = origenAuto.getPlace();
        if (place.geometry) ponerOrigen(place.geometry.location);
    });

    destinoAuto.addListener('place_changed', () => {
        const place = destinoAuto.getPlace();
        if (place.geometry) ponerDestino(place.geometry.location);
    });
}

function manejarClickMapa(e) {
    console.log('🖱️ Click en mapa, paso:', paso);
    
    if (paso === 'origen') {
        ponerOrigen(e.latLng);
    } else if (paso === 'destino') {
        ponerDestino(e.latLng);
    } else if (paradaEnEspera) {
        colocarParadaEnMapa(e.latLng, paradaEnEspera);
    }
}

// ========================================
// 📍 FUNCIONES DE ORIGEN Y DESTINO
// ========================================
function ponerOrigen(location) {
    console.log('📍 Poniendo origen');
    
    if (origenMarker) origenMarker.setMap(null);

    origenMarker = new google.maps.Marker({
        position: location,
        map: map,
        icon: iconoVerde,
        draggable: true,
        title: 'Origen',
        animation: google.maps.Animation.DROP
    });

    origenMarker.addListener('dragend', (e) => {
        actualizarOrigen(e.latLng);
        calcularRuta();
    });

    actualizarOrigen(location);
    paso = 'destino';
    document.getElementById('status').textContent = '🎯 Ahora haz clic para el destino';
    calcularRuta();
}

function ponerDestino(location) {
    console.log('🎯 Poniendo destino');
    
    if (destinoMarker) destinoMarker.setMap(null);

    destinoMarker = new google.maps.Marker({
        position: location,
        map: map,
        icon: iconoRojo,
        draggable: true,
        title: 'Destino',
        animation: google.maps.Animation.DROP
    });

    destinoMarker.addListener('dragend', (e) => {
        actualizarDestino(e.latLng);
        calcularRuta();
    });

    actualizarDestino(location);
    paso = 'listo';
    document.getElementById('status').textContent = '✅ ¡Ruta lista! Puedes agregar paradas';
    calcularRuta();
}

function actualizarOrigen(location) {
    document.getElementById('origen_coords').value = `${location.lat()},${location.lng()}`;
    
    geocoder.geocode({ location: location }, (results, status) => {
        if (status === 'OK' && results[0]) {
            document.getElementById('origen_input').value = results[0].formatted_address;
            document.getElementById('origen_direccion').value = results[0].formatted_address;
        }
    });
}

function actualizarDestino(location) {
    document.getElementById('destino_coords').value = `${location.lat()},${location.lng()}`;
    
    geocoder.geocode({ location: location }, (results, status) => {
        if (status === 'OK' && results[0]) {
            document.getElementById('destino_input').value = results[0].formatted_address;
            document.getElementById('destino_direccion').value = results[0].formatted_address;
        }
    });
}

// ========================================
// 🛑 FUNCIONES DE PARADAS INTERMEDIAS
// ========================================
function agregarNuevaParada() {
    if (!origenMarker || !destinoMarker) {
        showModal('error', 'Atención', 'Por favor, primero selecciona el origen y destino');
        return;
    }
    
    paradaCounter++;
    const paradaId = `parada_${paradaCounter}`;
    
    const paradaHTML = `
        <div class="parada-item" id="${paradaId}">
            <div class="parada-number">${paradaCounter}</div>
            <div class="parada-input-wrapper">
                <input type="text" 
                       class="parada-input" 
                       id="${paradaId}_input" 
                       placeholder="🔍 Busca una dirección o haz clic en el mapa">
            </div>
            <button class="btn-remove-parada" onclick="eliminarParada('${paradaId}')">✕</button>
        </div>
    `;
    
    document.getElementById('paradas-list').insertAdjacentHTML('beforeend', paradaHTML);
    
    // Configurar autocompletado para la nueva parada
    const input = document.getElementById(`${paradaId}_input`);
    const autocomplete = new google.maps.places.Autocomplete(input, {
        componentRestrictions: { country: 'ar' }
    });
    
    autocomplete.addListener('place_changed', () => {
        const place = autocomplete.getPlace();
        if (place.geometry) {
            colocarParadaEnMapa(place.geometry.location, paradaId);
            paradaEnEspera = null;
            document.getElementById('status').textContent = '✅ Parada agregada. Puedes agregar más';
            document.body.style.cursor = 'default';
        }
    });
    
    paradaEnEspera = paradaId;
    document.getElementById('status').textContent = '🛑 Busca una dirección o haz clic en el mapa para la parada';
    document.body.style.cursor = 'crosshair';
    input.focus();
    
    console.log('➕ Nueva parada creada:', paradaId);
}

function colocarParadaEnMapa(location, paradaId) {
    const marker = new google.maps.Marker({
        position: location,
        map: map,
        icon: iconoAmarillo,
        draggable: true,
        title: `Parada ${paradaId.split('_')[1]}`,
        animation: google.maps.Animation.DROP
    });
    
    marker.addListener('dragend', (e) => {
        actualizarParada(paradaId, e.latLng);
        calcularRuta();
    });
    
    paradas.push({ id: paradaId, marker: marker, location: location });
    
    geocoder.geocode({ location: location }, (results, status) => {
        const direccion = (status === 'OK' && results[0]) 
            ? results[0].formatted_address 
            : 'Ubicación personalizada';
        document.getElementById(`${paradaId}_input`).value = direccion;
    });
    
    paradaEnEspera = null;
    document.getElementById('status').textContent = '✅ Parada agregada. Puedes agregar más o arrastrar';
    document.body.style.cursor = 'default';
    calcularRuta();
    
    console.log('🛑 Parada colocada en:', location.toString());
}

function actualizarParada(paradaId, location) {
    const parada = paradas.find(p => p.id === paradaId);
    if (!parada) return;
    
    parada.location = location;
    
    geocoder.geocode({ location: location }, (results, status) => {
        if (status === 'OK' && results[0]) {
            document.getElementById(`${paradaId}_input`).value = results[0].formatted_address;
        }
    });
}

function eliminarParada(paradaId) {
    const paradaIndex = paradas.findIndex(p => p.id === paradaId);
    
    if (paradaIndex !== -1) {
        paradas[paradaIndex].marker.setMap(null);
        paradas.splice(paradaIndex, 1);
    }
    
    document.getElementById(paradaId)?.remove();
    
    if (paradaEnEspera === paradaId) {
        paradaEnEspera = null;
        document.getElementById('status').textContent = '✅ Listo para agregar más paradas';
        document.body.style.cursor = 'default';
    }
    
    calcularRuta();
    console.log('🗑️ Parada eliminada:', paradaId);
}

// ========================================
// 🛣️ CÁLCULO DE RUTA
// ========================================
function calcularRuta() {
    if (!origenMarker || !destinoMarker) {
        console.log('⚠️ Faltan origen o destino');
        return;
    }

    console.log('🔄 Calculando ruta con', paradas.length, 'paradas');

    const waypoints = paradas.map(parada => ({
        location: parada.location,
        stopover: true
    }));

    const request = {
        origin: origenMarker.getPosition(),
        destination: destinoMarker.getPosition(),
        waypoints: waypoints,
        travelMode: google.maps.TravelMode.DRIVING,
        unitSystem: google.maps.UnitSystem.METRIC,
        optimizeWaypoints: true,
        provideRouteAlternatives: true
    };

    directionsService.route(request, (result, status) => {
        if (status !== 'OK') {
            console.error('❌ Error calculando ruta:', status);
            return;
        }

        console.log('✅ Rutas calculadas:', result.routes.length);

        rutasDisponibles = result.routes;
        rutaSeleccionada = 0;

        directionsRenderer.setDirections(result);
        directionsRenderer.setRouteIndex(0);

        actualizarInfoRuta(result.routes[0]);
        calcularCostosCompletos(result.routes[0]);

        // Mostrar opciones de rutas
        if (result.routes.length > 1) {
            mostrarOpcionesRutas(result.routes);
        } else if (waypoints.length > 0) {
            mostrarMensajeRutaUnica();
        } else {
            ocultarOpcionesRutas();
        }

        document.getElementById('route-info').classList.add('show');
    });
}

function actualizarInfoRuta(route) {
    let totalDistancia = 0;
    let totalTiempo = 0;

    route.legs.forEach(leg => {
        totalDistancia += leg.distance.value;
        totalTiempo += leg.duration.value;
    });

    const km = (totalDistancia / 1000).toFixed(1);
    const horas = Math.floor(totalTiempo / 3600);
    const minutos = Math.floor((totalTiempo % 3600) / 60);
    const tiempoTexto = horas > 0 ? `${horas}h ${minutos}min` : `${minutos} min`;

    document.getElementById('distancia').textContent = `${km} km`;
    document.getElementById('tiempo').textContent = tiempoTexto;
    document.getElementById('num-paradas').textContent = paradas.length;
    document.getElementById('distancia_km').value = km;
    document.getElementById('tiempo_estimado').value = tiempoTexto;

    mostrarInfoProgramacion();
}

function seleccionarRuta(index) {
    if (index < 0 || index >= rutasDisponibles.length) return;

    rutaSeleccionada = index;

    directionsRenderer.setDirections({
        routes: rutasDisponibles,
        request: directionsRenderer.getDirections().request
    });
    directionsRenderer.setRouteIndex(index);

    actualizarInfoRuta(rutasDisponibles[index]);
    calcularCostosCompletos(rutasDisponibles[index]);

    // Actualizar UI de selección
    document.querySelectorAll('.ruta-opcion').forEach((btn, i) => {
        const esSeleccionada = i === index;
        
        if (esSeleccionada) {
            btn.classList.add('ruta-seleccionada');
            btn.style.border = '3px solid #00BFFF';
            btn.style.background = 'linear-gradient(135deg, #00BFFF 0%, #0080FF 100%)';
            btn.style.color = 'white';
            btn.style.boxShadow = '0 6px 20px rgba(0,191,255,0.5)';
            btn.style.transform = 'scale(1.02)';
            
            btn.querySelectorAll('div').forEach(div => div.style.color = 'white');
        } else {
            btn.classList.remove('ruta-seleccionada');
            btn.style.border = '3px solid #e2e8f0';
            btn.style.background = 'white';
            btn.style.color = '#334155';
            btn.style.boxShadow = '0 2px 4px rgba(0,0,0,0.05)';
            btn.style.transform = 'scale(1)';
            
            const divs = btn.querySelectorAll('div');
            if (divs[0]) divs[0].style.color = 'var(--primary)';
            if (divs[1]) divs[1].style.color = '#64748b';
            if (divs[2]) divs[2].style.color = '#64748b';
            if (divs[3]) divs[3].style.color = '#94a3b8';
        }
    });

    console.log('🛣️ Ruta', index + 1, 'seleccionada');
}

function mostrarOpcionesRutas(routes) {
    let opcionesHTML = `
        <div class="rutas-alternativas" style="margin-top: 1rem;">
            <div style="font-weight: 600; color: var(--primary); margin-bottom: 0.75rem; font-size: 0.95rem;">
                🛣️ Rutas disponibles (elige la más conveniente):
            </div>
            <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
    `;

    routes.forEach((route, index) => {
        const { distancia, tiempo } = calcularDistanciaTiempo(route);
        const esSeleccionada = index === rutaSeleccionada;

        opcionesHTML += crearBotonRuta(index, distancia, tiempo, route.summary, esSeleccionada);
    });

    opcionesHTML += '</div></div>';

    actualizarContenedorRutas(opcionesHTML);
}

function crearBotonRuta(index, km, minutos, summary, seleccionada) {
    const estilos = seleccionada 
        ? 'border: 3px solid #00BFFF; background: linear-gradient(135deg, #00BFFF 0%, #0080FF 100%); color: white; box-shadow: 0 6px 20px rgba(0,191,255,0.5); transform: scale(1.02);'
        : 'border: 3px solid #e2e8f0; background: white; color: #334155; box-shadow: 0 2px 4px rgba(0,0,0,0.05); transform: scale(1);';

    const colorTexto = seleccionada ? 'white' : 'var(--primary)';
    const colorSecundario = seleccionada ? 'rgba(255,255,255,0.95)' : '#64748b';

    return `
        <button class="ruta-opcion ${seleccionada ? 'ruta-seleccionada' : ''}"
                onclick="seleccionarRuta(${index})"
                onmouseover="if(!this.classList.contains('ruta-seleccionada')) { 
                    this.style.transform='translateY(-2px)'; 
                    this.style.boxShadow='0 4px 12px rgba(0,191,255,0.25)'; 
                    this.style.borderColor='#00BFFF'; 
                }"
                onmouseout="if(!this.classList.contains('ruta-seleccionada')) { 
                    this.style.transform='translateY(0)'; 
                    this.style.boxShadow='0 2px 4px rgba(0,0,0,0.05)'; 
                    this.style.borderColor='#e2e8f0'; 
                }"
                style="flex: 1; min-width: 150px; padding: 0.75rem 1rem; ${estilos} border-radius: 12px; cursor: pointer; transition: all 0.3s ease; text-align: left;">
            <div style="font-weight: 700; font-size: 0.95rem; margin-bottom: 0.4rem; color: ${colorTexto};">
                ${seleccionada ? '✓ ' : ''}Ruta ${index + 1}
            </div>
            <div style="font-size: 0.85rem; color: ${colorSecundario};">📏 ${km} km</div>
            <div style="font-size: 0.85rem; color: ${colorSecundario};">⏱️ ${minutos} min</div>
            ${summary ? `<div style="font-size: 0.75rem; color: ${seleccionada ? 'rgba(255,255,255,0.85)' : '#94a3b8'}; margin-top: 0.35rem;">${summary}</div>` : ''}
        </button>
    `;
}

function calcularDistanciaTiempo(route) {
    let totalDistancia = 0;
    let totalTiempo = 0;

    route.legs.forEach(leg => {
        totalDistancia += leg.distance.value;
        totalTiempo += leg.duration.value;
    });

    return {
        distancia: (totalDistancia / 1000).toFixed(1),
        tiempo: Math.floor(totalTiempo / 60)
    };
}

function actualizarContenedorRutas(html) {
    const container = document.querySelector('.search-panel');
    let rutasContainer = document.getElementById('rutas-alternativas-container');

    if (!rutasContainer) {
        rutasContainer = document.createElement('div');
        rutasContainer.id = 'rutas-alternativas-container';
        container.appendChild(rutasContainer);
    }

    rutasContainer.innerHTML = html;
}

function ocultarOpcionesRutas() {
    const rutasContainer = document.getElementById('rutas-alternativas-container');
    if (rutasContainer) rutasContainer.innerHTML = '';
}

function mostrarMensajeRutaUnica() {
    const mensajeHTML = `
        <div class="mensaje-ruta-unica" style="margin-top: 1rem; padding: 1rem 1.25rem; background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); border: 2px solid #fcd34d; border-radius: 12px; display: flex; align-items: center; gap: 0.75rem;">
            <span style="font-size: 1.5rem;">ℹ️</span>
            <div>
                <div style="font-weight: 600; color: #92400e; font-size: 0.95rem; margin-bottom: 0.25rem;">
                    Ruta única con paradas
                </div>
                <div style="color: #78350f; font-size: 0.85rem; line-height: 1.5;">
                    Con paradas intermedias, Google Maps calcula la mejor ruta única que pasa por todos los puntos.
                    Para ver rutas alternativas, elimina las paradas.
                </div>
            </div>
        </div>
    `;

    actualizarContenedorRutas(mensajeHTML);
}

// ========================================
// 💵 CÁLCULO DE COSTOS
// ========================================
function calcularCostosCompletos(route) {
    const { distanciaKm, tiempoTexto } = obtenerDatosRuta(route);

    // Actualizar UI
    document.getElementById('calc-distancia').textContent = `${distanciaKm.toFixed(1)} km`;
    document.getElementById('calc-tiempo').textContent = tiempoTexto;

    console.log('💰 === CÁLCULO COMPLETO DE COSTOS ===');
    console.log('📏 Distancia total:', distanciaKm.toFixed(2), 'km');
    console.log('⏱️ Tiempo estimado:', tiempoTexto);
    console.log('⚙️ Configuraciones:', CONFIG);

    // Paso 1: Costo base por km
    const costoBaseKm = Math.floor(distanciaKm * CONFIG.costoPorKm);
    console.log('📊 Paso 1 - Costo base (distancia × costo/km):', costoBaseKm, 'pesos');

    // Paso 2: Costo de combustible
    let costoCombustibleViaje = 0;
    if (CONFIG.costoCombustible > 0 && CONFIG.numeroGalones > 0) {
        const galonesNecesarios = distanciaKm / CONFIG.kmPorGalon;
        costoCombustibleViaje = Math.floor(galonesNecesarios * CONFIG.costoCombustible);
        console.log('⛽ Paso 2 - Costo combustible:', costoCombustibleViaje, 'pesos');
        console.log('  - Galones necesarios:', galonesNecesarios.toFixed(2));
    }

    // Paso 3: Mayor entre ambos
    const costoBaseOperativo = Math.max(costoBaseKm, costoCombustibleViaje);
    console.log('💵 Paso 3 - Costo base operativo:', costoBaseOperativo, 'pesos');

    // Paso 4: Comisión
    const comisionMonto = CONFIG.comisionPlataforma > 0 
        ? Math.floor((costoBaseOperativo * CONFIG.comisionPlataforma) / 100)
        : 0;
    console.log('🏦 Paso 4 - Comisión plataforma:', comisionMonto, 'pesos');

    // Paso 5: Tarifa mínima
    tarifaMinima = Math.floor(costoBaseOperativo + comisionMonto);
    console.log('✅ Paso 5 - TARIFA MÍNIMA:', tarifaMinima, 'pesos');

    // Paso 6: Tarifa máxima
    if (CONFIG.maximoGanancia > 0) {
        const gananciaMaxima = Math.floor((tarifaMinima * CONFIG.maximoGanancia) / 100);
        tarifaMaxima = Math.floor(tarifaMinima + gananciaMaxima);
        console.log('💰 Paso 6 - Ganancia máxima:', gananciaMaxima, 'pesos');
    } else {
        tarifaMaxima = Math.floor(tarifaMinima * 1.5);
    }
    console.log('✅ Paso 6 - TARIFA MÁXIMA:', tarifaMaxima, 'pesos');
    console.log('🎯 Rango permitido: $', tarifaMinima, ' - $', tarifaMaxima);

    actualizarInterfazPrecios();
}

function obtenerDatosRuta(route) {
    let totalDistancia = 0;
    let totalTiempo = 0;

    route.legs.forEach(leg => {
        totalDistancia += leg.distance.value;
        totalTiempo += leg.duration.value;
    });

    const distanciaKm = totalDistancia / 1000;
    const horas = Math.floor(totalTiempo / 3600);
    const minutos = Math.floor((totalTiempo % 3600) / 60);
    const tiempoTexto = horas > 0 ? `${horas}h ${minutos}min` : `${minutos} min`;

    return { distanciaKm, tiempoTexto };
}

function actualizarInterfazPrecios() {
    const inputValorViaje = document.getElementById('valor_viaje_manual');

    document.getElementById('calc-minimo').textContent = formatearMoneda(tarifaMinima);
    document.getElementById('calc-maximo').textContent = formatearMoneda(tarifaMaxima);
    document.getElementById('rango-minimo').textContent = formatearMoneda(tarifaMinima);
    document.getElementById('rango-maximo').textContent = formatearMoneda(tarifaMaxima);

    inputValorViaje.placeholder = formatearNumero(tarifaMinima);
    inputValorViaje.setAttribute('data-min', tarifaMinima);
    inputValorViaje.setAttribute('data-max', tarifaMaxima);

    const valorActual = desformatearNumero(inputValorViaje.value);
    if (valorActual === 0 || valorActual < tarifaMinima || valorActual > tarifaMaxima) {
        inputValorViaje.value = formatearNumero(tarifaMinima);
    }

    validarValorViaje();
    calcularPorPasajero();

    console.log('✅ Interfaz actualizada con los nuevos precios');
}

// ========================================
// ✅ VALIDACIONES
// ========================================
function validarValorViaje() {
    const inputViaje = document.getElementById('valor_viaje_manual');
    const valorIngresado = desformatearNumero(inputViaje.value);
    const mensajeDiv = document.getElementById('mensaje-validacion');

    if (valorIngresado === 0) {
        mensajeDiv.style.display = 'none';
        inputViaje.style.borderColor = '#fcd34d';
        calcularPorPasajero();
        return;
    }

    if (valorIngresado < tarifaMinima) {
        mostrarMensajeValidacion(mensajeDiv, inputViaje, 
            `⚠️ El valor es menor a la tarifa mínima de ${formatearMoneda(tarifaMinima)}`);
    } else if (valorIngresado > tarifaMaxima) {
        mostrarMensajeValidacion(mensajeDiv, inputViaje, 
            `⚠️ El valor excede la tarifa máxima permitida de ${formatearMoneda(tarifaMaxima)}`);
    } else {
        mensajeDiv.style.display = 'none';
        inputViaje.style.borderColor = '#16a34a';
    }

    calcularPorPasajero();
}

function mostrarMensajeValidacion(mensajeDiv, inputViaje, mensaje) {
    mensajeDiv.style.display = 'block';
    mensajeDiv.style.background = 'linear-gradient(135deg, #fee2e2 0%, #fecaca 100%)';
    mensajeDiv.style.color = '#991b1b';
    mensajeDiv.style.borderLeft = '4px solid #dc2626';
    mensajeDiv.innerHTML = mensaje;
    inputViaje.style.borderColor = '#dc2626';
}

function calcularPorPasajero() {
    const puestosDisponibles = parseInt(document.getElementById('puestos_disponibles').value) || 0;
    const puestosTotales = parseInt(document.getElementById('puestos_totales').value) || 0;
    const valorManual = desformatearNumero(document.getElementById('valor_viaje_manual').value);
    const totalViaje = valorManual > 0 ? valorManual : tarifaMinima;

    if (puestosTotales === 0 || totalViaje === 0) {
        document.getElementById('total-viaje').textContent = formatearMoneda(0);
        document.getElementById('precio-por-pasajero').textContent = formatearMoneda(0);
        return;
    }

    const precioPorPasajero = totalViaje / puestosTotales;

    document.getElementById('total-viaje').textContent = formatearMoneda(totalViaje);
    document.getElementById('precio-por-pasajero').textContent = formatearMoneda(precioPorPasajero);

    console.log('👥 Cálculo por pasajero:');
    console.log('- Puestos totales:', puestosTotales);
    console.log('- Puestos disponibles:', puestosDisponibles);
    console.log('- Total del viaje: $', totalViaje.toFixed(2));
    console.log('- Precio por pasajero: $', precioPorPasajero.toFixed(2));
}

// ========================================
// 📅 FUNCIONES DE FECHA Y PROGRAMACIÓN
// ========================================
function toggleIdaVuelta() {
    const checkbox = document.getElementById('ida_vuelta');
    const returnSection = document.getElementById('return-section');
    const horaRegreso = document.getElementById('hora_regreso');
    
    if (checkbox.checked) {
        returnSection.classList.add('show');
        horaRegreso.required = true;
        
        // Sugerir hora de regreso (4 horas después)
        const horaSalida = document.getElementById('hora_salida').value;
        if (horaSalida && /^([0-1][0-9]|2[0-3]):([0-5][0-9])$/.test(horaSalida)) {
            const [horas, minutos] = horaSalida.split(':').map(Number);
            const horaSalidaDate = new Date();
            horaSalidaDate.setHours(horas, minutos);
            horaSalidaDate.setHours(horaSalidaDate.getHours() + 4);
            
            const horaRegresoSugerida = String(horaSalidaDate.getHours()).padStart(2, '0') + ':' + 
                                       String(horaSalidaDate.getMinutes()).padStart(2, '0');
            horaRegreso.value = horaRegresoSugerida;
            validarFormatoHora(horaRegreso);
        }
        
        document.getElementById('status').textContent = '🔄 Viaje de ida y vuelta programado';
    } else {
        returnSection.classList.remove('show');
        horaRegreso.required = false;
        horaRegreso.value = '';
        horaRegreso.classList.remove('valido', 'invalido');
        
        document.getElementById('status').textContent = '✅ Viaje de ida programado';
    }
}

function actualizarInfoFecha() {
    if (document.getElementById('route-info').classList.contains('show')) {
        mostrarInfoProgramacion();
    }
}

function mostrarInfoProgramacion() {
    const fechaViaje = document.getElementById('fecha_viaje').value;
    const horaSalida = document.getElementById('hora_salida').value;
    const idaVuelta = document.getElementById('ida_vuelta').checked;
    const horaRegreso = document.getElementById('hora_regreso').value;
    
    // Guardar en campos hidden
    document.getElementById('es_ida_vuelta').value = idaVuelta ? '1' : '0';
    document.getElementById('fecha_programada').value = fechaViaje;
    document.getElementById('hora_salida_programada').value = horaSalida;
    document.getElementById('hora_regreso_programada').value = horaRegreso;
    
    // Mostrar tarjeta de fecha programada
    if (fechaViaje && horaSalida) {
        const fechaCard = document.getElementById('fecha-programada-card');
        const fechaTexto = document.getElementById('fecha-programada');
        
        const fecha = new Date(fechaViaje + 'T00:00:00');
        const fechaFormateada = fecha.toLocaleDateString('es-ES', { day: 'numeric', month: 'short' });
        
        let textoCompleto = `${fechaFormateada} ${horaSalida}`;
        if (idaVuelta && horaRegreso) {
            textoCompleto += ` 🔄 ${horaRegreso}`;
        }
        
        fechaTexto.textContent = textoCompleto;
        fechaCard.style.display = 'block';
    }
    
    // Mostrar sección de cálculos
    const resultadosDiv = document.getElementById('calculation-results');
    if (resultadosDiv) resultadosDiv.style.display = 'block';
}

// ========================================
// 💾 GUARDAR VIAJE
// ========================================
function guardarViaje() {
    // Validar formato de hora
    const horaSalidaInput = document.getElementById('hora_salida');
    if (!validarFormatoHora(horaSalidaInput)) {
        showModal('error', 'Hora inválida', 'Por favor, ingresa una hora de salida válida en formato 24 horas (HH:MM)');
        horaSalidaInput.focus();
        return;
    }
    
    if (document.getElementById('ida_vuelta').checked) {
        const horaRegresoInput = document.getElementById('hora_regreso');
        if (!validarFormatoHora(horaRegresoInput)) {
            showModal('error', 'Hora inválida', 'Por favor, ingresa una hora de regreso válida en formato 24 horas (HH:MM)');
            horaRegresoInput.focus();
            return;
        }
    }

    // Obtener datos
    const datos = obtenerDatosViaje();
    
    // Validaciones
    if (!validarDatosViaje(datos)) return;

    // Enviar al servidor
    enviarViajeAlServidor(datos);
}

function obtenerDatosViaje() {
    const origenCoords = document.getElementById('origen_coords').value.split(',');
    const destinoCoords = document.getElementById('destino_coords').value.split(',');
    const valorViajeFormateado = document.getElementById('valor_viaje_manual').value;
    const valorViaje = desformatearNumero(valorViajeFormateado);
    const puestosTotales = document.getElementById('puestos_totales').value;

    const paradasArray = paradas.map((parada, index) => ({
        numero: index + 1,
        nombre: document.getElementById(`${parada.id}_input`)?.value || '',
        latitud: parada.location.lat(),
        longitud: parada.location.lng()
    }));

    return {
        origen_lat: origenCoords[0],
        origen_lng: origenCoords[1],
        destino_lat: destinoCoords[0],
        destino_lng: destinoCoords[1],
        origen: document.getElementById('origen_direccion').value,
        destino: document.getElementById('destino_direccion').value,
        distancia_km: document.getElementById('distancia_km').value,
        tiempo_estimado: document.getElementById('tiempo_estimado').value,
        fecha_salida: document.getElementById('fecha_viaje').value,
        hora_salida: document.getElementById('hora_salida').value,
        ida_vuelta: document.getElementById('ida_vuelta').checked ? 1 : 0,
        hora_regreso: document.getElementById('hora_regreso').value || null,
        puestos_disponibles: document.getElementById('puestos_disponibles').value,
        puestos_totales: puestosTotales,
        valor_cobrado: valorViaje,
        valor_persona: (parseFloat(valorViaje) / parseInt(puestosTotales)).toFixed(2),
        paradas: JSON.stringify(paradasArray),
        _token: '{{ csrf_token() }}'
    };
}

function validarDatosViaje(datos) {
    if (!datos.origen_lat || !datos.destino_lat) {
        showModal('error', 'Datos incompletos', 'Por favor, selecciona el origen y el destino del viaje');
        return false;
    }

    if (!datos.fecha_salida || !datos.hora_salida) {
        showModal('error', 'Datos incompletos', 'Por favor, completa la fecha y hora de salida');
        return false;
    }

    if (!datos.puestos_disponibles || datos.puestos_disponibles <= 0) {
        showModal('error', 'Datos incompletos', 'Por favor, indica los puestos disponibles para este viaje');
        return false;
    }

    if (!datos.valor_cobrado || datos.valor_cobrado <= 0) {
        showModal('error', 'Datos incompletos', 'Por favor, establece el valor del viaje');
        return false;
    }

    if (datos.valor_cobrado < tarifaMinima || datos.valor_cobrado > tarifaMaxima) {
        showModal('error', 'Valor inválido', `El valor del viaje debe estar entre ${formatearMoneda(tarifaMinima)} y ${formatearMoneda(tarifaMaxima)}`);
        return false;
    }

    return true;
}

function enviarViajeAlServidor(datos) {
    const btnGuardar = document.getElementById('btn-guardar-viaje');
    const textoOriginal = btnGuardar.innerHTML;
    btnGuardar.disabled = true;
    btnGuardar.innerHTML = '<span style="font-size: 1.5rem;">⏳</span> GUARDANDO...';

    console.log('📤 Enviando datos del viaje:', datos);

    fetch('{{ route("conductor.guardar-viaje") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify(datos)
    })
    .then(response => response.json())
    .then(data => {
        console.log('✅ Respuesta del servidor:', data);

        if (data.success) {
            showModal(
                'success',
                '¡Viaje publicado exitosamente!',
                'Los pasajeros ya pueden ver y reservar tu viaje.',
                '{{ route("conductor.gestion") }}'
            );
        } else {
            showModal(
                'error',
                'Error al guardar el viaje',
                data.message || 'Error desconocido'
            );
            btnGuardar.disabled = false;
            btnGuardar.innerHTML = textoOriginal;
        }
    })
    .catch(error => {
        console.error('❌ Error al guardar:', error);
        showModal(
            'error',
            'Error al guardar el viaje',
            'Por favor, intenta nuevamente.'
        );
        btnGuardar.disabled = false;
        btnGuardar.innerHTML = textoOriginal;
    });
}

// ========================================
// FUNCIONES DEL MODAL
// ========================================
function showModal(type, title, message, redirect = null) {
    const overlay = document.getElementById('modalOverlay');
    const content = document.getElementById('modalContent');
    const icon = document.getElementById('modalIcon');
    const titleEl = document.getElementById('modalTitle');
    const messageEl = document.getElementById('modalMessage');

    // Configurar contenido
    titleEl.textContent = title;
    messageEl.textContent = message;

    // Configurar estilos según el tipo
    if (type === 'success') {
        content.className = 'modal-success';
        icon.className = 'modal-icon';
        icon.textContent = '✓';
    } else if (type === 'error') {
        content.className = 'modal-error';
        icon.className = 'modal-icon-error';
        icon.textContent = '✕';
    }

    // Mostrar modal
    overlay.classList.add('show');

    // Si hay redirección, configurarla
    if (redirect) {
        setTimeout(() => {
            window.location.href = redirect;
        }, 2500);
    }
}

function closeModal() {
    const overlay = document.getElementById('modalOverlay');
    overlay.classList.remove('show');
}

// Cerrar modal al hacer clic en el overlay
document.addEventListener('click', function(e) {
    if (e.target.id === 'modalOverlay') {
        closeModal();
    }
});

// ========================================
// 🚀 INICIALIZACIÓN
// ========================================
document.addEventListener('DOMContentLoaded', function() {
    // Configurar fecha
    const hoy = new Date().toISOString().split('T')[0];
    document.getElementById('fecha_viaje').setAttribute('min', hoy);
    document.getElementById('fecha_viaje').value = hoy;

    // Configurar hora actual en formato 24hrs
    const ahora = new Date();
    const horaActual = String(ahora.getHours()).padStart(2, '0') + ':' + 
                       String(ahora.getMinutes()).padStart(2, '0');
    document.getElementById('hora_salida').value = horaActual;

    // Configurar inputs de hora con formato 24hrs
    configurarInputsHora();

    // Listeners de eventos
    document.getElementById('fecha_viaje').addEventListener('change', actualizarInfoFecha);
    document.getElementById('hora_salida').addEventListener('change', actualizarInfoFecha);
    document.getElementById('hora_regreso').addEventListener('change', actualizarInfoFecha);

    console.log('✅ Sistema inicializado correctamente');
});

console.log('📜 Script cargado correctamente');

// ========================================
// 🕐 GENERADOR DE OPCIONES DE HORA
// ========================================
function generarOpcionesHora(selectId, intervaloMinutos = 15, horaActual = null) {
    const select = document.getElementById(selectId);
    select.innerHTML = '<option value="">Selecciona una hora</option>';
    
    for (let hora = 0; hora < 24; hora++) {
        for (let minuto = 0; minuto < 60; minuto += intervaloMinutos) {
            const horaStr = String(hora).padStart(2, '0');
            const minutoStr = String(minuto).padStart(2, '0');
            const valor = `${horaStr}:${minutoStr}`;
            
            const option = document.createElement('option');
            option.value = valor;
            option.textContent = valor;
            
            // Pre-seleccionar hora actual si se proporciona
            if (horaActual && valor === horaActual) {
                option.selected = true;
            }
            
            select.appendChild(option);
        }
    }
}

// ========================================
// 🚀 INICIALIZACIÓN ACTUALIZADA
// ========================================
document.addEventListener('DOMContentLoaded', function() {
    // Configurar fecha
    const hoy = new Date().toISOString().split('T')[0];
    document.getElementById('fecha_viaje').setAttribute('min', hoy);
    document.getElementById('fecha_viaje').value = hoy;

    // Obtener hora actual redondeada al intervalo más cercano
    const ahora = new Date();
    const minutos = ahora.getMinutes();
    const minutosRedondeados = Math.ceil(minutos / 15) * 15; // Redondear a múltiplo de 15
    
    ahora.setMinutes(minutosRedondeados);
    ahora.setSeconds(0);
    
    const horaActual = String(ahora.getHours()).padStart(2, '0') + ':' + 
                       String(ahora.getMinutes()).padStart(2, '0');

    // Generar opciones de hora con intervalos de 15 minutos
    generarOpcionesHora('hora_salida', 15, horaActual);
    generarOpcionesHora('hora_regreso', 15);

    // Listeners de eventos
    document.getElementById('fecha_viaje').addEventListener('change', actualizarInfoFecha);
    document.getElementById('hora_salida').addEventListener('change', actualizarInfoFecha);
    document.getElementById('hora_regreso').addEventListener('change', actualizarInfoFecha);

    console.log('✅ Sistema inicializado correctamente');
});

// ========================================
// 🔄 FUNCIÓN ACTUALIZADA DE IDA Y VUELTA
// ========================================
function toggleIdaVuelta() {
    const checkbox = document.getElementById('ida_vuelta');
    const returnSection = document.getElementById('return-section');
    const horaRegreso = document.getElementById('hora_regreso');
    
    if (checkbox.checked) {
        returnSection.classList.add('show');
        horaRegreso.required = true;
        
        // Sugerir hora de regreso (4 horas después)
        const horaSalida = document.getElementById('hora_salida').value;
        if (horaSalida) {
            const [horas, minutos] = horaSalida.split(':').map(Number);
            const horaSalidaDate = new Date();
            horaSalidaDate.setHours(horas, minutos);
            horaSalidaDate.setHours(horaSalidaDate.getHours() + 4);
            
            const horaRegresoSugerida = String(horaSalidaDate.getHours()).padStart(2, '0') + ':' + 
                                       String(horaSalidaDate.getMinutes()).padStart(2, '0');
            
            // Seleccionar la hora más cercana disponible
            const opcionesRegreso = horaRegreso.options;
            for (let i = 0; i < opcionesRegreso.length; i++) {
                if (opcionesRegreso[i].value >= horaRegresoSugerida) {
                    horaRegreso.selectedIndex = i;
                    break;
                }
            }
        }
        
        document.getElementById('status').textContent = '🔄 Viaje de ida y vuelta programado';
    } else {
        returnSection.classList.remove('show');
        horaRegreso.required = false;
        horaRegreso.selectedIndex = 0; // Volver a "Selecciona una hora"
        
        document.getElementById('status').textContent = '✅ Viaje de ida programado';
    }
}

// ========================================
// 💾 GUARDAR VIAJE (VALIDACIÓN SIMPLIFICADA)
// ========================================
function guardarViaje() {
    // Validar que se haya seleccionado hora
    const horaSalida = document.getElementById('hora_salida').value;
    if (!horaSalida) {
        showModal('error', 'Datos incompletos', 'Por favor, selecciona una hora de salida');
        document.getElementById('hora_salida').focus();
        return;
    }
    
    if (document.getElementById('ida_vuelta').checked) {
        const horaRegreso = document.getElementById('hora_regreso').value;
        if (!horaRegreso) {
            showModal('error', 'Datos incompletos', 'Por favor, selecciona una hora de regreso');
            document.getElementById('hora_regreso').focus();
            return;
        }
    }

    // Obtener datos
    const datos = obtenerDatosViaje();
    
    // Validaciones
    if (!validarDatosViaje(datos)) return;

    // Enviar al servidor
    enviarViajeAlServidor(datos);
}
</script>

<!-- Modal de éxito -->
<div id="modalOverlay" class="modal-overlay">
    <div id="modalContent" class="modal-success">
        <div class="modal-icon" id="modalIcon">✓</div>
        <h3 class="modal-title" id="modalTitle">¡Viaje publicado exitosamente!</h3>
        <p class="modal-message" id="modalMessage">Los pasajeros ya pueden ver y reservar tu viaje.</p>
        <button class="modal-button" onclick="closeModal()">Entendido</button>
    </div>
</div>

<script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.key') }}&libraries=places&callback=initMap&language=es&region=AR" async defer></script>
@endsection