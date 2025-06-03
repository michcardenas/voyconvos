@extends('layouts.app_dashboard')

@section('title', 'Detalle de tu reserva')

@section('content')
<div class="container py-5">
    <h2 class="mb-4 text-vcv fw-bold">📋 Detalles de tu reserva</h2>

    <div class="alert alert-info">
        <strong>Origen:</strong> {{ $reserva->viaje->origen_lat }}, {{ $reserva->viaje->origen_lng }}<br>
        <strong>Destino:</strong> {{ $reserva->viaje->destino_lat }}, {{ $reserva->viaje->destino_lng }}
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="text-vcv">Información del Viaje</h5>
            <ul class="list-group mb-3">
                <li class="list-group-item"><strong>Origen:</strong> {{ $reserva->viaje->origen_direccion }}</li>
                <li class="list-group-item"><strong>Destino:</strong> {{ $reserva->viaje->destino_direccion }}</li>
                <li class="list-group-item"><strong>Fecha:</strong> {{ $reserva->viaje->fecha_salida }}</li>
                <li class="list-group-item"><strong>Hora:</strong> {{ $reserva->viaje->hora_salida }}</li>
                <li class="list-group-item"><strong>Hora:</strong> {{ $reserva->viaje->fecha_salida }}</li>
                <li class="list-group-item"><strong>Puestos disponibles:</strong> {{ $reserva->viaje->puestos_disponibles }}</li>
            </ul>

            <h5 class="text-vcv">Conductor</h5>
            <ul class="list-group mb-3">
                <li class="list-group-item"><strong>Nombre:</strong> {{ $reserva->viaje->conductor->name ?? 'N/D' }}</li>
                <li class="list-group-item"><strong>Email:</strong> {{ $reserva->viaje->conductor->email ?? 'N/D' }}</li>
            </ul>

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
<script async defer
    src="https://maps.googleapis.com/maps/api/js?key={{ config('services.Maps.key') }}&callback=initReservaMapa&libraries=places&language=es">
</script>

<script>
    // Se declara la función initMap en el ámbito global para que Google Maps la pueda llamar
    function initReservaMapa() {
        try {
            console.log("✅ Ejecutando initMap para los detalles de la reserva");

            const origen = {
                lat: parseFloat({{ $reserva->viaje->origen_lat ?? 'NaN' }}),
                lng: parseFloat({{ $reserva->viaje->origen_lng ?? 'NaN' }})
            };

            const destino = {
                lat: parseFloat({{ $reserva->viaje->destino_lat ?? 'NaN' }}),
                lng: parseFloat({{ $reserva->viaje->destino_lng ?? 'NaN' }})
            };

            // Validar que las coordenadas sean números válidos
            if (isNaN(origen.lat) || isNaN(origen.lng) || isNaN(destino.lat) || isNaN(destino.lng)) {
                console.error("❌ Coordenadas de origen o destino no válidas.");
                document.getElementById("mapa").innerHTML = "<p class='text-danger text-center mt-4'>No se pudieron cargar las coordenadas del viaje. Asegúrate de que sean números válidos.</p>";
                return;
            }

            console.log("🛰️ Coordenadas:", origen, destino);

            const mapaDiv = document.getElementById("mapa");
            if (!mapaDiv) {
                console.error("❌ No se encontró el div #mapa. Asegúrate de que el ID sea correcto y el div exista.");
                return;
            }
            console.log("✅ Se encontró el div #mapa");

            const map = new google.maps.Map(mapaDiv, {
                zoom: 10,
                center: origen
            });

            const directionsService = new google.maps.DirectionsService();
            const directionsRenderer = new google.maps.DirectionsRenderer({
                map: map,
                suppressMarkers: false,
                draggable: false
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
                    alert("❌ Error al cargar la ruta del mapa. Estado: " + status + ". Revisa la consola para más detalles.");
                    document.getElementById("mapa").innerHTML = "<p class='text-danger text-center mt-4'>Error al cargar la ruta: " + status + "</p>";
                }
            });

        } catch (error) {
            console.error("❌ Error inesperado en initMap:", error);
            document.getElementById("mapa").innerHTML = "<p class='text-danger text-center mt-4'>Ocurrió un error al inicializar el mapa.</p>";
        }
    }
</script>
@endsection