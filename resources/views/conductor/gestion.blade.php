@extends('layouts.app_dashboard')

@section('title', 'Mapa del Conductor Global')

@section('content')
<style>
    .container-mapa {
        max-width: 1200px;
        margin: 0 auto;
        padding: 40px 20px 80px;
        font-family: 'Segoe UI', sans-serif;
    }
    h2 {
        color: #1F4E79;
        text-align: center;
        margin-bottom: 10px;
    }
    .subtitle {
        text-align: center;
        color: #3A3A3A;
        margin-bottom: 30px;
    }
    .inputs-lugar {
        display: flex;
        justify-content: center;
        gap: 20px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }
    .input-group {
        position: relative;
        width: 100%;
        max-width: 400px;
    }
    .inputs-lugar input {
        width: 100%;
        padding: 10px 15px;
        font-size: 16px;
        border: 2px solid #1F4E79;
        border-radius: 10px;
        box-sizing: border-box;
    }
    .pac-container {
        border-radius: 8px;
        border: 1px solid #1F4E79;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        z-index: 9999;
    }
    #map {
        width: 100%;
        height: 500px;
        border-radius: 16px;
        margin-bottom: 20px;
        border: 2px solid #DDF2FE;
    }
    .map-controls {
        text-align: center;
        margin-bottom: 15px;
        display: flex;
        justify-content: center;
        gap: 10px;
        flex-wrap: wrap;
    }
    .location-controls {
        text-align: center;
        margin-bottom: 15px;
        display: flex;
        justify-content: center;
        gap: 10px;
        flex-wrap: wrap;
    }
    .route-options {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 20px;
        display: none;
    }
    .route-selector {
        display: flex;
        gap: 10px;
        margin-bottom: 15px;
        flex-wrap: wrap;
        justify-content: center;
    }
    .route-option {
        padding: 8px 15px;
        border: 2px solid #6c757d;
        background: white;
        border-radius: 8px;
        cursor: pointer;
        font-size: 13px;
        transition: all 0.3s;
    }
    .route-option:hover {
        background: #f8f9fa;
    }
    .route-option.active {
        background: #1F4E79;
        color: white;
        border-color: #1F4E79;
    }
    .route-info {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin-bottom: 15px;
    }
    .info-card {
        background: white;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 12px;
        text-align: center;
    }
    .info-card h4 {
        margin: 0 0 8px 0;
        color: #1F4E79;
        font-size: 14px;
    }
    .info-card .value {
        font-size: 16px;
        font-weight: bold;
        color: #333;
    }
    .traffic-info {
        display: flex;
        justify-content: center;
        gap: 10px;
        margin-bottom: 15px;
        flex-wrap: wrap;
    }
    .traffic-toggle {
        padding: 8px 15px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 13px;
        transition: all 0.3s;
    }
    .form-box {
        background-color: #DDF2FE;
        border: 2px solid #1F4E79;
        border-radius: 20px;
        padding: 30px;
    }
    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }
    .form-group label {
        font-weight: bold;
        margin-bottom: 5px;
        display: block;
    }
    .form-group input,
    .form-group select {
        padding: 10px;
        width: 100%;
        border-radius: 8px;
        border: 1px solid #ccc;
        box-sizing: border-box;
    }
    .form-group input[readonly] {
        background-color: #f1f1f1;
    }
    .form-actions {
        text-align: center;
        margin-top: 30px;
    }
    .btn-primary {
        background-color: #1F4E79;
        color: white;
        padding: 12px 30px;
        border: none;
        border-radius: 10px;
        font-weight: bold;
        cursor: pointer;
    }
    .btn-primary:hover {
        background-color: #163b5a;
    }
    .btn-secondary {
        background-color: #6c757d;
        color: white;
        padding: 10px 15px;
        border: none;
        border-radius: 8px;
        font-weight: bold;
        cursor: pointer;
        margin: 0 5px;
        font-size: 14px;
    }
    .btn-secondary:hover {
        background-color: #545b62;
    }
    .btn-success {
        background-color: #28a745;
        color: white;
        padding: 10px 15px;
        border: none;
        border-radius: 8px;
        font-weight: bold;
        cursor: pointer;
        margin: 0 5px;
        font-size: 14px;
    }
    .btn-danger {
        background-color: #dc3545;
        color: white;
        padding: 10px 15px;
        border: none;
        border-radius: 8px;
        font-weight: bold;
        cursor: pointer;
        margin: 0 5px;
        font-size: 14px;
    }
    .btn-info {
        background-color: #17a2b8;
        color: white;
        padding: 10px 15px;
        border: none;
        border-radius: 8px;
        font-weight: bold;
        cursor: pointer;
        margin: 0 5px;
        font-size: 14px;
    }
    .status-message {
        margin: 10px 0;
        padding: 10px;
        border-radius: 5px;
        text-align: center;
        font-weight: bold;
    }
    .status-info {
        background-color: #d1ecf1;
        color: #0c5460;
        border: 1px solid #bee5eb;
    }
    .status-warning {
        background-color: #fff3cd;
        color: #856404;
        border: 1px solid #ffeeba;
    }
    .status-error {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
    .status-success {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }
    @media(max-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr;
        }
        .inputs-lugar {
            flex-direction: column;
        }
        .map-controls, .location-controls, .route-selector, .traffic-info {
            flex-direction: column;
            align-items: center;
        }
        .route-info {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="container-mapa">
    <h2>🌍 Navegador Global</h2>
    <p class="subtitle">Encuentra rutas en cualquier parte del mundo con tráfico en tiempo real</p>

    <div class="location-controls">
        <button class="btn-info" onclick="obtenerMiUbicacion()">📍 Mi Ubicación Actual</button>
        <button class="btn-secondary" onclick="centrarMundial()">🌍 Vista Mundial</button>
        <button class="btn-secondary" onclick="limpiarTodo()">🗑️ Limpiar Todo</button>
    </div>

    <div class="inputs-lugar">
        <div class="input-group">
            <input type="text" id="origen_input" placeholder="🚩 Buscar origen en cualquier parte del mundo">
        </div>
        <div class="input-group">
            <input type="text" id="destino_input" placeholder="🗺️ Buscar destino en cualquier parte del mundo">
        </div>
    </div>

    <div class="map-controls">
        <button class="btn-success" onclick="activarSeleccionOrigen()">📍 Seleccionar Origen en Mapa</button>
        <button class="btn-danger" onclick="activarSeleccionDestino()">📍 Seleccionar Destino en Mapa</button>
    </div>

    <div id="status-message"></div>

    <div class="route-options" id="route-options">
        <h4 style="text-align: center; margin-bottom: 15px; color: #1F4E79;">🛣️ Opciones de Ruta</h4>
        
        <div class="route-selector" id="route-selector">
            <!-- Se llenan dinámicamente -->
        </div>
        
        <div class="route-info" id="route-info">
            <!-- Se llenan dinámicamente -->
        </div>
        
        <div class="traffic-info">
            <button class="traffic-toggle" id="traffic-btn" onclick="toggleTrafico()" style="background: #17a2b8; color: white;">
                🚦 Activar Tráfico
            </button>
            <button class="traffic-toggle" onclick="cambiarModoViaje('DRIVING')" style="background: #007bff; color: white;">🚗 Auto</button>
            <button class="traffic-toggle" onclick="cambiarModoViaje('TRANSIT')" style="background: #6f42c1; color: white;">🚌 Transporte</button>
            <button class="traffic-toggle" onclick="cambiarModoViaje('WALKING')" style="background: #28a745; color: white;">🚶 Caminar</button>
            <button class="traffic-toggle" onclick="cambiarModoViaje('BICYCLING')" style="background: #fd7e14; color: white;">🚴 Bicicleta</button>
        </div>
    </div>

    <div id="map"></div>

    <div class="form-box">
        <h3 style="text-align:center; font-size: 24px; color: #1F4E79; margin-bottom: 25px;">
            💰 Calculadora de Costos de Viaje
        </h3>
        <form class="form-grid">
            <div class="form-group">
                <label>Origen</label>
                <input type="text" id="origen_direccion" readonly>
            </div>
            <div class="form-group">
                <label>Destino</label>
                <input type="text" id="destino_direccion" readonly>
            </div>
            <div class="form-group">
                <label>Coordenadas Origen (Lat, Lng)</label>
                <input type="text" id="origen_coords" readonly>
            </div>
            <div class="form-group">
                <label>Coordenadas Destino (Lat, Lng)</label>
                <input type="text" id="destino_coords" readonly>
            </div>
            <div class="form-group">
                <label>Tipo de vehículo</label>
                <select id="tipo_vehiculo" onchange="actualizarConsumoSugerido()">
                    <option value="">Selecciona un vehículo</option>
                    <option value="carro_economico">🚗 Carro Económico (35 km/galón)</option>
                    <option value="carro_mediano">🚙 Carro Mediano (28 km/galón)</option>
                    <option value="carro_lujo">🚘 Carro de Lujo (22 km/galón)</option>
                    <option value="suv_pequeño">🚙 SUV Pequeño (25 km/galón)</option>
                    <option value="suv_grande">🚐 SUV Grande (18 km/galón)</option>
                    <option value="moto">🏍️ Motocicleta (60 km/galón)</option>
                    <option value="camioneta">🛻 Camioneta (20 km/galón)</option>
                </select>
            </div>
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
        </form>

        <div class="form-actions">
            <button class="btn-primary" onclick="calcularCosto()">💰 Calcular Costo Total</button>
        </div>
    </div>
</div>

<script>
// Variables globales - declaradas al inicio
let map, directionsService, directionsRenderer, geocoder, trafficLayer;
let origenPlace = null, destinoPlace = null;
let origenMarker = null, destinoMarker = null;
let ubicacionActualMarker = null;
let modoSeleccion = 'ninguno';
let origenAutocomplete, destinoAutocomplete;
let rutasAlternativas = [];
let rutaSeleccionada = 0;
let modoViaje = null; // Se inicializa después

// Consumos típicos por tipo de vehículo
const consumosVehiculos = {
    carro_economico: 35,
    carro_mediano: 28,
    carro_lujo: 22,
    suv_pequeño: 25,
    suv_grande: 18,
    moto: 60,
    camioneta: 20
};

// Inicialización del mapa
function initMap() {
    try {
        console.log("Inicializando mapa global...");
        
        // Inicializar modoViaje aquí cuando Google Maps esté cargado
        modoViaje = google.maps.TravelMode.DRIVING;
        
        // Crear el mapa con vista mundial
        map = new google.maps.Map(document.getElementById("map"), {
            zoom: 2,
            center: { lat: 20, lng: 0 }, // Vista mundial
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            streetViewControl: true,
            fullscreenControl: true,
            mapTypeControl: true,
            zoomControl: true,
            scaleControl: true
        });

        // Inicializar servicios
        directionsService = new google.maps.DirectionsService();
        directionsRenderer = new google.maps.DirectionsRenderer({
            map: map,
            suppressMarkers: false,
            draggable: true,
            panel: null,
            preserveViewport: false
        });
        
        geocoder = new google.maps.Geocoder();
        
        // Capa de tráfico
        trafficLayer = new google.maps.TrafficLayer();

        // Configurar autocompletado global
        configurarAutocompletado();
        
        // Configurar eventos del mapa
        configurarEventosMapa();
        
        mostrarMensaje("🌍 Mapa global cargado correctamente. Haz clic en 'Mi Ubicación' para empezar.", "success");
        console.log("Mapa inicializado exitosamente");
        
        // Intentar obtener ubicación automáticamente al cargar
        obtenerMiUbicacion();
        
    } catch (error) {
        console.error("Error al inicializar el mapa:", error);
        mostrarMensaje("❌ Error al cargar el mapa: " + error.message, "error");
    }
}

// Configurar autocompletado global (sin restricciones de país)
function configurarAutocompletado() {
    try {
        const origenInput = document.getElementById("origen_input");
        const destinoInput = document.getElementById("destino_input");

        // Autocompletado global para origen
        origenAutocomplete = new google.maps.places.Autocomplete(origenInput, {
            types: ['geocode', 'establishment'],
            fields: ['place_id', 'geometry', 'name', 'formatted_address', 'address_components']
        });

        // Autocompletado global para destino
        destinoAutocomplete = new google.maps.places.Autocomplete(destinoInput, {
            types: ['geocode', 'establishment'],
            fields: ['place_id', 'geometry', 'name', 'formatted_address', 'address_components']
        });

        // Eventos de selección
        origenAutocomplete.addListener('place_changed', function() {
            const place = origenAutocomplete.getPlace();
            if (place.geometry) {
                seleccionarOrigenAutomatico(place);
            }
        });

        destinoAutocomplete.addListener('place_changed', function() {
            const place = destinoAutocomplete.getPlace();
            if (place.geometry) {
                seleccionarDestinoAutomatico(place);
            }
        });

        console.log("Autocompletado global configurado");
    } catch (error) {
        console.error("Error al configurar autocompletado:", error);
        mostrarMensaje("⚠️ Autocompletado no disponible. Usa selección manual en el mapa.", "warning");
    }
}

// Configurar eventos del mapa
function configurarEventosMapa() {
    map.addListener('click', function(event) {
        if (modoSeleccion === 'ninguno') {
            mostrarMensaje("💡 Haz clic en 'Seleccionar Origen' o 'Seleccionar Destino' primero.", "warning");
            return;
        }
        
        const location = event.latLng;
        
        if (modoSeleccion === 'origen') {
            seleccionarOrigen(location);
        } else if (modoSeleccion === 'destino') {
            seleccionarDestino(location);
        }
    });
}

// Obtener ubicación actual del usuario
function obtenerMiUbicacion() {
    if (navigator.geolocation) {
        mostrarMensaje("📍 Buscando tu ubicación...", "info");
        
        navigator.geolocation.getCurrentPosition(
            function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                const ubicacion = new google.maps.LatLng(lat, lng);
                
                // Centrar mapa en la ubicación actual
                map.setCenter(ubicacion);
                map.setZoom(14);
                
                // Crear marcador de ubicación actual
                if (ubicacionActualMarker) {
                    ubicacionActualMarker.setMap(null);
                }
                
                ubicacionActualMarker = new google.maps.Marker({
                    position: ubicacion,
                    map: map,
                    title: 'Tu ubicación actual',
                    icon: {
                        url: 'https://maps.google.com/mapfiles/ms/icons/blue-dot.png',
                        scaledSize: new google.maps.Size(40, 40)
                    },
                    animation: google.maps.Animation.DROP
                });
                
                // Obtener dirección de la ubicación actual
                geocoder.geocode({ location: ubicacion }, function(results, status) {
                    if (status === 'OK' && results[0]) {
                        const direccion = results[0].formatted_address;
                        mostrarMensaje(`📍 Ubicación encontrada: ${direccion}`, "success");
                        
                        // Prellenar el campo origen con la ubicación actual
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
                let mensaje = "❌ No se pudo obtener tu ubicación: ";
                switch(error.code) {
                    case error.PERMISSION_DENIED:
                        mensaje += "Permiso denegado. Permite el acceso a la ubicación en tu navegador.";
                        break;
                    case error.POSITION_UNAVAILABLE:
                        mensaje += "Ubicación no disponible.";
                        break;
                    case error.TIMEOUT:
                        mensaje += "Tiempo de espera agotado.";
                        break;
                    default:
                        mensaje += "Error desconocido.";
                        break;
                }
                mostrarMensaje(mensaje, "error");
                
                // Mostrar vista mundial como fallback
                centrarMundial();
            }
        );
    } else {
        mostrarMensaje("❌ Geolocalización no soportada en este navegador.", "error");
        centrarMundial();
    }
}

// Centrar en vista mundial
function centrarMundial() {
    map.setCenter({ lat: 20, lng: 0 });
    map.setZoom(2);
    mostrarMensaje("🌍 Vista mundial activada", "info");
}

// Activar/desactivar capa de tráfico
function toggleTrafico() {
    const btn = document.getElementById('traffic-btn');
    if (trafficLayer.getMap()) {
        trafficLayer.setMap(null);
        btn.textContent = '🚦 Activar Tráfico';
        btn.style.background = '#17a2b8';
    } else {
        trafficLayer.setMap(map);
        btn.textContent = '🚦 Desactivar Tráfico';
        btn.style.background = '#dc3545';
    }
}

// Cambiar modo de viaje
function cambiarModoViaje(modo) {
    modoViaje = google.maps.TravelMode[modo];
    let textoModo = '';
    switch(modo) {
        case 'DRIVING': textoModo = 'Conduciendo'; break;
        case 'TRANSIT': textoModo = 'Transporte público'; break;
        case 'WALKING': textoModo = 'Caminando'; break;
        case 'BICYCLING': textoModo = 'En bicicleta'; break;
    }
    mostrarMensaje(`🔄 Modo cambiado a: ${textoModo}`, "info");
    
    // Recalcular ruta si existe
    if (origenPlace && destinoPlace) {
        calcularRutasAlternativas();
    }
}

// Activar selección manual
function activarSeleccionOrigen() {
    modoSeleccion = 'origen';
    mostrarMensaje("🟢 Modo ORIGEN activado. Haz clic en cualquier lugar del mapa.", "info");
    document.body.style.cursor = 'crosshair';
}

function activarSeleccionDestino() {
    modoSeleccion = 'destino';
    mostrarMensaje("🔴 Modo DESTINO activado. Haz clic en cualquier lugar del mapa.", "info");
    document.body.style.cursor = 'crosshair';
}

// Seleccionar origen automáticamente desde autocompletado
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
        },
        animation: google.maps.Animation.DROP
    });
    
    origenPlace = place;
    
    document.getElementById("origen_coords").value = 
        `${location.lat().toFixed(6)}, ${location.lng().toFixed(6)}`;
    document.getElementById("origen_direccion").value = place.formatted_address || place.name;
    
    map.setCenter(location);
    map.setZoom(14);
    
    mostrarMensaje("✅ Origen seleccionado: " + (place.name || place.formatted_address), "success");
    
    if (destinoPlace) {
        calcularRutasAlternativas();
    }
}

