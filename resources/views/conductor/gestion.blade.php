@extends('layouts.app_dashboard')

@section('title', 'Planifica tu viaje')

@section('content')
<style>
    /* Variables para mantener consistencia con el dashboard */
    :root {
        --vcv-primary: #003366;
        --vcv-primary-light: #004080;
        --vcv-info: #00BFFF;
        --vcv-success: #28a745;
        --vcv-warning: #ffc107;
        --vcv-danger: #dc3545;
        --border-color: rgba(0, 51, 102, 0.1);
        --shadow-card: 0 4px 12px rgba(0, 51, 102, 0.1);
        --shadow-soft: 0 2px 8px rgba(0, 51, 102, 0.08);
        --border-radius: 12px;
        --transition: all 0.3s ease;
    }

    /* Contenedor principal */
    .container-mapa {
        max-width: 1200px;
        margin: 0 auto;
        padding: 1rem;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    /* Header de la página */
    .page-header {
        background: linear-gradient(135deg, var(--vcv-primary) 0%, var(--vcv-primary-light) 100%);
        color: white;
        padding: 2rem;
        border-radius: var(--border-radius);
        margin-bottom: 2rem;
        text-align: center;
        box-shadow: var(--shadow-card);
    }

    .page-header h2 {
        margin: 0;
        font-weight: 600;
        font-size: 1.8rem;
        color: white;
    }

    .page-header p {
        margin: 0.5rem 0 0 0;
        opacity: 0.9;
        font-size: 1rem;
    }

    /* Sección de controles */
    .controls-section {
        background: white;
        border-radius: var(--border-radius);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: var(--shadow-card);
        border: 1px solid var(--border-color);
    }

    .section-title {
        color: var(--vcv-primary);
        font-weight: 600;
        font-size: 1.1rem;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    /* Controles de ubicación */
    .location-controls {
        display: flex;
        justify-content: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
    }

    /* Inputs de lugar */
    .inputs-lugar {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .input-group {
        position: relative;
    }

    .input-group input {
        width: 100%;
        padding: 1rem;
        font-size: 1rem;
        border: 2px solid var(--border-color);
        border-radius: 8px;
        transition: var(--transition);
        background: white;
    }

    .input-group input:focus {
        outline: none;
        border-color: var(--vcv-info);
        box-shadow: 0 0 0 3px rgba(0, 191, 255, 0.1);
    }

    /* Controles del mapa */
    .map-controls {
        display: flex;
        justify-content: center;
        gap: 1rem;
        margin-bottom: 1rem;
        flex-wrap: wrap;
    }

    /* Mapa */
    #map {
        width: 100%;
        height: 500px;
        border-radius: var(--border-radius);
        margin-bottom: 1.5rem;
        border: 2px solid var(--border-color);
        box-shadow: var(--shadow-soft);
    }

    /* Opciones de ruta */
    .route-options {
        background: white;
        border-radius: var(--border-radius);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: var(--shadow-card);
        border: 1px solid var(--border-color);
        display: none;
    }

    .traffic-info {
        text-align: center;
        margin-bottom: 1rem;
    }

    .route-selector {
        display: flex;
        gap: 1rem;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
        justify-content: center;
    }

    .route-option {
        padding: 1rem;
        border: 2px solid var(--border-color);
        background: white;
        border-radius: 8px;
        cursor: pointer;
        font-size: 0.9rem;
        transition: var(--transition);
        text-align: center;
        min-width: 120px;
    }

    .route-option:hover {
        border-color: var(--vcv-info);
        background: rgba(0, 191, 255, 0.05);
    }

    .route-option.active {
        background: var(--vcv-primary);
        color: white;
        border-color: var(--vcv-primary);
    }

    .route-info {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }

    .info-card {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 1rem;
        text-align: center;
        border-left: 4px solid var(--vcv-info);
    }

    .info-card h4 {
        margin: 0 0 0.5rem 0;
        color: var(--vcv-primary);
        font-size: 0.9rem;
        font-weight: 600;
    }

    .info-card .value {
        font-size: 1.1rem;
        font-weight: 600;
        color: #495057;
    }

    /* Formulario principal */
    .form-container {
        background: white;
        border-radius: var(--border-radius);
        padding: 2rem;
        box-shadow: var(--shadow-card);
        border: 1px solid var(--border-color);
    }

    .form-title {
        text-align: center;
        font-size: 1.5rem;
        color: var(--vcv-primary);
        margin-bottom: 2rem;
        font-weight: 600;
    }

    .form-grid-responsive {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }

    @media (min-width: 992px) {
        .form-grid-responsive {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    .form-section {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 1.5rem;
        border: 1px solid var(--border-color);
    }

    .results-section {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-left: 4px solid var(--vcv-success);
    }

    .form-group {
        margin-bottom: 1rem;
    }

    .form-group:last-child {
        margin-bottom: 0;
    }

    .form-group label {
        font-weight: 600;
        margin-bottom: 0.5rem;
        display: block;
        color: var(--vcv-primary);
        font-size: 0.9rem;
    }

    .form-group input,
    .form-group select {
        width: 100%;
        padding: 0.75rem;
        border: 2px solid var(--border-color);
        border-radius: 6px;
        font-size: 0.9rem;
        transition: var(--transition);
        background: white;
    }

    .form-group input:focus,
    .form-group select:focus {
        outline: none;
        border-color: var(--vcv-info);
        box-shadow: 0 0 0 3px rgba(0, 191, 255, 0.1);
    }

    .form-group input:read-only {
        background: #e9ecef;
        color: var(--vcv-primary);
        font-weight: 600;
    }

    /* Alert mejorado */
    .alert {
        padding: 1rem;
        border-radius: 6px;
        margin-bottom: 1rem;
        border-left: 4px solid var(--vcv-info);
        background: rgba(0, 191, 255, 0.05);
        color: #495057;
    }

    /* Botones modernos */
    .btn-modern {
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.9rem;
        cursor: pointer;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
    }

    .btn-primary {
        background: linear-gradient(45deg, var(--vcv-primary), var(--vcv-primary-light));
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 51, 102, 0.3);
    }

    .btn-success {
        background: linear-gradient(45deg, var(--vcv-success), #20c997);
        color: white;
    }

    .btn-success:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
    }

    .btn-info {
        background: linear-gradient(45deg, var(--vcv-info), #17a2b8);
        color: white;
    }

    .btn-info:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 191, 255, 0.3);
    }

    .btn-secondary {
        background: linear-gradient(45deg, #6c757d, #5a6268);
        color: white;
    }

    .btn-secondary:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(108, 117, 125, 0.3);
    }

    .traffic-toggle {
        background: linear-gradient(45deg, var(--vcv-success), #20c997);
        color: white;
        padding: 0.6rem 1rem;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.85rem;
        font-weight: 600;
        transition: var(--transition);
    }

    .traffic-toggle:hover {
        transform: translateY(-1px);
        box-shadow: 0 3px 10px rgba(40, 167, 69, 0.3);
    }

    /* Acciones del formulario */
    .form-actions {
        text-align: center;
        margin-top: 2rem;
        display: flex;
        justify-content: center;
        gap: 1rem;
        flex-wrap: wrap;
    }

    /* Mensajes de estado */
    .status-message {
        margin: 1rem 0;
        padding: 1rem;
        border-radius: 6px;
        text-align: center;
        font-weight: 500;
    }

    .status-info {
        background: rgba(0, 191, 255, 0.1);
        color: var(--vcv-primary);
        border: 1px solid rgba(0, 191, 255, 0.2);
    }

    .status-success {
        background: rgba(40, 167, 69, 0.1);
        color: #155724;
        border: 1px solid rgba(40, 167, 69, 0.2);
    }

    .status-error {
        background: rgba(220, 53, 69, 0.1);
        color: #721c24;
        border: 1px solid rgba(220, 53, 69, 0.2);
    }

    .status-warning {
        background: rgba(255, 193, 7, 0.1);
        color: #856404;
        border: 1px solid rgba(255, 193, 7, 0.2);
    }

    /* Campos ocultos */
    .hidden-coords {
        display: none !important;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .container-mapa {
            padding: 0.5rem;
        }

        .page-header {
            padding: 1.5rem;
        }

        .page-header h2 {
            font-size: 1.5rem;
        }

        .controls-section {
            padding: 1rem;
        }

        .form-container {
            padding: 1.5rem;
        }

        .location-controls,
        .map-controls,
        .route-selector,
        .form-actions {
            flex-direction: column;
            align-items: center;
        }

        .inputs-lugar {
            grid-template-columns: 1fr;
        }

        #map {
            height: 400px;
        }
    }

    /* Animaciones */
    .controls-section,
    .route-options,
    .form-container {
        animation: slideUp 0.5s ease-out;
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
</style>

<div class="container-mapa">
    <!-- Header de la página -->
    <div class="page-header">
        <h2>🗺️ Planifica tu viaje</h2>
        <p>Encuentra la mejor ruta y calcula los costos de tu viaje</p>
    </div>

    <!-- Controles principales -->
    <div class="controls-section">
        <div class="section-title">⚙️ Controles de ubicación</div>
        
        <div class="location-controls">
            <button class="btn-modern btn-info" onclick="obtenerMiUbicacion()">📍 Mi Ubicación Actual</button>
            <button class="btn-modern btn-secondary" onclick="limpiarTodo()">🗑️ Limpiar Todo</button>
        </div>

        <div class="inputs-lugar">
            <div class="input-group">
                <input type="text" id="origen_input" placeholder="🚩 Buscar origen">
            </div>
            <div class="input-group">
                <input type="text" id="destino_input" placeholder="🗺️ Buscar destino">
            </div>
        </div>

        <div class="map-controls">
            <button class="btn-modern btn-info" onclick="activarSeleccionOrigen()">📍 Seleccionar Origen en Mapa</button>
            <button class="btn-modern btn-info" onclick="activarSeleccionDestino()">📍 Seleccionar Destino en Mapa</button>
        </div>
    </div>

    <!-- Mensajes de estado -->
    <div id="status-message"></div>

    <!-- Mapa -->
    <div id="map"></div>

    <!-- Opciones de ruta -->
    <div class="route-options" id="route-options">
        <div class="section-title">🛣️ Opciones de ruta</div>
        
        <div class="traffic-info">
            <button class="traffic-toggle" id="traffic-btn" onclick="toggleTrafico()">🚦 Activar Tráfico</button>
        </div>
        
        <div class="route-selector" id="route-selector"></div>
        <div class="route-info" id="route-info"></div>
    </div>

    <!-- Formulario de costo -->
    <div class="form-container">
        <h3 class="form-title">💰 Cálculo de Costos del Viaje</h3>
        
        <form id="viajeForm">
            <div class="form-grid-responsive">
                {{-- Columna 1: Origen y Destino --}}
                <div class="form-section">
                    <div class="section-title">📍 Ubicaciones</div>
                    
                    <div class="form-group">
                        <label>Origen</label>
                        <input type="text" id="origen_direccion" readonly>
                    </div>
                    <div class="form-group">
                        <label>Destino</label>
                        <input type="text" id="destino_direccion" readonly>
                    </div>
                    <input type="hidden" id="origen_coords" class="hidden-coords">
                    <input type="hidden" id="destino_coords" class="hidden-coords">
                </div>

                {{-- Columna 2: Datos del vehículo --}}
                <div class="form-section">
                    <div class="section-title">🚗 Información del Vehículo</div>
                    
                    @if($marca)
                        <div class="alert">
                            🚗 Tu vehículo registrado es: <strong>{{ $marca }}</strong>
                        </div>
                    @endif

                    <div class="form-group">
                        <label>Consumo (km por galón/litro)</label>
                        <input type="number" id="consumo_km" placeholder="Ej: 30" step="0.1">
                    </div>
                    <div class="form-group">
                        <label>Precio del combustible (por galón/litro)</label>
                        <input type="number" id="precio_galon" placeholder="Ej: 4.50 USD o 14000 COP" step="0.01">
                    </div>
                    <div class="form-group">
                        <label>Número de peajes</label>
                        <input type="number" id="peajes" placeholder="Ej: 2" min="0">
                    </div>
                    <div class="form-group">
                        <label>Costo promedio por peaje</label>
                        <input type="number" id="costo_peaje" placeholder="Ej: 5.00 USD o 10000 COP" step="0.01">
                    </div>
                </div>

                {{-- Columna 3: Resultados --}}
                <div class="form-section results-section">
                    <div class="section-title">📊 Resultados del Cálculo</div>
                    
                    <div class="form-group">
                        <label>Distancia total (km)</label>
                        <input type="text" id="distancia_km" readonly>
                    </div>
                    <div class="form-group">
                        <label>Tiempo estimado</label>
                        <input type="text" id="tiempo_estimado" readonly>
                    </div>
                    <div class="form-group">
                        <label>Costo combustible</label>
                        <input type="text" id="costo_estimado" readonly>
                    </div>
                    <div class="form-group">
                        <label>Total estimado (combustible + peajes)</label>
                        <input type="text" id="costo_total" readonly>
                    </div>
                </div>
            </div>

            {{-- Botones de acción --}}
            <div class="form-actions">
                <button type="button" class="btn-modern btn-primary" onclick="calcularCosto()">💰 Calcular Costo Total</button>
                <button type="button" class="btn-modern btn-success" id="continuarBtn" onclick="guardarViaje()">➡️ Continuar</button>
            </div>
        </form>
    </div>
</div>

{{-- TODO EL JAVASCRIPT ORIGINAL EXACTO - SIN CAMBIOS --}}
<script>
// Variables globales
let map, directionsService, directionsRenderer, geocoder, trafficLayer;
let origenPlace = null, destinoPlace = null;
let origenMarker = null, destinoMarker = null;
let ubicacionActualMarker = null;
let modoSeleccion = 'ninguno';
let origenAutocomplete, destinoAutocomplete;
let rutasAlternativas = [];
let rutaSeleccionada = 0;
let modoViaje = null;

const consumosVehiculos = {
    carro_economico: 35,
    carro_mediano: 28,
    carro_lujo: 22,
    suv_pequeño: 25,
    suv_grande: 18,
    moto: 60,
    camioneta: 20
};

function initMap() {
    try {
        modoViaje = google.maps.TravelMode.DRIVING;
        
        map = new google.maps.Map(document.getElementById("map"), {
            zoom: 2,
            center: { lat: 20, lng: 0 },
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            streetViewControl: true,
            fullscreenControl: true
        });

        directionsService = new google.maps.DirectionsService();
        directionsRenderer = new google.maps.DirectionsRenderer({
            map: map,
            suppressMarkers: false,
            draggable: true
        });
        
        geocoder = new google.maps.Geocoder();
        trafficLayer = new google.maps.TrafficLayer();

        configurarAutocompletado();
        configurarEventosMapa();
        
        mostrarMensaje("✅ Mapa cargado correctamente. Selecciona origen y destino.", "success");
        obtenerMiUbicacion();
        
    } catch (error) {
        console.error("Error al inicializar el mapa:", error);
        mostrarMensaje("❌ Error al cargar el mapa: " + error.message, "error");
    }
}

function configurarAutocompletado() {
    try {
        origenAutocomplete = new google.maps.places.Autocomplete(
            document.getElementById("origen_input"), {
                fields: ['place_id', 'geometry', 'name', 'formatted_address']
            });

        destinoAutocomplete = new google.maps.places.Autocomplete(
            document.getElementById("destino_input"), {
                fields: ['place_id', 'geometry', 'name', 'formatted_address']
            });

        origenAutocomplete.addListener('place_changed', function() {
            const place = origenAutocomplete.getPlace();
            if (place.geometry) seleccionarOrigenAutomatico(place);
        });

        destinoAutocomplete.addListener('place_changed', function() {
            const place = destinoAutocomplete.getPlace();
            if (place.geometry) seleccionarDestinoAutomatico(place);
        });
        
    } catch (error) {
        console.error("Error en autocompletado:", error);
        mostrarMensaje("⚠️ Autocompletado no disponible. Usa selección manual.", "warning");
    }
}

function configurarEventosMapa() {
    map.addListener('click', function(event) {
        if (modoSeleccion === 'ninguno') return;
        
        const location = event.latLng;
        
        if (modoSeleccion === 'origen') {
            seleccionarOrigen(location);
        } else if (modoSeleccion === 'destino') {
            seleccionarDestino(location);
        }
    });
}

function obtenerMiUbicacion() {
    if (navigator.geolocation) {
        mostrarMensaje("📍 Buscando tu ubicación...", "info");
        
        navigator.geolocation.getCurrentPosition(
            function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                const ubicacion = new google.maps.LatLng(lat, lng);
                
                map.setCenter(ubicacion);
                map.setZoom(14);
                
                if (ubicacionActualMarker) ubicacionActualMarker.setMap(null);
                
                ubicacionActualMarker = new google.maps.Marker({
                    position: ubicacion,
                    map: map,
                    title: 'Tu ubicación actual',
                    icon: {
                        url: 'https://maps.google.com/mapfiles/ms/icons/blue-dot.png',
                        scaledSize: new google.maps.Size(40, 40)
                    }
                });
                
                geocoder.geocode({ location: ubicacion }, function(results, status) {
                    if (status === 'OK' && results[0]) {
                        const direccion = results[0].formatted_address;
                        document.getElementById("origen_input").value = direccion;
                        origenPlace = {
                            geometry: { location: ubicacion },
                            formatted_address: direccion
                        };
                        document.getElementById("origen_coords").value = `${lat.toFixed(6)}, ${lng.toFixed(6)}`;
                        document.getElementById("origen_direccion").value = direccion;
                    }
                });
            },
            function(error) {
                mostrarMensaje("❌ No se pudo obtener tu ubicación. Usa selección manual.", "error");
            }
        );
    } else {
        mostrarMensaje("❌ Geolocalización no soportada en este navegador.", "error");
    }
}

function toggleTrafico() {
    const btn = document.getElementById('traffic-btn');
    if (trafficLayer.getMap()) {
        trafficLayer.setMap(null);
        btn.textContent = '🚦 Activar Tráfico';
    } else {
        trafficLayer.setMap(map);
        btn.textContent = '🚦 Desactivar Tráfico';
    }
}

function activarSeleccionOrigen() {
    modoSeleccion = 'origen';
    mostrarMensaje("🟢 Modo ORIGEN activado. Haz clic en el mapa.", "info");
    document.body.style.cursor = 'crosshair';
}

function activarSeleccionDestino() {
    modoSeleccion = 'destino';
    mostrarMensaje("🔴 Modo DESTINO activado. Haz clic en el mapa.", "info");
    document.body.style.cursor = 'crosshair';
}

function seleccionarOrigenAutomatico(place) {
    const location = place.geometry.location;
    
    if (origenMarker) origenMarker.setMap(null);
    
    origenMarker = new google.maps.Marker({
        position: location,
        map: map,
        title: 'Origen: ' + (place.name || place.formatted_address),
        icon: {
            url: 'https://maps.google.com/mapfiles/ms/icons/green-dot.png',
            scaledSize: new google.maps.Size(40, 40)
        }
    });
    
    origenPlace = place;
    document.getElementById("origen_coords").value = `${location.lat().toFixed(6)}, ${location.lng().toFixed(6)}`;
    document.getElementById("origen_direccion").value = place.formatted_address || place.name;
    
    map.setCenter(location);
    map.setZoom(14);
    
    if (destinoPlace) calcularRutasAlternativas();
}

function seleccionarDestinoAutomatico(place) {
    const location = place.geometry.location;
    
    if (destinoMarker) destinoMarker.setMap(null);
    
    destinoMarker = new google.maps.Marker({
        position: location,
        map: map,
        title: 'Destino: ' + (place.name || place.formatted_address),
        icon: {
            url: 'https://maps.google.com/mapfiles/ms/icons/red-dot.png',
            scaledSize: new google.maps.Size(40, 40)
        }
    });
    
    destinoPlace = place;
    document.getElementById("destino_coords").value = `${location.lat().toFixed(6)}, ${location.lng().toFixed(6)}`;
    document.getElementById("destino_direccion").value = place.formatted_address || place.name;
    
    if (origenPlace) {
        const bounds = new google.maps.LatLngBounds();
        bounds.extend(origenPlace.geometry.location);
        bounds.extend(location);
        map.fitBounds(bounds);
        calcularRutasAlternativas();
    } else {
        map.setCenter(location);
        map.setZoom(14);
    }
}

function seleccionarOrigen(location) {
    if (origenMarker) origenMarker.setMap(null);
    
    origenMarker = new google.maps.Marker({
        position: location,
        map: map,
        title: 'Punto de Origen',
        icon: {
            url: 'https://maps.google.com/mapfiles/ms/icons/green-dot.png',
            scaledSize: new google.maps.Size(40, 40)
        }
    });
    
    geocoder.geocode({ location: location }, (results, status) => {
        if (status === 'OK' && results[0]) {
            const direccion = results[0].formatted_address;
            origenPlace = {
                geometry: { location: location },
                formatted_address: direccion
            };
            document.getElementById("origen_direccion").value = direccion;
            document.getElementById("origen_input").value = direccion;
        } else {
            origenPlace = {
                geometry: { location: location },
                formatted_address: "Ubicación personalizada"
            };
            document.getElementById("origen_direccion").value = "Ubicación personalizada";
        }
    });
    
    document.getElementById("origen_coords").value = `${location.lat().toFixed(6)}, ${location.lng().toFixed(6)}`;
    
    modoSeleccion = 'ninguno';
    document.body.style.cursor = 'default';
    
    if (destinoPlace) calcularRutasAlternativas();
}

function seleccionarDestino(location) {
    document.getElementById("destino_input").value = '';
    
    if (destinoMarker) destinoMarker.setMap(null);
    
    destinoMarker = new google.maps.Marker({
        position: location,
        map: map,
        title: 'Punto de Destino',
        icon: {
            url: 'https://maps.google.com/mapfiles/ms/icons/red-dot.png',
            scaledSize: new google.maps.Size(40, 40)
        }
    });
    
    const geocodeWithRetry = (retryCount = 0) => {
        geocoder.geocode({ location: location }, (results, status) => {
            if (status === 'OK' && results[0]) {
                const direccion = results[0].formatted_address;
                destinoPlace = {
                    geometry: { location: location },
                    formatted_address: direccion
                };
                document.getElementById("destino_direccion").value = direccion;
                document.getElementById("destino_input").value = direccion;
            } else if (retryCount < 1) {
                setTimeout(() => geocodeWithRetry(retryCount + 1), 500);
            } else {
                destinoPlace = {
                    geometry: { location: location },
                    formatted_address: "Ubicación personalizada"
                };
                document.getElementById("destino_direccion").value = "Ubicación personalizada";
            }
        });
    };
    
    geocodeWithRetry();
    
    document.getElementById("destino_coords").value = `${location.lat().toFixed(6)}, ${location.lng().toFixed(6)}`;
    
    modoSeleccion = 'ninguno';
    document.body.style.cursor = 'default';
    
    if (origenPlace) calcularRutasAlternativas();
}

function calcularRutasAlternativas() {
    if (!origenPlace || !destinoPlace) {
        mostrarMensaje("⚠️ Selecciona origen y destino primero.", "warning");
        return;
    }

    mostrarMensaje("🔄 Calculando rutas...", "info");

    const request = {
        origin: origenPlace.geometry.location,
        destination: destinoPlace.geometry.location,
        travelMode: modoViaje,
        unitSystem: google.maps.UnitSystem.METRIC,
        provideRouteAlternatives: true
    };

    directionsService.route(request, function(result, status) {
        if (status === 'OK') {
            rutasAlternativas = result.routes;
            mostrarOpcionesRuta(result);
            seleccionarRuta(0);
        } else {
            let mensaje = "❌ Error al calcular la ruta.";
            if (status === 'ZERO_RESULTS') mensaje = "❌ No hay rutas disponibles entre estos puntos.";
            mostrarMensaje(mensaje, "error");
        }
    });
}

function mostrarOpcionesRuta(result) {
    const routeOptions = document.getElementById('route-options');
    const routeSelector = document.getElementById('route-selector');
    const routeInfo = document.getElementById('route-info');
    
    routeOptions.style.display = 'block';
    routeSelector.innerHTML = '';
    routeInfo.innerHTML = '';
    
    result.routes.forEach((route, index) => {
        const leg = route.legs[0];
        const distanceKm = leg.distance.value / 1000;
        const duration = leg.duration_in_traffic ? leg.duration_in_traffic.text : leg.duration.text;
        
        const routeBtn = document.createElement('div');
        routeBtn.className = 'route-option';
        routeBtn.id = `route-${index}`;
        routeBtn.onclick = () => seleccionarRuta(index);
        routeBtn.innerHTML = `🛣️ Ruta ${index + 1}<br><small>${distanceKm.toFixed(1)} km • ${duration}</small>`;
        routeSelector.appendChild(routeBtn);
        
        const infoCard = document.createElement('div');
        infoCard.className = 'info-card';
        infoCard.innerHTML = `
            <h4>Ruta ${index + 1}</h4>
            <div class="value">${distanceKm.toFixed(1)} km</div>
            <div style="font-size: 12px; color: #666;">⏱️ ${duration}</div>
        `;
        routeInfo.appendChild(infoCard);
    });
}

function seleccionarRuta(index) {
    rutaSeleccionada = index;
    
    document.querySelectorAll('.route-option').forEach(btn => btn.classList.remove('active'));
    document.getElementById(`route-${index}`).classList.add('active');
    
    directionsRenderer.setDirections({
        routes: [rutasAlternativas[index]],
        request: {
            origin: origenPlace.geometry.location,
            destination: destinoPlace.geometry.location,
            travelMode: modoViaje
        }
    });
    
    const leg = rutasAlternativas[index].legs[0];
    const distanceKm = leg.distance.value / 1000;
    const duration = leg.duration_in_traffic ? leg.duration_in_traffic.text : leg.duration.text;
    
    document.getElementById("distancia_km").value = distanceKm.toFixed(1);
    document.getElementById("tiempo_estimado").value = duration;
    
    if (origenMarker) origenMarker.setVisible(false);
    if (destinoMarker) destinoMarker.setVisible(false);
}

function actualizarConsumoSugerido() {
    const tipoVehiculo = document.getElementById('tipo_vehiculo').value;
    if (tipoVehiculo && consumosVehiculos[tipoVehiculo]) {
        document.getElementById('consumo_km').value = consumosVehiculos[tipoVehiculo];
    }
}

function calcularCosto() {
    const consumo = parseFloat(document.getElementById("consumo_km").value) || 0;
    const precio = parseFloat(document.getElementById("precio_galon").value) || 0;
    const distancia = parseFloat(document.getElementById("distancia_km").value) || 0;
    const peajes = parseInt(document.getElementById("peajes").value) || 0;
    const costoPeaje = parseFloat(document.getElementById("costo_peaje").value) || 0;

    if (distancia <= 0) {
        mostrarMensaje("⚠️ Primero calcula una ruta válida.", "warning");
        return;
    }
    
    if (consumo <= 0 || precio <= 0) {
        mostrarMensaje("⚠️ Completa el consumo y precio de combustible.", "warning");
        return;
    }

    try {
        const unidadesCombustible = distancia / consumo;
        const costoCombustible = unidadesCombustible * precio;
        const costoPeajes = peajes * costoPeaje;
        const costoTotal = costoCombustible + costoPeajes;

        document.getElementById("costo_estimado").value = costoCombustible.toFixed(2);
        document.getElementById("costo_total").value = costoTotal.toFixed(2);
        
        mostrarMensaje("✅ Costo calculado correctamente", "success");
    } catch (error) {
        mostrarMensaje("❌ Error en el cálculo. Revisa los valores.", "error");
    }
}

function guardarViaje() {
    if (!origenPlace || !destinoPlace) {
        mostrarMensaje("⚠️ Completa origen y destino primero.", "warning");
        return;
    }

    const viajeData = {
        origen: {
            direccion: document.getElementById("origen_direccion").value,
            coords: document.getElementById("origen_coords").value
        },
        destino: {
            direccion: document.getElementById("destino_direccion").value,
            coords: document.getElementById("destino_coords").value
        },
        costo: document.getElementById("costo_total").value,
        distancia: document.getElementById("distancia_km").value,
        vehiculo: marcaVehiculo
    };

    localStorage.setItem('ultimoViaje', JSON.stringify(viajeData));
    mostrarMensaje("✅ Datos guardados correctamente", "success");
    
    window.location.href = "{{ route('detalle.viaje') }}";
}

function limpiarTodo() {
    document.getElementById("origen_input").value = '';
    document.getElementById("destino_input").value = '';
    document.getElementById("origen_direccion").value = '';
    document.getElementById("destino_direccion").value = '';
    document.getElementById("origen_coords").value = '';
    document.getElementById("destino_coords").value = '';
    document.getElementById("distancia_km").value = '';
    document.getElementById("tiempo_estimado").value = '';
    document.getElementById("costo_estimado").value = '';
    document.getElementById("costo_total").value = '';
    
    if (origenMarker) origenMarker.setMap(null);
    if (destinoMarker) destinoMarker.setMap(null);
    if (ubicacionActualMarker) ubicacionActualMarker.setMap(null);
    
    directionsRenderer.setDirections({routes: []});
    document.getElementById('route-options').style.display = 'none';
    
    origenPlace = null;
    destinoPlace = null;
    modoSeleccion = 'ninguno';
    rutasAlternativas = [];
    document.body.style.cursor = 'default';
    
    mostrarMensaje("🧹 Todo limpiado. Puedes comenzar de nuevo.", "info");
}

function mostrarMensaje(mensaje, tipo) {
    const statusDiv = document.getElementById("status-message");
    statusDiv.innerHTML = `<div class="status-message status-${tipo}">${mensaje}</div>`;
    
    if (tipo === 'info' || tipo === 'success') {
        setTimeout(() => statusDiv.innerHTML = '', 5000);
    }
}

window.gm_authFailure = function() {
    mostrarMensaje("❌ Error de autenticación con Google Maps", "error");
};

document.addEventListener('DOMContentLoaded', function() {
    console.log("Sistema de planificación de viajes listo");
});
</script>

<script>
    const marcaVehiculo = @json($marca);
</script>

<script async defer 
    src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.key') }}&callback=initMap&libraries=places&language=es">
</script>
@endsection