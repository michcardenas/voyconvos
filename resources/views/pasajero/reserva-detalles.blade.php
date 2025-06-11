@extends('layouts.app_dashboard')

@section('title', 'Detalle de tu reserva')

@section('content')
<style>
    :root {
        --vcv-primary: #1F4E79;
        --vcv-light: #DDF2FE;
        --vcv-dark: #3A3A3A;
        --vcv-accent: #4CAF50;
        --vcv-bg: #FCFCFD;
    }

    .details-wrapper {
        background: linear-gradient(135deg, #DDF2FE 0%, #FCFCFD 50%, rgba(31, 78, 121, 0.03) 100%);
        min-height: 100vh;
        padding: 2rem 0;
        position: relative;
    }

    .details-wrapper::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: 
            radial-gradient(circle at 20% 80%, rgba(76, 175, 80, 0.05) 0%, transparent 50%),
            radial-gradient(circle at 80% 20%, rgba(31, 78, 121, 0.05) 0%, transparent 50%);
        pointer-events: none;
    }

    .container {
        position: relative;
        z-index: 1;
    }

    .page-header {
        background: linear-gradient(135deg, var(--vcv-primary) 0%, rgba(31, 78, 121, 0.9) 50%, rgba(58, 58, 58, 0.8) 100%);
        color: white;
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 6px 20px rgba(31, 78, 121, 0.15);
        border: 1px solid rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        position: relative;
        overflow: hidden;
    }

    .page-header::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 150px;
        height: 150px;
        background: radial-gradient(circle, rgba(76, 175, 80, 0.2) 0%, transparent 70%);
        border-radius: 50%;
        transform: translate(30%, -30%);
    }

    .page-header h2 {
        margin: 0;
        font-weight: 600;
        font-size: 1.8rem;
        position: relative;
        z-index: 2;
    }

    .info-card {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border-radius: 12px;
        padding: 1.8rem;
        margin-bottom: 1.5rem;
        border: 1px solid rgba(31, 78, 121, 0.12);
        box-shadow: 0 4px 12px rgba(31, 78, 121, 0.08);
        transition: all 0.3s ease;
    }

    .info-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(31, 78, 121, 0.12);
    }

    .card-header-custom {
        display: flex;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 0.8rem;
        border-bottom: 2px solid rgba(31, 78, 121, 0.1);
    }

    .card-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        font-size: 1.1rem;
    }

    .card-icon.route {
        background: rgba(31, 78, 121, 0.1);
        color: var(--vcv-primary);
    }

    .card-icon.driver {
        background: rgba(76, 175, 80, 0.1);
        color: var(--vcv-accent);
    }

    .card-icon.booking {
        background: rgba(221, 242, 254, 0.8);
        color: var(--vcv-primary);
    }

    .card-title {
        margin: 0;
        color: var(--vcv-primary);
        font-weight: 600;
        font-size: 1.1rem;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }

    .info-item {
        background: rgba(221, 242, 254, 0.3);
        padding: 1rem;
        border-radius: 8px;
        border-left: 3px solid var(--vcv-primary);
        transition: all 0.2s ease;
    }

    .info-item:hover {
        background: rgba(221, 242, 254, 0.5);
        border-left-color: var(--vcv-accent);
    }

    .info-label {
        font-weight: 600;
        color: var(--vcv-dark);
        font-size: 0.85rem;
        margin-bottom: 0.3rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .info-value {
        color: var(--vcv-primary);
        font-weight: 500;
        font-size: 1rem;
    }

    .status-badge {
        display: inline-block;
        padding: 0.4rem 1rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-confirmado {
        background: rgba(76, 175, 80, 0.1);
        color: var(--vcv-accent);
        border: 1px solid rgba(76, 175, 80, 0.3);
    }

    .status-pendiente {
        background: rgba(255, 193, 7, 0.1);
        color: #f57c00;
        border: 1px solid rgba(255, 193, 7, 0.3);
    }

    .status-cancelado {
        background: rgba(220, 53, 69, 0.1);
        color: #dc3545;
        border: 1px solid rgba(220, 53, 69, 0.3);
    }

    .map-container {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        border: 1px solid rgba(31, 78, 121, 0.12);
        box-shadow: 0 4px 12px rgba(31, 78, 121, 0.08);
    }

    .map-header {
        display: flex;
        align-items: center;
        margin-bottom: 1rem;
    }

    .map-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: rgba(31, 78, 121, 0.1);
        color: var(--vcv-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        font-size: 1.1rem;
    }

    .map-title {
        margin: 0;
        color: var(--vcv-primary);
        font-weight: 600;
        font-size: 1.1rem;
    }

    #mapa {
        width: 100%;
        height: 400px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .action-section {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border-radius: 12px;
        padding: 1.5rem;
        border: 1px solid rgba(31, 78, 121, 0.12);
        box-shadow: 0 4px 12px rgba(31, 78, 121, 0.08);
        text-align: center;
    }

    .btn-custom {
        border: none;
        border-radius: 25px;
        padding: 0.8rem 2rem;
        font-weight: 600;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
        margin: 0.5rem;
        font-size: 0.9rem;
    }

    .btn-custom.primary {
        background: var(--vcv-primary);
        color: white;
    }

    .btn-custom.primary:hover {
        background: rgba(31, 78, 121, 0.9);
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(31, 78, 121, 0.3);
        color: white;
    }

    .btn-custom.accent {
        background: var(--vcv-accent);
        color: white;
    }

    .btn-custom.accent:hover {
        background: rgba(76, 175, 80, 0.9);
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(76, 175, 80, 0.3);
        color: white;
    }

    .btn-custom.secondary {
        background: rgba(58, 58, 58, 0.1);
        color: var(--vcv-dark);
        border: 1px solid rgba(58, 58, 58, 0.3);
    }

    .btn-custom.secondary:hover {
        background: var(--vcv-dark);
        color: white;
        transform: translateY(-2px);
    }

    .rating-status {
        background: rgba(76, 175, 80, 0.1);
        border: 1px solid rgba(76, 175, 80, 0.3);
        color: var(--vcv-accent);
        padding: 1rem;
        border-radius: 10px;
        margin: 1rem 0;
        text-align: center;
        font-weight: 600;
    }

    .route-summary {
        background: rgba(31, 78, 121, 0.05);
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1rem;
        border: 1px solid rgba(31, 78, 121, 0.1);
    }

    .route-item {
        display: flex;
        align-items: center;
        margin-bottom: 0.5rem;
    }

    .route-item:last-child {
        margin-bottom: 0;
    }

    .route-marker {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        margin-right: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.7rem;
        font-weight: bold;
        color: white;
    }

    .route-marker.origin {
        background: var(--vcv-accent);
    }

    .route-marker.destination {
        background: #dc3545;
    }

    .route-text {
        color: var(--vcv-dark);
        font-weight: 500;
    }

    @media (max-width: 768px) {
        .details-wrapper {
            padding: 1rem 0;
        }
        
        .page-header {
            padding: 1.5rem;
        }
        
        .page-header h2 {
            font-size: 1.5rem;
        }
        
        .info-grid {
            grid-template-columns: 1fr;
        }
        
        .btn-custom {
            width: 100%;
            margin: 0.3rem 0;
        }
    }
</style>

<div class="details-wrapper">
    <div class="container">
        <!-- Header -->
        <div class="page-header">
            <h2>📋 Detalles de tu reserva</h2>
        </div>

        <!-- Información del Viaje -->
        <div class="info-card">
            <div class="card-header-custom">
                <div class="card-icon route">
                    <i class="fas fa-route"></i>
                </div>
                <h5 class="card-title">Información del Viaje</h5>
            </div>
            
            <div class="route-summary">
                <div class="route-item">
                    <div class="route-marker origin">A</div>
                    <div class="route-text">{{ $reserva->viaje->origen_direccion }}</div>
                </div>
                <div class="route-item">
                    <div class="route-marker destination">B</div>
                    <div class="route-text">{{ $reserva->viaje->destino_direccion }}</div>
                </div>
            </div>

            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Origen</div>
                    <div class="info-value">{{ $reserva->viaje->origen_direccion }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Destino</div>
                    <div class="info-value">{{ $reserva->viaje->destino_direccion }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Fecha</div>
                    <div class="info-value">{{ $reserva->viaje->fecha_salida }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Hora</div>
                    <div class="info-value">{{ $reserva->viaje->hora_salida }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Fecha de Salida</div>
                    <div class="info-value">{{ $reserva->viaje->fecha_salida }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Puestos Reservados</div>
                    <div class="info-value">{{ $reserva->cantidad_puestos }}</div>
                </div>
            </div>
        </div>

        <!-- Información del Conductor -->
        <div class="info-card">
            <div class="card-header-custom">
                <div class="card-icon driver">
                    <i class="fas fa-user-tie"></i>
                </div>
                <h5 class="card-title">Conductor</h5>
            </div>
            
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Nombre</div>
                    <div class="info-value">{{ $reserva->viaje->conductor->name ?? 'N/D' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Email</div>
                    <div class="info-value">{{ $reserva->viaje->conductor->email ?? 'N/D' }}</div>
                </div>
            </div>

            @if (!$reserva->calificacionEnviadaPorPasajero())
                <div style="margin-top: 1.5rem; text-align: center;">
                    <a href="{{ route('pasajero.calificar.formulario', $reserva->id) }}" 
                       class="btn-custom accent">
                        <i class="fas fa-star me-2"></i>Calificar al conductor
                    </a>
                </div>
            @else
                <div class="rating-status">
                    <i class="fas fa-check-circle me-2"></i>Ya calificaste al conductor
                </div>
            @endif
        </div>

        <!-- Tu Reserva -->
        <div class="info-card">
            <div class="card-header-custom">
                <div class="card-icon booking">
                    <i class="fas fa-ticket-alt"></i>
                </div>
                <h5 class="card-title">Tu Reserva</h5>
            </div>
            
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Estado</div>
                    <div class="info-value">
                        <span class="status-badge status-{{ strtolower($reserva->estado) }}">
                            {{ ucfirst($reserva->estado) }}
                        </span>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-label">Fecha de Reserva</div>
                    <div class="info-value">{{ $reserva->created_at->format('d/m/Y H:i') }}</div>
                </div>
            </div>
        </div>

       <!-- Mapa -->
<div class="map-container">
    <div class="map-header">
        <div class="map-icon">
            <i class="fas fa-map-marked-alt"></i>
        </div>
        <h5 class="map-title">Ruta del viaje</h5>
    </div>
    <div id="mapa" style="height: 400px; width: 100%; border-radius: 8px; overflow: hidden;"></div>
</div>

<!-- Acciones -->
<div class="action-section">
    <h5 class="mb-3" style="color: var(--vcv-primary); font-weight: 600;">¿Qué quieres hacer?</h5>
    <a href="{{ route('pasajero.dashboard') }}" class="btn-custom secondary">
        <i class="fas fa-arrow-left me-2"></i>Volver al listado
    </a>
    <a href="{{ route('chat.ver', $viaje->id) }}" class="btn-custom primary">
        <i class="fas fa-comments me-2"></i>Abrir Chat
    </a>
</div>

@section('scripts')
<script>
    // 🔍 DEBUG: Verificar datos disponibles
    console.log("📊 Datos del viaje:", {
        viaje_id: {{ $viaje->id }},
        origen_lat: {{ $viaje->origen_lat ?? 'null' }},
        origen_lng: {{ $viaje->origen_lng ?? 'null' }},
        destino_lat: {{ $viaje->destino_lat ?? 'null' }},
        destino_lng: {{ $viaje->destino_lng ?? 'null' }},
        origen_direccion: "{{ $viaje->origen_direccion ?? 'No definido' }}",
        destino_direccion: "{{ $viaje->destino_direccion ?? 'No definido' }}"
    });

    // Variable global para controlar la inicialización
    let mapaInicializado = false;
    
    function initReservaMapa() {
        if (mapaInicializado) {
            console.log("⚠️ Mapa ya inicializado, saltando...");
            return;
        }
        
        console.log("🚀 Iniciando mapa...");
        
        try {
            // ✅ Verificar que Google Maps esté disponible
            if (typeof google === 'undefined' || !google.maps) {
                throw new Error("Google Maps API no está cargada");
            }

            // 📍 Definir coordenadas con validación
            const origenLat = {{ $viaje->origen_lat ?? 'null' }};
            const origenLng = {{ $viaje->origen_lng ?? 'null' }};
            const destinoLat = {{ $viaje->destino_lat ?? 'null' }};
            const destinoLng = {{ $viaje->destino_lng ?? 'null' }};

            console.log("📍 Coordenadas:", {
                origen: [origenLat, origenLng],
                destino: [destinoLat, destinoLng]
            });

            // ❌ Verificar si las coordenadas son válidas
            if (!origenLat || !origenLng || !destinoLat || !destinoLng) {
                throw new Error("Coordenadas del viaje no están configuradas");
            }

            const origen = { lat: origenLat, lng: origenLng };
            const destino = { lat: destinoLat, lng: destinoLng };

            // 🗺️ Verificar que el contenedor del mapa exista
            const mapaDiv = document.getElementById("mapa");
            if (!mapaDiv) {
                throw new Error("No se encontró el elemento #mapa en el DOM");
            }

            // 🎨 Crear el mapa
            const map = new google.maps.Map(mapaDiv, {
                zoom: 10,
                center: origen,
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                styles: [
                    {
                        featureType: "water",
                        elementType: "geometry",
                        stylers: [{ color: "#DDF2FE" }]
                    },
                    {
                        featureType: "road",
                        elementType: "geometry.stroke",
                        stylers: [{ color: "#1F4E79" }, { weight: 0.5 }]
                    }
                ]
            });

            // 📍 Crear marcadores
            const markerOrigen = new google.maps.Marker({
                position: origen,
                map: map,
                title: "Origen: {{ $viaje->origen_direccion ?? 'Punto de origen' }}",
                icon: {
                    url: 'https://maps.google.com/mapfiles/ms/icons/green-dot.png',
                    scaledSize: new google.maps.Size(32, 32)
                }
            });

            const markerDestino = new google.maps.Marker({
                position: destino,
                map: map,
                title: "Destino: {{ $viaje->destino_direccion ?? 'Punto de destino' }}",
                icon: {
                    url: 'https://maps.google.com/mapfiles/ms/icons/red-dot.png',
                    scaledSize: new google.maps.Size(32, 32)
                }
            });

            // 💬 Ventanas de información
            const infoOrigen = new google.maps.InfoWindow({
                content: `
                    <div style="padding: 8px; min-width: 200px;">
                        <h6 style="margin: 0 0 5px 0; color: #28a745;">🟢 Punto de Origen</h6>
                        <p style="margin: 0; font-size: 14px;">{{ $viaje->origen_direccion ?? 'Dirección no especificada' }}</p>
                        <small style="color: #6c757d;">{{ date('d/m/Y H:i', strtotime($viaje->fecha_salida)) }}</small>
                    </div>
                `
            });

            const infoDestino = new google.maps.InfoWindow({
                content: `
                    <div style="padding: 8px; min-width: 200px;">
                        <h6 style="margin: 0 0 5px 0; color: #dc3545;">🔴 Punto de Destino</h6>
                        <p style="margin: 0; font-size: 14px;">{{ $viaje->destino_direccion ?? 'Dirección no especificada' }}</p>
                    </div>
                `
            });

            // 👆 Event listeners para los marcadores
            markerOrigen.addListener('click', () => {
                infoOrigen.open(map, markerOrigen);
                infoDestino.close();
            });

            markerDestino.addListener('click', () => {
                infoDestino.open(map, markerDestino);
                infoOrigen.close();
            });

            // 🛣️ Crear la ruta
            const directionsService = new google.maps.DirectionsService();
            const directionsRenderer = new google.maps.DirectionsRenderer({
                map: map,
                suppressMarkers: true, // Usamos nuestros marcadores personalizados
                polylineOptions: {
                    strokeColor: '#1F4E79',
                    strokeWeight: 4,
                    strokeOpacity: 0.8
                }
            });

            // 📊 Solicitar la ruta
            directionsService.route({
                origin: origen,
                destination: destino,
                travelMode: google.maps.TravelMode.DRIVING
            }, function(response, status) {
                if (status === "OK") {
                    directionsRenderer.setDirections(response);
                    console.log("✅ Ruta cargada exitosamente");
                } else {
                    console.warn("⚠️ No se pudo cargar la ruta:", status);
                    // Si falla la ruta, al menos mostrar los puntos
                    const bounds = new google.maps.LatLngBounds();
                    bounds.extend(origen);
                    bounds.extend(destino);
                    map.fitBounds(bounds);
                }
            });

            mapaInicializado = true;
            console.log("✅ Mapa inicializado correctamente");

        } catch (error) {
            console.error("❌ Error al inicializar el mapa:", error);
            
            // 🚨 Mostrar mensaje de error amigable
            const mapaDiv = document.getElementById("mapa");
            if (mapaDiv) {
                mapaDiv.innerHTML = `
                    <div style="
                        background: linear-gradient(135deg, rgba(220, 53, 69, 0.1) 0%, rgba(220, 53, 69, 0.05) 100%);
                        border: 1px solid rgba(220, 53, 69, 0.3);
                        border-radius: 12px;
                        padding: 2rem;
                        text-align: center;
                        color: #dc3545;
                        height: 100%;
                        display: flex;
                        flex-direction: column;
                        justify-content: center;
                        align-items: center;
                    ">
                        <i class="fas fa-map-marked-alt" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.7;"></i>
                        <h5 style="margin-bottom: 1rem;">Mapa no disponible</h5>
                        <p style="margin-bottom: 0.5rem;">${error.message}</p>
                        <small style="opacity: 0.8;">
                            <strong>Ruta:</strong> {{ $viaje->origen_direccion ?? 'Origen no definido' }} 
                            → {{ $viaje->destino_direccion ?? 'Destino no definido' }}
                        </small>
                    </div>
                `;
            }
        }
    }

    // 🔄 Intentar inicializar cuando el DOM esté listo
    document.addEventListener('DOMContentLoaded', function() {
        console.log("📄 DOM cargado, esperando Google Maps...");
        
        // Verificar cada 500ms si Google Maps está disponible
        let intentos = 0;
        const maxIntentos = 20; // 10 segundos máximo
        
        const verificarGoogleMaps = setInterval(function() {
            intentos++;
            
            if (typeof google !== 'undefined' && google.maps) {
                console.log("✅ Google Maps detectado, iniciando mapa...");
                clearInterval(verificarGoogleMaps);
                initReservaMapa();
            } else if (intentos >= maxIntentos) {
                console.error("❌ Timeout: Google Maps no se cargó en 10 segundos");
                clearInterval(verificarGoogleMaps);
                
                // Mostrar error de timeout
                const mapaDiv = document.getElementById("mapa");
                if (mapaDiv) {
                    mapaDiv.innerHTML = `
                        <div style="
                            background: rgba(255, 193, 7, 0.1);
                            border: 1px solid rgba(255, 193, 7, 0.3);
                            border-radius: 12px;
                            padding: 2rem;
                            text-align: center;
                            color: #f57c00;
                            height: 100%;
                            display: flex;
                            flex-direction: column;
                            justify-content: center;
                            align-items: center;
                        ">
                            <i class="fas fa-exclamation-triangle" style="font-size: 3rem; margin-bottom: 1rem;"></i>
                            <h5>Google Maps no disponible</h5>
                            <p>Verifique la API Key o la conexión a internet</p>
                            <small>Intente recargar la página</small>
                        </div>
                    `;
                }
            }
        }, 500);
    });
</script>

<!-- 🗺️ Script de Google Maps con callback -->
<script async defer
    src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.key') }}&callback=initReservaMapa&libraries=geometry&v=3.55">
</script>
@endsection