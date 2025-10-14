@extends('layouts.app_dashboard')

@section('title', 'Planifica tu viaje')

@section('content')
<style>
    :root {
        --primary: #003366;
        --success: #00C853;
        --danger: #FF1744;
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

    @media (max-width: 768px) {
        .search-grid {
            grid-template-columns: 1fr;
        }
        #map {
            height: 450px;
        }
    }
</style>

<div class="container-mapa">
    <div class="page-header">
        <h2>🗺️ Planifica tu viaje</h2>
        <p class="page-subtitle">Buenos Aires, Argentina</p>
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
                <label>🎯 Punto de llegada</label>
                <input type="text" id="destino_input" class="search-input" placeholder="Busca o haz clic en el mapa">
            </div>
        </div>
    </div>

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
                <div class="info-icon">🚗</div>
                <div class="info-label">Modo</div>
                <div class="info-value" style="font-size: 1.2rem;">Auto</div>
            </div>
        </div>
    </div>

    <input type="hidden" id="origen_coords">
    <input type="hidden" id="destino_coords">
    <input type="hidden" id="origen_direccion">
    <input type="hidden" id="destino_direccion">
    <input type="hidden" id="distancia_km">
    <input type="hidden" id="tiempo_estimado">
</div>

<script>
let map;
let directionsService;
let directionsRenderer;
let geocoder;
let origenMarker = null;
let destinoMarker = null;
let paso = 'origen';
let iconoVerde, iconoRojo;

function initMap() {
    console.log('🗺️ Iniciando mapa...');
    
    // AHORA sí podemos usar google.maps porque ya está cargado
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
    
    // Buenos Aires
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

    // Autocompletado
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

    // Click en el mapa
    map.addListener('click', function(e) {
        console.log('🖱️ Click en mapa:', e.latLng.toString());
        console.log('📍 Paso actual:', paso);
        
        if (paso === 'origen') {
            console.log('✅ Colocando origen...');
            ponerOrigen(e.latLng);
        } else if (paso === 'destino') {
            console.log('✅ Colocando destino...');
            ponerDestino(e.latLng);
        }
    });

    console.log('✅ Mapa listo - Haz clic para colocar origen');
    document.getElementById('status').textContent = '✅ Mapa listo - Haz clic para el origen';
}

function ponerOrigen(location) {
    console.log('📍 Poniendo origen en:', location.toString());
    
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
        if (destinoMarker) calcularRuta();
    });

    actualizarOrigen(location);
    
    paso = 'destino';
    document.getElementById('status').textContent = '🎯 Ahora haz clic para el destino';
    console.log('✅ Origen colocado, esperando destino');
    
    if (destinoMarker) calcularRuta();
}

function ponerDestino(location) {
    console.log('🎯 Poniendo destino en:', location.toString());
    
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
    document.getElementById('status').textContent = '✅ ¡Ruta calculada! Puedes arrastrar los puntos';
    console.log('✅ Destino colocado, calculando ruta...');
    
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
            console.log('📍 Dirección origen:', results[0].formatted_address);
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
            console.log('🎯 Dirección destino:', results[0].formatted_address);
        }
    });
}

function calcularRuta() {
    if (!origenMarker || !destinoMarker) {
        console.log('⚠️ Faltan marcadores para calcular ruta');
        return;
    }

    console.log('🔄 Calculando ruta...');

    const request = {
        origin: origenMarker.getPosition(),
        destination: destinoMarker.getPosition(),
        travelMode: google.maps.TravelMode.DRIVING,
        unitSystem: google.maps.UnitSystem.METRIC
    };

    directionsService.route(request, function(result, status) {
        if (status === 'OK') {
            console.log('✅ Ruta calculada exitosamente');
            directionsRenderer.setDirections(result);

            const leg = result.routes[0].legs[0];
            const km = (leg.distance.value / 1000).toFixed(1);
            const tiempo = leg.duration.text;

            document.getElementById('distancia').textContent = km + ' km';
            document.getElementById('tiempo').textContent = tiempo;
            document.getElementById('distancia_km').value = km;
            document.getElementById('tiempo_estimado').value = tiempo;
            
            document.getElementById('route-info').classList.add('show');
            console.log('📊 Distancia:', km, 'km - Tiempo:', tiempo);
        } else {
            console.error('❌ Error calculando ruta:', status);
        }
    });
}

window.onerror = function(msg, url, line) {
    console.error('❌ Error JS:', msg, 'en línea:', line);
};

console.log('📜 Script cargado, esperando Google Maps...');
</script>

<script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.key') }}&libraries=places&callback=initMap&language=es&region=AR" async defer></script>

@endsection