// Seleccionar destino automáticamente desde autocompletado
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
        },
        animation: google.maps.Animation.DROP
    });
    
    destinoPlace = place;
    
    document.getElementById("destino_coords").value = 
        `${location.lat().toFixed(6)}, ${location.lng().toFixed(6)}`;
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
    
    mostrarMensaje("✅ Destino seleccionado: " + (place.name || place.formatted_address), "success");
}

// Seleccionar puntos manualmente en el mapa
function seleccionarOrigen(location) {
    if (origenMarker) origenMarker.setMap(null);
    
    origenMarker = new google.maps.Marker({
        position: location,
        map: map,
        title: 'Punto de Origen',
        icon: {
            url: 'https://maps.google.com/mapfiles/ms/icons/green-dot.png',
            scaledSize: new google.maps.Size(40, 40)
        },
        animation: google.maps.Animation.DROP
    });
    
    // Geocodificación inversa para obtener dirección
    geocoder.geocode({ location: location }, function(results, status) {
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
            document.getElementById("origen_input").value = "Ubicación seleccionada en mapa";
        }
    });
    
    document.getElementById("origen_coords").value = 
        `${location.lat().toFixed(6)}, ${location.lng().toFixed(6)}`;
    
    modoSeleccion = 'ninguno';
    document.body.style.cursor = 'default';
    mostrarMensaje("✅ Origen seleccionado manualmente en el mapa.", "success");
    
    if (destinoPlace) {
        calcularRutasAlternativas();
    }
}

