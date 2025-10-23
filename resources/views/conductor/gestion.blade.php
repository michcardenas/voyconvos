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
        .search-grid {
            grid-template-columns: 1fr;
        }
        #map {
            height: 450px;
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
        }
        .info-grid {
            grid-template-columns: 1fr 1fr;
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
    .results-grid {
        grid-template-columns: 1fr;
    }
    
    .result-item.final {
        grid-column: span 1;
    }
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
        <input type="time" id="hora_salida" class="trip-input" required>
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
<div id="return-section" class="return-section" style="margin-top: 1.5rem;">
    <label class="return-label">🕐 Hora de regreso</label>
    <input type="time" id="hora_regreso" class="trip-input">
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
<div class="calculation-results" id="calculation-results" style="display: none;">
    <div class="results-header">
        <h3>📊 Resultados del Cálculo</h3>
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
        
        <div class="result-item tarifa-input-item">
            <div class="result-label">💵 Tarifa base del viaje</div>
            <input type="number" id="tarifa_base" class="result-input" placeholder="0.00" min="0" step="0.01" oninput="recalcularTotales()">
        </div>
        
        <div class="result-item commission">
            <div class="result-label">🔧 Costo de mantenimiento ({{ $comision_plataforma ?? 0 }}%)</div>
            <div class="result-value" id="calc-mantenimiento">$--</div>
        </div>
        
        <div class="result-item final">
            <div class="result-label">🎯 TOTAL A COBRAR AL PASAJERO</div>
            <div class="result-value-final" id="calc-total-final">$--</div>
        </div>
    </div>
    
    <div class="calculation-note">
        <p><strong>ℹ️ Nota:</strong> El costo de mantenimiento es la comisión de la plataforma para cubrir servicios, soporte y mantenimiento del sistema.</p>
    </div>
</div>
<script>
let map;
let directionsService;
let directionsRenderer;
let geocoder;
let origenMarker = null;
let destinoMarker = null;
let paradasMarkers = [];
let paradas = [];
let paso = 'origen';
let iconoVerde, iconoRojo, iconoAmarillo;
let paradaCounter = 0;
let paradaEnEspera = null; // Nueva variable para la parada que espera ser colocada

function initMap() {
    console.log('🗺️ Iniciando mapa...');
    
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
    
    const centro = { lat: -34.6037, lng: -58.3816 };
    
    map = new google.maps.Map(document.getElementById('map'), {
        zoom: 12,
        center: centro,
        mapTypeControl: false,
        streetViewControl: false
    });

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

    const origenAuto = new google.maps.places.Autocomplete(
        document.getElementById('origen_input'),
        { componentRestrictions: { country: 'ar' } }
    );

    const destinoAuto = new google.maps.places.Autocomplete(
        document.getElementById('destino_input'),
        { componentRestrictions: { country: 'ar' } }
    );

    origenAuto.addListener('place_changed', function() {
        const place = origenAuto.getPlace();
        if (place.geometry) {
            ponerOrigen(place.geometry.location);
        }
    });

    destinoAuto.addListener('place_changed', function() {
        const place = destinoAuto.getPlace();
        if (place.geometry) {
            ponerDestino(place.geometry.location);
        }
    });

    map.addListener('click', function(e) {
        console.log('🖱️ Click en mapa, paso:', paso);
        
        if (paso === 'origen') {
            ponerOrigen(e.latLng);
        } else if (paso === 'destino') {
            ponerDestino(e.latLng);
        } else if (paradaEnEspera) {
            // Si hay una parada esperando, colocarla en el mapa
            colocarParadaEnMapa(e.latLng, paradaEnEspera);
        }
    });

    console.log('✅ Mapa listo');
}

// Configurar fecha mínima (hoy)
document.addEventListener('DOMContentLoaded', function() {
    const hoy = new Date().toISOString().split('T')[0];
    document.getElementById('fecha_viaje').setAttribute('min', hoy);
    document.getElementById('fecha_viaje').value = hoy;
    
    // Hora actual como sugerencia
    const ahora = new Date();
    const horaActual = ahora.getHours().toString().padStart(2, '0') + ':' + ahora.getMinutes().toString().padStart(2, '0');
    document.getElementById('hora_salida').value = horaActual;
    
    // Listeners para actualizar info cuando cambien los campos
    document.getElementById('fecha_viaje').addEventListener('change', function() {
        if (document.getElementById('route-info').classList.contains('show')) {
            mostrarInfoProgramacion();
        }
    });
    
    document.getElementById('hora_salida').addEventListener('change', function() {
        if (document.getElementById('route-info').classList.contains('show')) {
            mostrarInfoProgramacion();
        }
    });
    
    document.getElementById('hora_regreso').addEventListener('change', function() {
        if (document.getElementById('route-info').classList.contains('show')) {
            mostrarInfoProgramacion();
        }
    });
});

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
            const [horas, minutos] = horaSalida.split(':');
            const horaSalidaDate = new Date();
            horaSalidaDate.setHours(parseInt(horas), parseInt(minutos));
            horaSalidaDate.setHours(horaSalidaDate.getHours() + 4);
            
            const horaRegresoSugerida = horaSalidaDate.getHours().toString().padStart(2, '0') + ':' + 
                                       horaSalidaDate.getMinutes().toString().padStart(2, '0');
            horaRegreso.value = horaRegresoSugerida;
        }
        
        document.getElementById('status').textContent = '🔄 Viaje de ida y vuelta programado';
    } else {
        returnSection.classList.remove('show');
        horaRegreso.required = false;
        horaRegreso.value = '';
        
        document.getElementById('status').textContent = '✅ Viaje de ida programado';
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
    
    if (fechaViaje && horaSalida) {
        const fechaCard = document.getElementById('fecha-programada-card');
        const fechaTexto = document.getElementById('fecha-programada');
        
        // Formatear fecha
        const fecha = new Date(fechaViaje + 'T00:00:00');
        const opciones = { day: 'numeric', month: 'short' };
        const fechaFormateada = fecha.toLocaleDateString('es-ES', opciones);
        
        let textoCompleto = `${fechaFormateada} ${horaSalida}`;
        
        if (idaVuelta && horaRegreso) {
            textoCompleto += ` 🔄 ${horaRegreso}`;
        }
        
        fechaTexto.textContent = textoCompleto;
        fechaCard.style.display = 'block';
    }
}

