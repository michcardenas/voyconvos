@extends('layouts.app_dashboard')

@section('title', 'Detalle de tu reserva')

@section('content')
<div class="container py-5">
    <h2 class="mb-4 text-vcv fw-bold">📋 Detalles de tu reserva</h2>
    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="text-vcv">Información del Viaje</h5>
            <ul class="list-group mb-3">
                <li class="list-group-item"><strong>Origen:</strong> {{ $reserva->viaje->origen_direccion }}</li>
                <li class="list-group-item"><strong>Destino:</strong> {{ $reserva->viaje->destino_direccion }}</li>
                <li class="list-group-item"><strong>Fecha:</strong> {{ $reserva->viaje->fecha_salida }}</li>
                <li class="list-group-item"><strong>Hora:</strong> {{ $reserva->viaje->hora_salida }}</li>
                <li class="list-group-item"><strong>Hora:</strong> {{ $reserva->viaje->fecha_salida }}</li>
                <li class="list-group-item"><strong>Puestos reservados:</strong> {{ $reserva->cantidad_puestos }}</li>
            </ul>

            <h5 class="text-vcv">Conductor</h5>
            <ul class="list-group mb-3">
                <li class="list-group-item"><strong>Nombre:</strong> {{ $reserva->viaje->conductor->name ?? 'N/D' }}</li>
                <li class="list-group-item"><strong>Email:</strong> {{ $reserva->viaje->conductor->email ?? 'N/D' }}</li>
            </ul>

            @if (!$reserva->calificacionEnviadaPorPasajero())
                <a href="{{ route('pasajero.calificar.formulario', $reserva->id) }}" class="btn btn-warning mt-3">
                    Calificar al conductor ⭐
                </a>
            @else
                <p class="mt-3 text-success">✅ Ya calificaste al conductor.</p>
            @endif

            <h5 class="text-vcv">Tu Reserva</h5>
            <ul class="list-group">
                <li class="list-group-item"><strong>Estado:</strong> {{ ucfirst($reserva->estado) }}</li>
                <li class="list-group-item"><strong>Fecha de reserva:</strong> {{ $reserva->created_at->format('Y-m-d H:i') }}</li>
            </ul>

            <h5 class="text-vcv mt-5">🗺️ Ruta del viaje</h5>
            <div id="mapa" style="width: 100%; height: 400px;" class="mb-4 rounded shadow"></div>

            <div class="mt-4">
                <a href="{{ route('pasajero.dashboard') }}" class="btn btn-secondary">⬅ Volver al listado</a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')


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

            // Crear el mapa
            const map = new google.maps.Map(mapaDiv, {
                zoom: 10,
                center: origen,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            });

            // Crear marcadores
            const markerOrigen = new google.maps.Marker({
                position: origen,
                map: map,
                title: "Origen",
                icon: 'https://maps.google.com/mapfiles/ms/icons/green-dot.png'
            });

            const markerDestino = new google.maps.Marker({
                position: destino,
                map: map,
                title: "Destino",
                icon: 'https://maps.google.com/mapfiles/ms/icons/red-dot.png'
            });

            // Crear la ruta
            const directionsService = new google.maps.DirectionsService();
            const directionsRenderer = new google.maps.DirectionsRenderer({
                map: map,
                suppressMarkers: true, // Usamos nuestros marcadores personalizados
                polylineOptions: {
                    strokeColor: '#4285F4',
                    strokeWeight: 5
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
                `<div class='alert alert-danger text-center'>
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
                    `<div class='alert alert-warning text-center'>
                        <h5>Google Maps no disponible</h5>
                        <p>Verifique la API Key o la conexión a internet</p>
                    </div>`;
            }
        }, 5000);
    });
</script>

<script async defer
   src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.key') }}&callback=initReservaMapa&v=3.55">
>
</script>
@endsection
@endsection