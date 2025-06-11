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
@php
    dd([
        'reserva_id' => $reserva->id,
        'viaje_id' => $reserva->viaje->id,
        'coordenadas' => [
            'origen_lat' => $reserva->viaje->origen_lat,
            'origen_lng' => $reserva->viaje->origen_lng,
            'destino_lat' => $reserva->viaje->destino_lat,
            'destino_lng' => $reserva->viaje->destino_lng,
        ],
        'direcciones' => [
            'origen' => $reserva->viaje->origen_direccion,
            'destino' => $reserva->viaje->destino_direccion,
        ]
    ]);
@endphp
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
            <div id="mapa"></div>
        </div>

        <!-- Acciones -->
        <div class="action-section">
            <h5 class="mb-3" style="color: var(--vcv-primary); font-weight: 600;">¿Qué quieres hacer?</h5>
            <a href="{{ route('pasajero.dashboard') }}" class="btn-custom secondary">
                <i class="fas fa-arrow-left me-2"></i>Volver al listado
            </a>
            <a href="{{ route('chat.ver', $reserva->viaje_id ?? $viaje->id) }}" class="btn-custom primary">
                <i class="fas fa-comments me-2"></i>Abrir Chat
            </a>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Variable global para asegurar que Google Maps esté cargado
    let googleMapsLoaded = false;
    
    function initReservaMapa() {
        if (googleMapsLoaded) return;
        googleMapsLoaded = true;
        
        try {
            console.log("✅ Ejecutando initReservaMapa");

            const origen = {
                lat: {{ $reserva->viaje->origen_lat ?? 'null' }},
                lng: {{ $reserva->viaje->origen_lng ?? 'null' }}
            };

            const destino = {
                lat: {{ $reserva->viaje->destino_lat ?? 'null' }},
                lng: {{ $reserva->viaje->destino_lng ?? 'null' }}
            };

            console.log("🛰️ Coordenadas:", origen, destino);

            // Validar coordenadas
            if (!origen.lat || !origen.lng || !destino.lat || !destino.lng) {
                throw new Error("Coordenadas inválidas");
            }

            const mapaDiv = document.getElementById("mapa");
            if (!mapaDiv) {
                throw new Error("No se encontró el div #mapa");
            }

            // Crear el mapa con estilo personalizado
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

            // Crear marcadores personalizados
            const markerOrigen = new google.maps.Marker({
                position: origen,
                map: map,
                title: "Origen: " + "{{ $reserva->viaje->origen_direccion }}",
                icon: {
                    url: 'https://maps.google.com/mapfiles/ms/icons/green-dot.png',
                    scaledSize: new google.maps.Size(32, 32)
                }
            });

            const markerDestino = new google.maps.Marker({
                position: destino,
                map: map,
                title: "Destino: " + "{{ $reserva->viaje->destino_direccion }}",
                icon: {
                    url: 'https://maps.google.com/mapfiles/ms/icons/red-dot.png',
                    scaledSize: new google.maps.Size(32, 32)
                }
            });

            // Crear ventanas de información
            const infoOrigen = new google.maps.InfoWindow({
                content: '<div style="padding:5px;"><strong>🟢 Origen</strong><br>{{ $reserva->viaje->origen_direccion }}</div>'
            });

            const infoDestino = new google.maps.InfoWindow({
                content: '<div style="padding:5px;"><strong>🔴 Destino</strong><br>{{ $reserva->viaje->destino_direccion }}</div>'
            });

            markerOrigen.addListener('click', () => {
                infoOrigen.open(map, markerOrigen);
                infoDestino.close();
            });

            markerDestino.addListener('click', () => {
                infoDestino.open(map, markerDestino);
                infoOrigen.close();
            });

            // Crear la ruta
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

            directionsService.route({
                origin: origen,
                destination: destino,
                travelMode: google.maps.TravelMode.DRIVING
            }, function(response, status) {
                if (status === "OK") {
                    directionsRenderer.setDirections(response);
                    console.log("✅ Ruta mostrada correctamente");
                } else {
                    console.error("❌ Error al cargar ruta:", status);
                    // Mostrar al menos los marcadores si falla la ruta
                    const bounds = new google.maps.LatLngBounds();
                    bounds.extend(origen);
                    bounds.extend(destino);
                    map.fitBounds(bounds);
                }
            });

        } catch (error) {
            console.error("❌ Error en initReservaMapa:", error);
            document.getElementById("mapa").innerHTML = 
                `<div style='background: rgba(220, 53, 69, 0.1); border: 1px solid rgba(220, 53, 69, 0.3); border-radius: 8px; padding: 2rem; text-align: center; color: #dc3545;'>
                    <i class="fas fa-exclamation-triangle" style="font-size: 2rem; margin-bottom: 1rem;"></i>
                    <h5>Error al cargar el mapa</h5>
                    <p>${error.message}</p>
                    <small>Coordenadas: Origen ({{ $reserva->viaje->origen_lat }}, {{ $reserva->viaje->origen_lng }}) - Destino ({{ $reserva->viaje->destino_lat }}, {{ $reserva->viaje->destino_lng }})</small>
                </div>`;
        }
    }

    // Fallback si Google Maps no carga
    window.addEventListener('load', function() {
        setTimeout(function() {
            if (!window.google) {
                document.getElementById("mapa").innerHTML = 
                    `<div style='background: rgba(255, 193, 7, 0.1); border: 1px solid rgba(255, 193, 7, 0.3); border-radius: 8px; padding: 2rem; text-align: center; color: #f57c00;'>
                        <i class="fas fa-exclamation-circle" style="font-size: 2rem; margin-bottom: 1rem;"></i>
                        <h5>Google Maps no disponible</h5>
                        <p>Verifique la API Key o la conexión a internet</p>
                    </div>`;
            }
        }, 5000);
    });
</script>

<script async defer
   src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.key') }}&callback=initReservaMapa&v=3.55">
</script>
@endsection