function seleccionarDestino(location) {
    if (destinoMarker) destinoMarker.setMap(null);
    
    destinoMarker = new google.maps.Marker({
        position: location,
        map: map,
        title: 'Punto de Destino',
        icon: {
            url: 'https://maps.google.com/mapfiles/ms/icons/red-dot.png',
            scaledSize: new google.maps.Size(40, 40)
        },
        animation: google.maps.Animation.DROP
    });
    
    // Geocodificación inversa para obtener dirección
    geocoder.geocode({ location: location }, function(results, status) {
        if (status === 'OK' && results[0]) {
            const direccion = results[0].formatted_address;
            destinoPlace = {
                geometry: { location: location },
                formatted_address: direccion
            };
            document.getElementById("destino_direccion").value = direccion;
            document.getElementById("destino_input").value = direccion;
        } else {
            destinoPlace = {
                geometry: { location: location },
                formatted_address: "Ubicación personalizada"
            };
            document.getElementById("destino_direccion").value = "Ubicación personalizada";
            document.getElementById("destino_input").value = "Ubicación seleccionada en mapa";
        }
    });
    
    document.getElementById("destino_coords").value = 
        `${location.lat().toFixed(6)}, ${location.lng().toFixed(6)}`;
    
    modoSeleccion = 'ninguno';
    document.body.style.cursor = 'default';
    mostrarMensaje("✅ Destino seleccionado manualmente en el mapa.", "success");
    
    if (origenPlace) {
        calcularRutasAlternativas();
    }
}

