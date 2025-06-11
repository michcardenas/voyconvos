@extends('layouts.app_dashboard')

@section('content')
<style>
    :root {
        --vcv-primary: #1F4E79;
        --vcv-light: #DDF2FE;
        --vcv-dark: #3A3A3A;
        --vcv-accent: #4CAF50;
        --vcv-bg: #FCFCFD;
    }

    .confirm-wrapper {
        background: linear-gradient(135deg, #DDF2FE 0%, #FCFCFD 50%, rgba(31, 78, 121, 0.03) 100%);
        min-height: 100vh;
        padding: 2rem 0;
        position: relative;
    }

    .confirm-wrapper::before {
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
        max-width: 700px;
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
        text-align: center;
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

    .page-subtitle {
        margin: 0.5rem 0 0 0;
        opacity: 0.9;
        font-size: 1rem;
        position: relative;
        z-index: 2;
    }

    .trip-summary-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 16px;
        padding: 0;
        border: 1px solid rgba(31, 78, 121, 0.12);
        box-shadow: 0 4px 12px rgba(31, 78, 121, 0.08);
        overflow: hidden;
        margin-bottom: 2rem;
    }

    .trip-header {
        background: linear-gradient(135deg, var(--vcv-primary), rgba(31, 78, 121, 0.9));
        color: white;
        padding: 1.5rem 2rem;
        position: relative;
        overflow: hidden;
    }

    .trip-header::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 100px;
        height: 100px;
        background: radial-gradient(circle, rgba(76, 175, 80, 0.2) 0%, transparent 70%);
        border-radius: 50%;
        transform: translate(30%, -30%);
    }

    .route-display {
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
        position: relative;
        z-index: 2;
    }

    .route-city {
        font-size: 1.3rem;
        font-weight: 600;
        padding: 0 1rem;
    }

    .route-arrow {
        margin: 0 1rem;
        font-size: 1.5rem;
        color: rgba(255, 255, 255, 0.8);
    }

    .trip-date-time {
        text-align: center;
        font-size: 1rem;
        opacity: 0.95;
        position: relative;
        z-index: 2;
    }

    .trip-details {
        padding: 2rem;
    }

    .detail-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }

    .detail-item {
        display: flex;
        align-items: center;
        padding: 1rem;
        background: rgba(221, 242, 254, 0.3);
        border-radius: 12px;
        border-left: 4px solid var(--vcv-primary);
        transition: all 0.3s ease;
    }

    .detail-item:hover {
        background: rgba(221, 242, 254, 0.5);
        border-left-color: var(--vcv-accent);
        transform: translateX(3px);
    }

    .detail-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1.5rem;
        font-size: 1.2rem;
    }

    .detail-icon.time {
        background: rgba(76, 175, 80, 0.1);
        color: var(--vcv-accent);
    }

    .detail-icon.driver {
        background: rgba(31, 78, 121, 0.1);
        color: var(--vcv-primary);
    }

    .detail-icon.seats {
        background: rgba(255, 193, 7, 0.1);
        color: #f57c00;
    }

    .detail-icon.price {
        background: rgba(76, 175, 80, 0.15);
        color: var(--vcv-accent);
    }

    .detail-content h6 {
        margin: 0 0 0.3rem 0;
        color: var(--vcv-dark);
        font-weight: 600;
        font-size: 1rem;
    }

    .detail-content p {
        margin: 0;
        color: rgba(58, 58, 58, 0.8);
        font-size: 0.9rem;
    }

    .driver-section {
        background: rgba(31, 78, 121, 0.02);
        border-radius: 12px;
        padding: 1.5rem;
        border: 1px solid rgba(31, 78, 121, 0.08);
    }

    .driver-info {
        display: flex;
        align-items: center;
    }

    .driver-avatar {
        width: 60px;
        height: 60px;
        margin-right: 1.5rem;
    }

    .driver-photo {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid var(--vcv-primary);
        box-shadow: 0 2px 8px rgba(31, 78, 121, 0.2);
    }

    .driver-photo-placeholder {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--vcv-primary), rgba(31, 78, 121, 0.8));
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
        border: 3px solid var(--vcv-primary);
        box-shadow: 0 2px 8px rgba(31, 78, 121, 0.2);
    }

    .driver-details h6 {
        margin: 0 0 0.5rem 0;
        color: var(--vcv-dark);
        font-weight: 600;
        font-size: 1.1rem;
    }

    .driver-rating {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.3rem;
    }

    .stars {
        display: flex;
        gap: 0.1rem;
    }

    .stars i {
        font-size: 0.9rem;
        color: #ffc107;
    }

    .stars .far {
        color: rgba(255, 193, 7, 0.3);
    }

    .rating-value {
        font-weight: 600;
        color: var(--vcv-primary);
        font-size: 0.9rem;
    }

    .rating-count {
        color: rgba(58, 58, 58, 0.6);
        font-size: 0.8rem;
    }

    .verified-badge {
        display: inline-block;
        margin-left: 0.5rem;
        color: var(--vcv-accent);
        font-size: 1rem;
    }

    .booking-form {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 16px;
        padding: 2rem;
        border: 1px solid rgba(31, 78, 121, 0.12);
        box-shadow: 0 4px 12px rgba(31, 78, 121, 0.08);
        margin-bottom: 2rem;
    }

    .form-header {
        text-align: center;
        margin-bottom: 2rem;
    }

    .form-header h4 {
        color: var(--vcv-primary);
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .form-header p {
        color: rgba(58, 58, 58, 0.7);
        margin: 0;
    }

    .seats-selector {
        background: rgba(221, 242, 254, 0.3);
        border-radius: 12px;
        padding: 1.5rem;
        border: 2px solid rgba(31, 78, 121, 0.1);
        margin-bottom: 2rem;
        transition: all 0.3s ease;
    }

    .seats-selector:focus-within {
        border-color: var(--vcv-primary);
        background: rgba(221, 242, 254, 0.5);
    }

    .seats-label {
        display: block;
        color: var(--vcv-primary);
        font-weight: 600;
        margin-bottom: 1rem;
        font-size: 1rem;
    }

    .seats-input {
        width: 100%;
        border: 2px solid rgba(31, 78, 121, 0.2);
        border-radius: 10px;
        padding: 1rem 1.5rem;
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--vcv-dark);
        background: white;
        transition: all 0.3s ease;
        text-align: center;
    }

    .seats-input:focus {
        outline: none;
        border-color: var(--vcv-primary);
        box-shadow: 0 0 0 3px rgba(31, 78, 121, 0.1);
    }

    .seats-info {
        display: flex;
        justify-content: space-between;
        margin-top: 1rem;
        font-size: 0.9rem;
        color: rgba(58, 58, 58, 0.7);
    }

    .price-summary {
        background: rgba(76, 175, 80, 0.1);
        border: 2px solid rgba(76, 175, 80, 0.2);
        border-radius: 12px;
        padding: 1.5rem;
        text-align: center;
        margin-bottom: 2rem;
    }

    .price-label {
        color: rgba(58, 58, 58, 0.7);
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
    }

    .price-amount {
        font-size: 2rem;
        font-weight: 700;
        color: var(--vcv-accent);
        margin: 0;
    }

    .price-per-person {
        color: rgba(58, 58, 58, 0.6);
        font-size: 0.8rem;
        margin-top: 0.3rem;
    }

    .form-actions {
        display: flex;
        gap: 1rem;
    }

    .btn-custom {
        border: none;
        border-radius: 25px;
        padding: 1rem 2rem;
        font-weight: 600;
        transition: all 0.3s ease;
        text-decoration: none;
        font-size: 1rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        justify-content: center;
        flex: 1;
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

    .btn-custom.secondary {
        background: rgba(58, 58, 58, 0.1);
        color: var(--vcv-dark);
        border: 1px solid rgba(58, 58, 58, 0.3);
    }

    .btn-custom.secondary:hover {
        background: var(--vcv-dark);
        color: white;
        transform: translateY(-2px);
        text-decoration: none;
    }

    @media (max-width: 768px) {
        .confirm-wrapper {
            padding: 1rem 0;
        }
        
        .page-header {
            padding: 1.5rem;
        }
        
        .page-header h2 {
            font-size: 1.5rem;
        }
        
        .route-city {
            font-size: 1.1rem;
            padding: 0 0.5rem;
        }
        
        .route-arrow {
            margin: 0 0.5rem;
            font-size: 1.2rem;
        }
        
        .trip-details {
            padding: 1.5rem;
        }
        
        .detail-item {
            flex-direction: column;
            text-align: center;
            padding: 1.5rem 1rem;
        }
        
        .detail-icon {
            margin-right: 0;
            margin-bottom: 1rem;
        }
        
        .driver-info {
            flex-direction: column;
            text-align: center;
        }
        
        .driver-avatar {
            margin-right: 0;
            margin-bottom: 1rem;
        }
        
        .form-actions {
            flex-direction: column;
        }
        
        .btn-custom {
            width: 100%;
            margin: 0.3rem 0;
        }
    }
</style>
<div class="confirm-wrapper">
    <div class="container">
        <!-- Page Header -->
        <div class="page-header">
            <h2>✅ Confirmar Reserva</h2>
            <p class="page-subtitle">Revisa los detalles antes de continuar</p>
        </div>

        <!-- Trip Summary Card -->
        <div class="trip-summary-card">
            <!-- Trip Header -->
            <div class="trip-header">
                <div class="route-display">
                    <div class="route-city">{{ explode(',', $viaje->origen_direccion)[0] ?? $viaje->origen_direccion }}</div>
                    <div class="route-arrow">
                        <i class="fas fa-arrow-right"></i>
                    </div>
                    <div class="route-city">{{ explode(',', $viaje->destino_direccion)[0] ?? $viaje->destino_direccion }}</div>
                </div>
                <div class="trip-date-time">
                    <i class="fas fa-calendar me-2"></i>
                    {{ \Carbon\Carbon::parse($viaje->fecha_salida)->format('d/m/Y') }}
                    <span class="mx-2">•</span>
                    <i class="fas fa-clock me-2"></i>
                    {{ $viaje->hora_salida }}
                </div>
            </div>

            <!-- Trip Details -->
            <div class="trip-details">
                <div class="detail-grid">
                    <!-- Conductor -->
                    <div class="driver-section">
                        <div class="driver-info">
                            <div class="driver-avatar">
                                @if($viaje->conductor?->foto)
                                    <img src="{{ asset('storage/' . $viaje->conductor->foto) }}" alt="{{ $viaje->conductor->name }}" class="driver-photo">
                                @elseif($viaje->conductor?->avatar)
                                    <img src="{{ $viaje->conductor->avatar }}" alt="{{ $viaje->conductor->name }}" class="driver-photo">
                                @else
                                    <div class="driver-photo-placeholder">
                                        <i class="fas fa-user"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="driver-details">
                                <h6>
                                    {{ $viaje->conductor->name ?? 'No disponible' }}
                                    @if($viaje->conductor && ($viaje->conductor->verificado ?? ($viaje->conductor->calificacion_promedio ?? 4.2) >= 4.5))
                                        <span class="verified-badge">
                                            <i class="fas fa-check-circle"></i>
                                        </span>
                                    @endif
                                </h6>
                                @if($viaje->conductor)
                                    <div class="driver-rating">
                                        @php
                                            $rating = $viaje->conductor->calificacion_promedio ?? $viaje->conductor->rating ?? 4.2;
                                            $fullStars = floor($rating);
                                            $hasHalfStar = ($rating - $fullStars) >= 0.5;
                                            $emptyStars = 5 - $fullStars - ($hasHalfStar ? 1 : 0);
                                        @endphp
                                        <div class="stars">
                                            @for($i = 1; $i <= $fullStars; $i++)
                                                <i class="fas fa-star"></i>
                                            @endfor
                                            @if($hasHalfStar)
                                                <i class="fas fa-star-half-alt"></i>
                                            @endif
                                            @for($i = 1; $i <= $emptyStars; $i++)
                                                <i class="far fa-star"></i>
                                            @endfor
                                        </div>
                                        <span class="rating-value">{{ number_format($rating, 1) }}</span>
                                        <span class="rating-count">({{ $viaje->conductor->total_calificaciones ?? rand(5, 47) }})</span>
                                    </div>
                                @endif
                                <p style="margin: 0; color: rgba(58, 58, 58, 0.7); font-size: 0.9rem;">
                                    <i class="fas fa-steering-wheel me-1"></i>Tu conductor
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Available Seats -->
                    <div class="detail-item">
                        <div class="detail-icon seats">
                            <i class="fas fa-chair"></i>
                        </div>
                        <div class="detail-content">
                            <h6>Puestos Disponibles</h6>
                            <p>{{ $viaje->puestos_disponibles }} asientos libres</p>
                        </div>
                    </div>

                    <!-- Price -->
                    <div class="detail-item">
                        <div class="detail-icon price">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                        <div class="detail-content">
                            <h6>Precio por persona</h6>
                            <p>${{ number_format($viaje->valor_persona, 2, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

     <!-- NUEVA SECCIÓN: Mapa de la ruta -->
        <div class="trip-summary-card" style="margin-top: 1.5rem;">
            <div class="trip-header">
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fas fa-map" style="color: #4285f4;"></i>
                    <h5 style="margin: 0; font-weight: 600;">Ruta del Viaje</h5>
                </div>
                <p style="margin: 0.5rem 0 0 0; color: rgba(58, 58, 58, 0.7); font-size: 0.9rem;">
                    Visualiza el recorrido de {{ $viaje->distancia_km ?? '—' }} km
                </p>
            </div>
            <div style="padding: 1rem;">
                <div id="map" style="height: 350px; width: 100%; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);"></div>
            </div>
        </div>

        <!-- Booking Form -->
        <div class="booking-form">
            <div class="form-header">
                <h4>💺 Selecciona tus puestos</h4>
                <p>¿Cuántos asientos necesitas para tu viaje?</p>
            </div>

            <form action="{{ route('pasajero.reserva.resumen', $viaje->id) }}" method="GET">
                @csrf
                
                <div class="seats-selector">
                    <label for="cantidad_puestos" class="seats-label">
                        <i class="fas fa-users me-2"></i>Número de pasajeros
                    </label>
                    <input 
                        type="number" 
                        name="cantidad_puestos" 
                        id="cantidad_puestos" 
                        class="seats-input" 
                        min="1" 
                        max="{{ $viaje->puestos_disponibles }}" 
                        value="1" 
                        required
                        onchange="updatePrice()"
                    >
                    <div class="seats-info">
                        <span>Mínimo: 1 pasajero</span>
                        <span>Máximo: {{ $viaje->puestos_disponibles }} pasajeros</span>
                    </div>
                </div>

                <div class="price-summary">
                    <div class="price-label">Total a pagar</div>
                    <p class="price-amount" id="totalPrice">
                        ${{ number_format($viaje->valor_cobrado ?? $viaje->valor_estimado ?? 0, 0, ',', '.') }}
                    </p>
                    <div class="price-per-person" id="priceBreakdown">
                        1 persona × ${{ number_format($viaje->valor_cobrado ?? $viaje->valor_estimado ?? 0, 0, ',', '.') }}
                    </div>
                </div>

                <div class="form-actions">
                    <a href="{{ route('pasajero.viajes.disponibles') }}" class="btn-custom secondary">
                        <i class="fas fa-arrow-left"></i>
                        Cancelar
                    </a>
                    <button type="submit" class="btn-custom primary">
                        <i class="fas fa-eye"></i>
                        Ver Resumen
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Variables del viaje (valores seguros)
    const VIAJE_DATA = {
        origen_lat: {{ is_numeric($viaje->origen_lat) ? $viaje->origen_lat : 'null' }},
        origen_lng: {{ is_numeric($viaje->origen_lng) ? $viaje->origen_lng : 'null' }},
        destino_lat: {{ is_numeric($viaje->destino_lat) ? $viaje->destino_lat : 'null' }},
        destino_lng: {{ is_numeric($viaje->destino_lng) ? $viaje->destino_lng : 'null' }},
        precio: {{ $viaje->valor_persona ?? $viaje->valor_cobrado ?? $viaje->valor_estimado ?? 0 }},
        puestos_max: {{ $viaje->puestos_disponibles ?? 1 }}
    };

    let mapaInicializado = false;

    // Función principal del mapa
    window.initConfirmarReservaMapa = function() {
        if (mapaInicializado) return;
        mapaInicializado = true;
        
        console.log('Iniciando mapa con datos:', VIAJE_DATA);
        
        // Validar coordenadas
        if (!VIAJE_DATA.origen_lat || !VIAJE_DATA.origen_lng || 
            !VIAJE_DATA.destino_lat || !VIAJE_DATA.destino_lng) {
            document.getElementById('map').innerHTML = 
                '<div style="padding:20px;text-align:center;color:#666;"><i class="fas fa-map"></i><br>Coordenadas no disponibles</div>';
            return;
        }

        const origen = { lat: VIAJE_DATA.origen_lat, lng: VIAJE_DATA.origen_lng };
        const destino = { lat: VIAJE_DATA.destino_lat, lng: VIAJE_DATA.destino_lng };

        // Crear mapa
        const map = new google.maps.Map(document.getElementById('map'), {
            zoom: 12,
            center: origen,
            mapTypeId: 'roadmap'
        });

        // Marcadores
        new google.maps.Marker({
            position: origen,
            map: map,
            title: 'Origen',
            icon: 'https://maps.google.com/mapfiles/ms/icons/green-dot.png'
        });

        new google.maps.Marker({
            position: destino,
            map: map,
            title: 'Destino', 
            icon: 'https://maps.google.com/mapfiles/ms/icons/red-dot.png'
        });

        // Ruta
        const directionsService = new google.maps.DirectionsService();
        const directionsRenderer = new google.maps.DirectionsRenderer({
            suppressMarkers: true,
            polylineOptions: { strokeColor: '#4285f4', strokeWeight: 4 }
        });
        
        directionsRenderer.setMap(map);

        directionsService.route({
            origin: origen,
            destination: destino,
            travelMode: google.maps.TravelMode.DRIVING
        }, function(response, status) {
            if (status === 'OK') {
                directionsRenderer.setDirections(response);
            } else {
                const bounds = new google.maps.LatLngBounds();
                bounds.extend(origen);
                bounds.extend(destino);
                map.fitBounds(bounds);
            }
        });
    };

    // Función para actualizar precios
    function updatePrice() {
        const cantidad = parseInt(document.getElementById('cantidad_puestos').value) || 1;
        const precio = VIAJE_DATA.precio;
        const total = cantidad * precio;
        
        document.getElementById('totalPrice').textContent = 
            '$' + total.toLocaleString('es-CO');
        document.getElementById('priceBreakdown').textContent = 
            cantidad + ' persona' + (cantidad > 1 ? 's' : '') + ' × $' + precio.toLocaleString('es-CO');
    }

    // Alias para compatibilidad
    function calcularCosto() {
        updatePrice();
    }

    // Configuración inicial
    document.addEventListener('DOMContentLoaded', function() {
        const input = document.getElementById('cantidad_puestos');
        if (input) {
            input.addEventListener('input', function() {
                if (this.value > VIAJE_DATA.puestos_max) this.value = VIAJE_DATA.puestos_max;
                if (this.value < 1) this.value = 1;
                updatePrice();
            });
        }
    });
</script>

<script async defer 
    src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.key') }}&callback=initConfirmarReservaMapa">
</script>
@endsection