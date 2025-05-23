@extends('layouts.app_dashboard')

@section('title', 'Planifica tu viaje')

@section('content')
<style>
    :root {
        --color-primario: #1F4E79;
        --color-secundario: #DDF2FE;
        --color-texto: #3A3A3A;
        --color-complementario: #4CAF50;
        --color-fondo: #FCFCFD;
    }

    .container-mapa {
        max-width: 1200px;
        margin: 0 auto;
        padding: 40px 20px 80px;
        font-family: 'Segoe UI', sans-serif;
        background-color: var(--color-fondo);
    }
    
    h2 {
        color: var(--color-primario);
        text-align: center;
        margin-bottom: 10px;
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
        border: 2px solid var(--color-primario);
        border-radius: 10px;
        box-sizing: border-box;
    }
    
    #map {
        width: 100%;
        height: 500px;
        border-radius: 16px;
        margin-bottom: 20px;
        border: 2px solid var(--color-secundario);
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
        background-color: var(--color-secundario);
        border: 1px solid var(--color-primario);
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
        border: 2px solid var(--color-primario);
        background: white;
        border-radius: 8px;
        cursor: pointer;
        font-size: 13px;
        transition: all 0.3s;
    }
    
    .route-option:hover {
        background: var(--color-secundario);
    }
    
    .route-option.active {
        background: var(--color-primario);
        color: white;
        border-color: var(--color-primario);
    }
    
    .route-info {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin-bottom: 15px;
    }
    
    .info-card {
        background: white;
        border: 1px solid var(--color-primario);
        border-radius: 8px;
        padding: 12px;
        text-align: center;
    }
    
    .info-card h4 {
        margin: 0 0 8px 0;
        color: var(--color-primario);
        font-size: 14px;
    }
    
    .form-box {
        background-color: var(--color-secundario);
        border: 2px solid var(--color-primario);
        border-radius: 20px;
        padding: 30px;
    }
    
    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 20px;
    }

    .form-section {
        margin-bottom: 20px;
        padding: 15px;
        background: white;
        border-radius: 10px;
        border: 1px solid var(--color-primario);
    }
    
    .results-section {
        background: white;
        border: 1px solid var(--color-complementario);
    }
    
    .form-group label {
        font-weight: bold;
        margin-bottom: 5px;
        display: block;
        color: var(--color-texto);
    }
    
    .form-group input,
    .form-group select {
        padding: 10px;
        width: 100%;
        border-radius: 8px;
        border: 1px solid var(--color-primario);
        box-sizing: border-box;
    }
    
    .hidden-coords {
        display: none !important;
    }
    
    .form-actions {
        text-align: center;
        margin-top: 30px;
        display: flex;
        justify-content: center;
        gap: 15px;
    }
    
    .btn-primary {
        background-color: var(--color-primario);
        color: white;
        padding: 12px 30px;
        border: none;
        border-radius: 10px;
        font-weight: bold;
        cursor: pointer;
    }
    
    .btn-success {
        background-color: var(--color-complementario);
        color: white;
        padding: 12px 30px;
        border: none;
        border-radius: 10px;
        font-weight: bold;
        cursor: pointer;
    }
    
    .btn-info {
        background-color: var(--color-primario);
        color: white;
        padding: 10px 15px;
        border: none;
        border-radius: 8px;
        font-weight: bold;
        cursor: pointer;
    }
    
    .btn-secondary {
        background-color: var(--color-texto);
        color: white;
        padding: 10px 15px;
        border: none;
        border-radius: 8px;
        font-weight: bold;
        cursor: pointer;
    }
    
    .traffic-toggle {
        padding: 8px 15px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 13px;
        transition: all 0.3s;
        background: var(--color-primario);
        color: white;
    }
    
    #traffic-btn {
        background: var(--color-complementario) !important;
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
    
    .status-success {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }
    
    .status-error {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
    
    @media(max-width: 768px) {
        .inputs-lugar {
            flex-direction: column;
        }
        
        .map-controls, .location-controls, .route-selector {
            flex-direction: column;
            align-items: center;
        }
        
        .form-actions {
            flex-direction: column;
        }
    }

    .form-grid-responsive {
    display: grid;
    grid-template-columns: 1fr;
    gap: 30px;
}

@media (min-width: 992px) {
    .form-grid-responsive {
        grid-template-columns: repeat(3, 1fr);
    }
}

</style>

<div class="container-mapa">
    <h2>Planifica tu viaje</h2>

    <div class="location-controls">
        <button class="btn-info" onclick="obtenerMiUbicacion()">📍 Mi Ubicación Actual</button>
        <button class="btn-secondary" onclick="limpiarTodo()">🗑️ Limpiar Todo</button>
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
        <button class="btn-info" onclick="activarSeleccionOrigen()">📍 Seleccionar Origen en Mapa</button>
        <button class="btn-info" onclick="activarSeleccionDestino()">📍 Seleccionar Destino en Mapa</button>
    </div>

    <div id="status-message"></div>

    <div id="map"></div>

    <div class="route-options" id="route-options">
        <div class="traffic-info">
            <button class="traffic-toggle" id="traffic-btn" onclick="toggleTrafico()">🚦 Activar Tráfico</button>
        </div>
        <div class="route-selector" id="route-selector"></div>
        <div class="route-info" id="route-info"></div>
    </div>

    <div class="form-box">
        <h3 style="text-align:center; font-size: 24px; color: var(--color-primario); margin-bottom: 25px;">
            💰 Costo de Viaje
        </h3>
        <form id="viajeForm" style="width: 100%;">
    <div class="form-grid-responsive">


        {{-- Columna 1: Origen y Destino --}}
        <div class="form-section">
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
        </div>

        {{-- Columna 3: Resultados --}}
        <div class="form-section results-section">
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
        <button type="button" class="btn-primary" onclick="calcularCosto()">💰 Calcular Costo Total</button>
        <button type="button" class="btn-success" id="continuarBtn" onclick="guardarViaje()">➡️ Continuar</button>
    </div>
</form>


    </div>
</div>

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
    // Resetear el input para evitar conflictos
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
    
    // Función de geocoding con reintento
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
        vehiculo: document.getElementById("tipo_vehiculo").value
    };

    // Aquí puedes enviar los datos al servidor o almacenar localmente
    localStorage.setItem('ultimoViaje', JSON.stringify(viajeData));
    mostrarMensaje("✅ Datos guardados correctamente", "success");
    
    // Redirigir o continuar con el flujo
    window.location.href = "{{ route('detalle.viaje') }}";
}

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
    if (origenMarker) origenMarker.setMap(null);
    if (destinoMarker) destinoMarker.setMap(null);
    if (ubicacionActualMarker) ubicacionActualMarker.setMap(null);
    
    // Limpiar ruta
    directionsRenderer.setDirections({routes: []});
    document.getElementById('route-options').style.display = 'none';
    
    // Resetear variables
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

<script async defer 
    src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.key') }}&callback=initMap&libraries=places&language=es">
</script>
@endsection