// Calcular rutas alternativas
function calcularRutasAlternativas() {
    if (!origenPlace || !destinoPlace) {
        mostrarMensaje("⚠️ Selecciona tanto el origen como el destino.", "warning");
        return;
    }

    mostrarMensaje("🔄 Calculando rutas alternativas...", "info");

    const request = {
        origin: origenPlace.geometry.location,
        destination: destinoPlace.geometry.location,
        travelMode: modoViaje,
        unitSystem: google.maps.UnitSystem.METRIC,
        avoidHighways: false,
        avoidTolls: false,
        provideRouteAlternatives: true
    };

    // Agregar opciones de tráfico solo para modo DRIVING
    if (modoViaje === google.maps.TravelMode.DRIVING) {
        request.drivingOptions = {
            departureTime: new Date(),
            trafficModel: google.maps.TrafficModel.BEST_GUESS
        };
    }

    directionsService.route(request, function(result, status) {
        if (status === 'OK') {
            rutasAlternativas = result.routes;
            mostrarOpcionesRuta(result);
            seleccionarRuta(0);
        } else {
            console.error("Error al calcular rutas:", status);
            let mensaje = "❌ Error al calcular la ruta.";
            if (status === 'NOT_FOUND') {
                mensaje = "❌ No se encontró una ruta entre estos puntos.";
            } else if (status === 'ZERO_RESULTS') {
                mensaje = "❌ No hay rutas disponibles entre estos puntos.";
            }
            mostrarMensaje(mensaje, "error");
        }
    });
}