function ponerOrigen(location) {
    console.log('📍 Poniendo origen');
    
    if (origenMarker) {
        origenMarker.setMap(null);
    }

    origenMarker = new google.maps.Marker({
        position: location,
        map: map,
        icon: iconoVerde,
        draggable: true,
        title: 'Origen',
        animation: google.maps.Animation.DROP
    });

    origenMarker.addListener('dragend', function(e) {
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
    
    if (destinoMarker) {
        destinoMarker.setMap(null);
    }

    destinoMarker = new google.maps.Marker({
        position: location,
        map: map,
        icon: iconoRojo,
        draggable: true,
        title: 'Destino',
        animation: google.maps.Animation.DROP
    });

    destinoMarker.addListener('dragend', function(e) {
        actualizarDestino(e.latLng);
        calcularRuta();
    });

    actualizarDestino(location);
    
    paso = 'listo';
    document.getElementById('status').textContent = '✅ ¡Ruta lista! Puedes agregar paradas';
    
    calcularRuta();
}

function actualizarOrigen(location) {
    const lat = location.lat();
    const lng = location.lng();
    
    document.getElementById('origen_coords').value = lat + ',' + lng;
    
    geocoder.geocode({ location: location }, function(results, status) {
        if (status === 'OK' && results[0]) {
            document.getElementById('origen_input').value = results[0].formatted_address;
            document.getElementById('origen_direccion').value = results[0].formatted_address;
        }
    });
}

function actualizarDestino(location) {
    const lat = location.lat();
    const lng = location.lng();
    
    document.getElementById('destino_coords').value = lat + ',' + lng;
    
    geocoder.geocode({ location: location }, function(results, status) {
        if (status === 'OK' && results[0]) {
            document.getElementById('destino_input').value = results[0].formatted_address;
            document.getElementById('destino_direccion').value = results[0].formatted_address;
        }
    });
}

function agregarNuevaParada() {
    if (!origenMarker || !destinoMarker) {
        alert('⚠️ Por favor, primero selecciona el origen y destino');
        return;
    }
    
    paradaCounter++;
    const paradaId = 'parada_' + paradaCounter;
    
    // Crear el HTML de la parada
    const paradaHTML = `
        <div class="parada-item" id="${paradaId}">
            <div class="parada-number">${paradaCounter}</div>
            <div class="parada-input-wrapper">
                <input type="text" 
                       class="parada-input" 
                       id="${paradaId}_input" 
                       placeholder="🔍 Busca una dirección o haz clic en el mapa">
            </div>
            <button class="btn-remove-parada" onclick="eliminarParada('${paradaId}')">
                ✕
            </button>
        </div>
    `;
    
    document.getElementById('paradas-list').insertAdjacentHTML('beforeend', paradaHTML);
    
    // Configurar autocompletado
    const input = document.getElementById(paradaId + '_input');
    const autocomplete = new google.maps.places.Autocomplete(input, {
        componentRestrictions: { country: 'ar' }
    });
    
    autocomplete.addListener('place_changed', function() {
        const place = autocomplete.getPlace();
        if (place.geometry) {
            colocarParadaEnMapa(place.geometry.location, paradaId);
            paradaEnEspera = null; // Ya se colocó
            document.getElementById('status').textContent = '✅ Parada agregada. Puedes agregar más';
            document.body.style.cursor = 'default';
        }
    });
    
    // Activar modo de espera para click en mapa
    paradaEnEspera = paradaId;
    document.getElementById('status').textContent = '🛑 Busca una dirección o haz clic en el mapa para la parada';
    document.body.style.cursor = 'crosshair';
    
    // Hacer focus en el input
    input.focus();
    
    console.log('➕ Nueva parada creada:', paradaId);
}

function colocarParadaEnMapa(location, paradaId) {
    const marker = new google.maps.Marker({
        position: location,
        map: map,
        icon: iconoAmarillo,
        draggable: true,
        title: 'Parada ' + paradaId.split('_')[1],
        animation: google.maps.Animation.DROP
    });
    
    marker.addListener('dragend', function(e) {
        actualizarParada(paradaId, e.latLng);
        calcularRuta();
    });
    
    paradas.push({
        id: paradaId,
        marker: marker,
        location: location
    });
    
    // Obtener y actualizar dirección
    geocoder.geocode({ location: location }, function(results, status) {
        if (status === 'OK' && results[0]) {
            document.getElementById(paradaId + '_input').value = results[0].formatted_address;
        } else {
            document.getElementById(paradaId + '_input').value = 'Ubicación personalizada';
        }
    });
    
    // Resetear modo de espera
    paradaEnEspera = null;
    document.getElementById('status').textContent = '✅ Parada agregada. Puedes agregar más o arrastrar';
    document.body.style.cursor = 'default';
    
    calcularRuta();
    
    console.log('🛑 Parada colocada en:', location.toString());
}

function activarModoParada() {
    if (!origenMarker || !destinoMarker) {
        alert('⚠️ Por favor, primero selecciona el origen y destino');
        return;
    }
    
    paso = 'parada';
    document.getElementById('status').textContent = '🛑 Haz clic en el mapa para agregar una parada';
    document.body.style.cursor = 'crosshair';
    console.log('📍 Modo parada activado');
}

function agregarParadaPorClick(location) {
    paradaCounter++;
    const paradaId = 'parada_' + paradaCounter;
    
    // Crear el HTML de la parada
    const paradaHTML = `
        <div class="parada-item" id="${paradaId}">
            <div class="parada-number">${paradaCounter}</div>
            <div class="parada-input-wrapper">
                <input type="text" 
                       class="parada-input" 
                       id="${paradaId}_input" 
                       placeholder="Cargando dirección...">
            </div>
            <button class="btn-remove-parada" onclick="eliminarParada('${paradaId}')">
                ✕
            </button>
        </div>
    `;
    
    document.getElementById('paradas-list').insertAdjacentHTML('beforeend', paradaHTML);
    
    // Agregar al mapa
    agregarParadaMapa(location, paradaId);
    
    // Obtener dirección
    geocoder.geocode({ location: location }, function(results, status) {
        if (status === 'OK' && results[0]) {
            document.getElementById(paradaId + '_input').value = results[0].formatted_address;
        } else {
            document.getElementById(paradaId + '_input').value = 'Ubicación personalizada';
        }
    });
    
    // Restaurar modo normal
    paso = 'listo';
    document.getElementById('status').textContent = '✅ Parada agregada. Puedes agregar más o arrastrar';
    document.body.style.cursor = 'default';
    
    console.log('🛑 Parada agregada por click en:', location.toString());
}

function agregarParadaMapa(location, paradaId) {
    // Esta función ya no se usa, todo pasa por colocarParadaEnMapa
    colocarParadaEnMapa(location, paradaId);
}

function actualizarParada(paradaId, location) {
    const parada = paradas.find(p => p.id === paradaId);
    if (parada) {
        parada.location = location;
        
        geocoder.geocode({ location: location }, function(results, status) {
            if (status === 'OK' && results[0]) {
                document.getElementById(paradaId + '_input').value = results[0].formatted_address;
            }
        });
    }
}

function eliminarParada(paradaId) {
    const paradaIndex = paradas.findIndex(p => p.id === paradaId);
    if (paradaIndex !== -1) {
        paradas[paradaIndex].marker.setMap(null);
        paradas.splice(paradaIndex, 1);
    }
    
    document.getElementById(paradaId).remove();
    
    // Si era la parada en espera, limpiar
    if (paradaEnEspera === paradaId) {
        paradaEnEspera = null;
        document.getElementById('status').textContent = '✅ Listo para agregar más paradas';
        document.body.style.cursor = 'default';
    }
    
    calcularRuta();
    console.log('🗑️ Parada eliminada:', paradaId);
}

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
        optimizeWaypoints: true
    };

    directionsService.route(request, function(result, status) {
        if (status === 'OK') {
            console.log('✅ Ruta calculada');
            directionsRenderer.setDirections(result);

            let totalDistancia = 0;
            let totalTiempo = 0;

            result.routes[0].legs.forEach(leg => {
                totalDistancia += leg.distance.value;
                totalTiempo += leg.duration.value;
            });

            const km = (totalDistancia / 1000).toFixed(1);
            const horas = Math.floor(totalTiempo / 3600);
            const minutos = Math.floor((totalTiempo % 3600) / 60);
            const tiempoTexto = horas > 0 ? `${horas}h ${minutos}min` : `${minutos} min`;

            document.getElementById('distancia').textContent = km + ' km';
            document.getElementById('tiempo').textContent = tiempoTexto;
            document.getElementById('num-paradas').textContent = paradas.length;
            document.getElementById('distancia_km').value = km;
            document.getElementById('tiempo_estimado').value = tiempoTexto;
            
            // Mostrar información de programación
            mostrarInfoProgramacion();
            
            document.getElementById('route-info').classList.add('show');
        } else {
            console.error('❌ Error calculando ruta:', status);
        }
    });
}