// Mostrar opciones de ruta
function mostrarOpcionesRuta(result) {
    const routeOptions = document.getElementById('route-options');
    const routeSelector = document.getElementById('route-selector');
    const routeInfo = document.getElementById('route-info');
    
    routeOptions.style.display = 'block';
    
    // Limpiar opciones anteriores
    routeSelector.innerHTML = '';
    routeInfo.innerHTML = '';
    
    result.routes.forEach((route, index) => {
        const leg = route.legs[0];
        const distanceKm = leg.distance.value / 1000;
        const duration = leg.duration_in_traffic ? leg.duration_in_traffic.text : leg.duration.text;
        const trafficDelay = leg.duration_in_traffic ? 
            (leg.duration_in_traffic.value - leg.duration.value) / 60 : 0;
        
        // Botón de selección de ruta
        const routeBtn = document.createElement('div');
        routeBtn.className = 'route-option';
        routeBtn.id = `route-${index}`;
        routeBtn.onclick = () => seleccionarRuta(index);
        routeBtn.innerHTML = `
            🛣️ Ruta ${index + 1}<br>
            <small>${distanceKm.toFixed(1)} km • ${duration}</small>
        `;
        routeSelector.appendChild(routeBtn);
        
        // Información detallada
        const infoCard = document.createElement('div');
        infoCard.className = 'info-card';
        infoCard.innerHTML = `
            <h4>Ruta ${index + 1}</h4>
            <div class="value">${distanceKm.toFixed(1)} km</div>
            <div style="font-size: 12px; color: #666;">
                ⏱️ ${duration}<br>
                ${trafficDelay > 0 ? `🚨 +${Math.round(trafficDelay)} min por tráfico` : '✅ Tráfico normal'}
            </div>
        `;
        routeInfo.appendChild(infoCard);
    });
    
    // Seleccionar primera ruta por defecto
    if (document.getElementById('route-0')) {
        document.getElementById('route-0').classList.add('active');
    }
}

// Seleccionar una ruta específica
function seleccionarRuta(index) {
    rutaSeleccionada = index;
    
    // Actualizar botones
    document.querySelectorAll('.route-option').forEach(btn => btn.classList.remove('active'));
    const routeBtn = document.getElementById(`route-${index}`);
    if (routeBtn) {
        routeBtn.classList.add('active');
    }
    
    // Mostrar ruta en el mapa
    const selectedRoute = rutasAlternativas[index];
    directionsRenderer.setDirections({
        routes: [selectedRoute],
        request: {
            origin: origenPlace.geometry.location,
            destination: destinoPlace.geometry.location,
            travelMode: modoViaje
        }
    });
    
    // Actualizar información
    const leg = selectedRoute.legs[0];
    const distanceKm = leg.distance.value / 1000;
    const duration = leg.duration_in_traffic ? leg.duration_in_traffic.text : leg.duration.text;
    
    document.getElementById("distancia_km").value = distanceKm.toFixed(1);
    document.getElementById("tiempo_estimado").value = duration;
    
    // Ocultar marcadores individuales
    if (origenMarker) origenMarker.setVisible(false);
    if (destinoMarker) destinoMarker.setVisible(false);
    
    mostrarMensaje(`✅ Ruta ${index + 1} seleccionada: ${distanceKm.toFixed(1)} km en ${duration}`, "success");
}