console.log('📜 Script cargado');
// Variables de configuración del servidor
const comisionPlataforma = {{ $comision_plataforma ?? 0 }};

function mostrarInfoProgramacion() {
    const resultadosDiv = document.getElementById('calculation-results');
    if (resultadosDiv) {
        resultadosDiv.style.display = 'block';
        actualizarDistanciaTiempo();
    }
}

function actualizarDistanciaTiempo() {
    // Obtener distancia y tiempo del cálculo de ruta
    const distanciaKm = parseFloat(document.getElementById('distancia_km').value) || 0;
    const tiempoEstimado = document.getElementById('tiempo_estimado').value || '--';
    
    // Actualizar valores básicos
    document.getElementById('calc-distancia').textContent = distanciaKm > 0 ? distanciaKm.toFixed(1) + ' km' : '-- km';
    document.getElementById('calc-tiempo').textContent = tiempoEstimado;
    
    // Calcular costos si ya hay tarifa base
    recalcularTotales();
}

function recalcularTotales() {
    // Obtener tarifa base ingresada por el conductor
    const tarifaBase = parseFloat(document.getElementById('tarifa_base').value) || 0;
    
    if (tarifaBase === 0) {
        document.getElementById('calc-mantenimiento').textContent = '$--';
        document.getElementById('calc-total-final').textContent = '$--';
        return;
    }
    
    // Calcular costo de mantenimiento (comisión de la plataforma)
    const costoMantenimiento = (tarifaBase * comisionPlataforma) / 100;
    
    // Total a cobrar al pasajero
    const totalFinal = tarifaBase + costoMantenimiento;
    
    // Actualizar la interfaz
    document.getElementById('calc-mantenimiento').textContent = '$' + costoMantenimiento.toFixed(2);
    document.getElementById('calc-total-final').textContent = '$' + totalFinal.toFixed(2);
    
    // Log para debug
    console.log('💰 Cálculo de tarifa:');
    console.log('- Tarifa base: $', tarifaBase.toFixed(2));
    console.log('- Costo mantenimiento (' + comisionPlataforma + '%): $', costoMantenimiento.toFixed(2));
    console.log('- TOTAL A COBRAR: $', totalFinal.toFixed(2));
}
</script>

<script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.key') }}&libraries=places&callback=initMap&language=es&region=AR" async defer></script>

@endsection