// Actualizar consumo sugerido según vehículo
function actualizarConsumoSugerido() {
    const tipoVehiculo = document.getElementById('tipo_vehiculo').value;
    const consumoInput = document.getElementById('consumo_km');
    
    if (tipoVehiculo && consumosVehiculos[tipoVehiculo]) {
        consumoInput.value = consumosVehiculos[tipoVehiculo];
    }
}

// Calcular costo del viaje
function calcularCosto() {
    const consumo = parseFloat(document.getElementById("consumo_km").value) || 0;
    const precio = parseFloat(document.getElementById("precio_galon").value) || 0;
    const distancia = parseFloat(document.getElementById("distancia_km").value) || 0;
    const peajes = parseInt(document.getElementById("peajes").value) || 0;
    const costoPeaje = parseFloat(document.getElementById("costo_peaje").value) || 0;

    if (distancia <= 0) {
        mostrarMensaje("⚠️ Primero calcula una ruta entre origen y destino.", "warning");
        return;
    }
    
    if (consumo <= 0 || precio <= 0) {
        mostrarMensaje("⚠️ Completa el consumo del vehículo y el precio del combustible.", "warning");
        return;
    }

    try {
        const unidadesCombustible = distancia / consumo;
        const costoCombustible = unidadesCombustible * precio;
        const costoPeajes = peajes * costoPeaje;
        const costoTotal = costoCombustible + costoPeajes;

        // Formatear números con separadores de miles
        const formatear = (num) => num.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });

        document.getElementById("costo_estimado").value = formatear(costoCombustible);
        document.getElementById("costo_total").value = formatear(costoTotal);
        
        mostrarMensaje(`💰 Cálculo completado: ${unidadesCombustible.toFixed(2)} unidades de combustible necesarias`, "success");
        
    } catch (error) {
        console.error("Error al calcular costo:", error);
        mostrarMensaje("❌ Error en el cálculo. Revisa los valores ingresados.", "error");
    }
}

// Limpiar todo
function limpiarTodo() {
    // Limpiar inputs
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
    
    // Limpiar marcadores
    if (origenMarker) {
        origenMarker.setMap(null);
        origenMarker = null;
    }
    if (destinoMarker) {
        destinoMarker.setMap(null);
        destinoMarker = null;
    }
    if (ubicacionActualMarker) {
        ubicacionActualMarker.setMap(null);
        ubicacionActualMarker = null;
    }
    
    // Limpiar ruta
    directionsRenderer.setDirections({routes: []});
    
    // Ocultar opciones de ruta
    document.getElementById('route-options').style.display = 'none';
    
    // Resetear variables
    origenPlace = null;
    destinoPlace = null;
    modoSeleccion = 'ninguno';
    rutasAlternativas = [];
    rutaSeleccionada = 0;
    document.body.style.cursor = 'default';
    
    mostrarMensaje("🧹 Todo limpiado. Puedes empezar una nueva búsqueda.", "info");
}

// Mostrar mensajes
function mostrarMensaje(mensaje, tipo) {
    const statusDiv = document.getElementById("status-message");
    statusDiv.innerHTML = `<div class="status-message status-${tipo}">${mensaje}</div>`;
    
    if (tipo === 'info' || tipo === 'success') {
        setTimeout(() => {
            statusDiv.innerHTML = '';
        }, 8000);
    }
}

// Error de autenticación
window.gm_authFailure = function() {
    mostrarMensaje("❌ Error de autenticación con Google Maps. Verifica la clave API y las APIs habilitadas.", "error");
};

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    console.log("DOM cargado, esperando Google Maps API...");
});
</script>

<!-- Cargar Google Maps API -->
<script async defer 
    src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.key') }}&callback=initMap&libraries=places&loading=async&v=3&language=es">
</script>
@endsection